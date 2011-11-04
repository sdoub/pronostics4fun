<?php
require_once("begin.file.php");
$playerKey = $_GET["PlayerKey"];
$viewMode = $_GET["ViewMode"];
$view = $_GET["View"];

$sqlPlayer = "SELECT PrimaryKey,NickName FROM playersenabled players
		WHERE PrimaryKey=" . $playerKey . "
ORDER BY NickName
	LIMIT 0,15";
$resultSetPlayer = $_databaseObject->queryPerf($sqlPlayer,"Get matches linked to selected group");
$rowSetPlayer = $_databaseObject -> fetch_assoc ($resultSetPlayer);
$arr["name"] = $rowSetPlayer["NickName"];

$serie = "{name: '" . $playerKey . "', data: [";

switch ($view)
{
  case "Ranking":
    switch ($viewMode) {
      case "Global":
        $sql = "SELECT tmp.PlayerKey, tmp.GroupName, MAX(tmp.RankDate), tmp.Rank from (
SELECT PlayerKey,
(SELECT GROUP_CONCAT(groups.Code) FROM groups WHERE DATE(groups.EndDate)=playerranking.RankDate) GroupName, RankDate,Rank FROM playerranking
WHERE CompetitionKey=" . COMPETITION . "
AND EXISTS (SELECT 1 FROM groups WHERE DATE(groups.EndDate)=playerranking.RankDate AND IsCompleted='1')
GROUP BY PlayerKey,RankDate
ORDER BY RankDate) tmp
WHERE tmp.PlayerKey=". $playerKey ."
GROUP BY tmp.PlayerKey, tmp.GroupName
ORDER BY tmp.RankDate, tmp.Rank";
        $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

        $serie = array();
        while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
        {
          $serie[]= -$rowSet["Rank"];
        }

        break;
      case "Group":
        $sql = "SELECT tmp.PlayerKey, tmp.GroupName, MAX(tmp.RankDate), tmp.Rank from (
SELECT PlayerKey, groups.Code GroupName,playergroupranking.RankDate, playergroupranking.Rank
FROM playergroupranking
INNER JOIN groups ON groups.PrimaryKey=playergroupranking.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
  AND groups.IsCompleted='1' AND playergroupranking.RankDate=Date(groups.EndDate)
GROUP BY PlayerKey,RankDate,GroupName
ORDER BY RankDate,groups.DayKey) tmp
WHERE tmp.PlayerKey=". $playerKey ."
GROUP BY tmp.PlayerKey, tmp.GroupName
ORDER BY tmp.RankDate";

        $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

        $serie = array();
        while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
        {
          $serie[]= -$rowSet["Rank"];
        }
        break;
      case "MinMaxAvg":
        $sql = "SELECT MIN(TMP2.Rank) MinRank, MAX(TMP2.Rank) MaxRank, AVG(TMP2.Rank) AvgRank FROM (SELECT tmp.PlayerKey, tmp.GroupName, MAX(tmp.RankDate), tmp.Rank from (
