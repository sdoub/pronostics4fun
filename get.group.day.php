<?php
require_once("begin.file.php");

if (isset($_GET["RankDate"])) {
  $_rankDate = (int)substr($_GET["RankDate"], 0, -3) -((is_est((int)substr($_GET["RankDate"], 0, -3))?2:1)*3600);
}

if (isset($_GET["FullDay"]) ) {
  $_fullDay = $_GET["FullDay"];
}

$sql = "SELECT PrimaryKey FROM players WHERE NickName='" . $_GET["NickName"] . "'";
$resultSet = $_databaseObject->queryPerf($sql,"Get user information");

$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$_playerKey = $rowSet['PrimaryKey'];
unset($rowSet,$resultSet,$sql);

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="icon" href="http://pronostics4fun.com/favico.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://pronostics4fun.com/favico.ico" type="image/x-icon" />
</head>
<body>
';


echo '<table style="width:390px;font-size:8pt;border-spacing:0px;border-collapse:collapse">';


if ($_fullDay == "true"){

  $sql = "SELECT matches.PrimaryKey MatchKey,TeamHome.PrimaryKey TeamHomeKey,
       TeamHome.Name TeamHomeName,
forecasts.TeamHomeScore ForecastTeamHomeScore,
       TeamAway.PrimaryKey TeamAwayKey,
       TeamAway.Name TeamAwayName,
forecasts.TeamAwayScore ForecastTeamAwayScore,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.Status,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<5) TeamHomeScore90,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<5) TeamAwayScore90,
IFNULL(results.livestatus,0) LiveStatus,
playermatchresults.Score,
matches.IsBonusMatch,
matches.GroupKey,
UNIX_TIMESTAMP(DATE(groups.EndDate)) GroupEndDate,
UNIX_TIMESTAMP(DATE(FROM_UNIXTIME($_rankDate))) CurrentDate
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=$_playerKey
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
LEFT JOIN playermatchresults ON playermatchresults.PlayerKey=$_playerKey AND playermatchresults.MatchKey=matches.PrimaryKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND DATE(groups.EndDate)=DATE(FROM_UNIXTIME($_rankDate)) AND groups.CompetitionKey = " . COMPETITION . "
ORDER BY matches.ScheduleDate";

} else {
$sql = "SELECT matches.PrimaryKey MatchKey,TeamHome.PrimaryKey TeamHomeKey,
       TeamHome.Name TeamHomeName,
forecasts.TeamHomeScore ForecastTeamHomeScore,
       TeamAway.PrimaryKey TeamAwayKey,
       TeamAway.Name TeamAwayName,
forecasts.TeamAwayScore ForecastTeamAwayScore,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.Status,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<5) TeamHomeScore90,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<5) TeamAwayScore90,
IFNULL(results.livestatus,0) LiveStatus,
playermatchresults.Score,
matches.IsBonusMatch,
matches.GroupKey,
UNIX_TIMESTAMP(DATE(groups.EndDate)) GroupEndDate,
UNIX_TIMESTAMP(DATE(FROM_UNIXTIME($_rankDate))) CurrentDate
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=$_playerKey
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
LEFT JOIN playermatchresults ON playermatchresults.PlayerKey=$_playerKey AND playermatchresults.MatchKey=matches.PrimaryKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey = " . COMPETITION . "
WHERE DATE(matches.ScheduleDate)=DATE(FROM_UNIXTIME($_rankDate))
ORDER BY matches.ScheduleDate";
}

$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

$_groupKey= 0;
$_isGroupEndDay = false;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
$_groupKey =$rowSet["GroupKey"];
$_isGroupEndDay = $rowSet["GroupEndDate"] == $rowSet["CurrentDate"];
  echo '
<tr style="border-bottom:1px solid #CCCCCC;">';
  $styleBonus = "";

  if ($rowSet["IsBonusMatch"]==1) {
    $styleBonus = "background: url('".ROOT_SITE."/images/star_25.png') no-repeat scroll left center transparent;";
  }

  echo '<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:120px;text-align:right;'.$styleBonus.'">'.$rowSet["TeamHomeName"].'<img src="'.ROOT_SITE.'/images/teamFlags/'.$rowSet["TeamHomeKey"].'.png" width="20px" height="20px"/></td>
