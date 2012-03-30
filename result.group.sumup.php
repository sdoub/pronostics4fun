<?php
require_once("begin.file.php");

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

echo "<p align='center'>Si ce message ne s'affiche pas correctement, visualisez-le <a href='".ROOT_SITE."/result.group.sumup.php?GroupKey=$_groupKey'>ici</a></p><hr/>";

echo "<div ><a style='border:0;' href='".ROOT_SITE."'><img style='border:0;' src='".ROOT_SITE."/images/Logo.png' ></a></div><br>";

echo '<p>Bonjour,</p>';

echo '<table style="width:500px;font-size:14px;border-spacing:0px;border-collapse:collapse">
<tr style="background-color:#6d8aa8;color:#FFFFFF;font-weight:bold;">
<td style="vertical-align: middle;font-size:14px;font-variant: small-caps ;" colspan="5"><img src="' . ROOT_SITE . '/images/stats.png" style="height:20px;width:20px;padding-right:15px;"/>Résumé de la '.$_groupDescription.'</td>
</tr>';



$sql = "SELECT matches.PrimaryKey MatchKey,TeamHome.PrimaryKey TeamHomeKey,
       TeamHome.Name TeamHomeName,
       TeamAway.PrimaryKey TeamAwayKey,
       TeamAway.Name TeamAwayName,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.Status,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<5) TeamHomeScore90,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<5) TeamAwayScore90,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey ) TeamHomeEvents,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey ) TeamAwayEvents,
IFNULL(results.livestatus,0) LiveStatus,
matches.IsBonusMatch
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
WHERE matches.GroupKey=$_groupKey
ORDER BY matches.ScheduleDate";


$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  echo '
<tr style="background-color:#d4f2ff;border-bottom:1px solid #CCCCCC;">';
  $styleBonus = "";

  if ($rowSet["IsBonusMatch"]==1) {
    $styleBonus = "background: url('".ROOT_SITE."/images/star_25.png') no-repeat scroll left center transparent;";
  }

  echo '<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;text-align:right;'.$styleBonus.'">'.$rowSet["TeamHomeName"].'<img src="'.ROOT_SITE.'/images/teamFlags/'.$rowSet["TeamHomeKey"].'.png" width="25px" height="25px"/></td>
