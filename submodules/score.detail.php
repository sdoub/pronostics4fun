<?php

$score = $_GET["score"];
$teamKeys = $_GET["teamKeys"];
$groupKeys = $_GET["groupKeys"];
$line ="1-;";
$content ='';

$content.='<div id="FixedContent"><table>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>';
$line.="3-len:".strlen($content).";";

$sql= "SELECT GroupCode, GroupDescription,
 TeamHomeName,
 HomeScore TeamHomeScore,
 AwayScore TeamAwayScore,
 CONCAT(HomeScore,'-',AwayScore) Score,
 UNIX_TIMESTAMP(TMP.ScheduleDate) ScheduleDate,
 TeamAwayName,
 TeamAwayKey,
 TeamHomeKey,
 MatchKey
 FROM (
select
(SELECT COUNT(1) FROM events WHERE events.EventType IN (1,2,3) AND events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey) HomeScore,
(SELECT COUNT(1) FROM events WHERE events.EventType IN (1,2,3) AND events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey) AwayScore,
groups.DayKey GroupCode,
groups.Description GroupDescription,
TeamHome.Name TeamHomeName,
TeamHome.PrimaryKey TeamHomeKey,
TeamAway.Name TeamAwayName,
TeamAway.PrimaryKey TeamAwayKey,
matches.ScheduleDate,
matches.PrimaryKey MatchKey
FROM matches
INNER JOIN results ON results.MatchKey=matches.PrimaryKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=".COMPETITION;

if ($groupKeys!="All") {
  $sql .=" AND groups.PrimaryKey IN ($groupKeys) ";
}

$sql .=" INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
) TMP
WHERE CONCAT(HomeScore,'-',AwayScore)='$score'";

if ($teamKeys!="All") {
  $sql .=" AND (TeamHomeKey IN ($teamKeys) OR TeamAwayKey IN ($teamKeys)) ";
}


$sql .= " ORDER BY 1";
$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

$scheduleMonth = "00";
$scheduleDay = "00";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $tempScheduleMonth=strftime("%m",$rowSet['ScheduleDate']);
  $tempScheduleDay=strftime("%d",$rowSet['ScheduleDate']);
  if (!($scheduleMonth==$tempScheduleMonth && $scheduleDay==$tempScheduleDay))
  {

    setlocale(LC_TIME, "fr_FR");
    $scheduleFormattedDate = strftime("%A %d %B %Y",$rowSet['ScheduleDate']);

    $content.= '<tr class="day"
      	    style="">
      	  <td colspan="6">' . $rowSet['GroupDescription'] ." : " . $scheduleFormattedDate . '</td>
      	  <td colspan="2">&nbsp;</td>
      	</tr>';
  }

  $content.='<tr class="match" match-key="' . $rowSet['MatchKey'] . '" >
      	  <td class="time">' . strftime("%H:%M",$rowSet['ScheduleDate']) . '</td>
      	  <td class="teamHome">' . $rowSet['TeamHomeName'] . '</td>
      	  <td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamHomeKey'] . '.png" width="30px" height="30px"></img></td>';
   $line.="9-len:".strlen($content).";";


    $content.='<td class="score">' . $rowSet["TeamHomeScore"] . "-" . $rowSet["TeamAwayScore"] .'</td>';

  $content.='<td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamAwayKey'] . '.png"></img></td>
      	  <td class="teamAway">' . $rowSet['TeamAwayName'] . '</td>';

  $content.='</tr>';
  $scheduleMonth = strftime("%m",$rowSet['ScheduleDate']);
  $scheduleDay = strftime("%d",$rowSet['ScheduleDate']);

}
unset($rowSet,$resultSet,$sql);
$content.='</table></div>';

$arr["status"] = false;
$arr["message"] =__encode($content);

WriteJsonResponse($arr);
?>