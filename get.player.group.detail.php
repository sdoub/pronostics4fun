<?php
require_once("begin.file.php");

$_groupKey = $_GET["GroupKey"];
$_playerKeys = $_GET["PlayerKeys"];
$_mode = "P4F";
$_liveScore = false;
if (isset($_GET["Mode"])) {
	$_mode = $_GET["Mode"];
}
if (isset($_GET["Live"])) {
	$_liveScore = $_GET["Live"] =="1";
}

$sql = "SET NAMES utf8";
$_databaseObject->query($sql);
echo '<div><ul style="font-size:12px;">';
echo '<li style="width:95px;float:left;">
&nbsp;
</li>';

if ($_mode=="P4F" && !$_liveScore) {
	$queryNotIn = "SELECT distinct matches.PrimaryKey MatchKey FROM matches 
	INNER JOIN groups currentday ON currentday.PrimaryKey=matches.GroupKey AND currentday.PrimaryKey = $_groupKey
	CROSS JOIN groups
	WHERE groups.PrimaryKey NOT IN ($_groupKey)
	AND groups.CompetitionKey=". COMPETITION ." 
	AND groups.BeginDate BETWEEN currentday.BeginDate AND currentday.EndDate
	AND matches.scheduleDate> groups.BeginDate";

	$rowsSetP4FMatchesNotIn = $_databaseObject -> queryGetFullArray ($queryNotIn, "Get matches not taken into account");
}
$query= "SELECT
matches.PrimaryKey MatchKey,
matches.TeamHomeKey,
matches.TeamAwayKey,
TeamHome.Code TeamHomeName,
TeamAway.Code TeamAwayName,
matches.GroupKey,
matches.IsBonusMatch,
matches.Status,
groups.Description GroupName,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3)) TeamHomeScore,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3)) TeamAwayScore,
IFNULL(results.LiveStatus,0) LiveStatus,
IFNULL(results.ActualTime,0) ActualTime
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
LEFT JOIN results ON results.MatchKey=matches.PrimaryKey
WHERE matches.GroupKey=$_groupKey
ORDER BY matches.ScheduleDate ASC, matches.TeamHomeKey";

$resultSet = $_databaseObject->queryPerf($query,"Get matches to be played by current day");
$_isMatchInProgress =false;
$nbrOfMatch = 0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $nbrOfMatch++;
  $matchKey = $rowSet["MatchKey"];
  $groupName = $rowSet["GroupName"];
  $teamHomeKey = $rowSet["TeamHomeKey"];
  $teamAwayKey = $rowSet["TeamAwayKey"];
  $teamHomeName = $rowSet["TeamHomeName"];
  $teamAwayName = $rowSet["TeamAwayName"];
  $teamHomeScore = $rowSet["TeamHomeScore"];
  $teamAwayScore = $rowSet["TeamAwayScore"];
  if ($rowSet["LiveStatus"]==0){
    $teamHomeScore = "&nbsp;";
    $teamAwayScore = "&nbsp;";
  }

  $classBonus ="";
  if ($rowSet["IsBonusMatch"]==1) {
    $classBonus =" matchesliveBonus";
  }

	if ($_mode=="P4F" && !$_liveScore && seekKey($rowsSetP4FMatchesNotIn,"MatchKey",$matchKey)){
  	$classBonus =" matchesliveOfflimit";
  }

  echo "<li class='matcheslive$classBonus' match-key='$matchKey' >

  <div class='liveteamHome' ><img src='" . ROOT_SITE . "/images/teamFlags/$teamHomeKey.png' width='20px' height='20px'></img></div>
  <div class='liveteamsep' >-</div>
  <div class='liveteamAway' ><img src='" . ROOT_SITE . "/images/teamFlags/$teamAwayKey.png' width='20px' height='20px'></img></div>
  <div class='livescoreHome'>$teamHomeScore</div>
  <div class='livescoresep' >-</div>
  <div class='livescoreAway' >$teamAwayScore</div>
";

  switch ($rowSet["LiveStatus"]) {
    case 2:
      $_isMatchInProgress = true;
    case 10:
      echo "<div class='livestatus' style='width:100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;'>" . getStatus($rowSet["LiveStatus"]) . "</div>";
      break;
    case 0:
      if ($rowSet["Status"]==1) { //Postponed
        echo "<div class='livestatus' style='width:100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;'>Reporté</div>";
      } else {
        echo "<div class='livestatus' style='width:100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;' >".$rowSet["ActualTime"]."'</div>";
        $scheduleDate = $rowSet["ScheduleDate"];
        echo "<div style='display:none;'  countdown='true' year='". date("Y",$scheduleDate) ."' month='". date("n",$scheduleDate) ."' day='". date("j",$scheduleDate) ."' hour='". date("G",$scheduleDate) ."' minute='". date("i",$scheduleDate) ."'></div>";
      }
      break;
    default:
      echo "<div class='livestatus' style='width:100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;'>" . $rowSet["ActualTime"] . "'</div>";
      $_isMatchInProgress = true;
      break;
  }
  echo"</li>";
}
echo "</ul></div>";

