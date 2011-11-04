<?php
function ComputeScore ($matchKey){
  global  $_databaseObject;

  $query = "SELECT results.PrimaryKey, matches.IsBonusMatch FROM results INNER JOIN matches ON results.MatchKey=matches.PrimaryKey WHERE MatchKey=$matchKey AND LiveStatus>0";
  $resulSetMatch = $_databaseObject -> queryPerf ($query, "Get match result");
  if ($_databaseObject -> num_rows()>0) {
    $rowSetMatch = $_databaseObject -> fetch_assoc ($resulSetMatch);

    if ($rowSetMatch["IsBonusMatch"]==1) {
      $isBonus = "*2";
    }
    else {
      $isBonus ="";
    }

    $query = "
SELECT
(SELECT COUNT(1) FROM events eventsTeamHome
  WHERE eventsTeamHome.ResultKey=results.PrimaryKey
    AND matches.TeamHomeKey=eventsTeamHome.TeamKey
    AND eventsTeamHome.EventType IN (1,2,3)
    AND half<4) TeamHomeScore,
(SELECT COUNT(1) FROM events eventsTeamAway
  WHERE eventsTeamAway.ResultKey=results.PrimaryKey
    AND matches.TeamAwayKey=eventsTeamAway.TeamKey
    AND eventsTeamAway.EventType IN (1,2,3)
    AND half<4) TeamAwayScore
FROM results
INNER JOIN matches ON matches.PrimaryKey=results.MatchKey AND matches.PrimaryKey=$matchKey";

    $resultSet = $_databaseObject -> queryPerf ($query, "Get match result");
    $rowSet = $_databaseObject -> fetch_assoc ($resultSet);

    $_teamHomeScore = $rowSet["TeamHomeScore"];
    $_teamAwayScore = $rowSet["TeamAwayScore"];

    //Compute Perfect
    $query= "INSERT IGNORE INTO playermatchresults (PlayerKey, MatchKey, Score, IsPerfect)
(SELECT
PlayerKey,
    $matchKey,
15$isBonus,
1
FROM forecasts
WHERE forecasts.MatchKey=$matchKey
AND TeamHomeScore=$_teamHomeScore
AND TeamAwayScore=$_teamAwayScore) ON DUPLICATE KEY UPDATE Score=15$isBonus, IsPerfect=1";

    $_databaseObject -> queryPerf ($query, "Compute perfect result");

    //Compute correct with good diff
    $query= "INSERT IGNORE INTO playermatchresults (PlayerKey, MatchKey, Score, IsPerfect)
(SELECT
PlayerKey,
    $matchKey,
8$isBonus,
0
FROM forecasts
WHERE forecasts.MatchKey=$matchKey
AND TeamHomeScore<>$_teamHomeScore
AND TeamAwayScore<>$_teamAwayScore
AND $_teamHomeScore-$_teamAwayScore=TeamHomeScore-TeamAwayScore) ON DUPLICATE KEY UPDATE Score=8$isBonus, IsPerfect=0";

    $_databaseObject -> queryPerf ($query, "Compute correct result with good diff");


    //Compute correct and good goals for one team
    $query= "INSERT IGNORE INTO playermatchresults (PlayerKey, MatchKey, Score, IsPerfect)
(SELECT
PlayerKey,
    $matchKey,
6$isBonus,
0
FROM forecasts
WHERE forecasts.MatchKey=$matchKey
AND (TeamHomeScore=$_teamHomeScore
OR TeamAwayScore=$_teamAwayScore)
AND $_teamHomeScore-$_teamAwayScore<>TeamHomeScore-TeamAwayScore
AND SIGN($_teamHomeScore-$_teamAwayScore)=SIGN(TeamHomeScore-TeamAwayScore)) ON DUPLICATE KEY UPDATE Score=6$isBonus, IsPerfect=0";

    $_databaseObject -> queryPerf ($query, "Compute correct and good goals for one team");


    //Compute correct
    $query= "INSERT IGNORE INTO playermatchresults (PlayerKey, MatchKey, Score, IsPerfect)
(SELECT
PlayerKey,
    $matchKey,
5$isBonus,
0
FROM forecasts
WHERE forecasts.MatchKey=$matchKey
AND TeamHomeScore<>$_teamHomeScore
AND TeamAwayScore<>$_teamAwayScore
AND SIGN($_teamHomeScore-$_teamAwayScore)=SIGN(TeamHomeScore-TeamAwayScore)
AND $_teamHomeScore-$_teamAwayScore<>TeamHomeScore-TeamAwayScore) ON DUPLICATE KEY UPDATE Score=5$isBonus, IsPerfect=0";

    $_databaseObject -> queryPerf ($query, "Compute correct");

    //Compute good goal for one team
    $query= "INSERT IGNORE INTO playermatchresults (PlayerKey, MatchKey, Score, IsPerfect)
(SELECT
PlayerKey,
    $matchKey,
1$isBonus,
0
FROM forecasts
WHERE forecasts.MatchKey=$matchKey
AND (TeamHomeScore=$_teamHomeScore
OR TeamAwayScore=$_teamAwayScore)
AND SIGN($_teamHomeScore-$_teamAwayScore)<>SIGN(TeamHomeScore-TeamAwayScore)) ON DUPLICATE KEY UPDATE Score=1$isBonus, IsPerfect=0";

    $_databaseObject -> queryPerf ($query, "Compute good goal for one team");


    //Compute bad result
    $query= "INSERT IGNORE INTO playermatchresults (PlayerKey, MatchKey, Score, IsPerfect)
(SELECT
PlayerKey,
    $matchKey,
0,
0
FROM forecasts
WHERE forecasts.MatchKey=$matchKey
AND TeamHomeScore<>$_teamHomeScore
AND TeamAwayScore<>$_teamAwayScore
AND SIGN($_teamHomeScore-$_teamAwayScore)<>SIGN(TeamHomeScore-TeamAwayScore)) ON DUPLICATE KEY UPDATE Score=0, IsPerfect=0";

    $_databaseObject -> queryPerf ($query, "bad result");

    //Compute without forecasts
    $query= "INSERT IGNORE INTO playermatchresults (PlayerKey, MatchKey, Score, IsPerfect)
(SELECT
PrimaryKey,
    $matchKey,
0,
0
FROM players
WHERE NOT EXISTS (SELECT 1 FROM forecasts WHERE forecasts.PlayerKey=players.PrimaryKey AND forecasts.MatchKey=$matchKey))
ON DUPLICATE KEY UPDATE Score=0, IsPerfect=0";

    $_databaseObject -> queryPerf ($query, "Without forecasts");
  }
}

