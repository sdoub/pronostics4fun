#!/usr/local/bin/php
<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/lib/match.php");
include_once(dirname(__FILE__). "/lib/ranking.php");
include_once(dirname(__FILE__). "/lib/score.php");
require_once(dirname(__FILE__). "/lib/http.php");

$_jobName='RefreshMatches';
$_logInfo = "";
$selectQuery = "SELECT LastStatus,TIME_TO_SEC(TIMEDIFF(NOW(),LastExecution)) LastExecution FROM cronjobs WHERE JobName='$_jobName'";
$resultSet = $_databaseObject -> queryPerf ($selectQuery , "Check cronjob status");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
if ($rowSet["LastStatus"]!=1 || $rowSet["LastExecution"]>60) {

  $updateQuery = "UPDATE cronjobs SET LastStatus='1', LastExecutionInformation='" .str_replace("'","''",mysql_real_escape_string(__encode(utf8_decode($_logInfo))))."' WHERE JobName='$_jobName'";
  $_databaseObject -> queryPerf ($updateQuery , "Update cronjob information");


  $days="";
  if (isset($_GET["Days"])) {
    $days="- INTERVAL ".$_GET["Days"]." DAY";
  }
  $limit ="";
  if (isset($_GET["Limit"])) {
    $limit=" LIMIT ".$_GET["Limit"];
  }
  $currentTime = time();

  if ($days){
    $query= "SELECT
matches.PrimaryKey MatchKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.TeamHomeKey,
matches.TeamAwayKey,
matches.ExternalKey
 FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
WHERE DATE(matches.ScheduleDate)=(CURDATE()$days) $limit";

  }
  else {
    $query= "SELECT
matches.PrimaryKey MatchKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.TeamHomeKey,
matches.TeamAwayKey,
matches.ExternalKey
 FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
WHERE $currentTime >= (UNIX_TIMESTAMP(matches.ScheduleDate)) AND $currentTime <= (UNIX_TIMESTAMP(matches.ScheduleDate)+11400)";
  }
  $resultSetGroup = $_databaseObject->queryPerf($query,"Get matches to be refreshed");
  $_databaseObject -> fetch_assoc ($resultSetGroup);

  $rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get all groups of the current competition");
  $_databaseObject->close();

  $_queries = array();
  $totaltime = getElapsedTime();
  $http = Http::connect("pronostics4fun.com", 80);
  $http->silentMode();
  foreach ($rowsSet as $rowSet)
  {
    $parameter = array();
    $parameter["TeamHomeKey"] = $rowSet["TeamHomeKey"];
    $parameter["TeamAwayKey"] = $rowSet["TeamAwayKey"];
    $parameter["ExternalKey"] = $rowSet["ExternalKey"];
    $parameter["MatchKey"] = $rowSet["MatchKey"];
    $parameter["Live"] = 1;
    $http->post('refresh.match.php', $parameter);
  }

  $results = $http ->run();
  //print_r($results);

  $_queries = array();
  foreach ($results as $result) {

    $queries = json_decode(trim($result));
    foreach ($queries->Queries as $query)
    {
      $_queries[] = $query;
    }
  }


  $_databaseObject = new mysql (SQL_HOST, SQL_LOGIN,  SQL_PWD, SQL_DB, $_dbOptions);
  foreach ($_queries as $query) {
    //print($query);
    $_databaseObject -> queryPerf ($query , "Execute query");
  }
  //$_databaseObject->close();

  foreach ($rowsSet as $rowSet)
  {
    $_logInfo .= "Refresh match with key ".$rowSet["MatchKey"] ;
    try {

      ComputeScore($rowSet["MatchKey"]);
      ComputeGroupScore($rowSet["GroupKey"]);
      CalculateRanking($rowSet["ScheduleDate"]);
      CalculateGroupRanking($rowSet["GroupKey"],$rowSet["ScheduleDate"]);


      $_groupKey = $rowSet["GroupKey"];
      GenerateMatchStates($_groupKey);

      $query = "SELECT PrimaryKey MatchStateKey, MatchKey, TeamHomeScore, TeamAwayScore, UNIX_TIMESTAMP(StateDate) StateDate
    FROM matchstates WHERE MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey)";
      $resultSetStates = $_databaseObject->queryPerf($query,"Get players and score");

      while ($rowSetState = $_databaseObject -> fetch_assoc ($resultSetStates)) {
        ComputeScoreState ($rowSetState["MatchKey"], $rowSetState["TeamHomeScore"], $rowSetState["TeamAwayScore"], $rowSetState["MatchStateKey"]);
      }
      $query = "SELECT UNIX_TIMESTAMP(StateDate) StateDate
    FROM matchstates WHERE MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey)
    GROUP BY StateDate ORDER BY StateDate";
      $resultSetStates = $_databaseObject->queryPerf($query,"Get players and score");

      while ($rowSetState = $_databaseObject -> fetch_assoc ($resultSetStates)) {
        ComputeGroupScoreState ($_groupKey,$rowSetState["StateDate"]);
        CalculateGroupRankingState($_groupKey,$rowSetState["StateDate"]);
      }

      $isCompleted = 0;
      $query = "SELECT IsCompleted FROM groups WHERE groups.PrimaryKey=" . $rowSet["GroupKey"];
      $resultSetGroup = $_databaseObject->queryPerf($query,"update group");
      $rowSetGroup = $_databaseObject -> fetch_assoc ($resultSetGroup);
      $isCompleted += (int)$rowSetGroup["IsCompleted"];

      $query = "UPDATE groups SET groups.Status=2 WHERE groups.PrimaryKey=" . $rowSet["GroupKey"];
      $_databaseObject->queryPerf($query,"update group");

      // If all matches have been played the group should be completed
      $query = "UPDATE groups SET groups.IsCompleted=1, groups.Status=3 WHERE groups.PrimaryKey=" . $rowSet["GroupKey"] . "
               AND NOT EXISTS (SELECT results.PrimaryKey
							     FROM matches
								 LEFT JOIN results ON results.MatchKey=matches.PrimaryKey
								  AND results.LiveStatus=10
								WHERE matches.GroupKey=groups.PrimaryKey
								  AND results.PrimaryKey IS NULL)";
      $_databaseObject->queryPerf($query,"update group");

      $query = "SELECT IsCompleted FROM groups WHERE groups.PrimaryKey=" . $rowSet["GroupKey"];
      $resultSetGroup = $_databaseObject->queryPerf($query,"update group");
      $rowSetGroup = $_databaseObject -> fetch_assoc ($resultSetGroup);
      $isCompleted += (int)$rowSetGroup["IsCompleted"];

      if ($isCompleted==1) {
        // Reset field IsResultEmailSent to 0, once the group is completed
        $query = "UPDATE players SET players.IsResultEmailSent=0 WHERE players.ReceiveResult=1
                 AND EXISTS (SELECT 1 FROM groups WHERE IsCompleted=1 AND groups.PrimaryKey=" . $rowSet["GroupKey"] . ")";
        $_databaseObject->queryPerf($query,"update group");

        ComputeGroupScore($rowSet["GroupKey"]);
        CalculateRanking($rowSet["ScheduleDate"]);
        CalculateGroupRanking($rowSet["GroupKey"],$rowSet["ScheduleDate"]);

      }

    }
    catch (Exception $e) {
      $_error=true;
      $_errorMessage =$e;

    }
  }

  $arr = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');

  $totaltime = getElapsedTime();
  //$_logInfo .= implode(',',$arr["errorLog"]);
  echo json_encode($arr);
  $_logInfo .= "This page loaded in $totaltime seconds.";
  if (count($arr["errorLog"])>0) {
    if ($arr["errorLog"]!="") {
      $_error = true;
      $_errorMessage="An error occured during queries execution";
      print_r($arr["errorLog"]);
    }
  }

  //    $script_tz = date_default_timezone_get();
  //
  //    if (strcmp($script_tz, ini_get('date.timezone'))){
  //      $_logInfo .= 'Script timezone differs from ini-set timezone.';
  //      $_logInfo .= $script_tz;
  //    } else {
  //      $_logInfo .= 'Script timezone and ini-set timezone match.';
  //      $_logInfo .= $script_tz;
  //    }

  echo $_logInfo;

  $updateQuery = "UPDATE cronjobs SET LastStatus=2, LastExecutionInformation='" .str_replace("'","''",mysql_real_escape_string(__encode(utf8_decode($_logInfo))))."' WHERE JobName='$_jobName'";
  $_databaseObject -> queryPerf ($updateQuery , "Update cronjob information");
} else {
  echo __encode("Un refresh est déjà en cours!");
}
require_once(dirname(__FILE__)."/end.file.php");
?>