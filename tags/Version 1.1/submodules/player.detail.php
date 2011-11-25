<?php

$playerKey = $_GET["playerKey"];
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
$line.="2-len:".strlen($content).";";


$content.='<div id="FixedContent"><table>
	<tr>
		<td colspan="6">&nbsp;</td>
		<td
			style="width: 100px; text-align: center; font-family: georgia; font-size: 12px; font-weight: bold; color: #365F89">Pronostics</td>
		<td
			style="width: 100px; text-align: center; font-family: georgia; font-size: 12px; font-weight: bold; color: #365F89">Points</td>
	</tr>';
$line.="3-len:".strlen($content).";";

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
(SELECT playerranking.Rank FROM playerranking WHERE playerranking.CompetitionKey=" . COMPETITION . " AND playerranking.PlayerKey=" . $playerKey . " AND playerranking.RankDate =DATE(results.ResultDate) ORDER BY RankDate desc LIMIT 0,1) GlobalRank,
(SELECT playerranking.Rank FROM playerranking WHERE playerranking.CompetitionKey=" . COMPETITION . " AND playerranking.PlayerKey=" . $playerKey . " AND playerranking.RankDate <=DATE(results.ResultDate) ORDER BY RankDate desc LIMIT 1,1) GlobalPreviousRank
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=" . $playerKey . "
INNER JOIN results ON matches.PrimaryKey=results.MatchKey
INNER JOIN playermatchresults ON playermatchresults.PlayerKey=" . $playerKey . " AND playermatchresults.MatchKey=matches.PrimaryKey
WHERE matches.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")
ORDER BY matches.ScheduleDate DESC";
$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");
$line.="4-len:".strlen($content).";";

$scheduleMonth = "00";
$scheduleDay = "00";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $line.="5-len:".strlen($content).";";

  $tempScheduleMonth=strftime("%m",$rowSet['ScheduleDate']);
  $tempScheduleDay=strftime("%d",$rowSet['ScheduleDate']);
  if (!($scheduleMonth==$tempScheduleMonth && $scheduleDay==$tempScheduleDay))
  {
    $line.="6-len:".strlen($content).";";

    setlocale(LC_TIME, "fr_FR");
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
      $variation="up";
      $diff = 0;
    }
$line.="7-len:".strlen($content).";";

    $content.= '<tr class="day"
      	    style="">
      	  <td colspan="6">' . $scheduleFormattedDate . '</td>
      	  <td colspan="2"><span class="var ' . $variation . '"></span>'. $rowSet["GlobalRank"] . ' (' . $diff . ')</td>
      	</tr>';
  }
$line.="8-len:".strlen($content).";";

  $content.='<tr class="match" match-key="' . $rowSet['MatchKey'] . '" status="' . $rowSet["LiveStatus"] . '">
      	  <td class="time">' . strftime("%H:%M",$rowSet['ScheduleDate']) . '</td>
      	  <td class="teamHome">' . $rowSet['TeamHomeName'] . '</td>
      	  <td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamHomeKey'] . '.png" width="30px" height="30px"></img></td>';
   $line.="9-len:".strlen($content).";";

  if ($rowSet["LiveStatus"]==10) {
$line.="10-len:".strlen($content).";";

    $content.='<td class="score">' . $rowSet["TeamHomeScore"] . "-" . $rowSet["TeamAwayScore"] .'</td>';
  }
  else {
$line.="11-len:".strlen($content).";";

    $content.='<td class="score">' . getStatus($rowSet["LiveStatus"]) .  '</td>';
  }
  $line.="12-len:".strlen($content).";";

  $content.='<td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamAwayKey'] . '.png"></img></td>
      	  <td class="teamAway">' . $rowSet['TeamAwayName'] . '</td>
      	  <td nowrap><div style="text-align:center;">';
  if (!($rowSet['ForecastTeamHomeScore']=="")) {
    $line.="13-len:".strlen($content).";";

    $content.=$rowSet['ForecastTeamHomeScore'] . "-" . $rowSet['ForecastTeamAwayScore'];
  }
  else
  {
    $line.="14-len:".strlen($content).";";

    $content.=__encode("Pas de pronos.");
  }

  $content.='</div></td>';
  $line.="15-len:".strlen($content).";";

  if ($rowSet["LiveStatus"]==10) {
    $content.='<td style="text-align:right;padding-right:15px;">' . $rowSet["Score"]. '</td>';
  }
  else
  {
    $line.="16-len:".strlen($content).";";

    $content.='<td>&nbsp;</td>';
  }

  $content.='</tr>';
$line.="17-len:".strlen($content).";";

  $scheduleMonth = strftime("%m",$rowSet['ScheduleDate']);
  $scheduleDay = strftime("%d",$rowSet['ScheduleDate']);

}
unset($rowSet,$resultSet,$sql);
/*
 $sql = "SELECT
 SUM(playermatchresults.Score) Score,
 IFNULL((SELECT playergroupresults.Score FROM playergroupresults WHERE playergroupresults.PlayerKey=playermatchresults.PlayerKey AND playergroupresults.GroupKey= $_groupKey),0) GroupScore,
 IFNULL((SELECT playergroupranking.Rank FROM playergroupranking WHERE playergroupranking.PlayerKey=playermatchresults.PlayerKey AND playergroupranking.GroupKey= $_groupKey ORDER BY playergroupranking.RankDate desc LIMIT 0,1),0) GroupRank,
 IFNULL((SELECT playergroupranking.Rank FROM playergroupranking WHERE playergroupranking.PlayerKey=playermatchresults.PlayerKey AND playergroupranking.GroupKey= $_groupKey ORDER BY playergroupranking.RankDate desc LIMIT 1,1),0) PreviousGroupRank
 FROM playermatchresults
 WHERE playermatchresults.PlayerKey=" . $_authorisation->getConnectedUserKey() . "
 AND playermatchresults.MatchKey IN (SELECT PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey  AND matches.PrimaryKey IN (SELECT MatchKey FROM results WHERE LiveStatus=10))";

 $resultSet = $_databaseObject->queryPerf($sql,"Get bonus and player score");

 $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
 $playerScore=$rowSet["Score"];
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
 */
$content.='</table></div>';
$line.="18-len:".strlen($content).";";

$arr["status"] = false;
$arr["message"] =__encode($content);
//$arr["lines"] =$line;

WriteJsonResponse($arr);
?>