switch ($nbrOfMatch) {
  case 1:
    $rowWidth = 167;
    break;
  case 2:
    $rowWidth = 112 * $nbrOfMatch;
    break;
  case 3:
  case 4:
    $rowWidth = 85 * $nbrOfMatch;
    break;
  case 6:
    $rowWidth = 76 * $nbrOfMatch;
    break;
  case 8:
    $rowWidth = 73 * $nbrOfMatch;
    break;
  default;
    $rowWidth = 70 * $nbrOfMatch;
    break;
}
?>
<div id="playerDetail2" style="width:<?php echo $rowWidth+20;?>;font-size:12px;margin-top:58px;">

<ul>

<?php
  $sql = "select @rownum:=@rownum+1 as rank, A.* from
(SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
CONCAT(SUBSTR(players.NickName,1,10),IF (LENGTH(nickname)>9,'...','')) NickName,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0)) Score,
(SELECT CASE COUNT(*) WHEN 10 THEN 100 WHEN 9 THEN 60 WHEN 8 THEN 40 WHEN 7 THEN 20 ELSE 0 END
		 FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)+
          (SELECT CASE COUNT(*) WHEN 0 THEN 0 ELSE 0 END FROM votes
        INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND votes.playerKey=players.PrimaryKey)
      GroupScore
FROM playersenabled players
WHERE players.PrimaryKey IN (".$_playerKeys.")
GROUP BY NickName
ORDER BY NickName) A, (SELECT @rownum:=0) r";

$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
$rank=0;
$previousRank=0;
$realRank=0;
$previousScore=0;
$livePlayerList="";



