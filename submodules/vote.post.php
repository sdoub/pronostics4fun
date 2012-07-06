<?php
if($_SERVER['REQUEST_METHOD']=='GET')
{

  $sql = "SELECT TeamKey, SUM(NbrOfBonus) NbrOfBonus
FROM (
SELECT matches.TeamHomeKey TeamKey, 1 NbrOfBonus
  FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=".COMPETITION."
WHERE matches.IsBonusMatch=1
UNION ALL
SELECT matches.TeamAwayKey, 1
  FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=".COMPETITION."
WHERE matches.IsBonusMatch=1
UNION ALL
SELECT teams.PrimaryKey, 0
  FROM teams
WHERE NOT EXISTS (SELECT 1 FROM matches
  INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=".COMPETITION."
WHERE matches.IsBonusMatch=1
  AND (teams.PrimaryKey=matches.TeamHomeKey OR teams.PrimaryKey=matches.TeamAwayKey))
) TMP
GROUP BY TMP.TeamKey";
  $rowsTeamsBonus = $_databaseObject -> queryGetFullArray ($sql, "Get all teams with number of bonus match participate");

  $sql = "SELECT TeamKey, SUM(NbrOfBonus)
FROM (
SELECT matches.TeamHomeKey TeamKey, 1 NbrOfBonus
  FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=".COMPETITION."
WHERE matches.IsBonusMatch=1
UNION ALL
SELECT matches.TeamAwayKey, 1
  FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=".COMPETITION."
WHERE matches.IsBonusMatch=1 ) TMP
GROUP BY TMP.TeamKey
HAVING SUM(NbrOfBonus)>9";
  $rowsTeamsExcluded10 = $_databaseObject -> queryGetFullArray ($sql, "Get all teams exceeded number of bonus match");


  $sql = "SELECT TeamKey,SUM(NbrOfBonus) FROM (
SELECT matches.TeamHomeKey TeamKey,1 NbrOfBonus
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=".COMPETITION."
AND groups.DayKey >= (SELECT MIN(g.DayKey)-2 FROM groups g WHERE g.CompetitionKey=".COMPETITION." AND g.IsCompleted=0 AND BeginDate + INTERVAL 1 HOUR > NOW())
AND groups.DayKey < (SELECT MIN(g2.DayKey) FROM groups g2 WHERE g2.CompetitionKey=".COMPETITION." AND g2.IsCompleted=0 AND BeginDate + INTERVAL 1 HOUR > NOW())
WHERE matches.IsBonusMatch=1
UNION ALL
SELECT matches.TeamAwayKey,1 NbrOfBonus
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=".COMPETITION."
AND groups.DayKey >= (SELECT MIN(g.DayKey)-2 FROM groups g WHERE g.CompetitionKey=".COMPETITION." AND g.IsCompleted=0 AND BeginDate + INTERVAL 1 HOUR > NOW())
AND groups.DayKey < (SELECT MIN(g2.DayKey) FROM groups g2 WHERE g2.CompetitionKey=".COMPETITION." AND g2.IsCompleted=0 AND BeginDate + INTERVAL 1 HOUR > NOW())
WHERE matches.IsBonusMatch=1
) TMP
GROUP BY TeamKey
HAVING SUM(NbrOfBonus)>1";
  $rowsTeamsExcluded2 = $_databaseObject -> queryGetFullArray ($sql, "Get all teams exceeded number of bonus match");

  $sql = "SELECT matches.PrimaryKey MatchKey,
	groups.Description GroupName,
	groups.PrimaryKey GroupKey,
	TeamHome.PrimaryKey TeamHomeKey,
       TeamHome.Name TeamHomeName,
forecasts.TeamHomeScore ForecastTeamHomeScore,
       TeamAway.PrimaryKey TeamAwayKey,
       TeamAway.Name TeamAwayName,
