<?php
require_once("begin.file.php");

$_matchKey = $_GET["MatchKey"];

$sql = "SET NAMES utf8";
$_databaseObject->query($sql);

$query= "SELECT
matches.PrimaryKey MatchKey,
matches.TeamHomeKey,
matches.TeamAwayKey,
TeamHome.Name TeamHomeName,
TeamAway.Name TeamAwayName,
matches.GroupKey,
matches.IsBonusMatch,
groups.Description GroupName,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) ) TeamHomeScore,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) ) TeamAwayScore,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey ) TeamHomeEvents,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey ) TeamAwayEvents,
IFNULL(results.LiveStatus,0) LiveStatus,
IFNULL(results.ActualTime,0) ActualTime
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
LEFT JOIN results ON results.MatchKey=matches.PrimaryKey
WHERE matches.PrimaryKey=$_matchKey
ORDER BY matches.ScheduleDate ASC, matches.TeamHomeKey";

$resultSet = $_databaseObject->queryPerf($query,"Get matches to be played by current day");
$_isMatchInProgress =false;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $matchKey = $rowSet["MatchKey"];
  $groupName = $rowSet["GroupName"];
  $teamHomeKey = $rowSet["TeamHomeKey"];
  $teamAwayKey = $rowSet["TeamAwayKey"];
  $teamHomeName = $rowSet["TeamHomeName"];
  $teamAwayName = $rowSet["TeamAwayName"];
  $teamHomeScore = $rowSet["TeamHomeScore"];
  $teamAwayScore = $rowSet["TeamAwayScore"];
  $teamHomeEvents = $rowSet["TeamHomeEvents"];
  $teamAwayEvents = $rowSet["TeamAwayEvents"];
  if ($rowSet["LiveStatus"]==0){
    $teamHomeScore = "&nbsp;";
    $teamAwayScore = "&nbsp;";
  }

  $classBonus ="";
  if ($rowSet["IsBonusMatch"]==1) {
    $classBonus =" matchesliveBonus";
  }


  echo "<div style='width:560px;padding-top:20px; _padding-top:0px;background: url(\"".ROOT_SITE."/images/tooltipmatchbg.png\") no-repeat scroll center bottom transparent;'>
  <div  style='float: left; width: 100px; text-align: center;'><img src='" . ROOT_SITE . "/images/teamFlags/$teamHomeKey.png'></img></div>
  <div style='white-space:nowrap;color:#FFFFFF;float: left; text-align: center; font-weight: bold; font-size: 30px; width: 180px;height:36px;line-height:33px;' >$teamHomeName</div>
  <div  style='float: right;width: 100px; text-align: center;' ><img src='" . ROOT_SITE . "/images/teamFlags/$teamAwayKey.png'></img></div>
  <div  style='white-space:nowrap;color:#FFFFFF;float: right; width: 180px; text-align: center; font-size: 30px; font-weight: bold;height:36px;line-height:33px;'>$teamAwayName</div>
  <div style='text-align:center;font-family:Century Gothic,Trebuchet MS,Arial;font-size:55px;font-weight:bolder;color:#FFFFFF;width:180px;height:79px;float:left;background: url(\"".ROOT_SITE."/images/scorebg.png\") no-repeat scroll center top transparent;'><span style='margin-top: 15px; float: left; height: 55px; margin-left: 75px; line-height: 55px;'>$teamHomeScore</span></div>
  <div style='text-align:center;font-family:Century Gothic,Trebuchet MS,Arial;font-size:55px;font-weight:bolder;color:#FFFFFF;width:180px;height:79px;float:right;background: url(\"".ROOT_SITE."/images/scorebg.png\") no-repeat scroll center top transparent;' ><span style='margin-top: 15px; float: left; height: 55px; margin-left: 75px; line-height: 55px;'>$teamAwayScore</span></div>