<td style="border-bottom:1px solid #CCCCCC;font-size:16px;vertical-align:bottom;width:50px;text-align:center;">'.$rowSet["TeamHomeScore"].'-'.$rowSet["TeamAwayScore"].'</td>
<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:150px;text-align:left;"><img src="'.ROOT_SITE.'/images/teamFlags/'.$rowSet["TeamAwayKey"].'.png" width="25px" height="25px"/>'.$rowSet["TeamAwayName"].'</td>';
  echo '</tr><tr><td style="vertical-align:top;">';
  $_matchKey = $rowSet["MatchKey"];
  $teamHomeKey = $rowSet["TeamHomeKey"];
  $teamAwayKey = $rowSet["TeamAwayKey"];
  $teamHomeEvents = $rowSet["TeamHomeEvents"];
  $teamAwayEvents = $rowSet["TeamAwayEvents"];

  $maxGoal = 0;
  if ($teamHomeEvents>$teamAwayEvents) {
    $maxGoal = $teamHomeEvents;
  }
  else {
    $maxGoal = $teamAwayEvents;
  }

  $divHeight= 80 + ($maxGoal * 20);
  $queryTeamHome= "SELECT teamplayers.*, events.TeamKey, events.EventType, events.EventTime
                     FROM `events`
                    INNER JOIN results ON results.PrimaryKey=events.ResultKey
                    INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
                    LEFT JOIN teamplayers ON teamplayers.PrimaryKey=events.TeamPlayerKey
                    WHERE matches.PrimaryKey=$_matchKey
                      AND events.TeamKey=$teamHomeKey
				    ORDER BY events.EventTime,events.EventType";

  $resultSetTeamHome = $_databaseObject->queryPerf($queryTeamHome,"Get goaler");
  unset($goalTeamHome,$goalTeamAway);
  $goalTeamHome[]=array();
  while ($rowSetTeamHome = $_databaseObject -> fetch_assoc ($resultSetTeamHome)) {
    $goalType = "";
    $eventDescription = $rowSetTeamHome["FullName"] . " (".$rowSetTeamHome["EventTime"] . "')";

    switch ($rowSetTeamHome["EventType"]) {
      case "1":
        $goalType = "";
        $styleEvent = "background: url(\"".ROOT_SITE."/images/goal2.png\") no-repeat scroll right top transparent;";
        break;
      case "2":
        $goalType = __encode(" (pén)");
        $styleEvent = "background: url(\"".ROOT_SITE."/images/goal2.png\") no-repeat scroll right top transparent;";
        break;
      case "3":
        $goalType = " (csc)";
        $styleEvent = "background: url(\"".ROOT_SITE."/images/goal2.png\") no-repeat scroll right top transparent;";
        break;
      case "4":
        $eventDescription = "<span style='font-size:7px;color:#CCCCCC;'>passeur : </span>" .$rowSetTeamHome["FullName"];
        $styleEvent = "font-style:italic;font-size:8px";
        break;
      case "5":
        $styleEvent = "background: url(\"".ROOT_SITE."/images/yellow.card.png\") no-repeat scroll right top transparent;";
        break;
      case "6":
        $styleEvent = "background: url(\"".ROOT_SITE."/images/red.card.png\") no-repeat scroll right top transparent;";
        break;
      default:
        $styleEvent="";
        break;

    }
    echo "<div style='text-align:right;font-family:Century Gothic,Trebuchet MS,Arial;
  font-size:10px;
  font-weight:bolder;
  color:#000000;
  width:180px;
  float:left;
  padding-right:30px;
  marging-right:30px;
    padding-top:3px;
  padding-bottom:3px;
  $styleEvent
  '>". $eventDescription . $goalType ."</div>";


  }
  echo '</td><td>&nbsp;</td><td style="vertical-align:top;">';
  $queryTeamAway= "SELECT teamplayers.*, events.TeamKey, events.EventType, events.EventTime
                     FROM `events`
                    INNER JOIN results ON results.PrimaryKey=events.ResultKey
                    INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
                    LEFT JOIN teamplayers ON teamplayers.PrimaryKey=events.TeamPlayerKey
                    WHERE matches.PrimaryKey=$_matchKey
                      AND events.TeamKey=$teamAwayKey
				    ORDER BY events.EventTime, events.EventType";

  $resultSetTeamAway = $_databaseObject->queryPerf($queryTeamAway,"Get goaler");
  $goalTeamAway[]=array();
  while ($rowSetTeamAway = $_databaseObject -> fetch_assoc ($resultSetTeamAway)) {
    $goalType = "";
    $eventDescription =  "(".$rowSetTeamAway["EventTime"] . "') " . $rowSetTeamAway["FullName"];
    switch ($rowSetTeamAway["EventType"]) {
      case "1":
        $goalType = "";
        $styleEvent = "background: url(\"".ROOT_SITE."/images/goal2.png\") no-repeat scroll left top transparent;";
        break;
      case "2":
        $goalType = __encode(" (pén)");
        $styleEvent = "background: url(\"".ROOT_SITE."/images/goal2.png\") no-repeat scroll left top transparent;";
        break;
      case "3":
        $goalType = " (csc)";
        $styleEvent = "background: url(\"".ROOT_SITE."/images/goal2.png\") no-repeat scroll left top transparent;";
        break;
      case "4":
        $eventDescription = "<span style='font-size:7px;color:#CCCCCC;'>passeur : </span>" .$rowSetTeamAway["FullName"];
        $styleEvent = "font-style:italic;font-size:8px;";
        break;
      case "5":
        $styleEvent = "background: url(\"".ROOT_SITE."/images/yellow.card.png\") no-repeat scroll left top transparent;";
        break;
      case "6":
        $styleEvent = "background: url(\"".ROOT_SITE."/images/red.card.png\") no-repeat scroll left top transparent;";
        break;
      default:
        $styleEvent="";
        break;
    }

    echo "<div style='text-align:left;font-family:Century Gothic,Trebuchet MS,Arial;
  font-size:10px;
  font-weight:bolder;
  color:#000000;
  width:180px;
  float:right;
  padding-left:30px;
  margin-left:30px;
  padding-top:3px;
  padding-bottom:3px;
  $styleEvent
  '>". $eventDescription . $goalType ."</div>";
  }


//  for ($index = 1; $index <= $maxGoal; $index++) {
//    if ($teamHomeEvents>=$index) {
//
//      print($goalTeamHome[$index]);
//    }
//    else
//    {
//      echo "<div style='text-align:left;font-family:Century Gothic,Trebuchet MS,Arial;
//font-size:14px;
//  font-weight:bolder;
//  color:#000000;
//  width:180px;
//  float:left;
//  padding-right:30px;
//  padding-top:3px;
//  padding-bottom:3px;
//  marging-right:30px;'>&nbsp;</div>";
//    }
//    if ($teamAwayEvents>=$index ) {
//      echo $goalTeamAway[$index];
//    }
//    else
//    {
//      echo "<div style='text-align:left;font-family:Century Gothic,Trebuchet MS,Arial;
//  font-size:14px;
//  font-weight:bolder;
//  color:#000000;
//  width:180px;
//  float:right;
//  padding-left:30px;
//    padding-top:3px;
//  padding-bottom:3px;
//  margin-left:30px;'>&nbsp;</div>";
//    }
//
//  }




  echo '</td></tr>';
}
echo "</table>";
echo "<p>Rendez-vous sur <a href='".ROOT_SITE."'>".ROOT_SITE."</a> pour consulter plus de détail!</p>
<p>L'administrateur de Pronostics4Fun.</p>

</body>
</html>";

//writePerfInfo();

require_once("end.file.php");
?>