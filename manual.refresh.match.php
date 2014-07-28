<?php
require_once("begin.file.php");
require_once("lib/match.php");
require_once("lib/ranking.php");
require_once("lib/score.php");
require_once("lib/http.php");

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

$_databaseObject->query( "SET NAMES utf8");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  //echo "Refresh match with key ".$rowSet["MatchKey"] ;
  $_queries = array();
  if (!isset($_GET["DontGetMatchInfo"])) {
    //echo "<br/>Get info from external web site";
    //GetMatchInfo($rowSet["TeamHomeKey"],$rowSet["TeamAwayKey"],$rowSet["ExternalKey"],$rowSet["MatchKey"]);

//    foreach ($rowsSet as $rowSet)
//    {
//      $parameter = array();
//      $parameter["TeamHomeKey"] = $rowSet["TeamHomeKey"];
//      $parameter["TeamAwayKey"] = $rowSet["TeamAwayKey"];
//      $parameter["ExternalKey"] = $rowSet["ExternalKey"];
//      $parameter["MatchKey"] = $rowSet["MatchKey"];
//      $parameter["Live"] = 0;
//      $http->post('p4f.dev/refresh.match.php', $parameter);

      $teamHomeKey = $rowSet["TeamHomeKey"];
      $teamAwayKey = $rowSet["TeamAwayKey"];
      $externalKey = $rowSet["ExternalKey"];
      $matchKey = $rowSet["MatchKey"];
      $isLive = 0;

      switch ($_competitionType) {
        case 2:
          $matchInfo = GetFifaMatchInfo($teamHomeKey,$teamAwayKey,$externalKey,$matchKey,$isLive=="1");
          $arr["GetFifaMatchInfo"] = $matchInfo;
          foreach ($matchInfo["Queries"] as $query) {
            $_queries[] =$query;
          }
          break;
        case 3:

          $matchInfo = GetUefaMatchInfo($teamHomeKey,$teamAwayKey,$externalKey,$matchKey,$isLive=="1");
          $arr["GetUefaMatchInfo"] = $matchInfo;
          foreach ($matchInfo["Queries"] as $query) {
            $_queries[] =$query;
          }
          break;
        default:
          $matchInfo = GetMatchInfo($teamHomeKey,$teamAwayKey,$externalKey,$matchKey,$isLive=="1");
          foreach ($matchInfo["Queries"] as $query) {
            $_queries[] =$query;
          }
          $matchInfo = GetMatchsLineupsInfo($teamHomeKey,$teamAwayKey,$externalKey,$matchKey,$isLive=="1",$matchInfo["HomeId"],$matchInfo["AwayId"]);
          foreach ($matchInfo["Queries"] as $query) {
            $_queries[] =$query;
          }

          break;
      }

    //print_r($results);

    //    $_queries = array();
    //    foreach ($results as $result) {
    //      $queries = json_decode(trim($result));
    //      foreach ($queries->Queries as $query)
    //      {
    //        $_queries[] = $query;
    //      }
    //    }
  }
  $arr["Queries"]=$_queries;
  foreach ($_queries as $query) {
    //print($query);
    $_databaseObject -> queryPerf ($query , "Execute query");
  }
  if (!isset($_GET["DontComputeScore"])) {

    ComputeScore($rowSet["MatchKey"]);
  switch ($_competitionType) {
          case 2:
          case 3:
            ComputeCoupeGroupScore($rowSet["GroupKey"]);
            break;
          default:
            ComputeGroupScore($rowSet["GroupKey"]);
            break;
        }
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
}

$arr["Database"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');

$query= "SELECT
matches.PrimaryKey MatchKey,
matches.TeamHomeKey,
matches.TeamAwayKey,
TeamHome.Name TeamHomeName,
TeamAway.Name TeamAwayName,
matches.GroupKey,
matches.IsBonusMatch,
groups.Description GroupName,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) ) TeamHomeScore,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) ) TeamAwayScore,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey ) TeamHomeEvents,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey ) TeamAwayEvents,
IFNULL(results.LiveStatus,0) LiveStatus,
IFNULL(results.ActualTime,0) ActualTime
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
LEFT JOIN results ON results.MatchKey=matches.PrimaryKey
WHERE matches.PrimaryKey=$_matchKey
ORDER BY matches.ScheduleDate ASC, matches.TeamHomeKey";

$resultSet = $_databaseObject->queryPerf($query,"Get matches to be played by current day");

$dataMatchInfo = array();
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $dataMatchInfo["MatchKey"] = $rowSet["MatchKey"];
  $dataMatchInfo["GroupName"] = $rowSet["GroupName"];
  $dataMatchInfo["TeamHomeKey"] = $rowSet["TeamHomeKey"];
  $dataMatchInfo["TeamAwayKey"] = $rowSet["TeamAwayKey"];
  $dataMatchInfo["TeamHomeName"] = $rowSet["TeamHomeName"];
  $dataMatchInfo["TeamAwayName"] = $rowSet["TeamAwayName"];
  $dataMatchInfo["TeamHomeScore"] = $rowSet["TeamHomeScore"];
  $dataMatchInfo["TeamAwayScore"] = $rowSet["TeamAwayScore"];
  $dataMatchInfo["TeamHomeEvents"] = $rowSet["TeamHomeEvents"];
  $dataMatchInfo["TeamAwayEvents"] = $rowSet["TeamAwayEvents"];

}
$arr["MatchData"] = $dataMatchInfo;
writeJsonResponse($arr);


require_once("end.file.php");
?>