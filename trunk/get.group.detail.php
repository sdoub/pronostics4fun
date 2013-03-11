<?php
require_once("begin.file.php");
$_dayKey = $_GET["DayKey"];
$query = "SELECT PrimaryKey GroupKey, Description FROM groups WHERE groups.DayKey= " . $_dayKey . " AND groups.CompetitionKey=".COMPETITION;
$resultSet = $_databaseObject -> queryPerf ($query, "Get group info");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$_groupDescription=$rowSet["Description"];
$_groupKey= $rowSet["GroupKey"];
$arr["GroupDescription"] = $_groupDescription;
$arr["GroupKey"] = $_groupKey;
$arr["DayKey"] = $_dayKey;

/* start - Get group ranking */
$rankings = array();
$sql = "SELECT * FROM (SELECT
            (SELECT PRK.Rank FROM playergroupranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND RankDate<CURDATE() ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
            players.PrimaryKey PlayerKey,
            players.NickName FullNickName,
            players.AvatarName,
            SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
                  AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey)
                  ),0)) Score,
                  (SELECT
            CASE COUNT(*)
            WHEN 10 THEN 100
            WHEN 9 THEN 60
            WHEN 8 THEN 40
            WHEN 7 THEN 20
            ELSE 0 END
            FROM playermatchresults
            INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
            INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
            WHERE groups.PrimaryKey=$_groupKey
            AND playermatchresults.Score>=5
            AND playermatchresults.playerKey=players.PrimaryKey
            )+
            (SELECT CASE COUNT(*) WHEN 0 THEN 0 ELSE 0 END FROM votes
                    INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
                    INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
                    WHERE groups.PrimaryKey=$_groupKey
                      AND votes.playerKey=players.PrimaryKey) BonusScore,
        (SELECT COUNT(*) FROM playermatchresults WHERE playermatchresults.Score>=5 AND playermatchresults.Score<>15 AND playermatchresults.Score<>30 AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey) AND playermatchresults.PlayerKey= players.PrimaryKey) CorrectScore,
        (SELECT COUNT(*) FROM playermatchresults WHERE (playermatchresults.Score=15 OR playermatchresults.Score=30) AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey) AND playermatchresults.PlayerKey= players.PrimaryKey ) PerfectScore,
        (SELECT COUNT(*) FROM playermatchresults WHERE playermatchresults.Score<5 AND playermatchresults.PlayerKey= players.PrimaryKey AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey)) BadScore
            FROM playersenabled players
            GROUP BY NickName) TMP
            ORDER BY  Score+BonusScore DESC, CorrectScore DESC, PerfectScore DESC";

$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
$rank=0;
$previousRank=0;
$realRank=0;
$previousScore=0;
$previousMatchGood = 0;
$previousMatchPerfect = 0;

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
  $ranking = array();
  $realRank++;
  if ($rowSet["Score"]!=$previousScore || $rowSet["Score"]>0) {
    $rank=$realRank;
  } elseif ($rowSet["CorrectScore"]!=$previousMatchGood){
    $rank=$realRank;
  } elseif ($rowSet["PerfectScore"]!=$previousMatchPerfect){
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
  $previousMatchGood=$rowSet["CorrectScore"];
  $previousMatchPerfect=$rowSet["PerfectScore"];

  if ($rowSet["PreviousRank"]) {
    if ($rowSet["PreviousRank"]>$rank) {
      $variation = "up";
    }
    else if ($rowSet["PreviousRank"]<$rank) {
      $variation = "down";
    } else {
      $variation = "eq";
    }
  } else {
    $variation = "eq";
  }
  $score = $rowSet["Score"] + $rowSet["BonusScore"];
  $ranking["PlayerKey"] = $rowSet["PlayerKey"];
  $ranking["RankToBeDisplayed"] = $rankToBeDisplayed;
  $ranking["Rank"] = $rank;
  $ranking["AvatarPath"] = $avatarPath ;
  $ranking["NickName"] = __encode($rowSet["FullNickName"]);
  $ranking["BonusScore"] = $rowSet["BonusScore"];
  $ranking["Score"] = $rowSet["Score"];
  $ranking["GlobalScore"] = $score;
  $ranking["CorrectScore"] = $rowSet["CorrectScore"];
  $ranking["PerfectScore"] = $rowSet["PerfectScore"];
  $ranking["BadScore"] = $rowSet["BadScore"];
  $previousRank=$rank;
  $rankings[] = $ranking;
}
$arr["Rankings"] = $rankings;
/* end - Get group ranking */

