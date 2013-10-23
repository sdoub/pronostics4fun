<?php
require_once("begin.file.php");
require_once("lib/p4f.championship.php");

$currentTime = time();

$query= "SELECT groups.PrimaryKey GroupKey
           FROM groups
          WHERE groups.CompetitionKey=" . COMPETITION . "
            AND $currentTime >= (UNIX_TIMESTAMP(groups.EndDate))
            ORDER BY groups.EndDate DESC";

$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get last completed group");

$arr = array();
$arr[] = GetP4FMatchScores($rowsSet[0]["GroupKey"]);
$arr[] = CalculateP4FDivisionsRanking(2);
writeJsonResponse($arr);

require_once("end.file.php");
?>