<?php
require_once("begin.file.php");

if (isset($_GET["StateDate"])) {
  $_stateDate = (int)substr($_GET["StateDate"], 0, -3) -((is_est((int)substr($_GET["StateDate"], 0, -3))?2:1)*3600);
}
if (isset($_GET["PlayerKey"])) {
  $_playerKey = $_GET["PlayerKey"];
}

if (isset($_GET["FullRanking"])) {
  $_fullRanking = true;
}


if (isset($_GET['DayKey']) && $_GET['DayKey']!="") {
  	$query= "SELECT
	groups.PrimaryKey GroupKey,
	groups.Description GroupDescription
	FROM groups
	WHERE groups.DayKey=" . $_GET['DayKey'] . "
	 AND groups.CompetitionKey=" . COMPETITION;

$resultSet = $_databaseObject->queryPerf($query,"Get group information");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$_groupKey =$rowSet["GroupKey"];
}
else {
  $_groupKey = $_GET["groupKey"];
}

$query = "SELECT matchstates.EventKey, events.EventTime, matchstates.TeamHomeScore, matchstates.TeamAwayScore, teamplayers.FullName Scorer,
teams.Name TeamGoal, TeamHome.Name TeamHomeName, TeamAway.Name TeamAwayName,
forecasts.TeamHomeScore ForecastHomeScore,forecasts.TeamAwayScore ForecastAwayScore, playermatchstates.Score
FROM `matchstates`
INNER JOIN matches ON matches.PrimaryKey=matchstates.MatchKey
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
INNER JOIN forecasts ON forecasts.MatchKey=matchstates.MatchKey AND forecasts.PlayerKey=$_playerKey
INNER JOIN playermatchstates ON playermatchstates.MatchStateKey=matchstates.PrimaryKey AND playermatchstates.PlayerKey=$_playerKey
LEFT JOIN events ON events.PrimaryKey=matchstates.EventKey
LEFT JOIN teamplayers ON teamplayers.PrimaryKey=events.TeamPlayerKey
LEFT JOIN teams ON teams.PrimaryKey=events.TeamKey
WHERE StateDate=FROM_UNIXTIME($_stateDate)";

$rowsSet = $_databaseObject -> queryGetFullArray ($query, "");

$query = "SELECT players.PrimaryKey PlayerKey, players.NickName, Rank, Score+Bonus Score,
(SELECT PGS.Rank FROM playergroupstates PGS WHERE PGS.StateDate< playergroupstates.StateDate AND PGS.PlayerKey=playergroupstates.PlayerKey AND playergroupstates.GroupKey=PGS.GroupKey ORDER BY PGS.StateDate DESC LIMIT 0,1) PreviousRank
FROM playergroupstates
INNER JOIN playersenabled players ON players.PrimaryKey=playergroupstates.PlayerKey
WHERE playergroupstates.StateDate=FROM_UNIXTIME($_stateDate)
AND playergroupstates.GroupKey=$_groupKey
ORDER BY Rank";

$rowsRankingSet = $_databaseObject -> queryGetFullArray ($query, "");