<td style="border-bottom:1px solid #CCCCCC;font-size:12px;vertical-align:bottom;width:50px;text-align:center;">'.$rowSet["TeamHomeScore"].'-'.$rowSet["TeamAwayScore"].'</td>
<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:120px;text-align:left;"><img src="'.ROOT_SITE.'/images/teamFlags/'.$rowSet["TeamAwayKey"].'.png" width="20px" height="20px"/>'.$rowSet["TeamAwayName"].'</td>';
  $scoreResult ="";

  if ($rowSet["ForecastTeamHomeScore"]!=null) {
    if ($rowSet["TeamHomeScore"]==$rowSet["ForecastTeamHomeScore"]) {
      $scoreResult .= "<strong>" . $rowSet["ForecastTeamHomeScore"] . "</strong>";
    }
    else {
      $scoreResult .= $rowSet["ForecastTeamHomeScore"];
    }
    $scoreResult .= "-";

    if ($rowSet["TeamAwayScore"]==$rowSet["ForecastTeamAwayScore"]) {
      $scoreResult .= "<strong>" . $rowSet["ForecastTeamAwayScore"] . "</strong>";
    }
    else {
      $scoreResult .= $rowSet["ForecastTeamAwayScore"];
    }
  }
  else {
    $scoreResult = "<span style='font-size:10px;'>Pas de pronostics</span>";
  }

  echo '<td style="border-bottom:1px solid #CCCCCC;">'.$scoreResult.'</td>';

  $styleScore = "";

  if ($rowSet["Score"]>=5) {
    $styleScore .= "color:#96EF95";
  }
  else {
    $styleScore .= "color:#EF6868";
  }
  $score = "";
  if ($rowSet["Score"]>1) {
    $score .= $rowSet["Score"] . "pts";
  }
  else {
    if ($rowSet["Score"]!=null)
    $score .= $rowSet["Score"] . "pt";
    else
    $score .= "0pt";
  }


  echo '<td style="border-bottom:1px solid #CCCCCC;text-align:right;padding-right:5px;'.$styleScore.'">'.$score.'</td>
</tr>
';
}


$sql = "SELECT
SUM(playermatchresults.Score) Score,
IFNULL((SELECT playergroupresults.Score FROM playergroupresults WHERE playergroupresults.PlayerKey=playermatchresults.PlayerKey AND playergroupresults.GroupKey= $_groupKey),0) GroupScore,
IFNULL((SELECT playergroupranking.Rank FROM playergroupranking WHERE playergroupranking.PlayerKey=playermatchresults.PlayerKey AND playergroupranking.GroupKey= $_groupKey ORDER BY playergroupranking.RankDate desc LIMIT 0,1),0) GroupRank,
IFNULL((SELECT playergroupranking.Rank FROM playergroupranking WHERE playergroupranking.PlayerKey=playermatchresults.PlayerKey AND playergroupranking.GroupKey= $_groupKey ORDER BY playergroupranking.RankDate desc LIMIT 1,1),0) PreviousGroupRank
FROM playermatchresults
WHERE playermatchresults.PlayerKey=$_playerKey
  AND playermatchresults.MatchKey IN (SELECT PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey  AND matches.PrimaryKey IN (SELECT MatchKey FROM results WHERE LiveStatus=10))";


$resultSet = $_databaseObject->queryPerf($sql,"Get player result information");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);

$bonus = "0pt";
if ($rowSet["GroupScore"]>0) {
  $bonus =$rowSet["GroupScore"]."pts";
}

$grandTotal = (int)$rowSet["GroupScore"]+(int)$rowSet["Score"];
$grandTotalDisplayed = $grandTotal."pt";
if ($grandTotal>1) {
  $grandTotalDisplayed=$grandTotal."pts";
}

if ($_fullDay=="true") {
echo '<tr>
<td colspan="3">&nbsp;</td>
<td style="border-bottom:1px solid #CCCCCC;background-color:#6d8aa8;color:#FFFFFF;font-weight:bold;">Bonus</td>
<td style="border-bottom:1px solid #CCCCCC;text-align:right;padding-right:5px;">'.$bonus.'</td>
</tr>
<tr>
<td colspan="3">&nbsp;</td>
<td >Total</td>
<td style="text-align:right;padding-right:5px;font-weight:bold;font-style:italic;">'.$grandTotalDisplayed.'</td>
</tr>';
} else {
  if ($rowSet["GroupScore"]>0 && $_isGroupEndDay) {
  echo '<tr>
<td colspan="3">&nbsp;</td>
<td style="border-bottom:1px solid #CCCCCC;background-color:#6d8aa8;color:#FFFFFF;font-weight:bold;">Bonus</td>
<td style="border-bottom:1px solid #CCCCCC;text-align:right;padding-right:5px;">'.$bonus.'</td>
</tr>
';
}}
echo "</table>";
echo "</body>
</html>";

writePerfInfo();

require_once("end.file.php");
?>