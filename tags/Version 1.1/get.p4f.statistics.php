<?php
require_once("begin.file.php");

if (isset($_GET["Group"])) {
  $_groups = $_GET["Group"];
}
else {
  $_groups = "All";
}

if (isset($_GET["View"])) {
  $_view = $_GET["View"];
}
else {
  $_view = "Forecasts";
}


$sql = "SELECT
SUM(STATS.`perfect`) perfects,
SUM(`correct`) corrects,
SUM(`NbrOfForecasts`) NbrOfForecasts,
SUM(`MaxNbrOfPlayers`) MaxNbrOfPlayers,
SUM(GroupScore) GroupScore,
SUM(PlayersScore) PlayersScore,
STATS.DayKey
FROM (
SELECT
    SUM(NbrOfPerfect) perfect,
    SUM(NbrOfCorrect) correct,
    SUM(NbrOfPlayers) NbrOfForecasts,
    MAX(NbrOfPlayers) MaxNbrOfPlayers,
    MAX(0) GroupScore,
    MAX(0) PlayersScore,
    DayKey
    FROM
    (SELECT matches.PrimaryKey MatchKey,
            matches.GroupKey GroupKey,
            SUM((SELECT COUNT(1) FROM forecasts
              WHERE forecasts.MatchKey = matches.PrimaryKey)) NbrOfPlayers,
            SUM((SELECT COUNT(1) FROM playermatchresults
              WHERE playermatchresults.MatchKey = results.MatchKey
                AND playermatchresults.IsPerfect = 1)) NbrOfPerfect,
            SUM((SELECT COUNT(1) FROM playermatchresults
              WHERE playermatchresults.MatchKey = results.MatchKey
                AND playermatchresults.Score >= 5)) NbrOfCorrect,
            groups.DayKey DayKey
    FROM matches
    LEFT JOIN results ON matches.PrimaryKey = results.MatchKey
    INNER JOIN groups ON groups.PrimaryKey = matches.GroupKey AND groups.CompetitionKey = " . COMPETITION . "
    AND groups.IsCompleted=1
    GROUP BY groups.DayKey, matches.PrimaryKey,matches.GroupKey
    ) TMP
    GROUP BY DayKey
UNION ALL
SELECT 0,0,0,0,
    (SELECT SUM(IFNULL(playergroupresults.Score, 0))
       FROM playergroupresults
      WHERE playergroupresults.GroupKey = TMP2.GroupKey) GroupScore,
    SUM(PlayersScore),
    DayKey
FROM ( SELECT matches.PrimaryKey MatchKey,
              matches.GroupKey,
              (SELECT SUM(IFNULL(playermatchresults.Score, 0))
                 FROM playermatchresults
                WHERE playermatchresults.MatchKey = matches.PrimaryKey) PlayersScore,
              groups.DayKey DayKey
FROM matches
INNER JOIN groups ON groups.PrimaryKey = matches.GroupKey AND groups.CompetitionKey = " . COMPETITION . "
AND groups.IsCompleted=1
GROUP BY groups.DayKey,matches.PrimaryKey
) TMP2
GROUP BY TMP2.DayKey
) STATS
INNER JOIN groups ON groups.DayKey=STATS.DayKey AND groups.IsCompleted=1 AND groups.CompetitionKey = " . COMPETITION ;

if ($_groups!="All") {
  $sql .= " AND groups.PrimaryKey IN (" . $_groups . ") ";
}
$sql .= " GROUP BY DayKey ORDER BY DayKey;";

$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

$series = array();
$chartTitle = "";
switch ($_view) {
  case "Forecasts":


    $serie1 = array();
    $serie2 = array();
    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {

      $serie1[]= (int)$rowSet["MaxNbrOfPlayers"];
      $serie2[]= (int)$rowSet["NbrOfForecasts"];
    }
    $series[0]["type"] = "bar";
    $series[0]["name"]="Nombres de participants";
    $series[0]["data"] = $serie1;

    $series[1]["type"] = "bar";
    $series[1]["name"]="Nombres de pronostics";
    $series[1]["data"] = $serie2;

    $chartTitle= utf8_encode("Pronostics");

    break;
  case "Results":
    $serie = array();
    $serie1 = array();
    $serie2 = array();
    $serie3 = array();
    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {

      $serie1[]= (int)$rowSet["perfects"];
      $serie2[]= (int)$rowSet["corrects"] - (int)$rowSet["perfects"];
      $serie3[]= (int)$rowSet["NbrOfForecasts"] - (int)$rowSet["corrects"];
    }

    $series[0]["type"] = "column";
    $series[0]["name"] = "Score faux";
    $series[0]["data"] = $serie3;

    $series[1]["type"] = "column";
    $series[1]["name"]="Score correct";
    $series[1]["data"] = $serie2;

    $series[2]["type"] = "column";
    $series[2]["name"]="Score 'perfect'";
    $series[2]["data"] = $serie1;

    $chartTitle= utf8_encode("Résultats");

    break;
  case "Points":
    $serie = array();
    $serie1 = array();
    $serie2 = array();
    $serie3 = array();
    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {

      $serie1[]= (int)$rowSet["PlayersScore"] - (int)$rowSet["GroupScore"];
      $serie2[]= (int)$rowSet["GroupScore"];
      $serie3[]= round((int)$rowSet["PlayersScore"]/((int)$rowSet["NbrOfForecasts"]==0?1:(int)$rowSet["NbrOfForecasts"]),2);
    }

    $series[0]["type"] = "column";
    $series[0]["name"] = utf8_encode("Points marqués");
    $series[0]["data"] = $serie1;

    $series[1]["type"] = "column";
    $series[1]["name"] = "Points bonus";
    $series[1]["data"] = $serie2;

    $series[2]["type"] = "spline";
    $series[2]["name"] = "Moyenne/Match";
    $series[2]["yAxis"] = 1;
    $series[2]["color"] = '#55BF3B';
    $series[2]["data"] = $serie3;
    $chartTitle= "Points";
    break;
}
$arr["series"] = $series;
$arr["chartTitle"] = $chartTitle;

//$arr["perfAndQueries"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');

require_once("end.file.php");


echo json_encode($arr);
?>