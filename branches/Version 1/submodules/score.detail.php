<?php

$score = $_GET["score"];
$teamKeys = $_GET["teamKeys"];
$groupKeys = $_GET["groupKeys"];
$line ="1-;";
$content ='<style>
.day {
	font-size: 14px;
	text-align: center;
	background-color: #6D8AA8;
	color: #FFF;
	font-weight: bold;
	height: 25px;
}

.match {
	font-size: 12px;
	color: #365F89;
	font-weight: bold;
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}

.equipeRankUp {
	font-size: 12px;
	color: #365F89;
	font-weight: bold;
}

.equipeRankDown {
	font-size: 12px;
	color: #365F89;
	font-weight: normal;
}

.teamAway {
	text-align: left;
	width: 150px;
}

.teamFlag {
	width: 30px;
}

.teamFlag img {
	width: 30px;
	height: 30px;
}

.score {
	width: 80px;
	text-align: center;
}

.teamHome {
	width: 150px;
	text-align: right;
}

.time {
	width: 60px;
	padding-left: 20px;
}

#FixedContent {
	overflow:auto;
		width:480px;
		height:450px;
}

#FixedContent .var{
	background-image: url('. ROOT_SITE . '/images/sprite.png);
	background-repeat: no-repeat;
	height:25px;
	width:37px;
	padding:0px 25px 15px 15px;

}

#FixedContent .eq  { background-position: -13px -263px; }
#FixedContent .up  { background-position: -15px -315px; }
#FixedContent .down{ background-position: -14px -366px; }



</style>';

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