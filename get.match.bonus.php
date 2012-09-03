<?php
require_once("begin.file.php");

$_teamKey = $_GET["TeamKey"];

$query= "SELECT 	TeamHome.PrimaryKey TeamHomeKey,
       TeamHome.Name TeamHomeName,
       TeamAway.PrimaryKey TeamAwayKey,
       TeamAway.Name TeamAwayName,
 groups.Description,UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore
  FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
WHERE matches.IsBonusMatch=1
AND matches.TeamHomeKey=$_teamKey
UNION ALL
SELECT 	TeamHome.PrimaryKey TeamHomeKey,
       TeamHome.Name TeamHomeName,
       TeamAway.PrimaryKey TeamAwayKey,
       TeamAway.Name TeamAwayName,
 groups.Description, UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore
  FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
WHERE matches.IsBonusMatch=1
AND matches.TeamAwayKey=$_teamKey";

$resultSet = $_databaseObject->queryPerf($query,"Get matches which have participated for a bonus match");
echo "<div style='text-align:center;background-color:#365F89;font-weight:bold;width:500px;color:#FFFFFF;margin-bottom:10px;'>Liste des matchs Bonus</div>";
echo "<div style='color:#FFFFFF; font-size:11px;'>";
echo "<ul>";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $scheduleFormattedDate = strftime("%A %d %B %Y à %H:%M",$rowSet['ScheduleDate']);
  echo "<li style='height:16px;padding-left:5px;'><u>".$rowSet["Description"]." :</u> ". $rowSet["TeamHomeName"] ." <strong>(".$rowSet["TeamHomeScore"]." - ".$rowSet["TeamAwayScore"].")</strong> " . $rowSet["TeamAwayName"] . "". __encode(" -> joué le ") . $scheduleFormattedDate . "<br/></li>";
}
echo "</ul>";
echo "</div>";

require_once("end.file.php");
?>