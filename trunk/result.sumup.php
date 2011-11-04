<?php
require_once("begin.file.php");

if (isset($_GET["PlayerKey"])) {
  $_playerKey = $_GET["PlayerKey"];
}
else
{
  $_playerKey = 19;
}

if (isset($_GET["GroupKey"])) {
  $_groupKey = $_GET["GroupKey"];
}
else
{
  $_groupKey = 40;
}


$sql = "SELECT Description FROM groups WHERE PrimaryKey=" . $_groupKey;
$resultSet = $_databaseObject->queryPerf($sql,"Get group information");

$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$_groupDescription = $rowSet['Description'];
unset($rowSet,$resultSet,$sql);

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>' . __encode("Pronostics4Fun - Résulat de la ") . $_groupDescription .'</title>
<link rel="icon" href="http://pronostics4fun.com/favico.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://pronostics4fun.com/favico.ico" type="image/x-icon" />
</head>
<body>
';


$sql = "SELECT NickName, ActivationKey FROM playersenabled players WHERE PrimaryKey=$_playerKey";

$resultSet = $_databaseObject->queryPerf($sql,"Get player information");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$activationKey = $rowSet["ActivationKey"];
echo "<p align='center'>Si ce message ne s'affiche pas correctement, visualisez-le <a href='".ROOT_SITE."/result.sumup.php?PlayerKey=$_playerKey&GroupKey=$_groupKey'>ici</a></p><hr/>";

echo "<div ><a style='border:0;' href='".ROOT_SITE."'><img style='border:0;' src='".ROOT_SITE."/images/Logo.png' ></a></div><br>";

echo '<p>Bonjour <strong>' . $rowSet["NickName"] . '</strong>,</p>';





echo '<table style="width:500px;font-size:14px;border-spacing:0px;border-collapse:collapse">
<tr style="background-color:#6d8aa8;color:#FFFFFF;font-weight:bold;">
<td style="vertical-align: middle;font-size:14px;font-variant: small-caps ;" colspan="5"><img src="' . ROOT_SITE . '/images/TropheeGold.png" style="height:20px;width:20px;padding-right:15px;"/>Vos résultats de la '.$_groupDescription.'</td>
</tr>';



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
matches.IsBonusMatch
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=$_playerKey
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
LEFT JOIN playermatchresults ON playermatchresults.PlayerKey=$_playerKey AND playermatchresults.MatchKey=matches.PrimaryKey
WHERE matches.GroupKey=$_groupKey
ORDER BY matches.ScheduleDate";


$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  echo '
<tr style="border-bottom:1px solid #CCCCCC;">';
  $styleBonus = "";

  if ($rowSet["IsBonusMatch"]==1) {
    $styleBonus = "background: url('".ROOT_SITE."/images/star_25.png') no-repeat scroll left center transparent;";
  }

  echo '<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;text-align:right;'.$styleBonus.'">'.$rowSet["TeamHomeName"].'<img src="'.ROOT_SITE.'/images/teamFlags/'.$rowSet["TeamHomeKey"].'.png" width="25px" height="25px"/></td>
<td style="border-bottom:1px solid #CCCCCC;font-size:16px;vertical-align:bottom;width:50px;text-align:center;">'.$rowSet["TeamHomeScore"].'-'.$rowSet["TeamAwayScore"].'</td>
<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;text-align:left;"><img src="'.ROOT_SITE.'/images/teamFlags/'.$rowSet["TeamAwayKey"].'.png" width="25px" height="25px"/>'.$rowSet["TeamAwayName"].'</td>';
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
echo '<tr>
<td colspan="5">&nbsp;</td>
</tr>
';
echo '<tr>
<td colspan="5">&nbsp;</td>
</tr>
';


