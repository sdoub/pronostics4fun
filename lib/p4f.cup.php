<?php
function CreateCup () {

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

  $competitors = array();
  $queryPlayers= "SELECT PrimaryKey PlayerKey FROM playersenabled players WHERE  `LastConnection` > NOW( ) - INTERVAL 1 YEAR";
  $resultSetPlayers = $_databaseObject->queryPerf($queryPlayers,"Get players");
  while ($rowSetPlayer = $_databaseObject -> fetch_assoc ($resultSetPlayers))
  {
    $competitors[] = $rowSetPlayer["PlayerKey"];
  }

  shuffle($competitors);
  $cup = new Cup($competitors);
  $matches[]=$cup->ToArray();

//  $bracket = $cup->getBracket();
//  shuffle($competitors);
//  $cup2 = new Cup($competitors);
//  $cup2->setBracket($bracket);
//  $matches[]=$cup2->ToArray();

  $queryGroups= "SELECT PrimaryKey, EndDate, DayKey FROM groups WHERE CompetitionKey=".COMPETITION." AND DayKey BETWEEN 3 AND 10 ORDER BY DayKey ASC";
  $rowSetGroups = $_databaseObject->queryGetFullArray($queryGroups,"Get Groups");

  $queryCupRounds= "SELECT PrimaryKey FROM cuprounds ORDER BY PrimaryKey";
  $rowSetCupRounds = $_databaseObject->queryGetFullArray($queryCupRounds,"Get Cup Rounds");

  $rounds = $cup->getBracket();

  $groupKey = $rowSetGroups[0]["PrimaryKey"];
  if (count($rounds)>1) {
    // Create qualification round
    foreach ($rounds[0] as $match) {
      $insertQuery = "INSERT IGNORE INTO playercupmatches (PlayerHomeKey, PlayerAwayKey, SeasonKey, CupRoundKey, GroupKey)
      VALUES (".$match["c1"].", ".$match["c2"].", $seasonKey, ".$rowSetCupRounds[0]["PrimaryKey"].", ".$groupKey.")";
      $_databaseObject->queryPerf($insertQuery,"Added player match");
      array_unshift($competitors, $match["c1"]);
      array_unshift($competitors, $match["c2"]);
    }

    // Create Dummy matches for rest of players
    foreach ($competitors as $qualifiedPlayerKey) {
      $insertQuery = "INSERT IGNORE INTO playercupmatches (PlayerHomeKey, PlayerAwayKey, SeasonKey, CupRoundKey, GroupKey)
      VALUES (".$qualifiedPlayerKey.", -2, $seasonKey, ".$rowSetCupRounds[0]["PrimaryKey"].", ".$groupKey.")";

      $_databaseObject->queryPerf($insertQuery,"Added player match");
    }

    $groupKey = $rowSetGroups[1]["PrimaryKey"];
  }

  // Create first round
  foreach ($rounds[1] as $match) {
    $awayPlayerKey=$match["c2"];
    $homePlayerKey=$match["c1"];
    if ($match["c2"]==null)
      $awayPlayerKey = -1;
    if ($match["c1"]==null)
      $homePlayerKey = -1;
    $insertQuery = "INSERT IGNORE INTO playercupmatches (PlayerHomeKey, PlayerAwayKey, SeasonKey, CupRoundKey, GroupKey)
    VALUES (".$homePlayerKey.", ".$awayPlayerKey.", $seasonKey, ".$rowSetCupRounds[1]["PrimaryKey"].", ".$groupKey.")";

    $_databaseObject->queryPerf($insertQuery,"Added player match");
  }

  unset($cup);
  unset($competitors);

  return $matches;
}

function CreateNextRound ($cupRoundKey, $seasonKey) {
  global $_databaseObject;
  $queries = array();
  $groupKey= "";
  $queryRounds= "SELECT NextRoundKey FROM cuprounds WHERE PrimaryKey=$cupRoundKey";
  $rowRounds = $_databaseObject->queryGetFullArray($queryRounds,"Get Groups");

  $deleteQuery= "DELETE FROM playercupmatches WHERE CupRoundKey=".$rowRounds[0]["NextRoundKey"]." AND SeasonKey=$seasonKey";

  $_databaseObject->queryPerf($deleteQuery,"delete next round matches");

  $query= "SELECT PlayerHomeKey, PlayerAwayKey, HomeScore, AwayScore, GroupKey
FROM playercupmatches
WHERE CupRoundKey=$cupRoundKey AND SeasonKey=$seasonKey
ORDER BY PrimaryKey";

    $resultSet = $_databaseObject->queryPerf($query,"Get players and score");

    $qualifiedPlayers = array();
    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
    {
      $groupKey = $rowSet["GroupKey"] + 1;
      if ($rowSet["PlayerAwayKey"]==-1) {
        // home player is automatically qualified
        $qualifiedPlayers[]=$rowSet["PlayerHomeKey"];
      } elseif ($rowSet["HomeScore"]==null) {
        $qualifiedPlayers[]=-1;
      }elseif ($rowSet["HomeScore"]>$rowSet["AwayScore"]) {
        // Home Player is qualified
        $qualifiedPlayers[]=$rowSet["PlayerHomeKey"];
      } elseif ($rowSet["HomeScore"]<$rowSet["AwayScore"]) {
        // Away player is qualified
        $qualifiedPlayers[]=$rowSet["PlayerAwayKey"];
      } elseif ($rowSet["HomeScore"]==$rowSet["AwayScore"]) {
        // apply specific rules when players did same score
        // best correct score, then perfect, then first pronostics
        $qualifiedPlayers[]=$rowSet["PlayerAwayKey"];
      } else {
        // match is not played
        $qualifiedPlayers[]=-1;
      }
      $queries[] = $rowSet["HomeScore"];
    }
    $queries[] = $qualifiedPlayers;
    $count = 1;
    $homePlayer=0;
    foreach ($qualifiedPlayers as $player) {
      if ($count==1)
        $homePlayer = $player;
      else {
      // create cup match
        $insertQuery = "INSERT IGNORE INTO playercupmatches (PlayerHomeKey, PlayerAwayKey, SeasonKey, CupRoundKey, GroupKey)
        VALUES (".$homePlayer.", ".$player.", $seasonKey, ".$rowRounds[0]["NextRoundKey"].", ".$groupKey.")";

        $_databaseObject->queryPerf($insertQuery,"Added player match");

        $count=0;
      }
      $count++;
    }
    return $queries;
}

function GetP4FCupMatchScores ($groupKey) {

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

      $updateQuery="UPDATE playercupmatches SET HomeScore=$score WHERE PlayerHomeKey=$playerKey AND GroupKey=$groupKey";
      $_databaseObject -> queryPerf ($updateQuery, "Update home player score against group");

      $queries[] = $updateQuery;
      $updateQuery="UPDATE playercupmatches SET AwayScore=$score WHERE PlayerAwayKey=$playerKey AND GroupKey=$groupKey";
      $_databaseObject -> queryPerf ($updateQuery, "Update away player score against group");

      $queries[] = $updateQuery;

    }

  return $queries;
}