SELECT PlayerKey, groups.Code GroupName,playergroupranking.RankDate, playergroupranking.Rank
FROM playergroupranking
INNER JOIN groups ON groups.PrimaryKey=playergroupranking.GroupKey AND groups.CompetitionKey=" . COMPETITION . " AND groups.IsCompleted='1' AND DATE(groups.EndDate)=playergroupranking.RankDate
WHERE playergroupranking.PlayerKey=". $playerKey ."
GROUP BY PlayerKey,RankDate
ORDER BY RankDate) tmp
GROUP BY tmp.PlayerKey, tmp.GroupName
ORDER BY tmp.RankDate, tmp.Rank) TMP2 ";

        $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

        $serie = array();

        $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
        $pointMax = array();
        $pointMax[]=(int)$_GET["Position"];
        $pointMax[]=-$rowSet["MaxRank"];
        $pointMin = array();
        $pointMin[]=(int)$_GET["Position"];
        $pointMin[]=-$rowSet["MinRank"];
        $pointAvg = array();
        $pointAvg[]=(int)$_GET["Position"];
        $pointAvg[]=round(-$rowSet["AvgRank"],2);
        $serie[]= $pointMin;
        $serie[]= $pointAvg;
        $serie[]= $pointMax;
        $arr["type"] = "scatter";
        break;
    }
    break;
  case "Score":
    switch ($viewMode) {
      case "Global":
        $sql = "SELECT groups.Code,
(SELECT IFNULL(SUM(playermatchresults.Score),0)
     FROM playermatchresults
    WHERE playermatchresults.PlayerKey=$playerKey
      AND EXISTS (SELECT 1 FROM matches WHERE playermatchresults.MatchKey=matches.PrimaryKey AND matches.GroupKey=groups.PrimaryKey)) +
(SELECT IFNULL(SUM(playergroupresults.Score),0) FROM playergroupresults
    WHERE playergroupresults.PlayerKey=$playerKey
      AND playergroupresults.GroupKey=groups.PrimaryKey)
     Score
FROM groups
WHERE groups.CompetitionKey=" . COMPETITION . "
AND groups.IsCompleted=1
ORDER BY groups.PrimaryKey ";
        $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

        $serie = array();
        $cumulScore = 0;
        while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
        {
          $cumulScore += (int)$rowSet["Score"];
          $serie[]= $cumulScore;
        }
        break;
      case "Group":
        $sql = "SELECT groups.Code,
(SELECT IFNULL(SUM(playermatchresults.Score),0)
     FROM playermatchresults
    WHERE playermatchresults.PlayerKey=$playerKey
      AND EXISTS (SELECT 1 FROM matches WHERE playermatchresults.MatchKey=matches.PrimaryKey AND matches.GroupKey=groups.PrimaryKey)) +
(SELECT IFNULL(SUM(playergroupresults.Score),0) FROM playergroupresults
    WHERE playergroupresults.PlayerKey=$playerKey
      AND playergroupresults.GroupKey=groups.PrimaryKey)
     Score
FROM groups
WHERE groups.CompetitionKey=" . COMPETITION . "
AND groups.IsCompleted=1
ORDER BY groups.PrimaryKey ";

        $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

        $serie = array();

        while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
        {
          $serie[]= (int)$rowSet["Score"];
        }
        break;
      case "MinMaxAvg":
        $sql = "SELECT MIN(TMP.Score) MinScore, MAX(TMP.Score) MaxScore, AVG(TMP.Score) AvgScore FROM (SELECT groups.Code,
(SELECT IFNULL(SUM(playermatchresults.Score),0)
     FROM playermatchresults
    WHERE playermatchresults.PlayerKey=$playerKey
      AND EXISTS (SELECT 1 FROM matches WHERE playermatchresults.MatchKey=matches.PrimaryKey AND matches.GroupKey=groups.PrimaryKey)) +
(SELECT IFNULL(SUM(playergroupresults.Score),0) FROM playergroupresults
    WHERE playergroupresults.PlayerKey=$playerKey
      AND playergroupresults.GroupKey=groups.PrimaryKey)
     Score
FROM groups
WHERE groups.CompetitionKey=" . COMPETITION . "
AND groups.IsCompleted=1
ORDER BY groups.PrimaryKey) TMP ";

        $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

        $serie = array();

        $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
        $pointMax = array();
        $pointMax[]=(int)$_GET["Position"];
        $pointMax[]=(int)$rowSet["MaxScore"];
        $pointMin = array();
        $pointMin[]=(int)$_GET["Position"];
        $pointMin[]=(int)$rowSet["MinScore"];
        $pointAvg = array();
        $pointAvg[]=(int)$_GET["Position"];
        $pointAvg[]=round(floatval($rowSet["AvgScore"]),2);
        $serie[]= $pointMin;
        $serie[]= $pointAvg;
        $serie[]= $pointMax;
        $arr["type"] = "scatter";
        break;
    }
    break;
}

$arr["data"] = $serie;
$arr["perfAndQueries"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
require_once("end.file.php");


echo json_encode($arr);
?>