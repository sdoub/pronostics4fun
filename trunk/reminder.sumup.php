<?php
require_once("begin.file.php");

if (isset($_GET["PlayerKey"])) {
  $_playerKey = $_GET["PlayerKey"];
}
else
{
  $_playerKey = 19;
}


echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Pronostics4Fun - Rappel</title>
<link rel="icon" href="http://pronostics4fun.com/favico.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://pronostics4fun.com/favico.ico" type="image/x-icon" />
</head>
<body>
';


$sql = "SELECT NickName, ActivationKey, UNIX_TIMESTAMP((CURDATE()+ INTERVAL 1 DAY)) tomorrowDate FROM playersenabled players WHERE PrimaryKey=$_playerKey";

$resultSet = $_databaseObject->queryPerf($sql,"Get player information");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$activationKey = $rowSet["ActivationKey"];
echo "<p align='center'>Si ce message ne s'affiche pas correctement, visualisez-le <a href='".ROOT_SITE."/reminder.sumup.php?PlayerKey=$_playerKey'>ici</a></p><hr/>";

echo "<div ><a style='border:0;' href='".ROOT_SITE."'><img style='border:0;' src='".ROOT_SITE . $_themePath ."/images/Logo.png' ></a></div><br>";

echo '<p>Bonjour <strong>' . $rowSet["NickName"] . '</strong>,</p>';

echo "<p>Vous recevez cet email, car le/les match(s) suivant se dérouleront demain, et vous n'avez pas validé vos pronostics.<br/>";
$tomorrowFormattedDate = strftime("%A %d %B %Y",$rowSet['tomorrowDate']);

echo '<table style="width:500px;font-size:14px;border-spacing:0px;border-collapse:collapse">
<tr style="background-color:#6d8aa8;color:#FFFFFF;font-weight:bold;">
<td style="vertical-align: middle;font-size:14px;font-variant: small-caps ;" colspan="4"><img src="' . ROOT_SITE . '/images/stadium.png" style="height:20px;width:20px;padding-right:15px;"/>Pronostics non validés pour le ' . $tomorrowFormattedDate . '</td>
</tr>';

$query2= "SELECT TeamHome.Name TeamHomeName,
      TeamHome.PrimaryKey TeamHomeKey,
TeamAway.Name TeamAwayName,
TeamAway.PrimaryKey TeamAwayKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.IsBonusMatch
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
WHERE NOT EXISTS (SELECT 1 FROM
forecasts
WHERE matches.PrimaryKey=forecasts.MatchKey
AND forecasts.PlayerKey=$_playerKey)
AND DATE(matches.ScheduleDate)=(CURDATE()+ INTERVAL 1 DAY)
 ORDER BY  matches.ScheduleDate";

$resultSet = $_databaseObject->queryPerf($query2,"Get matches linked to selected group");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  echo '
<tr style="border-bottom:1px solid #CCCCCC;">';
  $styleBonus = "";

  if ($rowSet["IsBonusMatch"]==1) {
    $styleBonus = "background: url('".ROOT_SITE."/images/star_25.png') no-repeat scroll left center transparent;";
  }

  echo '<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;text-align:right;'.$styleBonus.'">'.$rowSet["TeamHomeName"].'<img src="'.ROOT_SITE.'/images/teamFlags/'.$rowSet["TeamHomeKey"].'.png" width="25px" height="25px"/></td>
<td style="border-bottom:1px solid #CCCCCC;font-size:16px;vertical-align:bottom;width:50px;text-align:center;">&nbsp;-&nbsp;</td>
<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;text-align:left;"><img src="'.ROOT_SITE.'/images/teamFlags/'.$rowSet["TeamAwayKey"].'.png" width="25px" height="25px"/>'.$rowSet["TeamAwayName"].'</td>';
  $scheduleFormattedDate = strftime("%H:%M",$rowSet['ScheduleDate']);

  echo '<td style="border-bottom:1px solid #CCCCCC;">'.$scheduleFormattedDate.'</td>';

  echo '</tr>
';
}

echo '</tr>';

$query2= "SELECT TeamHome.Name TeamHomeName,
      TeamHome.PrimaryKey TeamHomeKey,
TeamAway.Name TeamAwayName,
TeamAway.PrimaryKey TeamAwayKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.IsBonusMatch
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
WHERE NOT EXISTS (SELECT 1 FROM
forecasts
WHERE matches.PrimaryKey=forecasts.MatchKey
AND forecasts.PlayerKey=$_playerKey)
AND DATE(matches.ScheduleDate)=(CURDATE())
 ORDER BY  matches.ScheduleDate";

$resultSet = $_databaseObject->queryPerf($query2,"Get matches linked to selected group");


$today ="";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $today .= '<tr style="border-bottom:1px solid #CCCCCC;">';
  $styleBonus = "";

  if ($rowSet["IsBonusMatch"]==1) {
    $styleBonus = "background: url('".ROOT_SITE."/images/star_25.png') no-repeat scroll left center transparent;";
  }

  $today .= '<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;text-align:right;'.$styleBonus.'">'.$rowSet["TeamHomeName"].'<img src="'.ROOT_SITE.'/images/teamFlags/'.$rowSet["TeamHomeKey"].'.png" width="25px" height="25px"/></td>
<td style="border-bottom:1px solid #CCCCCC;font-size:16px;vertical-align:bottom;width:50px;text-align:center;">&nbsp;-&nbsp;</td>
<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;text-align:left;"><img src="'.ROOT_SITE.'/images/teamFlags/'.$rowSet["TeamAwayKey"].'.png" width="25px" height="25px"/>'.$rowSet["TeamAwayName"].'</td>';
  $scheduleFormattedDate = strftime("%H:%M",$rowSet['ScheduleDate']);

  $today .= '<td style="border-bottom:1px solid #CCCCCC;">'.$scheduleFormattedDate.'</td>';

  $today .= '</tr>
';
}
if ($today) {
  echo '<tr style="background-color:#6d8aa8;color:#FFFFFF;font-weight:bold;">
<td style="vertical-align: middle;font-size:14px;font-variant: small-caps ;" colspan="5"><img src="'.ROOT_SITE.'/images/warning.png" style="height:20px;width:20px;padding-right:15px;"/>Attention! Pronostics non validés pour aujourd\'hui</td>
</tr>
';
  echo $today;
  echo '<tr>
<td colspan="5">&nbsp;</td>
</tr>
';
}
echo "</table>";

echo "<p>Dépêchez-vous de vous rendre sur <a href='http://pronostics4fun.com/index.php?Page=1'>pronostics4fun.com</a> pour saisir vos pronostics!";
echo "<p>L'administrateur de Pronostics4Fun.</p>

<p style='font-size:12px;'>p.s. : Si vous ne souhaitez plus recevoir les résultats par emails, <a href='".ROOT_SITE."/unsubscribe.php?type=1&key=".$activationKey."'>cliquez ici</a></p>
</body>
</html>";

//writePerfInfo();

require_once("end.file.php");
?>