while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $playerKey = $rowSet["PlayerKey"];
	echo '<li id="li_' . $playerKey . '" class="playerforecastrow" style="width:714px;" player-key="'. $playerKey .'" >
	<div style="width:100%" class="playerforecastrowcontent">';

  $realRank++;
  if ($previousScore>$rowSet["Score"]||$previousScore==0) {
    $rank=$realRank;
  }

  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }
  $rankToBeDisplayed = $rank.'.';
  if ($previousRank==$rank) {
    $rankToBeDisplayed="-";
  }

  $previousScore=$rowSet["Score"];
  if ($rowSet["PreviousRank"]) {
    if ($rowSet["PreviousRank"]>$rank) {
      $variation = "up";
    }
    else if ($rowSet["PreviousRank"]<$rank) {
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

	$bonusUrl = ROOT_SITE;
	switch ($rowSet["GroupScore"]) {
		case 0: 
		  $bonusUrl = "";
			breeak;
		default:
		 	 $bonusUrl .= "/images/bonus.".(string)$rowSet["GroupScore"] .".png";
			 //$bonusUrl .= "/images/bullet.bonus.100.png";
			 break;
	}
	$totalScore = ($rowSet["Score"]+$rowSet["GroupScore"]);
	if ($_mode=="P4F" && !$_liveScore) {
		$bonusUrl = "";
		$sqlQueryP4FChamp = "SELECT 
		PlayerHomeKey, HomePlayer.NickName HomePlayerNickName, HomePlayer.AvatarName HomePlayerAvatar,
		PlayerAwayKey, AwayPlayer.NickName AwayPlayerNickName, AwayPlayer.AvatarName AwayPlayerAvatar,
		HomeScore, AwayScore, playerdivisionmatches.DivisionKey
		FROM playerdivisionmatches 
		INNER JOIN players HomePlayer ON HomePlayer.PrimaryKey=playerdivisionmatches.PlayerHomeKey
		INNER JOIN players AwayPlayer ON AwayPlayer.PrimaryKey=playerdivisionmatches.PlayerAwayKey
		WHERE GroupKey=$_groupKey AND (PlayerHomeKey=$playerKey OR PlayerAwayKey=$playerKey) ";

		$rowsSetP4FCh = $_databaseObject -> queryGetFullArray ($sqlQueryP4FChamp, "Get p4f championship info");
		if ($rowsSetP4FCh[0]["PlayerHomeKey"]==$playerKey) {
			$totalScore = $rowsSetP4FCh[0]["HomeScore"];
		} else {
			$totalScore = $rowsSetP4FCh[0]["AwayScore"];
		}
		
	}
  //echo '<p style="float:left;"><a class="popupscroll" href="#">'. $rowSet["NickName"] .'</a></p><img class="avat" style="width:30px;height:30px;" src="' . $avatarPath .'"></img>';
  echo '<div class="popupscroll" href="#" style="float:left;border-right:1px solid;width:92px;margin-left:5px;';
	echo 'background:url('.$bonusUrl.') no-repeat right center;" ><span class="ellipsis textOverflow" displayWidth="70" style="_width=65px;">';
	echo $rowSet["FullNickName"] .'</span><br/><span class="Score" style="font-size:9px;font-style:italic;" >';
	echo 'Score : <u>' . $totalScore . ' pts</u></span>';
	echo '</div>';
  echo ' <div style="float:right;margin-right:6px;height:33px;"> ';

  $queryForecats= "SELECT
  matches.PrimaryKey MatchKey,
matches.TeamHomeKey,
matches.TeamAwayKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE $playerKey=playermatchresults.PlayerKey
AND playermatchresults.MatchKey =matches.PrimaryKey
AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE $playerKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT MAX(matches.GroupKey) FROM matches WHERE matches.PrimaryKey=matches.PrimaryKey)
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score,
IFNULL((SELECT forecasts.TeamHomeScore FROM forecasts WHERE forecasts.PlayerKey=$playerKey AND forecasts.MatchKey=matches.PrimaryKey),'') TeamHomeForecast,
IFNULL((SELECT forecasts.TeamAwayScore FROM forecasts WHERE forecasts.PlayerKey=$playerKey AND forecasts.MatchKey=matches.PrimaryKey),'') TeamAwayForecast,
IFNULL(results.LiveStatus,0) LiveStatus,
IFNULL(results.ActualTime,0) ActualTime
FROM matches
LEFT JOIN results ON results.MatchKey =matches.PrimaryKey
WHERE matches.GroupKey=$_groupKey
GROUP BY matches.PrimaryKey
ORDER BY matches.ScheduleDate ASC, matches.TeamHomeKey";

 $resultSetForecasts = $_databaseObject->queryPerf($queryForecats,"Get matches to be played by current day");

while ($rowSetForecasts = $_databaseObject -> fetch_assoc ($resultSetForecasts))
{
  $matchKey = $rowSetForecasts["MatchKey"];
  $teamHomeKey = $rowSetForecasts["TeamHomeKey"];
  $teamAwayKey = $rowSetForecasts["TeamAwayKey"];
  $teamHomeForecast = $rowSetForecasts["TeamHomeForecast"];
  $teamAwayForecast = $rowSetForecasts["TeamAwayForecast"];
  $score = $rowSetForecasts["Score"];
  $matchStatus = $rowSetForecasts["LiveStatus"];
  $matchTime = $rowSetForecasts["ActualTime"];

  $separator = "-";
  if ($teamHomeForecast=="") {
    $separator = "&nbsp;";
  }
  $scoreWording = "pt";
  if ($score>1) {
    $scoreWording = "pts";
  }
  switch ($score)
  {
    case 0:
    case 1:
    case 2:
      $class = "Failed";
      break;
    default:
      $class = "Success";
      break;
  }
  if ($matchStatus==0 &&  $matchTime==0) {
    $class = "NotStarted";
    if ($teamHomeForecast!="" && $playerKey!=$_authorisation->getConnectedUserKey()){
	    $teamHomeForecast = "x";
	    $teamAwayForecast = "x";
    }
  }
	$classMatchResult = "forecastmatch forecastmatchTooltip";
	if ($_mode=="P4F" && !$_liveScore && seekKey($rowsSetP4FMatchesNotIn,"MatchKey",$matchKey)){
		$class = "NotStarted";
		$classMatchResult = "forecastmatchofflimit forecastmatchTooltip";
	}


  echo "<div class='$classMatchResult' match-key='$matchKey' >
  <div class='forecastteamHome' >$teamHomeForecast</div>
  <div class='forecastsep' >$separator</div>
  <div class='forecastteamAway' >$teamAwayForecast</div>
  <div class='forecastScore $class' >$score&nbsp;$scoreWording</div>
  </div>";

}
  echo '<div></div></li>';
$previousRank=$rank;

}
?>
</ul>
</div>

<?php
//writePerfInfo();
require_once("end.file.php");
?>