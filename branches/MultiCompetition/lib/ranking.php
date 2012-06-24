<?php
function CalculateRanking ($scheduleDate) {
  global $_databaseObject;

  $query="SELECT 1 FROM results
WHERE DATE(results.ResultDate)=DATE(FROM_UNIXTIME($scheduleDate))
AND results.LiveStatus=10
AND results.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . " )";
  $_databaseObject -> queryPerf ($query, "Check if at least one match has been played for the scheduledate");
  if ($_databaseObject -> num_rows()>0) {

    $query= "SELECT players.PrimaryKey,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . " AND DATE(matches.ScheduleDate)<=DATE(FROM_UNIXTIME($scheduleDate)))
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)
      ),0) +
      IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score,
(SELECT DATE(MAX(results.ResultDate)) FROM results WHERE results.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . " ) AND DATE(results.ResultDate)<=DATE(FROM_UNIXTIME($scheduleDate))) ResultDate,
(SELECT COUNT(1) FROM forecasts WHERE forecasts.PlayerKey = players.PrimaryKey
AND forecasts.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus=10 INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")) MatchPlayed,
(SELECT COUNT(1) FROM playermatchresults PMR
  WHERE PMR.PlayerKey = players.PrimaryKey
    AND PMR.MatchKey IN (SELECT results.MatchKey
                           FROM results
                          INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
                                AND matches.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")
                          WHERE results.LiveStatus=10)
    AND PMR.Score>=5) MatchGood,
(SELECT COUNT(1) FROM playermatchresults PMR
  WHERE PMR.IsPerfect=1
    AND PMR.PlayerKey = players.PrimaryKey
    AND PMR.MatchKey IN (SELECT results.MatchKey
                           FROM results
                          INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
                                AND matches.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")
                          WHERE results.LiveStatus=10)) MatchPerfect
FROM players
GROUP BY players.PrimaryKey
ORDER BY Score DESC,MatchGood DESC,MatchPerfect DESC,MatchPlayed DESC";

    $resultSet = $_databaseObject->queryPerf($query,"Get players and score");

    $rank = 0;
    $realRank = 0;
    $previousScore = 0;
    $previousMatchGood = 0;
    $previousMatchPerfect = 0;
    $previousMatchPlayed = 0;

    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
    {
      $playerKey =$rowSet["PrimaryKey"];
      $realRank++;
      if ($rowSet["Score"]!=$previousScore) {
        $rank=$realRank;
      } elseif ($rowSet["MatchGood"]!=$previousMatchGood){
        $rank=$realRank;
      } elseif ($rowSet["MatchPerfect"]!=$previousMatchPerfect){
        $rank=$realRank;
      } elseif ($rowSet["MatchPlayed"]!=$previousMatchPlayed){
        $rank=$realRank;
      }

      if ($rowSet["ResultDate"]) {
        $resultDate = $rowSet["ResultDate"];
        $previousScore=$rowSet["Score"];
        $previousMatchGood=$rowSet["MatchGood"];
        $previousMatchPerfect=$rowSet["MatchPerfect"];
        $previousMatchPlayed=$rowSet["MatchPlayed"];

        $insertQuery="INSERT IGNORE INTO playerranking (CompetitionKey,PlayerKey, RankDate, Rank)
                VALUES (". COMPETITION . ",$playerKey,'$resultDate',$rank) ON DUPLICATE KEY UPDATE Rank=$rank";
        $_databaseObject -> queryPerf ($insertQuery, "Insert rank record");
      }
    }
  }
}

