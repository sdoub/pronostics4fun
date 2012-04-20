<?php
require_once("begin.file.php");
require_once("lib/match.php");
require_once("lib/ranking.php");
require_once("lib/score.php");

if (!isset($_GET["MatchKey"])) {

if (!isset($_GET["GroupKey"])) {
  echo 'Please pass the MatchKey or GroupKey!';
  exit;
  // for testing purpose
  $_groupKey = 367;
}
else {
  $_groupKey = $_GET["GroupKey"];
  $query= "SELECT
matches.PrimaryKey MatchKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.TeamHomeKey,
matches.TeamAwayKey,
matches.ExternalKey
 FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
WHERE matches.GroupKey=".$_groupKey;
}
}
else {
  $_groupKey = $_GET["MatchKey"];
$query= "SELECT
matches.PrimaryKey MatchKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.TeamHomeKey,
matches.TeamAwayKey,
matches.ExternalKey
 FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
WHERE matches.PrimaryKey=".$_groupKey;

}



$currentTime = time();


$resultSet = $_databaseObject->queryPerf($query,"Get matches to be refreshed");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  echo "Refresh match with key ".$rowSet["MatchKey"] ;
  $_queries = array();
  if (!isset($_GET["DontGetMatchInfo"])) {
    echo "Get info from external web site";
    GetMatchInfo($rowSet["TeamHomeKey"],$rowSet["TeamAwayKey"],$rowSet["ExternalKey"],$rowSet["MatchKey"]);
  }
  foreach ($_queries as $query) {
    //print($query);
    $_databaseObject -> queryPerf ($query , "Execute query");
  }

  ComputeScore($rowSet["MatchKey"]);
  ComputeGroupScore($rowSet["GroupKey"]);
  CalculateRanking($rowSet["ScheduleDate"]);
  CalculateGroupRanking($rowSet["GroupKey"],$rowSet["ScheduleDate"]);
  // If all matches have been played the group should be completed
  $query = "UPDATE groups SET groups.IsCompleted=1 WHERE groups.PrimaryKey=" . $rowSet["GroupKey"] . "
               AND NOT EXISTS (SELECT results.PrimaryKey
							     FROM matches
								 LEFT JOIN results ON results.MatchKey=matches.PrimaryKey
								  AND results.LiveStatus=10
								WHERE matches.GroupKey=groups.PrimaryKey
								  AND results.PrimaryKey IS NULL)";
  $_databaseObject->queryPerf($query,"update group");
}

$arr = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');

$totaltime = getElapsedTime();
echo "<pre style='display:none'> This page loaded in $totaltime seconds.</pre>";
echo '<pre style="display:none">', print_r ($arr), '</pre>';
echo '<pre style="display:none">';

echo '</pre>';

require_once("end.file.php");
?>