/* start - Get match detail */
$playerKey=$_authorisation->getConnectedUserKey();
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
        (SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3)) TeamHomeScore,
        (SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3)) TeamAwayScore,
        IFNULL(results.LiveStatus,0) LiveStatus,
        IFNULL(results.ActualTime,0) ActualTime,
        forecasts.TeamHomeScore ForecastHomeScore,
        forecasts.TeamAwayScore ForecastAwayScore,
        IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE $playerKey=playermatchresults.PlayerKey
        AND playermatchresults.MatchKey =matches.PrimaryKey
        AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
              ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE $playerKey=playergroupresults.PlayerKey
              AND playergroupresults.GroupKey IN (SELECT MAX(matches.GroupKey) FROM matches WHERE matches.PrimaryKey=matches.PrimaryKey)
              AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0) Score,
        (SELECT COUNT(*) FROM playermatchresults WHERE playermatchresults.Score>=5 AND playermatchresults.Score<>15 AND playermatchresults.Score<>30 AND playermatchresults.MatchKey=matches.PrimaryKey) CorrectScore,
        (SELECT COUNT(*) FROM playermatchresults WHERE (playermatchresults.Score=15 OR playermatchresults.Score=30) AND playermatchresults.MatchKey=matches.PrimaryKey) PerfectScore,
        (SELECT COUNT(*) FROM playermatchresults WHERE playermatchresults.Score<5 AND playermatchresults.MatchKey=matches.PrimaryKey AND playermatchresults.PlayerKey IN (SELECT forecasts2.PlayerKey FROM forecasts forecasts2 WHERE forecasts2.MatchKey=matches.PrimaryKey)) BadScore
        FROM matches
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
        INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
        INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
        LEFT JOIN results ON results.MatchKey=matches.PrimaryKey
        LEFT JOIN forecasts ON forecasts.MatchKey = matches.PrimaryKey AND forecasts.PlayerKey= " .$playerKey . "
        WHERE matches.GroupKey=$_groupKey
        ORDER BY matches.ScheduleDate ASC, matches.TeamHomeKey";