forecasts.TeamAwayScore ForecastTeamAwayScore,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.Status,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore,
IFNULL(results.livestatus,0) LiveStatus,
matches.IsBonusMatch,
(SELECT COUNT(1) FROM forecasts WHERE forecasts.MatchKey =matches.PrimaryKey) NbrOfPlayers,
votes.value VoteValue,
(SELECT SUM(GlobalVotes.value) FROM votes GlobalVotes WHERE GlobalVotes.MatchKey=matches.PrimaryKey) GlobalVoteValue,
(SELECT COUNT(GlobalVotes.value) FROM votes GlobalVotes WHERE GlobalVotes.MatchKey=matches.PrimaryKey) GlobalVoteCount,
UNIX_TIMESTAMP(groups.BeginDate) - UNIX_TIMESTAMP(NOW()+INTERVAL 1 HOUR) IsLimitDateExceeded
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=" . $_authorisation->getConnectedUserKey() . "
LEFT JOIN votes ON matches.PrimaryKey = votes.MatchKey AND votes.PlayerKey=" . $_authorisation->getConnectedUserKey() . "
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
WHERE matches.GroupKey = (SELECT MIN(groups.PrimaryKey) FROM groups WHERE groups.CompetitionKey=".COMPETITION." AND IsCompleted=0 AND BeginDate + INTERVAL 1 HOUR > NOW())
 ORDER BY matches.ScheduleDate,matches.PrimaryKey";
  $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

  $scheduleMonth = "00";
  $scheduleDay = "00";
  $cuurentGroup="";

  $html = "<form id='frmVote'> <table>";
  $html .= "<tr id='TooLateVoted' style='display:none;color:#f54949;background:#D7E1F6;font-size:11px;font-weight:bold;height:25px;'><td colspan='7' style='text-align:center;'>" . __encode("Il est trop tard! le vote est clos, vous ne pouvez plus voter!") . "</td></tr>";
  $html .= "<tr id='Voted' style='display:none;color:#f54949;background:#D7E1F6;font-size:11px;font-weight:bold;height:25px;'><td colspan='7' style='text-align:center;'>" . __encode("Vous avez d�j� vot� pour cette journ�e, vous ne pouvez pas le modifier!") . "</td></tr>";
  $html .= "<tr id='ToBeVoted' style='padding-bottom:20px;height:30px;'><td colspan='7' style='text-align:center;'><div id='voteRemaining' >";
  $html .= '<input type="radio" name="voteRemaining" value="1" checked="true" />
        <input type="radio" name="voteRemaining" value="2" checked="true" />
        <input type="radio" name="voteRemaining" value="3" checked="true"/>
        <input type="radio" name="voteRemaining" value="4" checked="true"/>
        <input type="radio" name="voteRemaining" value="5" checked="true"/>
		<input type="radio" name="voteRemaining" value="6" checked="true"/>
        <input type="radio" name="voteRemaining" value="7" checked="true"/>
        <input type="radio" name="voteRemaining" value="8" checked="true"/>
        <input type="radio" name="voteRemaining" value="9" checked="true"/>
        <input type="radio" name="voteRemaining" value="10" checked="true"/>
                ';
  $html .= "</div><span id='messageStars' style='color:#ffffff;padding-left:20px;font-size:11px;'> il vous reste <span id='remainingStars'>10</span> étoiles à attribuer</span></td></tr>";
  $displayGroup = false;
  $teamExcluded2 = "";
  $teamExcluded10 = "";
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {

    if (!$displayGroup)
    {
      if ($rowSet["IsLimitDateExceeded"]<0) {
        $isExceeded = "true";
      } else {
        $isExceeded = "false";
      }
      $html .= '<tr class="day" is-exceeded="'.$isExceeded.'">
      	  <td colspan="7">' . $rowSet["GroupName"] . ' - <span class="votePlayers" style="font-size:10px;cursor:help" rel="get.group.vote.players.php?GroupKey='. $rowSet['GroupKey'] .'">'  . $rowSet["GlobalVoteCount"].' vote(s)</span></td>
      	</tr>';
      $displayGroup = true;
    }

    $isDisabled = "false";
    if ( array_value_exists($rowsTeamsExcluded10,"TeamKey",$rowSet['TeamHomeKey'])
    || array_value_exists($rowsTeamsExcluded10,"TeamKey",$rowSet['TeamAwayKey'])
    || array_value_exists($rowsTeamsExcluded2,"TeamKey",$rowSet['TeamHomeKey'])
    || array_value_exists($rowsTeamsExcluded2,"TeamKey",$rowSet['TeamAwayKey'])) {
      $isDisabled = "true";
    }


    if (array_value_exists($rowsTeamsExcluded10,"TeamKey",$rowSet['TeamHomeKey'])) {
      if ($teamExcluded10=="") {
        $teamExcluded10 =  $rowSet['TeamHomeName'];
      } else {
        $teamExcluded10 .= ", " . $rowSet['TeamHomeName'];
      }
    }

    if (array_value_exists($rowsTeamsExcluded10,"TeamKey",$rowSet['TeamAwayKey'])) {
      if ($teamExcluded10=="") {
        $teamExcluded10 =  $rowSet['TeamAwayName'];
      } else {
        $teamExcluded10 .= ", " . $rowSet['TeamAwayName'];
      }
    }
    if (array_value_exists($rowsTeamsExcluded2,"TeamKey",$rowSet['TeamHomeKey'])) {
      if ($teamExcluded2=="") {
        $teamExcluded2 =  $rowSet['TeamHomeName'];
      } else {
        $teamExcluded2 .= ", " . $rowSet['TeamHomeName'];
      }

    }
    if (array_value_exists($rowsTeamsExcluded2,"TeamKey",$rowSet['TeamAwayKey'])) {
      if ($teamExcluded2=="") {
        $teamExcluded2 =  $rowSet['TeamAwayName'];
      } else {
        $teamExcluded2 .= ", " . $rowSet['TeamAwayName'];
      }

    }


    $html .= '<tr class="match"  match-key="' . $rowSet['MatchKey'] . '" status="' . $rowSet["LiveStatus"] . '">
      	  <td class="teamHome">' . $rowSet['TeamHomeName'] ;

    $arrnbrOfBonus = seekKey($rowsTeamsBonus,"TeamKey",$rowSet['TeamHomeKey']);
    $nbrOfBonus = $arrnbrOfBonus[0]["NbrOfBonus"];
    $html .= '<span class="bonusMatch" style="cursor:help;" rel="get.match.bonus.php?TeamKey='. $rowSet['TeamHomeKey'] .'"><sup>('.$nbrOfBonus.')</sup></span></td>
      	  <td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamHomeKey'] . '.png" width="30px" height="30px"></img></td>';

    $html .= '<td width="50px;" style="text-align:center;">&nbsp;Vs&nbsp;</td><td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamAwayKey'] . '.png"></img></td>
      	  <td class="teamAway">' . $rowSet['TeamAwayName'] ;
    $arrnbrOfBonus = seekKey($rowsTeamsBonus,"TeamKey",$rowSet['TeamAwayKey']);
    $nbrOfBonus = $arrnbrOfBonus[0]["NbrOfBonus"];
    $html .= '<span class="bonusMatch" style="cursor:help;" rel="get.match.bonus.php?TeamKey='. $rowSet['TeamAwayKey'] .'"><sup>('.$nbrOfBonus.')</sup></span></td>';

    $html .= '<td width="450px;">
        <div id="stars-wrapper'.$rowSet['MatchKey'] .'" is-disabled="'.$isDisabled.'" name="stars-wrapper'.$rowSet['MatchKey'] .'" match-key="' . $rowSet['MatchKey'] . '" vote-value="'.$rowSet["VoteValue"].'">';

    $html .= '<input type="radio" name="vote'.$rowSet['MatchKey'] .'" value="1"  />';
    $html .= '<input type="radio" name="vote'.$rowSet['MatchKey'] .'" value="2"  />';
    $html .= '<input type="radio" name="vote'.$rowSet['MatchKey'] .'" value="3" />';
    $html .= '<input type="radio" name="vote'.$rowSet['MatchKey'] .'" value="4" />';
    $html .= '<input type="radio" name="vote'.$rowSet['MatchKey'] .'" value="5" />';


    $html .= '</div>
    </td>';
    if ($rowSet["GlobalVoteCount"]==0) {
      $globalNote = $rowSet["GlobalVoteValue"];
    }
    else {
      $globalNote = round($rowSet["GlobalVoteValue"]/$rowSet["GlobalVoteCount"],2);
    }
    $html .= '<td width="250px;" ><span style="display:none;" name="GlobalNote">('.$globalNote.'/5)</span></td>';
    $html .= '</tr>';

  }
  unset($rowSet,$resultSet,$sql);
  $html .= "</table>";

  $html .= '<div id="footerVote" style="text-align:right;margin-right:30px;" ><input type="submit"
	value="Valider" class="buttonfield" id="btnValidateVote" name="btnValidateVote"></div>
  </form>';

  if ($teamExcluded2!="") {
    $html .= "<div style='padding-left:20px;padding-top:3px;padding-right:3px;padding-bottom:3px;color:#365F89;font-size:10px;text-align:center;background:url(". ROOT_SITE . "/images/warning.32px.png) no-repeat scroll left top #D7E1F6;'>" . __encode("Il n'est pas possible de voter pour le match de ".$teamExcluded2.", <br/>car ".$teamExcluded2." a particp� au 2 pr�c�dents match Bonus"). "</div>";
  }
  if ($teamExcluded10!="") {
    $html .= "<div style='padding-left:20px;padding-top:3px;padding-right:3px;padding-bottom:3px;color:#365F89;font-size:10px;text-align:center;background:url(". ROOT_SITE . "/images/warning.32px.png) no-repeat scroll left top #D7E1F6;'>" . __encode("Il n'est plus possible de voter pour un match de ".$teamExcluded10.", car ".$teamExcluded10." a/ont d�j� particp� � 10 matchs Bonus"). "</div>";
  }

  $arr["status"] = false;
  $arr["message"] = $html;



}
else
{

  $matches = $_POST["matches"];
  $return = true;
  foreach ($matches as $match){
    if ($return) {
      $query = "REPLACE INTO votes (MatchKey, PlayerKey, Value) VALUES ( " . $match[0] . "," . $_authorisation->getConnectedUserKey() . "," . $match[1] . ")";
      if(!$_databaseObject->queryPerf($query,"Insertion des pronostics"))
      {
        $return= false;
      }
      else
      {
        $return = true;
      }
    }

  }
  if ($return) {
    $arr["status"] = true;
    $arr["message"] = '<form id="frmVoteValidated">
<label style="color:#FFFFFF;text-align:center;">Merci pour votre vote! <br/>2pts de bonus vous seront accordé lorsque la journée sera clôturée.</label>
<div id="footerVote" ><input type="submit"
	value="Fermer" class="buttonfield" id="btnClose" name="btnClose"></div>
</form>';
  } else {
    $arr["status"] = false;
    $arr["message"] = 'Une erreur est survenue durant la sauvegarde!Veuillez réessayer';

  }

}

$arr["perfAndError"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');

writeJsonResponse($arr);
//@ destroy instance
unset($forecast);
?>