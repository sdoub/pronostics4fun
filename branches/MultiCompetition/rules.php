<?php 
$query = "SELECT matches.PrimaryKey MatchKey,
teamHome.Code TeamHomeName, 
teamAway.Code TeamAwayName,
teamHome.PrimaryKey TeamHomeKey, 
teamAway.PrimaryKey TeamAwayKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate, 
groups.Code GroupCode,
forecasts.PlayerKey
FROM `matches` 
INNER JOIN teams teamHome ON matches.TeamHomeKey=teamHome.PrimaryKey
INNER JOIN teams teamAway ON matches.TeamAwayKey=teamAway.PrimaryKey
INNER JOIN groups ON matches.GroupKey = groups.PrimaryKey
LEFT JOIN  forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=$_playerKey
WHERE matches.ScheduleDate >= FROM_UNIXTIME($_startDate) 
  AND matches.ScheduleDate <= FROM_UNIXTIME($_endDate) 
  AND matches.ScheduleDate > FROM_UNIXTIME($timeRef)";


$pos = strpos($query,'UNIX_TIMESTAMP');

echo $pos; 
?>