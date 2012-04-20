<?php
require_once("begin.file.php");
require_once("lib/ranking.php");

$_matchKey = $_GET["MatchKey"];

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
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);

$matchKey = $rowSet["MatchKey"];
$groupName = $rowSet["GroupName"];
$teamHomeKey = $rowSet["TeamHomeKey"];
$teamAwayKey = $rowSet["TeamAwayKey"];
$teamHomeName = $rowSet["TeamHomeName"];
$teamAwayName = $rowSet["TeamAwayName"];
$teamHomeScore = $rowSet["TeamHomeScore"];
$teamAwayScore = $rowSet["TeamAwayScore"];

$arrTeams = GetTeamsRanking();
$teamHomeInfo = $arrTeams[$teamHomeKey];
$teamAwayInfo = $arrTeams[$teamAwayKey];

if ($rowSet["LiveStatus"]==0){
  $teamHomeScore = "&nbsp;";
  $teamAwayScore = "&nbsp;";
}

$classBonus ="";
if ($rowSet["IsBonusMatch"]==1) {
  $classBonus =" matchesliveBonus";
}

echo "<div style='width:560px;height:350px;width: 560px; padding-top: 20px; background: url(\"".ROOT_SITE."/images/tooltipmatchbg.png\") no-repeat scroll center bottom transparent;'>";

echo "<div style='width:560px;'>
  <div  style='float: left; width: 100px; text-align: center;'><img style='width:50px;height:50px;' src='" . ROOT_SITE . "/images/teamFlags/$teamHomeKey.png'></img></div>
  <div style='white-space:nowrap;color:#FFFFFF;float: left; text-align: center; font-weight: bold; font-size: 30px; width: 180px;height:36px;line-height:33px;' >$teamHomeName</div>
  <div  style='float: right;width: 100px; text-align: center;' ><img style='width:50px;height:50px;' src='" . ROOT_SITE . "/images/teamFlags/$teamAwayKey.png'></img></div>
  <div  style='white-space:nowrap;color:#FFFFFF;float: right; width: 180px; text-align: center; font-size: 30px; font-weight: bold;height:36px;line-height:33px;'>$teamAwayName</div>";
echo"</div>";

echo "<div style='height:80px;padding-top: 60px;_padding-top: 10px;'>";
echo "<div style='background: url(\"".ROOT_SITE."/images/team.stats.bg.png\") no-repeat scroll center top transparent;text-align:center;font-family:Century Gothic,Trebuchet MS,Arial;font-size:12px;font-weight:bolder;color:#FFFFFF;width:200px;height:79px;float:left;margin-left: 50px;'>
  <table style='height:40px;margin-top:20px;background: url(\"".ROOT_SITE."/images/stats.bg.png\") no-repeat scroll left top transparent;' class='TeamStats'><tr style='text-align:center;font-size:12px;color:#FFFFFF;'>
  <td rowspan='2' style='font-size:18px;font-weight:bold;width:40px;'>" . $teamHomeInfo["TeamRank"] . "</td>
  <td style='width:25px;border-left:1px solid #4F74A0;border-bottom:1px solid #4F74A0;' title='" . __encode("Points au classement général") . "'>Pts</td>
  <td style='width:25px;border-left:1px solid #4F74A0;border-bottom:1px solid #4F74A0;' title='But pour'>Bp</td>
  <td style='width:25px;border-left:1px solid #4F74A0;border-bottom:1px solid #4F74A0;' title='But contre'>Bc</td>
  <td style='width:40px;border-left:1px solid #4F74A0;border-bottom:1px solid #4F74A0;' title='" . __encode("But pour à domicile") . "'>Bpad</td>
  <td style='width:40px;border-left:1px solid #4F74A0;border-bottom:1px solid #4F74A0;' title='" . __encode("But contre à domicile") . "'>Bcad</td>
  </tr>
  <tr style='font-size:12px;color:#FFFFFF;font-weight:bold;text-align:center;'>
  <td style='border-left:1px solid #4F74A0;'>" . $teamHomeInfo["Score"] . "</td>
  <td style='border-left:1px solid #4F74A0;'>" . $teamHomeInfo["Goals"] . "</td>
  <td style='border-left:1px solid #4F74A0;'>" . $teamHomeInfo["GoalsAgainst"] . "</td>
  <td style='border-left:1px solid #4F74A0;'>" . $teamHomeInfo["GoalsHome"] . "</td>
  <td style='border-left:1px solid #4F74A0;'>" . $teamHomeInfo["GoalsHomeAgainst"] . "</td>
  </tr></table></div>";


