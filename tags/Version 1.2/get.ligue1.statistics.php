<?php
require_once("begin.file.php");

if (isset($_GET["Group"])) {
  $_groups = $_GET["Group"];
}
else {
  $_groups = "All";
}

if (isset($_GET["Team"])) {
  $_teams = $_GET["Team"];
}
else {
  $_teams = "All";
}

if (isset($_GET["View"])) {
  $_view = $_GET["View"];
}
else {
  $_view = "Goals";
}

switch ($_view) {

  case "Goals":

    $sql = "select
SUM(TMP.FirstHalf15) FirstHalf15,
SUM(TMP.FirstHalf30) FirstHalf30,
SUM(TMP.FirstHalf45) FirstHalf45,
SUM(TMP.FirstHalfET) FirstHalfET,
SUM(TMP.SecondHalf15) SecondHalf15,
SUM(TMP.SecondHalf30) SecondHalf30,
SUM(TMP.SecondHalf45) SecondHalf45,
SUM(TMP.SecondHalfET) SecondHalfET,
SUM(TMP.Penalty) Penalty,
SUM(TMP.GrandTotal) GrandTotal
FROM (
select
IF(Half=1 AND EventTime BETWEEN 0 AND 15,1, 0) FirstHalf15,
IF(Half=1 AND EventTime BETWEEN 16 AND 30,1, 0) FirstHalf30,
IF(Half=1 AND EventTime BETWEEN 31 AND 45,1, 0) FirstHalf45,
IF(Half=1 AND EventTime > 45,1, 0) FirstHalfET,
IF(Half=3 AND EventTime BETWEEN 45 AND 60,1, 0) SecondHalf15,
IF(Half=3 AND EventTime BETWEEN 61 AND 75,1, 0) SecondHalf30,
IF(Half=3 AND EventTime BETWEEN 76 AND 90,1, 0) SecondHalf45,
IF(Half=3 AND EventTime >90,1, 0) SecondHalfET,
IF(EventType=2,1,0) Penalty,
1 GrandTotal
 FROM events INNER JOIN results ON results.PrimaryKey=events.ResultKey AND results.LiveStatus=10
INNER JOIN teams ON teams.PrimaryKey=events.TeamKey";
    if ($_teams!="All"){
      $sql .= " AND teams.PrimaryKey IN (" . $_teams . ")";
    }

    $sql .= " INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey ";

    if ($_groups!="All"){
      $sql .= " AND groups.PrimaryKey IN (" . $_groups . ")";
    }
    $sql .= " AND groups.CompetitionKey=" . COMPETITION . "
WHERE (teams.PrimaryKey=matches.TeamHomeKey OR teams.PrimaryKey=matches.TeamAwayKey)
AND events.EventType IN (1,2,3)
order by events.EventTime
) TMP ";

    $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

    $serie = array();

    $rowSet = $_databaseObject -> fetch_assoc ($resultSet);

    $serie[]= ((int)$rowSet["FirstHalf15"]==0)?null:(int)$rowSet["FirstHalf15"];
    $serie[]= ((int)$rowSet["FirstHalf30"]==0)?null:(int)$rowSet["FirstHalf30"];
    $serie[]= ((int)$rowSet["FirstHalf45"]==0)?null:(int)$rowSet["FirstHalf45"];
    $serie[]= ((int)$rowSet["FirstHalfET"]==0)?null:(int)$rowSet["FirstHalfET"];
    $serie[]= ((int)$rowSet["SecondHalf15"]==0)?null:(int)$rowSet["SecondHalf15"];
    $serie[]= ((int)$rowSet["SecondHalf30"]==0)?null:(int)$rowSet["SecondHalf30"];
    $serie[]= ((int)$rowSet["SecondHalf45"]==0)?null:(int)$rowSet["SecondHalf45"];
    $serie[]= ((int)$rowSet["SecondHalfET"]==0)?null:(int)$rowSet["SecondHalfET"];

        $series = array();
    $series[0]["type"] = "scatter";
    $series[0]["name"] = "Buts";
    $series[0]["marker"]["symbol"] = "url(" .ROOT_SITE . "/images/goal.png)";
    $series[0]["color"] = "#FFFFFF";
    $series[0]["data"] = $serie;

    $arr["total1"] = (int)$rowSet["FirstHalf15"] + (int)$rowSet["FirstHalf30"] + (int)$rowSet["FirstHalf45"] + (int)$rowSet["FirstHalfET"];
    $arr["total2"] = (int)$rowSet["SecondHalf15"] + (int)$rowSet["SecondHalf30"] + (int)$rowSet["SecondHalf45"] + (int)$rowSet["SecondHalfET"];
    $arr["penalty"] =  (int)$rowSet["Penalty"];
    $arr["total"] =  (int)$rowSet["GrandTotal"];

    $sql = "SELECT COUNT(1) Total
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=".COMPETITION;
    if ($_groups!="All"){
      $sql .= " AND groups.PrimaryKey IN (" . $_groups . ")";
    }
$sql .= " INNER JOIN results ON results.MatchKey = matches.PrimaryKey AND results.LiveStatus=10 WHERE 1=1 ";
    if ($_teams!="All"){
      $sql .= " AND (matches.TeamHomeKey IN (" . $_teams . ")";
      $sql .= " OR matches.TeamAwayKey IN (" . $_teams . "))";
    }

    $resultSet = $_databaseObject->queryPerf($sql,"Get number of matches played");

    $rowSet = $_databaseObject -> fetch_assoc ($resultSet);

    $arr["series"] = $series;
    $arr["chartTitle"] = utf8_encode("Répartions des Scores");
    $arr["chartSubTitle"] = null;
    $arr["totalMatches"] =  (int)$rowSet["Total"];
    break;
  case "ScoreLigue1":
    $sql = "SELECT CONCAT(HomeScore,'-',AwayScore) Score, count(1) Total FROM (
 select
 (SELECT COUNT(1) FROM events WHERE events.EventType IN (1,2,3) AND events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey) HomeScore,
 (SELECT COUNT(1) FROM events WHERE events.EventType IN (1,2,3) AND events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey) AwayScore
 FROM matches
 INNER JOIN results ON results.MatchKey=matches.PrimaryKey";
    if ($_teams!="All"){
      $sql .= " AND (matches.TeamHomeKey IN (" . $_teams . ") OR matches.TeamAwayKey IN (" . $_teams . "))";
    }

 $sql  .= " INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION ;

    if ($_groups!="All"){
      $sql .= " AND groups.PrimaryKey IN (" . $_groups . ")";
    }
$sql .= "  ) TMP
 GROUP BY CONCAT(HomeScore,'-',AwayScore)
 ORDER BY 2 desc, 1";
//    if ($_teams!="All"){
//      $sql .= " AND teams.PrimaryKey IN (" . $_teams . ")";
//    }
//
//    $sql .= " INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
//INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey ";
//
//    if ($_groups!="All"){
//      $sql .= " AND groups.PrimaryKey IN (" . $_groups . ")";
//    }
//    $sql .= " AND groups.CompetitionKey=" . COMPETITION . "
//WHERE (teams.PrimaryKey=matches.TeamHomeKey OR teams.PrimaryKey=matches.TeamAwayKey)
//AND events.EventType IN (1,2,3)
//order by events.EventTime
//) TMP ";

    $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

    $serie = array();
    $total = 0;
    $distinctTotal = 0;
    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
      $values = array();
      $values[]= $rowSet["Score"];
      $values[]= (int)$rowSet["Total"];
      $total += (int)$rowSet["Total"];
      $distinctTotal +=1;
      $serie[]=$values;
    }
    $serieWithPercentage = array();
    while (list ($key, $value) = each ($serie)) {
      $valuesWithPercentage = array();
      $valuesWithPercentage[]=$value[0];
      $valuesWithPercentage[]=round($value[1] / $total * 100 ,2);

      $serieWithPercentage[]=$valuesWithPercentage;

    }
    $series = array();
    $series[0]["type"] = "pie";
    $series[0]["name"] = "Score";
    $series[0]["data"] = $serieWithPercentage;

    $arr["series"] = $series;
    $arr["chartTitle"] = utf8_encode("Répartions des Scores");
    $arr["chartSubTitle"] = utf8_encode("Depuis le début de la saison il y a eu $distinctTotal scores différents");
    break;
}


/* SELECT CONCAT(HomeScore,'-',AwayScore), count(1) FROM (
 select
 (SELECT COUNT(1) FROM events WHERE events.EventType IN (1,2,3) AND events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey) HomeScore,
 (SELECT COUNT(1) FROM events WHERE events.EventType IN (1,2,3) AND events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey) AwayScore
 FROM matches
 INNER JOIN results ON results.MatchKey=matches.PrimaryKey
 INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=2
 ) TMP
 GROUP BY CONCAT(HomeScore,'-',AwayScore)
 ORDER BY 2;
 */
//$arr["perfAndQueries"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');

require_once("end.file.php");


echo json_encode($arr);
?>