$resultSet = $_databaseObject->queryPerf($query,"Get matches to be played by current day");
$_isMatchInProgress =false;
$matches = array();

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
  $match = array();
  $matchKey = $rowSet["MatchKey"];
  $groupName = $rowSet["GroupName"];
  $teamHomeKey = $rowSet["TeamHomeKey"];
  $teamAwayKey = $rowSet["TeamAwayKey"];
  $teamHomeName = $rowSet["TeamHomeName"];
  $teamAwayName = $rowSet["TeamAwayName"];
  $teamHomeScore = $rowSet["TeamHomeScore"];
  $teamAwayScore = $rowSet["TeamAwayScore"];
  $score = $rowSet["Score"];
  $forecastHomeScore = $rowSet["ForecastHomeScore"];
  $forecastAwayScore = $rowSet["ForecastAwayScore"];
  $correctScore = $rowSet["CorrectScore"];
  $perfectScore = $rowSet["PerfectScore"];
  $badScore = $rowSet["BadScore"];
  $status = $rowSet["LiveStatus"];

  if ($rowSet["LiveStatus"]==0){
    $teamHomeScore = "&nbsp;";
    $teamAwayScore = "&nbsp;";
  }

  $classBonus ="";
  if ($rowSet["IsBonusMatch"]==1) {
    $classBonus =" matchesliveBonus";
  }
  $scoreWording = "pt";
  if ($score>1) {
    $scoreWording = "pts";
  }

  $scoreWording = $score.'&nbsp;'.$scoreWording;
  switch ($score)
  {
    case 0:
    case 1:
    case 2:
      $classScore = "failed";
      break;
    default:
      $classScore = "success";
      break;
  }

  $match["IsBonusMatch"] = $rowSet["IsBonusMatch"];
  $match["MatchKey"] = $matchKey;
  $match["TeamHomeScore"] = $teamHomeScore;
  $match["TeamAwayScore"] = $teamAwayScore;
  $match["TeamHomeName"] = $teamHomeName;
  $match["TeamAwayName"] = $teamAwayName;
  $match["ForecastHomeScore"] = $forecastHomeScore;
  $match["ForecastAwayScore"] = $forecastAwayScore;
  $match["ClassScore"] = $classScore;
  $match["TeamHomeKey"] = $teamHomeKey;
  $match["TeamAwayKey"] = $teamAwayKey;
  $match["ScoreWording"] = $scoreWording;
  $match["PerfectScore"] = $perfectScore;
  $match["CorrectScore"] = $correctScore;
  $match["BadScore"] = $badScore;
  $match["Status"] = $status;

  $matchForecasts = array();
  $queryForecats= "SELECT players.PrimaryKey PlayerKey,
    					players.AvatarName,
                        players.NickName,
                        IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
                        AND playermatchresults.MatchKey =$matchKey
                              ),0) Score,
                        IFNULL((SELECT forecasts.TeamHomeScore FROM forecasts WHERE forecasts.PlayerKey=players.PrimaryKey AND forecasts.MatchKey=$matchKey),'') TeamHomeForecast,
                        IFNULL((SELECT forecasts.TeamAwayScore FROM forecasts WHERE forecasts.PlayerKey=players.PrimaryKey AND forecasts.MatchKey=$matchKey),'') TeamAwayForecast,
                        IFNULL((SELECT results.LiveStatus FROM results WHERE results.MatchKey=$matchKey),0) LiveStatus,
                        IFNULL((SELECT results.ActualTime FROM results WHERE results.MatchKey=$matchKey),0) ActualTime
                        FROM playersenabled players
                        ORDER BY players.NickName ASC";

  $resultSetForecasts = $_databaseObject->queryPerf($queryForecats,"Get matches to be played by current day");

  while ($rowSetForecasts = $_databaseObject -> fetch_assoc ($resultSetForecasts)) {
    $teamHomeForecast = $rowSetForecasts["TeamHomeForecast"];
    $teamAwayForecast = $rowSetForecasts["TeamAwayForecast"];
    $score = $rowSetForecasts["Score"];
    $matchStatus = $rowSetForecasts["LiveStatus"];
    $matchTime = $rowSetForecasts["ActualTime"];
    $playerNickName = $rowSetForecasts["NickName"];
    $playerKey = $rowSetForecasts["PlayerKey"];

    $separator = "-";
    if ($teamHomeForecast=="") {
      $separator = "&nbsp;";
    }
    $scoreWording = "pt";
    if ($score>1) {
      $scoreWording = "pts";
    }
    $class= "";
    switch ($score) {
      case 0:
      case 1:
      case 2:
        if (trim($teamHomeForecast)!="") {
          $class = "failed";
        }
        break;
      case 5:
      case 6:
      case 8:
        $class = "success";
        break;
      case 15:
      case 30:
        $class = "perfect";
        break;
    }

    if ($matchStatus==0 &&  $matchTime==0) {
      $class = "NotStarted";

      if ($teamHomeForecast!="" && $playerKey!=$_authorisation->getConnectedUserKey()){
        $teamHomeForecast = "x";
        $teamAwayForecast = "x";
      }
      if ($teamHomeForecast =="" && $teamAwayForecast =="") {
        $class .= " noneForecast";
      }
    } else {
      if ($teamHomeForecast =="" && $teamAwayForecast =="") {
        $class .= " noneForecast";
      } else if ($teamHomeForecast == $teamAwayForecast ) {
        $class .= " draw";
      } else if ($teamHomeForecast > $teamAwayForecast ) {
        $class .= " teamHomeWin";
      } else if ($teamHomeForecast < $teamAwayForecast ) {
        $class .= " teamAwayWin";
      }
    }

    $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
    if (!empty($rowSetForecasts["AvatarName"])) {
      $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSetForecasts["AvatarName"];
    }

    $forecast = array();
    $forecast["Class"] = $class;
    $forecast["PlayerNickName"] = $playerNickName;
    $forecast["AvatarPath"] = $avatarPath;
    $forecast["TeamHomeForecast"] = $teamHomeForecast;
    $forecast["TeamAwayForecast"] = $teamAwayForecast;
    $forecast["Score"] = $score;
    $forecast["ScoreWording"] = $scoreWording;
    $matchForecasts[]=$forecast;
  }

  $match["Forecasts"]=$matchForecasts;

  $matchHomeEvents = array();
  $queryTeamHome= "SELECT teamplayers.*, events.TeamKey, events.EventType, events.EventTime

                                         FROM `events`

                                        INNER JOIN results ON results.PrimaryKey=events.ResultKey

                                        INNER JOIN matches ON matches.PrimaryKey=results.MatchKey

                                        INNER JOIN teamplayers ON teamplayers.PrimaryKey=events.TeamPlayerKey

                                        WHERE matches.PrimaryKey=$matchKey

                                          AND events.TeamKey=$teamHomeKey

                    				    ORDER BY events.EventTime, events.EventType";



  $resultSetTeamHome = $_databaseObject->queryPerf($queryTeamHome,"Get goaler");

  $homeYellowCards = 0;

  $homeRedCards = 0;

  while ($rowSetTeamHome = $_databaseObject -> fetch_assoc ($resultSetTeamHome)) {



    $goalType = "";

    $eventDescription = $rowSetTeamHome["FullName"] . " (".$rowSetTeamHome["EventTime"] . "')";

    switch ($rowSetTeamHome["EventType"]) {

      case "1":

        $goalType = "";

        $classEvent = "goal";

        break;

      case "2":

        $goalType = __encode(" (pén)");

        $classEvent = "goal";

        break;

      case "3":

        $goalType = " (csc)";

        $classEvent = "goal";

        break;

      case "4":

        $eventDescription = "<span class='title'>passeur : </span>" .$rowSetTeamHome["FullName"];

        $classEvent = "assist";

        break;

      case "5":

        $classEvent = "yellowCard";

        $homeYellowCards ++;

        break;

      case "6":

        $classEvent = "redCard";

        $homeRedCards ++;

        break;

      default:

        $classEvent = "";

        break;

    }



    $event = array();

    $event["ClassEvent"] = $classEvent;

    $event["EventDescription"] = $eventDescription;

    $event["GoalType"] = $goalType;



    $matchHomeEvents[]= $event;

  }

  $match["HomeEvents"]=$matchHomeEvents;

  $match["HomeYellowCards"]=$homeYellowCards;

  $match["HomeRedCards"]=$homeRedCards;





  $matchAwayEvents = array();

  $queryTeamAway= "SELECT teamplayers.*, events.TeamKey, events.EventType, events.EventTime

                                       FROM `events`

                                      INNER JOIN results ON results.PrimaryKey=events.ResultKey

                                      INNER JOIN matches ON matches.PrimaryKey=results.MatchKey

                                      INNER JOIN teamplayers ON teamplayers.PrimaryKey=events.TeamPlayerKey

                                      WHERE matches.PrimaryKey=$matchKey

                                        AND events.TeamKey=$teamAwayKey

                  				    ORDER BY events.EventTime, events.EventType";



  $resultSetTeamAway = $_databaseObject->queryPerf($queryTeamAway,"Get goaler");

  $goalTeamAway[]=array();

  $awayYellowCards = 0;

  $awayRedCards = 0;



  while ($rowSetTeamAway = $_databaseObject -> fetch_assoc ($resultSetTeamAway)) {



    $goalType = "";

    $eventDescription =  "(".$rowSetTeamAway["EventTime"] . "') " . $rowSetTeamAway["FullName"];

    switch ($rowSetTeamAway["EventType"]) {

      case "1":

        $goalType = "";

        $classEvent = "goal";

        break;

      case "2":

        $goalType = __encode(" (pén)");

        $classEvent = "goal";

        break;

      case "3":

        $goalType = " (csc)";

        $classEvent = "goal";

        break;

      case "4":

        $eventDescription = "<span class='title' >passeur : </span>" .$rowSetTeamAway["FullName"];

        $classEvent = "assist";

        break;

      case "5":

        $classEvent = "yellowCard";

        $awayYellowCards++;

        break;

      case "6":

        $classEvent = "redCard";

        $awayRedCards ++;

        break;

      default:

        $classEvent = "";

        break;

    }





    $event = array();

    $event["ClassEvent"] = $classEvent;

    $event["EventDescription"] = $eventDescription;

    $event["GoalType"] = $goalType;



    $matchAwayEvents[]= $event;



  }

  $match["AwayEvents"] = $matchAwayEvents;

  $match["AwayYellowCards"]=$awayYellowCards;

  $match["AwayRedCards"]=$awayRedCards;



  $matches[]=$match;

}