echo "<div style='background: url(\"".ROOT_SITE."/images/team.stats.bg.png\") no-repeat scroll center top transparent;text-align:center;font-family:Century Gothic,Trebuchet MS,Arial;font-size:12px;font-weight:bolder;color:#FFFFFF;width:200px;height:79px;float:right;margin-right: 50px;'>
  <table style='height:40px;margin-top:20px;background: url(\"".ROOT_SITE."/images/stats.bg.png\") no-repeat scroll left top transparent;' class='TeamStats'><tr style='text-align:center;font-size:12px;color:#FFFFFF;'>
  <td rowspan='2' style='font-size:18px;font-weight:bold;width:40px;'>" . $teamAwayInfo["TeamRank"] . "</td>
  <td style='width:25px;border-left:1px solid #4F74A0;border-bottom:1px solid #4F74A0;' title='" . __encode("Points au classement général") . "'>Pts</td>
  <td style='width:25px;border-left:1px solid #4F74A0;border-bottom:1px solid #4F74A0;' title='But pour'>Bp</td>
  <td style='width:25px;border-left:1px solid #4F74A0;border-bottom:1px solid #4F74A0;' title='But contre'>Bc</td>
  <td style='width:40px;border-left:1px solid #4F74A0;border-bottom:1px solid #4F74A0;' title=\"" . __encode("But pour à l'extérieur") ."\">Bpae</td>
  <td style='width:40px;border-left:1px solid #4F74A0;border-bottom:1px solid #4F74A0;' title=\"" . __encode("But contre à l'extérieur") ."\">Bcae</td>
  </tr>
  <tr style='font-size:12px;color:#FFFFFF;font-weight:bold;text-align:center;'>
  <td style='border-left:1px solid #4F74A0;'>" . $teamAwayInfo["Score"] . "</td>
  <td style='border-left:1px solid #4F74A0;'>" . $teamAwayInfo["Goals"] . "</td>
  <td style='border-left:1px solid #4F74A0;'>" . $teamAwayInfo["GoalsAgainst"] . "</td>
  <td style='border-left:1px solid #4F74A0;'>" . $teamAwayInfo["GoalsAway"] . "</td>
  <td style='border-left:1px solid #4F74A0;'>" . $teamAwayInfo["GoalsAwayAgainst"] . "</td>
  </tr></table></div>";
echo"</div>";



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
IFNULL(results.LiveStatus,0) LiveStatus,
IFNULL(results.ActualTime,0) ActualTime
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
LEFT JOIN results ON results.MatchKey=matches.PrimaryKey
WHERE matches.TeamHomeKey=$teamAwayKey
AND matches.TeamAwayKey=$teamHomeKey
ORDER BY matches.ScheduleDate ASC, matches.TeamHomeKey";

$resultSetFirstLeg = $_databaseObject->queryPerf($query,"Get matches to be played by current day");
$rowSetFirstLeg = $_databaseObject -> fetch_assoc ($resultSetFirstLeg);