echo '</tr>
<tr style="background-color:#6d8aa8;color:#FFFFFF;font-weight:bold;">
<td style="vertical-align: middle;font-size:14px;font-variant: small-caps ;" colspan="5"><img src="'.ROOT_SITE.'/images/TropheeGold.png" style="height:20px;width:20px;padding-right:15px;"/>Classement de la '.$_groupDescription.'</td>
</tr>
';
$sql = "SELECT
(SELECT PGRK.Rank FROM playergroupranking PGRK WHERE players.PrimaryKey=PGRK.PlayerKey AND PGRK.GroupKey=$_groupKey ORDER BY RankDate desc LIMIT 0,1) Rank,
(SELECT PGRK.Rank FROM playergroupranking PGRK WHERE players.PrimaryKey=PGRK.PlayerKey AND PGRK.GroupKey=$_groupKey ORDER BY RankDate desc LIMIT 1,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName NickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")),0)) Score
FROM playersenabled players
WHERE (EXISTS (SELECT 1 FROM forecasts INNER JOIN matches ON matches.PrimaryKey=forecasts.MatchKey AND matches.GroupKey=$_groupKey WHERE forecasts.PlayerKey=players.PrimaryKey)
OR players.ReceiveResult=1)
GROUP BY NickName
ORDER BY Score desc, NickName";

$resultSet = $_databaseObject->queryPerf($sql,"Get group table ranking");
$currentRecord=0;
$currentPlayerHasBeenDisplayed = false;
$nbrOfPlayers = $_databaseObject->num_rows();
$nbrOfPlayers--; // due to 0 base
$displayThreePoint = false;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $playerStyle="";
  if ($rowSet["PlayerKey"]==$_playerKey) {
    $playerStyle="font-weight:bold;";
    $currentPlayerHasBeenDisplayed = true;
  }
  $score  = $rowSet["Score"]."pt";
  if ($rowSet["Score"]>1) {
    $score =$rowSet["Score"]."pts";
  }
  if ($currentRecord<3 || ($currentRecord<5 && $currentPlayerHasBeenDisplayed) || $currentRecord==$nbrOfPlayers || $rowSet["PlayerKey"]==$_playerKey) {
    echo '<tr style="border-bottom:1px solid #CCCCCC;'.$playerStyle.'">
<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;padding-left:15px;" colspan="4">
<span style="background-color:#365F89;
border:1px solid #FFFFFF;
color:#FFFFFF;
font-family:tahoma;
font-size:10px;
font-weight:bold;
margin-left:2px;
margin-right:5px;
padding:2px 2px 4px 1px;
text-align:center;
width:30px;float:left;
">'.$rowSet["Rank"].'.</span>&nbsp;' .$rowSet["NickName"] .'</td>
<td style="border-bottom:1px solid #CCCCCC;text-align:right;padding-right:5px;">'.  $score .'</td>
</tr>';

    if ($rowSet["PlayerKey"]==$_playerKey) {
      $displayThreePoint = true;
    }
  }
  else {

    if ($currentRecord==4 || $displayThreePoint)
    echo '<tr style="border-bottom:1px solid #CCCCCC;">
<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;padding-left:15px;" colspan="4">.....</td>
<td style="border-bottom:1px solid #CCCCCC;text-align:right;padding-right:5px;">....</td>
</tr>';
    $displayThreePoint=false;


  }

  $currentRecord++;
}

echo '<tr>
<td colspan="5">&nbsp;</td>
</tr>
';
echo '<tr style="background-color:#6d8aa8;color:#FFFFFF;font-weight:bold;">
<td style="vertical-align: middle;font-size:14px;font-variant: small-caps ;" colspan="5"><img src="'.ROOT_SITE.'/images/podium.png" style="height:20px;width:20px;padding-right:15px;"/>Classement général</td>
</tr>
';

$sql = "SELECT
(SELECT playerranking.Rank FROM playerranking WHERE playerranking.CompetitionKey=" . COMPETITION . " AND players.PrimaryKey=playerranking.PlayerKey ORDER BY RankDate desc LIMIT 0,1) Rank,
(SELECT playerranking.Rank FROM playerranking WHERE playerranking.CompetitionKey=" . COMPETITION . " AND players.PrimaryKey=playerranking.PlayerKey ORDER BY RankDate desc LIMIT 1,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score
FROM playersenabled players
GROUP BY NickName
HAVING Score>0
ORDER BY Score desc, NickName";


$resultSet = $_databaseObject->queryPerf($sql,"Get global table ranking");
$currentRecord=0;
$currentPlayerHasBeenDisplayed = false;
$nbrOfPlayers = $_databaseObject->num_rows();
$nbrOfPlayers--; // due to 0 base
$displayThreePoint = false;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $playerStyle="";
  if ($rowSet["PlayerKey"]==$_playerKey) {
    $playerStyle="font-weight:bold;";
    $currentPlayerHasBeenDisplayed = true;
  }
  $score  = $rowSet["Score"]."pt";
  if ($rowSet["Score"]>1) {
    $score =$rowSet["Score"]."pts";
  }
  if ($currentRecord<3 || ($currentRecord<5 && $currentPlayerHasBeenDisplayed) || $currentRecord==$nbrOfPlayers || $rowSet["PlayerKey"]==$_playerKey) {
    echo '<tr style="border-bottom:1px solid #CCCCCC;'.$playerStyle.'">
<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;padding-left:15px;" colspan="4">
<span style="background-color:#365F89;
border:1px solid #FFFFFF;
color:#FFFFFF;
font-family:tahoma;
font-size:10px;
font-weight:bold;
margin-left:2px;
margin-right:5px;
padding:2px 2px 4px 1px;
text-align:center;
width:30px;float:left;
">'.$rowSet["Rank"].'.</span>&nbsp;' .$rowSet["NickName"] .'</td>
<td style="border-bottom:1px solid #CCCCCC;text-align:right;padding-right:5px;">'.  $score .'</td>
</tr>';

    if ($rowSet["PlayerKey"]==$_playerKey) {
      $displayThreePoint = true;
    }
  }
  else {

    if ($currentRecord==4 || $displayThreePoint)
    echo '<tr style="border-bottom:1px solid #CCCCCC;">
<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;padding-left:15px;" colspan="4">.....</td>
<td style="border-bottom:1px solid #CCCCCC;text-align:right;padding-right:5px;">....</td>
</tr>';
    $displayThreePoint=false;


  }

  $currentRecord++;
}

echo "</table>";
echo "<p>Rendez-vous sur <a href='".ROOT_SITE."'>".ROOT_SITE."</a> pour consulter plus de détail!</p>
<p>L'administrateur de Pronostics4Fun.</p>

<p style='font-size:12px;'>p.s. : Si vous ne souhaitez plus recevoir les résultats par emails, <a href='".ROOT_SITE."/unsubscribe.php?type=2&key=".$activationKey."'>cliquez ici</a></p>
</body>
</html>";

//writePerfInfo();

require_once("end.file.php");
?>