";

  echo "<div style='width:560px;height:150px;_height:80px;padding-top:20px;text-align:center;'>";
  switch ($rowSet["LiveStatus"]) {
    case 0:
      echo "<div id='liveStatusMatchDetail' style='width:560px;_width:320px;text-align:center;font-size:9px;' ></div>";
      $scheduleDate = $rowSet["ScheduleDate"];
      echo "<div style='display:none;'  countdownMatchDetail='true' year='". date("Y",$scheduleDate) ."' month='". date("n",$scheduleDate) ."' day='". date("j",$scheduleDate) ."' hour='". date("G",$scheduleDate) ."' minute='". date("i",$scheduleDate) ."'></div>";
      break;
    case 1:
    case 2:
    case 3:
      $_isMatchInProgress = true;
    case 10:
      echo "<div id='liveStatusMatchDetail'  style='width:560px;_width:320px;text-align:center;font-size:9px;margin-left:240px;margin-bottom:15px;'>
      <div class='liveStatusMatchDetail' >" . getStatus($rowSet["LiveStatus"]) . "</div></div>";
      break;
    default:
      echo "<div  id='liveStatusMatchDetail' style='width:560px;_width:320px;text-align:center;font-size:9px;'>" . $rowSet["ActualTime"] . "'</div>";
      break;
  }

  if ($rowSet["ActualTime"]>45 && $rowSet["LiveStatus"]>2) {
    $half1Time=45;
    if ($rowSet["ActualTime"]>90) {
      $half2Time=45;
    }
    else {
      $half2Time=$rowSet["ActualTime"]-45;
    }
  }
  else {
    $half1Time=$rowSet["ActualTime"];
    $half2Time=0;
  }

  echo "<div style='width:560px;text-align:center;float:left;'>";
  echo "<div id='half1' time='$half1Time' style='width:270px;padding-top:18px;float:left;background: url(\"".ROOT_SITE."/images/progressbarbghalf1.png\") no-repeat scroll center top transparent;'></div>";
  switch ($rowSet["LiveStatus"]) {
    case 1:
    case 2:
    case 3:
      break;
      echo "<div style='float: left; font-size: 9px; width: 20px;'>" . $rowSet["ActualTime"] . "'</div>";
    default:
      echo "<div style='float: left; font-size: 9px; width: 20px;'>&nbsp;</div>";
      break;
  }
  echo "<div id='half2' time='$half2Time' style='width:270px;padding-top:18px;float:right;background: url(\"".ROOT_SITE."/images/progressbarbghalf2.png\") no-repeat scroll center top transparent;'></div>";
  echo "</div>";
  echo "</div>";

  $maxGoal = 0;
  if ($teamHomeEvents>$teamAwayEvents) {
    $maxGoal = $teamHomeEvents;
  }
  else {
    $maxGoal = $teamAwayEvents;
  }

  $divHeight= 80 + ($maxGoal * 20);
  echo "<div style='width:560px;height:".$divHeight."px;padding-top:20px;'>";

  $queryTeamHome= "SELECT teamplayers.*, events.TeamKey, events.EventType, events.EventTime
                     FROM `events`
                    INNER JOIN results ON results.PrimaryKey=events.ResultKey
                    INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
                    INNER JOIN teamplayers ON teamplayers.PrimaryKey=events.TeamPlayerKey
                    WHERE matches.PrimaryKey=$_matchKey
                      AND events.TeamKey=$teamHomeKey
				    ORDER BY events.EventTime,events.EventType";

  $resultSetTeamHome = $_databaseObject->queryPerf($queryTeamHome,"Get goaler");
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
        $eventDescription = "<span style='font-size:8px;color:#CCCCCC;'>passeur : </span>" .$rowSetTeamHome["FullName"];
        $styleEvent = "font-style:italic;font-size:10px";
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
    $goalTeamHome[] = "<div style='text-align:right;font-family:Century Gothic,Trebuchet MS,Arial;
  font-size:12px;
  font-weight:bolder;
  color:#FFFFFF;
  width:220px;
  float:left;
  padding-right:30px;
  marging-right:30px;
    padding-top:3px;
  padding-bottom:3px;
  $styleEvent
  '>". $eventDescription . $goalType ."</div>";


  }

  $queryTeamAway= "SELECT teamplayers.*, events.TeamKey, events.EventType, events.EventTime
                     FROM `events`
                    INNER JOIN results ON results.PrimaryKey=events.ResultKey
                    INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
                    INNER JOIN teamplayers ON teamplayers.PrimaryKey=events.TeamPlayerKey
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
        $eventDescription = "<span style='font-size:8px;color:#CCCCCC;'>passeur : </span>" .$rowSetTeamAway["FullName"];
        $styleEvent = "font-style:italic;font-size:10px;";
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

    $goalTeamAway[] ="<div style='text-align:left;font-family:Century Gothic,Trebuchet MS,Arial;
  font-size:12px;
  font-weight:bolder;
  color:#FFFFFF;
  width:220px;
  float:right;
  padding-left:30px;
  margin-left:30px;
  padding-top:3px;
  padding-bottom:3px;
  $styleEvent
  '>". $eventDescription . $goalType ."</div>";
  }


   for ($index = 1; $index <= $maxGoal; $index++) {
     if ($teamHomeEvents>=$index) {
      echo $goalTeamHome[$index];
    }
    else
    {
      echo "<div style='text-align:left;font-family:Century Gothic,Trebuchet MS,Arial;
font-size:14px;
  font-weight:bolder;
  color:#FFFFFF;
  width:220px;
  float:left;
  padding-right:30px;
  padding-top:3px;
  padding-bottom:3px;
  marging-right:30px;'>&nbsp;</div>";
    }
    if ($teamAwayEvents>=$index) {
      echo $goalTeamAway[$index];
    }
    else
    {
      echo "<div style='text-align:left;font-family:Century Gothic,Trebuchet MS,Arial;
  font-size:14px;
  font-weight:bolder;
  color:#FFFFFF;
  width:220px;
  float:right;
  padding-left:30px;
    padding-top:3px;
  padding-bottom:3px;
  margin-left:30px;'>&nbsp;</div>";
    }

  }


  echo"</div>";
  echo"</div>";
}

writePerfInfo();
require_once("end.file.php");
?>