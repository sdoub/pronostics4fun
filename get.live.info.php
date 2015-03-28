<?php
require_once("begin.file.php");
$_groupKey=$_GET["GroupKey"];

switch ($_competitionType) {
  case 2:
$sql = "select @rownum:=@rownum+1 as rank, A.* from
(SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
CONCAT(SUBSTR(players.NickName,1,10),IF (LENGTH(nickname)>9,'...','')) NickName,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) ) Score,
((SELECT CASE COUNT(*) WHEN 8 THEN 60 WHEN 7 THEN 40 WHEN 6 THEN IF (groups.Code='1/8',10,40) WHEN 5 THEN IF (groups.Code='1/8',0,20) WHEN 4 THEN IF (groups.Code='1/4',20,0) WHEN 3 THEN IF (groups.Code='1/4',10,0) ELSE 0 END
     FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)
          + (SELECT CASE COUNT(*) WHEN 0 THEN 0 ELSE 0 END FROM votes
        INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND votes.playerKey=players.PrimaryKey))
       GroupScore
FROM playersenabled players
GROUP BY NickName
ORDER BY Score) A, (SELECT @rownum:=0) r";
    break;
  default:
$sql = "select @rownum:=@rownum+1 as rank, A.* from
(SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
CONCAT(SUBSTR(players.NickName,1,10),IF (LENGTH(nickname)>9,'...','')) NickName,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) ) Score,
((SELECT CASE COUNT(*) WHEN 10 THEN 100 WHEN 9 THEN 60 WHEN 8 THEN 40 WHEN 7 THEN 20 ELSE 0 END
     FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)
          + (SELECT CASE COUNT(*) WHEN 0 THEN 0 ELSE 0 END FROM votes
        INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND votes.playerKey=players.PrimaryKey))
       GroupScore
FROM playersenabled players
GROUP BY NickName
ORDER BY Score) A, (SELECT @rownum:=0) r";
    break;
}

$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
$rank=0;
$previousRank=0;
$realRank=0;
$previousScore=0;
$arr["players"] = array();
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{


  $playerKey = $rowSet["PlayerKey"];

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

  $tempPlayer["PlayerKey"]=$rowSet["PlayerKey"];
  $tempPlayer["PlayerScore"]=$rowSet["Score"];
  $tempPlayer["PlayerBonus"]=$rowSet["GroupScore"];
  $tempPlayer["PlayerRank"]=$rank;
  $tempPlayer["PlayerVariation"]=$variation;

  $queryForecats= "SELECT
  matches.PrimaryKey MatchKey,
matches.TeamHomeKey,
matches.TeamAwayKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE $playerKey=playermatchresults.PlayerKey
AND playermatchresults.MatchKey =matches.PrimaryKey
AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE $playerKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT MAX(matches.GroupKey) FROM matches WHERE matches.PrimaryKey=matches.PrimaryKey)
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score,
IFNULL((SELECT forecasts.TeamHomeScore FROM forecasts WHERE forecasts.PlayerKey=$playerKey AND forecasts.MatchKey=matches.PrimaryKey),'') TeamHomeForecast,
IFNULL((SELECT forecasts.TeamAwayScore FROM forecasts WHERE forecasts.PlayerKey=$playerKey AND forecasts.MatchKey=matches.PrimaryKey),'') TeamAwayForecast,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) ) TeamHomeScore,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) ) TeamAwayScore,
IFNULL(results.LiveStatus,0) LiveStatus,
IFNULL(results.ActualTime,0) ActualTime
FROM matches
LEFT JOIN results ON results.MatchKey =matches.PrimaryKey
WHERE matches.GroupKey=$_groupKey
GROUP BY matches.PrimaryKey
ORDER BY matches.ScheduleDate ASC, matches.TeamHomeKey";

  $resultSetForecasts = $_databaseObject->queryPerf($queryForecats,"Get matches to be played by current day");
  $tempPlayer["matches"]=array();
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

  $tempMatch["MatchKey"]=$matchKey;
  $tempMatch["TeamHomeScore"]=$rowSetForecasts["TeamHomeScore"];
  $tempMatch["TeamAwayScore"]=$rowSetForecasts["TeamAwayScore"];;
  $tempMatch["LiveStatus"]=$matchStatus;
  $tempMatch["LiveStatusWording"]=getStatus($matchStatus);
  $tempMatch["ActualTime"]=$matchTime;

  $tempMatch["TeamHomeForecast"]=$teamHomeForecast;
  $tempMatch["TeamAwayForecast"]=$teamAwayForecast;
  $tempMatch["Separator"]=$separator;
  $tempMatch["Class"]=$class;
  $tempMatch["Score"]=$score;
  $tempMatch["ScoreWording"]=$scoreWording;

  $tempPlayer["matches"][]=$tempMatch;


  }
  $arr["players"][] = $tempPlayer;

  $previousRank=$rank;

}

