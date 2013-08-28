<?php

function CreateFirstSeason () {

  global $_databaseObject;
  $matches = array();
// Get Competitors for divisions
  $seasonKey = "";
  $query= "SELECT seasons.PrimaryKey FROM seasons WHERE CompetitionKey=".COMPETITION." ORDER BY seasons.Order";
  $resultSet = $_databaseObject->queryPerf($query,"Get season");
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
    $seasonKey=$rowSet["PrimaryKey"];
  }

  $query= "SELECT divisions.PrimaryKey, divisions.Order FROM divisions ORDER BY divisions.Order";
  $resultSet = $_databaseObject->queryPerf($query,"Get divisions");
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
    $competitors = array();
    $from = ((int)$rowSet["Order"] -1) * 10;
    $nbrOfItem = 10;
    $queryPlayers= "SELECT PlayerKey FROM playerranking INNER JOIN  playersenabled ON playersenabled.PrimaryKey=playerranking.PlayerKey WHERE RankDate =(SELECT MAX(RankDate) FROM playerranking) ORDER BY Rank LIMIT ".$from.",".$nbrOfItem;
    $resultSetPlayers = $_databaseObject->queryPerf($queryPlayers,"Get players for current division");
    while ($rowSetPlayer = $_databaseObject -> fetch_assoc ($resultSetPlayers))
    {
      $competitors[] = $rowSetPlayer["PlayerKey"];
    }
    $championship = new Championship();
    $championship->create($competitors);
    $matches[]=$championship->ToArray();

    $queryGroups= "SELECT PrimaryKey, EndDate, DayKey FROM groups WHERE CompetitionKey=".COMPETITION." AND DayKey BETWEEN 2 AND 10";
    $resultSetGroups = $_databaseObject->queryPerf($queryGroups,"Get players for current division");
    while ($rowSetGroup = $_databaseObject -> fetch_assoc ($resultSetGroups))
    {
      foreach ($championship->tour as $match) {

        if ($rowSetGroup["DayKey"]-1==$match["Round"]) {
          $insertQuery = "INSERT IGNORE INTO playerdivisionmatches (PlayerHomeKey, PlayerAwayKey, SeasonKey, DivisionKey, GroupKey)
          VALUES (".$match["Home"].", ".$match["Away"].", $seasonKey, ".$rowSet["PrimaryKey"].", ".$rowSetGroup["PrimaryKey"].")";

          $_databaseObject->queryPerf($insertQuery,"Added player match");
        }
      }

    }

    unset ($championship);
    unset($competitors);
  }
  return $matches;
}

function CalculateP4FDivisionsRanking ($seasonKey) {

  global $_databaseObject;
  $queries = array();
  $queryDivisions= "SELECT divisions.PrimaryKey, divisions.Order FROM divisions ORDER BY divisions.Order";
  $resultSetDivisions = $_databaseObject->queryPerf($queryDivisions,"Get divisions");
  while ($rowSetDivision = $_databaseObject -> fetch_assoc ($resultSetDivisions))
  {

  $query= "SELECT TMP.PlayerKey, SUM(Won) AS Won, SUM(Drawn) AS Drawn, SUM(Lost) Lost,
                  SUM(PointsFor) AS PointsFor, SUM(PointsAgainst) AS PointsAgainst,
                  SUM(Won*3)+SUM(Drawn) AS Score,SUM(PointsFor) - SUM(PointsAgainst) AS Difference
  FROM (SELECT PlayerHomeKey AS PlayerKey,
                  CASE WHEN HomeScore>AwayScore THEN 1 ELSE 0 END AS Won,
                  CASE WHEN HomeScore=AwayScore THEN 1 ELSE 0 END AS Drawn,
                  CASE WHEN HomeScore<AwayScore THEN 1 ELSE 0 END AS Lost,
                  IFNULL(HomeScore,0) AS PointsFor,
                  IFNULL(AwayScore,0) AS PointsAgainst
    FROM playerdivisionmatches PDM
   WHERE PDM.DivisionKey=".$rowSetDivision["PrimaryKey"]."
     AND PDM.SeasonKey=$seasonKey
     UNION
    SELECT PlayerAwayKey AS PlayerKey,
                  CASE WHEN HomeScore<AwayScore THEN 1 ELSE 0 END AS Won,
                  CASE WHEN HomeScore=AwayScore THEN 1 ELSE 0 END AS Drawn,
                  CASE WHEN HomeScore>AwayScore THEN 1 ELSE 0 END AS Lost,
                  IFNULL(AwayScore,0),
                  IFNULL(HomeScore,0)
    FROM playerdivisionmatches PDM
   WHERE PDM.DivisionKey=".$rowSetDivision["PrimaryKey"]."
     AND PDM.SeasonKey=$seasonKey ) TMP
GROUP BY TMP.PlayerKey
ORDER BY Score DESC,Difference DESC, PointsFor DESC";

    $resultSet = $_databaseObject->queryPerf($query,"Get players and score");

    $rank = 0;
    $realRank = 0;
    $previousScore = 0;
    $previousDiff = 0;
    $previousPointsFor = 0;

    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
    {
      $playerKey =$rowSet["PlayerKey"];
      $realRank++;
      if ($rowSet["Score"]!=$previousScore) {
        $rank=$realRank;
      } elseif ($rowSet["Difference"]!=$previousDiff){
        $rank=$realRank;
      } elseif ($rowSet["PointsFor"]!=$previousPointsFor){
        $rank=$realRank;
      }

      $previousScore=$rowSet["Score"];
      $previousDiff=$rowSet["Difference"];
      $previousPointsFor=$rowSet["PointsFor"];

      $insertQuery="INSERT IGNORE INTO playerdivisionranking (DivisionKey, SeasonKey, PlayerKey, Rank, Score, Won, Drawn, Lost, PointsFor, PointsAgainst, PointsDifference)
              VALUES (". $rowSetDivision["PrimaryKey"] . ",$seasonKey,$playerKey,$rank,".$rowSet["Score"].",".$rowSet["Won"].",".$rowSet["Drawn"].",".$rowSet["Lost"].",".$rowSet["PointsFor"].",".$rowSet["PointsAgainst"].",".$rowSet["Difference"].") ON DUPLICATE KEY UPDATE Rank=$rank";
      $_databaseObject -> queryPerf ($insertQuery, "Insert rank record");

      $queries[] = $insertQuery;
    }

  }
  return $queries;
}

function GetP4FMatchScores ($groupKey) {

  global $_databaseObject;
  $queries = array();

      $query= "SELECT players.PrimaryKey PlayerKey,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey
                                            FROM matches
                                           INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
                                                              AND groups.PrimaryKey=$groupKey)
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey
                                            FROM results
                                           WHERE results.LiveStatus=10)),0)
      ) Score
FROM players
GROUP BY players.PrimaryKey";

    $resultSet = $_databaseObject->queryPerf($query,"Get players and score");

    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
    {
      $playerKey =$rowSet["PlayerKey"];
      $score =$rowSet["Score"];

      $updateQuery="UPDATE playerdivisionmatches SET HomeScore=$score WHERE PlayerHomeKey=$playerKey AND GroupKey=$groupKey";
      $_databaseObject -> queryPerf ($updateQuery, "Update home player score against group");

      $queries[] = $updateQuery;
      $updateQuery="UPDATE playerdivisionmatches SET AwayScore=$score WHERE PlayerAwayKey=$playerKey AND GroupKey=$groupKey";
      $_databaseObject -> queryPerf ($updateQuery, "Update away player score against group");

      $queries[] = $updateQuery;

    }


  return $queries;
}