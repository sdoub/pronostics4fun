<?php

$playerKey = $_GET["playerKey"];
$groupKey = $_GET["groupKey"];

$content ='';


$content.='<div id="FixedContent"><table>
	<tr>
		<td colspan="6">&nbsp;</td>
		<td
			style="width: 100px; text-align: center; font-family: georgia; font-size: 12px; font-weight: bold; color: #365F89">Pronostics</td>
		<td
			style="width: 100px; text-align: center; font-family: georgia; font-size: 12px; font-weight: bold; color: #365F89">Points</td>
	</tr>';

	$sql = "SELECT matches.PrimaryKey MatchKey,TeamHome.PrimaryKey TeamHomeKey,
       TeamHome.Name TeamHomeName,
forecasts.TeamHomeScore ForecastTeamHomeScore,
       TeamAway.PrimaryKey TeamAwayKey,
       TeamAway.Name TeamAwayName,
forecasts.TeamAwayScore ForecastTeamAwayScore,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.Status,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3)) TeamHomeScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3)) TeamAwayScore,
IFNULL(results.livestatus,0) LiveStatus,
playermatchresults.Score,
(SELECT playergroupranking.Rank FROM playergroupranking WHERE playergroupranking.PlayerKey=" . $playerKey . " AND playergroupranking.GroupKey=" . $groupKey . " AND playergroupranking.RankDate =DATE(results.ResultDate) ORDER BY RankDate desc LIMIT 0,1) GlobalRank,
(SELECT playergroupranking.Rank FROM playergroupranking WHERE playergroupranking.PlayerKey=" . $playerKey . " AND playergroupranking.GroupKey=" . $groupKey . " AND playergroupranking.RankDate <=DATE(results.ResultDate) ORDER BY RankDate desc LIMIT 1,1) GlobalPreviousRank
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=" . $playerKey . "
INNER JOIN results ON matches.PrimaryKey=results.MatchKey
INNER JOIN playermatchresults ON playermatchresults.PlayerKey=" . $playerKey . " AND playermatchresults.MatchKey=matches.PrimaryKey
WHERE matches.GroupKey=$groupKey
ORDER BY matches.ScheduleDate ASC";
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

	    if ($rowSet["GlobalPreviousRank"]) {
    if ($rowSet["GlobalPreviousRank"]>$rowSet["GlobalRank"]) {
      $variation="up";
      $diff = "+" . ($rowSet["GlobalPreviousRank"]-$rowSet["GlobalRank"]);
    }
    else if ($rowSet["GlobalPreviousRank"]<$rowSet["GlobalRank"]) {
      $variation="down";
      $diff = $rowSet["GlobalPreviousRank"]-$rowSet["GlobalRank"];
    }
    else {
      $variation="eq";
      $diff = 0;
    }
  }
  else {
    $variation="eq";
    $diff = 0;
  }

	    $content.= '<tr class="day"
      	    style="">
      	  <td colspan="6">' . $scheduleFormattedDate . '</td>
      	  <td colspan="2"><span class="var ' . $variation . '"></span>'. $rowSet["GlobalRank"] . ' (' . $diff . ')</td>
      	</tr>';
	  }

	  $content.='<tr class="match" match-key="' . $rowSet['MatchKey'] . '" status="' . $rowSet["LiveStatus"] . '">
      	  <td class="time">' . strftime("%H:%M",$rowSet['ScheduleDate']) . '</td>
      	  <td class="teamHome">' . $rowSet['TeamHomeName'] . '</td>
      	  <td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamHomeKey'] . '.png" width="30px" height="30px"></img></td>';

      if ($rowSet["LiveStatus"]==10) {
	    $content.='<td class="score">' . $rowSet["TeamHomeScore"] . "-" . $rowSet["TeamAwayScore"] .'</td>';
      }
      else {
        $content.='<td class="score">' . getStatus($rowSet["LiveStatus"]) .  '</td>';
      }
      	  $content.='<td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamAwayKey'] . '.png"></img></td>
      	  <td class="teamAway">' . $rowSet['TeamAwayName'] . '</td>
      	  <td nowrap><div style="text-align:center;">';
	  if (!($rowSet['ForecastTeamHomeScore']=="")) {
	    $content.=$rowSet['ForecastTeamHomeScore'] . "-" . $rowSet['ForecastTeamAwayScore'];
	  }
	  else
	  {
	    $content.="Pas de pronos.";
	  }

	  $content.='</div></td>';
	  if ($rowSet["LiveStatus"]==10) {
        $content.='<td style="text-align:right;padding-right:15px;">' . $rowSet["Score"]. '</td>';
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

	$sql = "SELECT
SUM(playermatchresults.Score) Score,
IFNULL((SELECT playergroupresults.Score FROM playergroupresults WHERE playergroupresults.PlayerKey=playermatchresults.PlayerKey AND playergroupresults.GroupKey= $groupKey),0) GroupScore,
IFNULL((SELECT playergroupranking.Rank FROM playergroupranking WHERE playergroupranking.PlayerKey=playermatchresults.PlayerKey AND playergroupranking.GroupKey= $groupKey ORDER BY playergroupranking.RankDate desc LIMIT 0,1),0) GroupRank,
IFNULL((SELECT playergroupranking.Rank FROM playergroupranking WHERE playergroupranking.PlayerKey=playermatchresults.PlayerKey AND playergroupranking.GroupKey= $groupKey ORDER BY playergroupranking.RankDate desc LIMIT 1,1),0) PreviousGroupRank
FROM playermatchresults
WHERE playermatchresults.PlayerKey=" . $playerKey . "
  AND playermatchresults.MatchKey IN (SELECT PrimaryKey FROM matches WHERE matches.GroupKey=$groupKey  AND matches.PrimaryKey IN (SELECT MatchKey FROM results WHERE LiveStatus=10))";

	$resultSet = $_databaseObject->queryPerf($sql,"Get bonus and player score");

	$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
	$playerScore=$rowSet["Score"]+$rowSet["GroupScore"];
	$groupScore=$rowSet["GroupScore"];

	$groupRank=$rowSet["GroupRank"];
  if ($rowSet["PreviousGroupRank"]) {
    if ($rowSet["PreviousGroupRank"]>$rowSet["GroupRank"]) {
      $variation = "up";
    }
    else if ($rowSet["PreviousGroupRank"]<$rowSet["GroupRank"]) {
      $variation = "down";
    }
    else {
      $variation = "eq";
    }
  }
  else
  {
    $variation = "eq";
  }

$content.= '<tr class="match"><td style="text-align:right;padding-right:15px;padding-top:10px;" colspan="7">Bonus : </td><td style="text-align:right;padding-right:15px;padding-top:10px;">+' . $groupScore . '</td></tr>';
$content.= '<tr class="match"><td style="text-align:right;padding-right:15px;padding-top:10px;" colspan="7">Total : </td><td style="text-align:right;padding-right:15px;border-top: 1px solid #FFF;padding-top:10px;">' . $playerScore . '</td></tr>';

$content.='</table></div>';

$arr["status"] = false;
$arr["message"] =$content;

WriteJsonResponse($arr);
?>