function CalculateGroupRanking ($groupKey,$scheduleDate) {
  global $_databaseObject;

  $query="SELECT 1 FROM results
WHERE DATE(results.ResultDate)=DATE(FROM_UNIXTIME($scheduleDate))
AND results.LiveStatus=10
AND results.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=" . $groupKey . " )";
  $_databaseObject -> queryPerf ($query, "Check if at least one match has been played for the scheduledate");
  if ($_databaseObject -> num_rows()>0) {


    $query= "SELECT players.PrimaryKey,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.PrimaryKey=$groupKey AND groups.CompetitionKey=" . COMPETITION . "  AND DATE(matches.ScheduleDate)<=DATE(FROM_UNIXTIME($scheduleDate)))
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . " AND groups.PrimaryKey=$groupKey)),0)) Score,
  (SELECT DATE(MAX(results.ResultDate)) FROM results WHERE results.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "  ) AND DATE(results.ResultDate)<=DATE(FROM_UNIXTIME($scheduleDate))) ResultDate,
(SELECT COUNT(playermatchresults.Score) FROM playermatchresults
  WHERE players.PrimaryKey=playermatchresults.PlayerKey
    AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey
    									FROM matches
    									INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
    									       AND groups.PrimaryKey=$groupKey
    									       AND groups.CompetitionKey=" . COMPETITION . "
    									       AND DATE(matches.ScheduleDate)<=DATE(FROM_UNIXTIME($scheduleDate)))
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)
      AND playermatchresults.Score>=5) ScoreCorrect,
(SELECT COUNT(playermatchresults.Score) FROM playermatchresults
  WHERE players.PrimaryKey=playermatchresults.PlayerKey
    AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey
    									FROM matches
    									INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
    									       AND groups.PrimaryKey=$groupKey
    									       AND groups.CompetitionKey=" . COMPETITION . "
    									       AND DATE(matches.ScheduleDate)<=DATE(FROM_UNIXTIME($scheduleDate)))
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)
      AND playermatchresults.Score>=15) ScorePerfect