if ($rowSetFirstLeg){
  echo "<div style='margin-top:10px;width:560px;text-align:center;color:#365F89;font-size:12px;'>";
  echo "<div style='margin-left:20px;margin-right:25px;text-align:center;color:#365F89;font-size:12px;background-color:#D7E1F6;font-weight: bold;'>";
  if ($rowSetFirstLeg["TeamHomeScore"]>$rowSetFirstLeg["TeamAwayScore"]) {
    $resultFirstLeg = __encode("Lors de la ". $rowSetFirstLeg["GroupName"] . ", " . $rowSetFirstLeg["TeamHomeName"] ." l'avait emporté sur le score de " . $rowSetFirstLeg["TeamHomeScore"] ."-" .$rowSetFirstLeg["TeamAwayScore"]);
  }
  else if ($rowSetFirstLeg["TeamHomeScore"]<$rowSetFirstLeg["TeamAwayScore"]) {
    $resultFirstLeg = __encode("Lors de la ". $rowSetFirstLeg["GroupName"] . ", " . $rowSetFirstLeg["TeamAwayName"] ." l'avait emporté sur le score de " . $rowSetFirstLeg["TeamAwayScore"] ."-" .$rowSetFirstLeg["TeamHomeScore"]);
  }
  else {
    $resultFirstLeg = __encode("Lors de la ". $rowSetFirstLeg["GroupName"] . ", " . $rowSetFirstLeg["TeamHomeName"] ." et " . $rowSetFirstLeg["TeamAwayName"] . " avaient fait match null : " . $rowSetFirstLeg["TeamAwayScore"] ."-" .$rowSetFirstLeg["TeamHomeScore"]);
  }
  echo $resultFirstLeg;
  echo"</div>";
  echo"</div>";

}

echo "<div style='margin-top:10px;width:560px;text-align:center;color:#FFFFFF;font-size:12px;'>";
echo "<div class='title-trigger' id='matchesHistory' style='margin-left:20px;margin-right:25px;text-align:center;color:#FFFFFF;font-size:12px;font-weight: bold;'>";
echo __encode("Historique des rencontres por la saison en cours");
echo"</div>";
echo"</div>";

echo "<div class='container-history' id='matchesHistoryContainer' style='height:140px;overflow:hidden;'>";

echo "<div style='padding-top:0px;text-align:left;font-family:Century Gothic,Trebuchet MS,Arial;font-size:12px;font-weight:bolder;color:#FFFFFF;width:250px;height:79px;float:left;margin-left: 20px;'>";
echo"<table style='font-size:12px;color:#FFFFFF;font-weight:bold;text-align:center;'>";
echo"<thead>";
echo"<tr style='font-size: 12px;text-align: center;	background-color: #D7E1F6;	color: #365F89;	font-weight: bold;'>";

echo"<th title='D=Domicile; E=Exterieur' style='width:25px;'>D/E</th>";
echo"<th style='width:120px;'>Equipe</th>";
echo"<th style='width:50px;'>Resultat</th>";
echo"<th style='width:50px;'>Score</th>";
echo"</tr>";
echo"</thead>";
echo "<tbody style='height:115px;_height:0px;overflow: hidden;' class='flexcroll'>";
$sql = "SELECT TeamHome.PrimaryKey TeamHomeKey,
			   TeamAway.PrimaryKey TeamAwayKey,
			   TeamHome.Name TeamHomeName,
			   TeamAway.Name TeamAwayName,
			   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
			   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore,
			   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<5) TeamHomeScore90,
			   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<5) TeamAwayScore90,
			   IFNULL(results.livestatus,0) LiveStatus
          FROM matches
         INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
         INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
          INNER JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus=10
          INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
          WHERE (matches.TeamHomeKey=$teamHomeKey OR matches.TeamAwayKey=$teamHomeKey)
          ORDER BY matches.ScheduleDate DESC";

$resultSet = $_databaseObject->queryPerf($sql,"Get matches to be played by current day");
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
  $diff = $rowSet["TeamHomeScore"]-$rowSet["TeamAwayScore"];
  $color = "#FFFFFF";

  if ($rowSet["TeamHomeKey"]==$teamHomeKey) {

    echo '<tr style="height:12px;font-size:11px;">';
    echo '<td>D</td>';
    echo '<td>' . $rowSet["TeamAwayName"] . '</td>';
    if ($diff == 0) {
      echo '<td>Nul</td>';
    }
    if ($diff > 0) {
      echo '<td>Victoire</td>';
      $color = "#B3D207";
    }
    if ($diff < 0) {
      echo '<td>' . __encode("Défaite") . '</td>';
      $color = "#EF0000";
    }
    echo '<td style="color:' . $color . '">' . $rowSet["TeamHomeScore"] . ' - '.$rowSet["TeamAwayScore"] . '</td>';
    echo '</tr>';
  }
  else {
    echo '<tr style="height:12px;font-size:11px;">';
    echo '<td>E</td>';
    echo '<td>' . $rowSet["TeamHomeName"] . '</td>';
    if ($diff == 0) {
      echo '<td>Nul</td>';
    }
    if ($diff < 0) {
      echo '<td>Victoire</td>';
      $color = "#B3D207";
    }
    if ($diff > 0) {
      echo '<td>' . __encode("Défaite") . '</td>';
      $color = "#EF0000";
    }
    echo '<td  style="color:' . $color . '">' . $rowSet["TeamHomeScore"] . ' - '.$rowSet["TeamAwayScore"] . '</td>';
    echo '</tr>';
  }
}
echo "</tbody>";
echo"</table>";
echo"</div>";