function ComputeGroupScore ($groupKey){
  global $_databaseObject;

  $query = "SELECT COUNT(*) NbrMatchCompleted FROM results INNER JOIN matches ON results.MatchKey=matches.PrimaryKey AND matches.GroupKey=$groupKey AND results.LiveStatus=10";
  $resulSetGroup = $_databaseObject -> queryPerf ($query, "Get match result");
  $rowSetGroup = $_databaseObject -> fetch_assoc ($resulSetGroup);

  if ($rowSetGroup["NbrMatchCompleted"]==10) {

    //Compute group score Bonus 20 points
    $query= "INSERT IGNORE INTO playergroupresults (PlayerKey, GroupKey, Score)
      (SELECT
      playermatchresults.PlayerKey,
      $groupKey,
      20
      FROM playermatchresults
      INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
      INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
      WHERE groups.PrimaryKey=$groupKey
      AND playermatchresults.Score>=5
      GROUP BY playermatchresults.PlayerKey
      HAVING COUNT(1)=7
      ) ON DUPLICATE KEY UPDATE Score=20";

      $_databaseObject -> queryPerf ($query, "Compute group score Bonus 20 points");


      //Compute group score Bonus 40 points
      $query= "INSERT IGNORE INTO playergroupresults (PlayerKey, GroupKey, Score)
      (SELECT
      playermatchresults.PlayerKey,
      $groupKey,
      40
      FROM playermatchresults
      INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
      INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
      WHERE groups.PrimaryKey=$groupKey
      AND playermatchresults.Score>=5
      GROUP BY playermatchresults.PlayerKey
      HAVING COUNT(1)=8
      ) ON DUPLICATE KEY UPDATE Score=40";

      $_databaseObject -> queryPerf ($query, "Compute group score Bonus 40 points");

      //Compute group score Bonus 60 points
      $query= "INSERT IGNORE INTO playergroupresults (PlayerKey, GroupKey, Score)
      (SELECT
      playermatchresults.PlayerKey,
      $groupKey,
      60
      FROM playermatchresults
      INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
      INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
      WHERE groups.PrimaryKey=$groupKey
      AND playermatchresults.Score>=5
      GROUP BY playermatchresults.PlayerKey
      HAVING COUNT(1)=9
      ) ON DUPLICATE KEY UPDATE Score=60";

      $_databaseObject -> queryPerf ($query, "Compute group score Bonus 60 points");

      //Compute group score Bonus 100 points
      $query= "INSERT IGNORE INTO playergroupresults (PlayerKey, GroupKey, Score)
      (SELECT
      playermatchresults.PlayerKey,
      $groupKey,
      100
      FROM playermatchresults
      INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
      INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
      WHERE groups.PrimaryKey=$groupKey
      AND playermatchresults.Score>=5
      GROUP BY playermatchresults.PlayerKey
      HAVING COUNT(1)=10
      ) ON DUPLICATE KEY UPDATE Score=100";

      $_databaseObject -> queryPerf ($query, "Compute group score Bonus 100 points");

      //Compute group score Bonus 0 points
      $query= "INSERT IGNORE INTO playergroupresults (PlayerKey, GroupKey, Score)
      (SELECT
      playermatchresults.PlayerKey,
      $groupKey,
      0
      FROM playermatchresults
      INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
      INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
      WHERE groups.PrimaryKey=$groupKey
      AND playermatchresults.Score>=5
      GROUP BY playermatchresults.PlayerKey
      HAVING COUNT(1)<7
      ) ON DUPLICATE KEY UPDATE Score=0";

      $_databaseObject -> queryPerf ($query, "Compute group score Bonus 0 points");


      $query= "UPDATE playergroupresults SET Score=Score+2 WHERE EXISTS
          (SELECT 1 FROM votes INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
            WHERE votes.PlayerKey=playergroupresults.PlayerKey
    		  AND matches.GroupKey=playergroupresults.GroupKey)
         AND playergroupresults.GroupKey=$groupKey";

      $_databaseObject -> queryPerf ($query, "Add 2 points for person who has voted");


  }


}