FROM players
GROUP BY players.PrimaryKey
ORDER BY Score DESC, ScoreCorrect DESC, ScorePerfect DESC";

    $resultSet = $_databaseObject->queryPerf($query,"Get players and score for current group");

    $rank = 0;
    $realRank=0;
    $previousScore = 0;
    $previousMatchGood = 0;
    $previousMatchPerfect = 0;


    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
    {
      $playerKey =$rowSet["PrimaryKey"];
      $realRank++;
      if ($rowSet["Score"]!=$previousScore || $rowSet["Score"]>0) {
        $rank=$realRank;
      } elseif ($rowSet["ScoreCorrect"]!=$previousMatchGood){
        $rank=$realRank;
      } elseif ($rowSet["ScorePerfect"]!=$previousMatchPerfect){
        $rank=$realRank;
      }


      if ($rowSet["ResultDate"]) {
        $resultDate = $rowSet["ResultDate"];
        $previousScore=$rowSet["Score"];
        $previousMatchGood=$rowSet["ScoreCorrect"];
        $previousMatchPerfect=$rowSet["ScorePerfect"];

        $insertQuery="INSERT IGNORE INTO playergroupranking (PlayerKey, GroupKey, RankDate, Rank)
                VALUES ($playerKey,$groupKey,'$resultDate',$rank) ON DUPLICATE KEY UPDATE Rank=$rank";
        $_databaseObject -> queryPerf ($insertQuery, "Insert group rank record");
      }
    }
  }
}
function GetTeamsRanking () {
  global $_databaseObject;
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
          LEFT JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus=10
          INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION;

  $resultSet = $_databaseObject->queryPerf($sql,"Get match results");
  $arrTeams = array();
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
    if (array_key_exists($rowSet["TeamHomeKey"],$arrTeams)) {

      $tempArray = $arrTeams[$rowSet["TeamHomeKey"]];
      $tempArray["TeamKey"]=$rowSet["TeamHomeKey"];
      $tempArray["TeamName"]=$rowSet["TeamHomeName"];
      $tempArray["GoalsHome"]+=$rowSet["TeamHomeScore"];
      $tempArray["GoalsHomeAgainst"]+=$rowSet["TeamAwayScore"];
      $tempArray["Goals"]+=$rowSet["TeamHomeScore"];
      $tempArray["GoalsAgainst"]+=$rowSet["TeamAwayScore"];
      if ($rowSet["LiveStatus"]==10) {
        if ($rowSet["TeamHomeScore"] - $rowSet["TeamAwayScore"]<0){
          $tempScore=0;
          $tempArray["MatchLost"]+=1;
        }
        else if ($rowSet["TeamHomeScore"] - $rowSet["TeamAwayScore"]>0){
          $tempScore=3;
          $tempArray["MatchWin"]+=1;
        }
        else {
          $tempScore=1;
          $tempArray["MatchDraw"]+=1;
        }
        $tempArray["Played"]+=1;
      }
      else {
        $tempScore=0;
        $tempArray["Played"]+=0;
      }


      $tempArray["Score"]+=$tempScore;

      $arrTeams[$rowSet["TeamHomeKey"]]=$tempArray;
    }
    else {

      $tempArray = array();
      $tempArray["TeamKey"]=$rowSet["TeamHomeKey"];
      $tempArray["TeamName"]=$rowSet["TeamHomeName"];
      $tempArray["GoalsHome"]=$rowSet["TeamHomeScore"];
      $tempArray["GoalsHomeAgainst"]=$rowSet["TeamAwayScore"];
      $tempArray["GoalsAway"]=0;
      $tempArray["GoalsAwayAgainst"]=0;
      $tempArray["Goals"]=$rowSet["TeamHomeScore"];
      $tempArray["GoalsAgainst"]=$rowSet["TeamAwayScore"];
      $tempArray["MatchWin"]=0;
      $tempArray["MatchLost"]=0;
      $tempArray["MatchDraw"]=0;
      if ($rowSet["LiveStatus"]==10) {

        if ($rowSet["TeamHomeScore"] - $rowSet["TeamAwayScore"]<0){
          $tempScore=0;
          $tempArray["MatchLost"]=1;

        }
        else if ($rowSet["TeamHomeScore"] - $rowSet["TeamAwayScore"]>0){
          $tempScore=3;
          $tempArray["MatchWin"]=1;
        }
        else {
          $tempScore=1;
          $tempArray["MatchDraw"]=1;
        }
        $tempArray["Played"]=1;
      }
      else {
        $tempScore=0;
        $tempArray["Played"]=0;
      }
      $tempArray["Score"]=$tempScore;
      $arrTeams[$rowSet["TeamHomeKey"]]=$tempArray;
    }
    if (array_key_exists($rowSet["TeamAwayKey"],$arrTeams)) {

      $tempArray = $arrTeams[$rowSet["TeamAwayKey"]];
      $tempArray["TeamKey"]=$rowSet["TeamAwayKey"];
      $tempArray["TeamName"]=$rowSet["TeamAwayName"];
      $tempArray["GoalsAway"]+=$rowSet["TeamAwayScore"];
      $tempArray["GoalsAwayAgainst"]+=$rowSet["TeamHomeScore"];
      $tempArray["Goals"]+=$rowSet["TeamAwayScore"];
      $tempArray["GoalsAgainst"]+=$rowSet["TeamHomeScore"];
      if ($rowSet["LiveStatus"]==10) {
        if ($rowSet["TeamHomeScore"] - $rowSet["TeamAwayScore"]<0){
          $tempScore=3;
          $tempArray["MatchWin"]+=1;
        }
        else if ($rowSet["TeamHomeScore"] - $rowSet["TeamAwayScore"]>0){
          $tempScore=0;
          $tempArray["MatchLost"]+=1;
        }
        else {
          $tempScore=1;
          $tempArray["MatchDraw"]+=1;
        }
        $tempArray["Played"]+=1;
      }
      else {
        $tempScore=0;
        $tempArray["Played"]+=0;
      }

      $tempArray["Score"]+=$tempScore;

      $arrTeams[$rowSet["TeamAwayKey"]]=$tempArray;

    }
    else {

      $tempArray = array();
      $tempArray["TeamKey"]=$rowSet["TeamAwayKey"];
      $tempArray["TeamName"]=$rowSet["TeamAwayName"];
      $tempArray["GoalsHome"]=0;
      $tempArray["GoalsHomeAgainst"]=0;
      $tempArray["GoalsAway"]=$rowSet["TeamAwayScore"];
      $tempArray["GoalsAwayAgainst"]=$rowSet["TeamHomeScore"];
      $tempArray["Goals"]=$rowSet["TeamAwayScore"];
      $tempArray["GoalsAgainst"]=$rowSet["TeamHomeScore"];
      $tempArray["MatchWin"]=0;
      $tempArray["MatchLost"]=0;
      $tempArray["MatchDraw"]=0;

      if ($rowSet["LiveStatus"]==10) {
        if ($rowSet["TeamHomeScore"] - $rowSet["TeamAwayScore"]<0){
          $tempScore=3;
          $tempArray["MatchWin"]=1;
        }
        else if ($rowSet["TeamHomeScore"] - $rowSet["TeamAwayScore"]>0){
          $tempScore=0;
          $tempArray["MatchLost"]=1;
        }
        else {
          $tempScore=1;
          $tempArray["MatchDraw"]=1;
        }
        $tempArray["Played"]=1;
      }
      else {
        $tempScore=0;
        $tempArray["Played"]=0;
      }
      $tempArray["Score"]=$tempScore;
      $arrTeams[$rowSet["TeamAwayKey"]]=$tempArray;
    }

  }


  function compare($a, $b)
  {
    if ($a["Score"]==$b["Score"]){
      if ($a["Goals"]-$a["GoalsAgainst"]==$b["Goals"]-$b["GoalsAgainst"]) {
        if ($a["Goals"]>$b["Goals"]) {
          return -1;
        }
        else if ($a["Goals"]==$b["Goals"])
        {
          if ($a["GoalsAgainst"]==$b["GoalsAgainst"]){
            return strcmp($a["TeamName"], $b["TeamName"]);
          }
          else  if ($a["GoalsAgainst"]>$b["GoalsAgainst"]) {
            return 1;
          }
          else
          return -1;

        }
        else
        return 1;
      }
      else if ($a["Goals"]-$a["GoalsAgainst"]>$b["Goals"]-$b["GoalsAgainst"]) {
        return -1;
      }
      else {
        return 1;
      }
    }
    else {
      return ($a["Score"]>$b["Score"]) ? -1 : 1;
    }

  }

  usort($arrTeams, "compare");

  $rank=0;
  $realRank=0;
  $previousScore = "0|0|0";
  $arrTeamsWithRank = array();
  while (list ($key, $value) = each ($arrTeams)) {
    $diff = $value['Goals'] - $value['GoalsAgainst'];
    $realRank++;
    if (strcmp($previousScore,$value['Score']."|".$diff."|".$value['Goals'])!=0) {
      $rank=$realRank;
    }
    $previousScore=$value['Score']."|".$diff."|".$value['Goals'];

    $tempArray = $value;
    $tempArray["TeamRank"]=$rank;
    $tempArray["GoalDifference"]=$diff;
    $arrTeamsWithRank[$value['TeamKey']]=$tempArray;
  }

  return $arrTeamsWithRank;
}

function CalculateGroupRankingState ($groupKey,$stateDate) {
//TODO: Review the issue regarding the equal score
  global $_databaseObject;

  $query= "
  SELECT playergroupstates.PlayerKey, playergroupstates.Score+playergroupstates.Bonus Score
      FROM playergroupstates
      WHERE playergroupstates.GroupKey =$groupKey
      AND playergroupstates.StateDate=FROM_UNIXTIME($stateDate)
ORDER BY Score DESC";

  $resultSet = $_databaseObject->queryPerf($query,"Get players and score for current state group");

  $rank = 0;
  $realRank=0;
  $previousScore = 0;
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
    $playerKey =$rowSet["PlayerKey"];
    $realRank++;
    if ($rowSet["Score"]!=$previousScore) {
      $rank=$realRank;
    }

    $previousScore=$rowSet["Score"];

    $insertQuery="UPDATE playergroupstates SET Rank =$rank WHERE PlayerKey=$playerKey AND GroupKey=$groupKey AND StateDate=FROM_UNIXTIME($stateDate)";
    $_databaseObject -> queryPerf ($insertQuery, "Update group rank of the current state");
  }

}

?>