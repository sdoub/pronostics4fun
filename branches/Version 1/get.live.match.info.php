<?php
require_once("begin.file.php");

$_matchKey = $_POST["matchKey"];

$sql = "SELECT
(SELECT playerranking.Rank FROM playerranking WHERE players.PrimaryKey=playerranking.PlayerKey ORDER BY RankDate desc LIMIT 0,1) GlobalRank,
(SELECT playerranking.Rank FROM playerranking WHERE players.PrimaryKey=playerranking.PlayerKey ORDER BY RankDate desc LIMIT 1,1) GlobalPreviousRank,
(SELECT playergroupranking.Rank FROM playergroupranking WHERE players.PrimaryKey=playergroupranking .PlayerKey AND playergroupranking.GroupKey =(SELECT matches.GroupKey FROM matches WHERE matches.PrimaryKey=$_matchKey) ORDER BY RankDate desc LIMIT 0,1) GroupRank,
(SELECT playergroupranking.Rank FROM playergroupranking WHERE players.PrimaryKey=playergroupranking .PlayerKey AND playergroupranking.GroupKey =(SELECT matches.GroupKey FROM matches WHERE matches.PrimaryKey=$_matchKey) ORDER BY RankDate desc LIMIT 1,1) GroupPreviousRank,
players.PrimaryKey PlayerKey,
players.NickName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
AND playermatchresults.MatchKey =$_matchKey
AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT matches.GroupKey FROM matches WHERE matches.PrimaryKey=$_matchKey)
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score,
IF (results.PrimaryKey,CONCAT_WS('-',forecasts.TeamHomeScore,forecasts.TeamAwayScore),'') Forecasts,
results.LiveStatus,
results.ActualTime,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) ) TeamHomeScore,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) ) TeamAwayScore
FROM playersenabled players
LEFT JOIN forecasts ON forecasts.PlayerKey=players.PrimaryKey
      AND forecasts.MatchKey=$_matchKey
LEFT JOIN results ON results.MatchKey =$_matchKey
LEFT JOIN matches ON matches.PrimaryKey =$_matchKey
WHERE players.PrimaryKey = " . $_authorisation->getConnectedUserKey() . "
GROUP BY NickName";

$resultSet = $_databaseObject->queryPerf($sql,"Get players");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$arr["MatchKey"]=$_matchKey;
$arr["TeamHomeScore"]=$rowSet["TeamHomeScore"];
$arr["TeamAwayScore"]= $rowSet["TeamAwayScore"];
$arr["LiveStatus"]= getStatus($rowSet["LiveStatus"]);
$arr["Status"]= $rowSet["LiveStatus"];
$arr["ActualTime"]= $rowSet["ActualTime"];
$arr["PlayerScore"]= $rowSet["Score"];
$arr["GroupRank"]= $rowSet["GroupRank"];
$arr["GlobalRank"]= $rowSet["GlobalRank"];
$arr["Forecasts"]= $rowSet["Forecasts"];
unset($rowSet);
unset($resultSet);

writeJsonResponse($arr);

require_once("end.file.php");
?>