$arr["Matches"] = $matches;





$query = "SELECT PlayerKey,UNIX_TIMESTAMP(StateDate) StateDate, Rank, Score, Bonus,  Score+ Bonus GrandTotal

      FROM playergroupstates

      WHERE PlayerKey=".$_authorisation->getConnectedUserKey()."

      AND GroupKey=$_groupKey

      ORDER BY StateDate";

$resultSet = $_databaseObject->queryPerf($query,"Get players and score");

$serie1=array();

$serie1Data = array();

$serie2 = array();

$serie2Data = array();

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {

  $stateDate = ((int)$rowSet['StateDate']*1000+((is_est((int)$rowSet['StateDate'])?2:1)*3600*1000));

  $rank=-$rowSet['Rank'];

  $serie1Data[] = array($stateDate,$rank) ;

  $serie2Data[] = array($stateDate,(int)$rowSet['GrandTotal']);

}

$serie1["name"] = "Classement";

$serie1["color"] = "#DF5353";

$serie1["data"] = $serie1Data;



$serie2["name"] = 'Points';

$serie2["yAxis"] = 1;

$serie2["color"] = '#55BF3B';

$serie2["data"] = $serie2Data;



$series= array();

$series[]=$serie1;

$series[]=$serie2;

$arr["series"] = $series;



/* end - Get match detail */

//$arr["Database"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');

writeJsonResponse($arr);

require_once("end.file.php");





?>