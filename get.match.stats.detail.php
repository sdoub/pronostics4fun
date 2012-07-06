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

echo "<div class='divPopup'>";

echo "<div class='teams'>
  <div  class='home'><img src='" . ROOT_SITE . "/images/teamFlags/$teamHomeKey.png'></img></div>
  <div class='homeName'>$teamHomeName</div>
  <div  class='away' ><img src='" . ROOT_SITE . "/images/teamFlags/$teamAwayKey.png'></img></div>
  <div class='awayName'>$teamAwayName</div>";
echo"</div>";

echo "<div class='teamRankings'>";
echo "<div class='home'>
  <table class='TeamStats'>
  <tr class='title'>
  <td class='rank' rowspan='2'>" . $teamHomeInfo["TeamRank"] . "</td>
  <td class='points' title='Points au classement général'>Pts</td>
  <td class='goals' title='But pour'>Bp</td>
  <td class='goalsAgainst' title='But contre'>Bc</td>
  <td class='goalsHome' title='But pour à domicile'>Bpad</td>
  <td class='goalsAgainstHome' title='But contre à domicile'>Bcad</td>
  </tr>
  <tr class='content'>
  <td class='points'>" . $teamHomeInfo["Score"] . "</td>
  <td class='goals'>" . $teamHomeInfo["Goals"] . "</td>
  <td class='goalsAgainst'>" . $teamHomeInfo["GoalsAgainst"] . "</td>
  <td class='goalsHome'>" . $teamHomeInfo["GoalsHome"] . "</td>
  <td class='goalsAgainstHome' >" . $teamHomeInfo["GoalsHomeAgainst"] . "</td>
  </tr></table></div>";


echo "<div class='away'>
  <table class='TeamStats'>
  <tr class='title'>
  <td rowspan='2' class='rank'>" . $teamAwayInfo["TeamRank"] . "</td>
  <td class='points' title='Points au classement général'>Pts</td>
  <td class='goals' title='But pour'>Bp</td>
  <td class='goalsAgainst' title='But contre'>Bc</td>
  <td class='goalsAway' title='But pour à l'extérieur'>Bpae</td>
  <td class='goalsAgainstAway' title='But contre à l'extérieur'>Bcae</td>
  </tr>
  <tr class='content'>
  <td class='points'>" . $teamAwayInfo["Score"] . "</td>
  <td class='goals'>" . $teamAwayInfo["Goals"] . "</td>
  <td class='goalsAgainst'>" . $teamAwayInfo["GoalsAgainst"] . "</td>
  <td class='goalsAway' >" . $teamAwayInfo["GoalsAway"] . "</td>
  <td class='goalsAgainstAway'>" . $teamAwayInfo["GoalsAwayAgainst"] . "</td>
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
  echo "<div class='previousMatch'>";
  echo "<div class='content'>";
  if ($rowSetFirstLeg["TeamHomeScore"]>$rowSetFirstLeg["TeamAwayScore"]) {
    $resultFirstLeg = "Lors de la ". $rowSetFirstLeg["GroupName"] . ", " . $rowSetFirstLeg["TeamHomeName"] ." l'avait emporté sur le score de " . $rowSetFirstLeg["TeamHomeScore"] ."-" .$rowSetFirstLeg["TeamAwayScore"];
  }
  else if ($rowSetFirstLeg["TeamHomeScore"]<$rowSetFirstLeg["TeamAwayScore"]) {
    $resultFirstLeg = "Lors de la ". $rowSetFirstLeg["GroupName"] . ", " . $rowSetFirstLeg["TeamAwayName"] ." l'avait emporté sur le score de " . $rowSetFirstLeg["TeamAwayScore"] ."-" .$rowSetFirstLeg["TeamHomeScore"];
  }
  else {
    $resultFirstLeg = "Lors de la ". $rowSetFirstLeg["GroupName"] . ", " . $rowSetFirstLeg["TeamHomeName"] ." et " . $rowSetFirstLeg["TeamAwayName"] . " avaient fait match null : " . $rowSetFirstLeg["TeamAwayScore"] ."-" .$rowSetFirstLeg["TeamHomeScore"];
  }
  echo $resultFirstLeg;
  echo"</div>";
  echo"</div>";

}

echo "<div class='historyTitle'>";
echo "<div class='title-trigger' id='matchesHistory'>";
echo "Historique des rencontres pour la saison en cours";
echo"</div>";
echo"</div>";

echo "<div class='container-history' id='matchesHistoryContainer'>";

echo "<div class='history teamHome'>";
echo"<table>";
echo"<thead>";
echo"<tr class='title'>";

echo"<th class='homeAway' title='D=Domicile; E=Exterieur'>D/E</th>";
echo"<th class='team' >Equipe</th>";
echo"<th class='result' >Résultat</th>";
echo"<th class='score' >Score</th>";
echo"</tr>";
echo"</thead>";
echo "<tbody class='content'>";
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

    echo '<tr class="match">';
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
      echo '<td>Défaite</td>';
      $color = "#EF0000";
    }
    echo '<td style="color:' . $color . '">' . $rowSet["TeamHomeScore"] . ' - '.$rowSet["TeamAwayScore"] . '</td>';
    echo '</tr>';
  }
  else {
    echo '<tr class="match">';
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
      echo '<td>Défaite</td>';
      $color = "#EF0000";
    }
    echo '<td  style="color:' . $color . '">' . $rowSet["TeamHomeScore"] . ' - '.$rowSet["TeamAwayScore"] . '</td>';
    echo '</tr>';
  }
}
echo "</tbody>";
echo"</table>";
echo"</div>";

echo "<div class='history teamAway' >";
echo"<table>";
echo"<thead>";
echo"<tr class='title'>";

echo"<th class='homeAway' title='D=Domicile; E=Exterieur' >D/E</th>";
echo"<th class='team' >Equipe</th>";
echo"<th class='result'>Résultat</th>";
echo"<th class='score'>Score</th>";
echo"</tr>";
echo"</thead>";
echo "<tbody class='content'>";
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
    echo '<tr class="match">';
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
      echo '<td>Défaite</td>';
      $color = "#EF0000";

    }
    echo '<td  style="color:' . $color . '">' . $rowSet["TeamHomeScore"] . ' - '.$rowSet["TeamAwayScore"] . '</td>';
    echo '</tr>';
  }
  else {
    $color = "#FFFFFF";
    echo '<tr class="match">';
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
      echo '<td>Défaite</td>';
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
echo "Les confrontations $teamHomeName - $teamAwayName en Ligue 1 depuis 2000/2001";
echo"</div>";
echo"</div>";

echo "<div class='container-history' id='sameMatchHistoryContainer' style='margin-left:20px;height:140px;margin-right:20px;overflow:hidden;'>";
echo"<table style='font-size:12px;color:#FFFFFF;font-weight:bold;text-align:center;'>";
echo"<thead>";
echo"<tr style='font-size: 12px;text-align: center;	background-color: #D7E1F6;	color: #365F89;	font-weight: bold;'>";

echo"<th style='width:100px;'>Saison</th>";
echo"<th style='width:120px;'>Journée</th>";
echo"<th style='width:120px;'>Date</th>";
echo"<th style='width:170px;'>Résultat</th>";
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
    $scheduleFormattedDate = strftime("%d %B %Y",$rowSet['ScheduleDate']);


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
    echo '<td><span style="'.$styleHome.'">' . $rowSet["TeamHomeScore"] . '</span> - <span style="'.$styleAway.'">'.$rowSet["TeamAwayScore"] . '</span></td>';
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
echo"</div>";

writePerfInfo();
require_once("end.file.php");
?>
