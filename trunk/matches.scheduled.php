<?php
require_once("begin.file.php");

//http://localhost/CDM2010/matchesCalendar.php?_=1272664330985&start=1272146400&end=1275775200

$_startDate = $_GET['start'];
$_endDate = $_GET['end'];
$_playerKey = $_authorisation->getConnectedUserKey();

$timeRef = time();
$timeRef -= 259200;

$query = "SELECT matches.PrimaryKey MatchKey,
teamHome.Code TeamHomeName,
teamAway.Code TeamAwayName,
teamHome.PrimaryKey TeamHomeKey,
teamAway.PrimaryKey TeamAwayKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
groups.Code GroupCode,
forecasts.PlayerKey,
matches.IsBonusMatch
FROM `matches`
INNER JOIN teams teamHome ON matches.TeamHomeKey=teamHome.PrimaryKey
INNER JOIN teams teamAway ON matches.TeamAwayKey=teamAway.PrimaryKey
INNER JOIN groups ON matches.GroupKey = groups.PrimaryKey AND groups.CompetitionKey=". COMPETITION . " AND groups.Status>0
LEFT JOIN  forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=$_playerKey
WHERE matches.ScheduleDate >= FROM_UNIXTIME($_startDate)
  AND matches.ScheduleDate <= FROM_UNIXTIME($_endDate)
  ";


$pos = strpos($query,'UNIX_TIMESTAMP');

$resultSet = $_databaseObject -> queryPerf ($query, "Get all matches for the current period");
$arr = array();
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $teamHome = utf8_decode($rowSet['TeamHomeName']);
  $teamAway = utf8_decode($rowSet['TeamAwayName']);

  $className = "ToBeDone";
  if ($rowSet['PlayerKey']){
    $className = "AlreadyDone";
  }

  if ($rowSet['ScheduleDate']<=time())
  {
    $className = "TooLate";
  }
  $className .= $rowSet['IsBonusMatch'];
  $arr[]=array(
            'matchKey' => $rowSet['MatchKey'],
			'title' => $rowSet['GroupCode'] . ": " . $teamHome." - ".$teamAway,
			'start' => $rowSet['ScheduleDate'],
			'className' => $className
  );
}
//$arr["perfAndError"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
echo json_encode($arr);

require_once("end.file.php");
?>