echo "<div style='padding-top:0px;text-align:left;font-family:Century Gothic,Trebuchet MS,Arial;font-size:12px;font-weight:bolder;color:#FFFFFF;width:250px;height:79px;float:right;margin-right: 20px;'>";
echo"<table style='font-size:12px;color:#FFFFFF;font-weight:bold;text-align:center;'>";
echo"<thead>";
echo"<tr style='font-size: 12px;text-align: center;	background-color: #D7E1F6; color: #365F89;	font-weight: bold;'>";

echo"<th title='D=Domicile; E=Exterieur' style='width:25px;'>D/E</th>";
echo"<th style='width:120px;'>Equipe</th>";
echo"<th style='width:50px;'>Resultat</th>";
echo"<th style='width:50px;'>Score</th>";
echo"</tr>";
echo"</thead>";
echo "<tbody style='height:115px;_height:0px;overflow: hidden;' class='flexcroll'>";
$sql = "SELECT TeamHome.PrimaryKey TeamHomeKey,
			   TeamAway.PrimaryKey TeamAwayKey,
			   TeamHome.Name TeamHomeName,
			   TeamAway.Name TeamAwayName,
			   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
			   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore,
			   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<5) TeamHomeScore90,
			   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<5) TeamAwayScore90,
			   IFNULL(results.livestatus,0) LiveStatus
          FROM matches
         INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
         INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
          INNER JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus=10
          INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
          WHERE (matches.TeamHomeKey=$teamAwayKey OR matches.TeamAwayKey=$teamAwayKey)
          ORDER BY matches.ScheduleDate DESC";

$resultSet = $_databaseObject->queryPerf($sql,"Get matches to be played by current day");
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
  $diff = $rowSet["TeamHomeScore"]-$rowSet["TeamAwayScore"];
  if ($rowSet["TeamHomeKey"]==$teamAwayKey) {
    $color = "#FFFFFF";
    echo '<tr style="height:12px;font-size:11px;">';
    echo '<td>D</td>';
    echo '<td>' . $rowSet["TeamAwayName"] . '</td>';
    if ($diff == 0) {
      echo '<td>Nul</td>';
    }
    if ($diff > 0) {
      echo '<td>Victoire</td>';
      $color = "#B3D207";
    }
    if ($diff < 0) {
      echo '<td>' . __encode("Défaite") . '</td>';
      $color = "#EF0000";

    }
    echo '<td  style="color:' . $color . '">' . $rowSet["TeamHomeScore"] . ' - '.$rowSet["TeamAwayScore"] . '</td>';
    echo '</tr>';
  }
  else {
    $color = "#FFFFFF";
    echo '<tr style="height:12px;font-size:11px;">';
    echo '<td>E</td>';
    echo '<td>' . $rowSet["TeamHomeName"] . '</td>';
    if ($diff == 0) {
      echo '<td>Nul</td>';
    }
    if ($diff < 0) {
      echo '<td>Victoire</td>';
      $color = "#B3D207";
    }
    if ($diff > 0) {
      echo '<td>' . __encode("Défaite") . '</td>';
      $color = "#EF0000";
    }
    echo '<td style="color:' . $color . '">' . $rowSet["TeamHomeScore"] . ' - '.$rowSet["TeamAwayScore"] . '</td>';
    echo '</tr>';
  }
}


echo "</tbody>";
echo"</table>";
echo"</div>";

echo"</div>";

