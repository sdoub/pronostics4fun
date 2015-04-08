<?php
require_once("begin.file.php");
require_once("lib/p4f.championship.php");

$currentTime = time();

if (isset($_GET['GroupKey']))
{
  $_groupKey = $_GET['GroupKey'];
}
else {
	$query= "SELECT groups.PrimaryKey GroupKey
						 FROM groups
						WHERE groups.CompetitionKey=" . COMPETITION . "
							AND $currentTime >= (UNIX_TIMESTAMP(groups.EndDate))
							ORDER BY groups.EndDate DESC";

	$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get last completed group");
	$_groupKey = $rowsSet[0]["GroupKey"];
}

if (isset($_GET['SeasonKey']))
{
  $_seasonKey = $_GET['SeasonKey'];
}
else {
	$query= "SELECT seasons.PrimaryKey SeasonKey
						 FROM seasons
						ORDER BY seasons.CompetitionKey DESC, seasons.Order DESC";

	$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get last season");
	$_seasonKey = $rowsSet[0]["SeasonKey"];
}


$arr = array();
if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1)
{
  $arr[] = GetP4FMatchScores($_groupKey);
  $arr[] = CalculateP4FDivisionsRanking($_seasonKey);
} else {
  $arr["Error"] = "Not authorized";
}
writeJsonResponse($arr);

require_once("end.file.php");
?>