$queryLastRefresh= "SELECT UNIX_TIMESTAMP(MAX(ResultDate)) LastRefreshDate, UNIX_TIMESTAMP(MAX(ResultDate))+UNIX_TIMESTAMP(MAX(ResultDate))+ 300 - UNIX_TIMESTAMP(NOW()) NextRefreshDate, UNIX_TIMESTAMP(NOW()) CurrentDate,UNIX_TIMESTAMP(MAX(ResultDate))+ 300 - UNIX_TIMESTAMP(NOW()) DiffDate
FROM results
INNER JOIN matches ON results.MatchKey=matches.PrimaryKey AND matches.GroupKey=$_groupKey
WHERE results.LiveStatus > 0";

$resultSetLastRefresh = $_databaseObject -> queryPerf ($queryLastRefresh, "Get last refresh date");
$rowSetLastRefresh = $_databaseObject -> fetch_assoc ($resultSetLastRefresh);


	function timeBetween($start_date,$end_date)
	{
		$diff = $end_date-$start_date;
 		$seconds = 0;
 		$hours   = 0;
 		$minutes = 0;

		if($diff % 86400 <= 0){$days = $diff / 86400;}  // 86,400 seconds in a day
		if($diff % 86400 > 0)
		{
			$rest = ($diff % 86400);
			$days = ($diff - $rest) / 86400;
     		if($rest % 3600 > 0)
			{
				$rest1 = ($rest % 3600);
				$hours = ($rest - $rest1) / 3600;
        		if($rest1 % 60 > 0)
				{
					$rest2 = ($rest1 % 60);
           		$minutes = ($rest1 - $rest2) / 60;
           		$seconds = $rest2;
        		}
        		else{$minutes = $rest1 / 60;}
     		}
     		else{$hours = $rest / 3600;}
		}

		if($days > 0){$days = $days;}
		else{$days = 0;}
		if($hours > 0){$hours = $hours;}
		else{$hours = 0;}
		if($minutes > 0){$minutes = $minutes;}
		else{$minutes = 0;}
		$seconds = $seconds; // always be at least one second
        $result = array();
		$result["days"]=$days;
		$result["hours"]=$hours;
		$result["minutes"]=$minutes;
		$result["seconds"]=$seconds;
        return $result;
}

if ($rowSetLastRefresh['DiffDate']>0) {
  $diffTime = timeBetween($rowSetLastRefresh['LastRefreshDate'],$rowSetLastRefresh['NextRefreshDate']);
}
else {
  $diffTime = array();
  $diffTime["days"]=0;
  $diffTime["hours"]=0;
  $diffTime["minutes"]=2;
  $diffTime["seconds"]=0;
}


  $lastRefreshFormattedDate = strftime("%A %d %B %Y à %H:%M:%S",$rowSetLastRefresh['LastRefreshDate']);
$arr["LastRefreshRaw"] = $rowSetLastRefresh['LastRefreshDate'];
$arr["LastRefresh"] = __encode($lastRefreshFormattedDate);
$arr["NextRefresh"] = "+" . $diffTime['minutes'] . "m +".$diffTime['seconds'] . "s";

writeJsonResponse($arr);
require_once("end.file.php");
?>