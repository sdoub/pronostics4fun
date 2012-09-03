<?php

$teamPlayerKey = $_GET["teamPlayerKey"];
$teamKey = $_GET["teamKey"];
$line ="1-;";
$content ='';

$content.='<div id="FixedContent"><table>
	<tr>
		<td colspan="6">&nbsp;</td>
		<td
			style="width: 100px; text-align: center; font-family: georgia; font-size: 12px; font-weight: bold; color: #365F89">Minute</td>
		<td
			style="width: 100px; text-align: center; font-family: georgia; font-size: 12px; font-weight: bold; color: #365F89">Penalty?</td>
	</tr>';
$line.="3-len:".strlen($content).";";

$sql = "SELECT  TMP2.HomeResult,
 TMP2.AwayResult,
TMP2.HomeScore TeamHomeScore,
TMP2.AwayScore TeamAwayScore,
TMP2.MatchKey,
TMP2.TeamHomeKey,
TMP2.TeamAwayKey,
TMP2.TeamHomeName,
TMP2.TeamAwayName,
TMP2.Description,
UNIX_TIMESTAMP(TMP2.ScheduleDate) ScheduleDate,
events.EventTime ,
events.EventType
FROM (
SELECT
IF ($teamKey!=TeamHomeKey,0,IF(SUM(HomeScore)>SUM(AwayScore),3, IF(SUM(HomeScore)=SUM(AwayScore),1,0))) HomeResult,
IF ($teamKey!=TeamAwayKey,0,IF(SUM(HomeScore)<SUM(AwayScore),3, IF(SUM(HomeScore)=SUM(AwayScore),1,0))) AwayResult,
SUM(HomeScore) HomeScore ,
SUM(AwayScore) AwayScore,
MatchKey,
TeamHomeKey,
TeamAwayKey,
TeamHome.Name TeamHomeName,
TeamAway.Name TeamAwayName,
groups.Description,
ScheduleDate FROM
    (
    SELECT 1 HomeScore,0 AwayScore, results.MatchKey, matches.TeamHomeKey, matches.TeamAwayKey, matches.GroupKey,matches.ScheduleDate
    from events
    INNER JOIN results ON results.PrimaryKey=events.ResultKey
     INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
     INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
     WHERE events.TeamKey=matches.TeamHomeKey
     AND events.EventType IN (1,2,3)
    UNION ALL
    SELECT 0,1, results.MatchKey, matches.TeamHomeKey, matches.TeamAwayKey, matches.GroupKey,matches.ScheduleDate
    from events
    INNER JOIN results ON results.PrimaryKey=events.ResultKey
     INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
     INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
     WHERE events.TeamKey=matches.TeamAwayKey
     AND events.EventType IN (1,2,3)) TMP
     INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=TeamHomeKey
     INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=TeamAwayKey
     INNER JOIN groups ON groups.PrimaryKey=GroupKey
  WHERE MatchKey IN (
 SELECT results.MatchKey
   FROM results
  INNER JOIN events ON results.PrimaryKey=events.ResultKey AND events.TeamPlayerKey=$teamPlayerKey AND events.EventType IN (1,2)
  INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
  INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
 GROUP BY MatchKey, TeamHomeKey, TeamAwayKey
 ) TMP2
 INNER JOIN results ON results.MatchKey=TMP2.MatchKey
 INNER JOIN events ON events.ResultKey=results.PrimaryKey AND events.TeamPlayerKey=$teamPlayerKey AND events.EventType IN (1,2)
 ORDER BY ScheduleDate DESC,EventTime DESC;";
$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

$scheduleMonth = "00";
$scheduleDay = "00";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $tempScheduleMonth=strftime("%m",$rowSet['ScheduleDate']);
  $tempScheduleDay=strftime("%d",$rowSet['ScheduleDate']);
  if (!($scheduleMonth==$tempScheduleMonth && $scheduleDay==$tempScheduleDay))
  {

    $scheduleFormattedDate = strftime("%A %d %B %Y",$rowSet['ScheduleDate']);

    $content.= '<tr class="day"
      	    style="">
      	  <td colspan="6">' . $rowSet['Description'] ." : " . $scheduleFormattedDate . '</td>
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
      	  <td class="teamAway">' . $rowSet['TeamAwayName'] . '</td>
      	  <td nowrap><div style="text-align:center;">';

      $content.=$rowSet['EventTime'] . "'";

  $content.='</div></td>';

  if ($rowSet["EventType"]==2) {
    $content.='<td style="text-align:right;padding-right:15px;">Oui</td>';
  }
  else
  {
    $content.='<td>&nbsp;</td>';
  }

  $content.='</tr>';
  $scheduleMonth = strftime("%m",$rowSet['ScheduleDate']);
  $scheduleDay = strftime("%d",$rowSet['ScheduleDate']);

}
unset($rowSet,$resultSet,$sql);
$content.='</table></div>';

$arr["status"] = false;
$arr["message"] =$content;

WriteJsonResponse($arr);
?>