$htmlul = '';
$htmlul .= '<ul>
<li class="title"><div>Evènements</div></li>';
foreach ($rowsSet as $rowSet) {
  switch ($rowSet["EventKey"]) {
    case 1:
      $htmlul .= '<li class="eventDetail" ><div class="eventTypeImage" style=""><img src="'.ROOT_SITE.$_themePath.'/images/sifflet.png" /></div><div class="eventType" >Coup d\'envoi de ' .$rowSet["TeamHomeName"]." - ".$rowSet["TeamAwayName"]."<br/>Score : ".$rowSet["TeamHomeScore"]."-".$rowSet["TeamAwayScore"]."<br/>Pronostic : ".$rowSet["ForecastHomeScore"]."-".$rowSet["ForecastAwayScore"]." -> ".$rowSet["Score"]."pt(s)" . '</div></li>';
      break;
    case 2:
      $htmlul .= '<li class="eventDetail" ><div class="eventTypeImage" style=""><img src="'.ROOT_SITE.$_themePath.'/images/finish.png" /></div><div class="eventType" >' ."Fin du match " .$rowSet["TeamHomeName"]." - ".$rowSet["TeamAwayName"]."<br/>Score final: ".$rowSet["TeamHomeScore"]."-".$rowSet["TeamAwayScore"]."<br/>Pronostic : ".$rowSet["ForecastHomeScore"]."-".$rowSet["ForecastAwayScore"]." -> ".$rowSet["Score"]."pt(s)" . '</div></li>';
      break;
    default:
      $oponentTeam = $rowSet["TeamGoal"] == $rowSet["TeamHomeName"] ? $rowSet["TeamAwayName"] : $rowSet["TeamHomeName"];
      $htmlul .= '<li class="eventDetail" title="But de '.$rowSet["Scorer"] .'"><div class="eventTypeImage" style=""><img src="'.ROOT_SITE.$_themePath.'/images/goal.png" /><br/>'.$rowSet["EventTime"].'\'</div><div class="eventType" >' . "BUT pour " .$rowSet["TeamGoal"]." contre ".$oponentTeam ."<br/>Score : ".$rowSet["TeamHomeScore"]."-".$rowSet["TeamAwayScore"]."<br/>Pronostic : ".$rowSet["ForecastHomeScore"]."-".$rowSet["ForecastAwayScore"]." -> ".$rowSet["Score"]."pt(s)" . '</div></li>';
      break;
  }
}

$htmlul .= '<li class="title"><div>Classement</div></li>';
$currentRecord=0;
$currentPlayerHasBeenDisplayed = false;
$nbrOfPlayers = sizeof($rowsRankingSet);
$nbrOfPlayers--; // due to 0 base
$displayThreePoint = false;
foreach ($rowsRankingSet as $rowSet) {
  $playerStyle="";
  if ($rowSet["PlayerKey"]==$_playerKey) {
    $currentPlayerHasBeenDisplayed = true;
  }
  $score  = $rowSet["Score"]."pt";
  if ($rowSet["Score"]>1) {
    $score =$rowSet["Score"]."pts";
  }

  if ($_fullRanking || $currentRecord<3 || ($currentRecord<5 && $currentPlayerHasBeenDisplayed) || $currentRecord==$nbrOfPlayers || $rowSet["PlayerKey"]==$_playerKey) {
    if ($rowSet["PreviousRank"]) {
      if ($rowSet["PreviousRank"]>$rowSet["Rank"]) {
        $variation = "up";
      } else if ($rowSet["PreviousRank"]<$rowSet["Rank"]) {
        $variation = "down";
      } else {
        $variation = "eq";
      }
    } else {
      $variation = "eq";
    }
    if ($rowSet["PreviousRank"]==$rowSet["Rank"] || empty($rowSet["PreviousRank"])) {
      $variationValue="";
    } else {
      $variationValue=(int)$rowSet["PreviousRank"]-(int)$rowSet["Rank"];
      $variationValue = "(" . $variationValue . ")";
    }

    $htmlul .= '<li class="rankingDetail var '.$variation.'" >'.$rowSet["Rank"].'. '.$rowSet["NickName"].'<span class="rankingVar" >'.$variationValue.'</span><span class="rankingScore" style="">'.$score.'</span></li>';
    if ($rowSet["PlayerKey"]==$_playerKey) {
      $displayThreePoint = true;
    }
  } else {
    if ($currentRecord==4 || $displayThreePoint) {
      $htmlul .= '<li class="rankingDetailEmpty">...</li>';
      $displayThreePoint=false;
    }
  }
  $currentRecord++;
}
if (!$_fullRanking) {
  $htmlul .= '<li id="buttonFullRanking" class="" style="cursor:pointer;font-style:italic;font-size:10px;text-align:center;padding-top:5px;">Tout le classement</li>';
}
$htmlul .= '</ul>';
$arr["detail"] = $htmlul ;
$arr["Database"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
writeJsonResponse($arr);
require_once("end.file.php");
?>