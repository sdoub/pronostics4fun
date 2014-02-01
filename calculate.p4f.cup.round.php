<?php
require_once("begin.file.php");
require_once("lib/p4f.cup.php");

$currentTime = time();

if (isset($_GET['CupRoundKey']))
{
  $_cupRoundKey = $_GET['CupRoundKey'];
}
else if (isset($_POST['CupRoundKey']))
{
  $_cupRoundKey = $_POST['CupRoundKey'];
}
else
{
  //exit ("The matchKey is required!");
  $_cupRoundKey =1;

}

$query= "SELECT groups.PrimaryKey GroupKey
           FROM groups
          WHERE groups.CompetitionKey=" . COMPETITION . "
            AND $currentTime >= (UNIX_TIMESTAMP(groups.EndDate))
            ORDER BY groups.EndDate DESC";

$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get last completed group");

$arr = array();
$arr[] = GetP4FCupMatchScores($rowsSet[0]["GroupKey"]);
$arr[] = CreateNextRound ($_cupRoundKey, 3);
writeJsonResponse($arr);

require_once("end.file.php");
?>