echo "<div class='title-trigger' style='margin-top:10px;width:560px;text-align:center;color:#FFFFFF;font-size:12px;'>";
echo "<div id='sameMatchHistory' style='margin-left:20px;margin-right:25px;text-align:center;color:#FFFFFF;font-size:12px;font-weight: bold;'>";
echo __encode("Les confrontations $teamHomeName - $teamAwayName en Ligue 1 depuis 2000/2001");
echo"</div>";
echo"</div>";

echo "<div class='container-history' id='sameMatchHistoryContainer' style='margin-left:20px;height:140px;margin-right:20px;overflow:hidden;'>";
echo"<table style='font-size:12px;color:#FFFFFF;font-weight:bold;text-align:center;'>";
echo"<thead>";
echo"<tr style='font-size: 12px;text-align: center;	background-color: #D7E1F6;	color: #365F89;	font-weight: bold;'>";

echo"<th style='width:100px;'>Saison</th>";
echo"<th style='width:120px;'>".__encode("Journée") . "</th>";
echo"<th style='width:120px;'>Date</th>";
echo"<th style='width:170px;'>".__encode("Résultat")."</th>";
echo"</tr>";
echo"</thead>";
echo "<tbody style='height:115px;_height:0px;overflow: hidden;' class='flexcroll'>";


try {
  /*** connect to SQLite database ***/
  $ligue1db  = new PDO("sqlite:data/ligue1.db");
  $ligue1db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "SELECT groups.Description GroupDescription,
substr(competitions.Name,17,10) CompetitionName,
TeamHome.PrimaryKey TeamHomeKey,
			   TeamAway.PrimaryKey TeamAwayKey,
			   TeamHome.Name TeamHomeName,
			   TeamAway.Name TeamAwayName,
			   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
			   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore,
			   strftime('%s',matches.ScheduleDate) ScheduleDate
          FROM matches
          INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
          INNER JOIN competitions ON competitions.PrimaryKey=groups.CompetitionKey
          INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
          INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
          INNER JOIN results ON results.MatchKey=matches.PrimaryKey
          WHERE matches.TeamHomeKey=$teamHomeKey
          AND matches.TeamAwayKey=$teamAwayKey
          ORDER BY matches.ScheduleDate DESC";

  $statement = $ligue1db->prepare($sql);

  $statement->execute();
  $rowsSet = $statement->fetchAll();

  $_queries = array();
  foreach ($rowsSet as $rowSet)
  {

    $diff = $rowSet["TeamHomeScore"]-$rowSet["TeamAwayScore"];
    $color = "#FFFFFF";

    setlocale(LC_TIME, "fr_FR");
    $scheduleFormattedDate = __encode(strftime("%d %B %Y",$rowSet['ScheduleDate']));


    echo '<tr style="height:12px;font-size:11px;">';
    echo '<td>'.$rowSet["CompetitionName"].'</td>';
    echo '<td>' . $rowSet["GroupDescription"] . '</td>';
    echo '<td>' . $scheduleFormattedDate . '</td>';

    $styleHome = "";
    $styleAway = "";

    if ($diff < 0) {
      $styleHome = "font-weight:bold;";
      $styleAway = "";
    }
    if ($diff > 0) {
      $styleHome = "";
      $styleAway = "font-weight:bold;";
    }
    echo '<td  style=""><span style="'.$styleHome.'">' . $rowSet["TeamHomeScore"] . '</span> - <span style="'.$styleAway.'">'.$rowSet["TeamAwayScore"] . '</span></td>';
    echo '</tr>';

  }

  $ligue1db = null;
  unset($ligue1db);
}
catch(PDOException $e)
{
  echo $e->getMessage();
}
echo "</tbody>";
echo"</table>";


echo"</div>";
//echo "<div style='width:560px;text-align:center;color:#FFFFFF;font-size:9px;'>";
//echo __encode("D=Domicile; E=Extérieur; Bp=But pour; Bc=But contre; Bpad=But pour à domicile; Bcad=But contre à domicile; Bpae=But pour à l'extérieur; Bcae=But contre à l'extérieur");
//echo"</div>";
echo"</div>";



//http://lfp.fr/ligue1/stat/confrontation.asp?no_affil_fff_rec=500211&no_affil_fff_vis=500091


writePerfInfo();
require_once("end.file.php");
?>

<style>
.TeamStats {
	margin-top: 10px;
}
</style>
