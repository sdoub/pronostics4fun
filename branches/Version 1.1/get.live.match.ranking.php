<?php
require_once("begin.file.php");


$_matchKey = $_GET["MatchKey"];
$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = 'NickName';
if (!$sortorder) $sortorder = 'asc';


$sort = "ORDER BY $sortname $sortorder";
if ($sortname=="Score") {
  $sort .= ", NickName asc";
}


if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "LIMIT $start, $rp";

$query = $_POST['query'];
$qtype = $_POST['qtype'];

$where = "";
if ($query)
  $where = " WHERE $qtype LIKE '%$query%' ";


$sql = "SELECT count(1) As Total FROM playersenabled players $where";
$resultSetTotal = $_databaseObject->queryPerf($sql,"Get number of players");
$totalRow = $_databaseObject -> fetch_assoc ($resultSetTotal);
$total = $totalRow["Total"];

$arr["page"] = $page;
$arr["total"] = $total;
$arr["rows"] = array();

$sql = "SELECT
(SELECT playerranking.Rank FROM playerranking WHERE players.PrimaryKey=playerranking.PlayerKey AND playerranking.CompetitionKey=" . COMPETITION . " ORDER BY RankDate desc LIMIT 0,1) GlobalRank,
(SELECT playerranking.Rank FROM playerranking WHERE players.PrimaryKey=playerranking.PlayerKey AND playerranking.CompetitionKey=" . COMPETITION . " ORDER BY RankDate desc LIMIT 1,1) GlobalPreviousRank,
(SELECT playergroupranking.Rank FROM playergroupranking WHERE players.PrimaryKey=playergroupranking .PlayerKey AND playergroupranking.GroupKey =(SELECT matches.GroupKey FROM matches WHERE matches.PrimaryKey=$_matchKey) ORDER BY RankDate desc LIMIT 0,1) GroupRank,
(SELECT playergroupranking.Rank FROM playergroupranking WHERE players.PrimaryKey=playergroupranking .PlayerKey AND playergroupranking.GroupKey =(SELECT matches.GroupKey FROM matches WHERE matches.PrimaryKey=$_matchKey) ORDER BY RankDate desc LIMIT 1,1) GroupPreviousRank,
players.PrimaryKey PlayerKey,
players.NickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
AND playermatchresults.MatchKey =$_matchKey
AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT matches.GroupKey FROM matches WHERE matches.PrimaryKey=$_matchKey)
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) GlobalScore,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey IN (SELECT matches.GroupKey FROM matches WHERE matches.PrimaryKey=$_matchKey))
AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT matches.GroupKey FROM matches WHERE matches.PrimaryKey=$_matchKey)
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) GroupScore,
IF (results.PrimaryKey,CONCAT_WS('-',forecasts.TeamHomeScore,forecasts.TeamAwayScore),'x-x') Forecasts,
IFNULL(results.LiveStatus,0) LiveStatus,
IFNULL(results.ActualTime,0) ActualTime
FROM playersenabled players
LEFT JOIN forecasts ON forecasts.PlayerKey=players.PrimaryKey
      AND forecasts.MatchKey=$_matchKey
LEFT JOIN results ON results.MatchKey =$_matchKey
$where
GROUP BY players.NickName
$sort $limit";

$resultSet = $_databaseObject->queryPerf($sql,"Get players");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  unset($tempArray);
  $tempArray["id"]=$rowSet["PlayerKey"];
  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }

  $tempArray["cell"][0]= "<img class='avat' border='0' src='" . $avatarPath . "'>".addslashes($rowSet["NickName"]);
  if ($rowSet["LiveStatus"]>0 && $rowSet["ActualTime"]>0) {
    $tempArray["cell"][1]= $rowSet["Forecasts"];
  }
  else {
    $tempArray["cell"][1]= $rowSet["Forecasts"] ? "x-x" : "";
  }
  $tempArray["cell"][2]= $rowSet["Score"];
  $tempArray["cell"][3]= $rowSet["GroupRank"];
  $tempArray["cell"][4]= $rowSet["GlobalRank"];

  $arr["rows"][]=$tempArray;
}

writeJsonResponse($arr);

require_once("end.file.php");
?>