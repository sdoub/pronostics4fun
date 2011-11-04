<?php
require_once("begin.file.php");

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = 'NickName';
if (!$sortorder) $sortorder = 'asc';

$sort = "ORDER BY $sortname $sortorder";

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
(SELECT playerranking.Rank FROM playerranking WHERE playerranking.CompetitionKey=" . COMPETITION . " AND players.PrimaryKey=playerranking.PlayerKey ORDER BY RankDate desc LIMIT 0,1) Rank,
(SELECT playerranking.Rank FROM playerranking WHERE playerranking.CompetitionKey=" . COMPETITION . " AND players.PrimaryKey=playerranking.PlayerKey ORDER BY RankDate desc LIMIT 1,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score,
(SELECT COUNT(1)
   FROM playergroupranking PGR
  WHERE PGR.Rank=1
    AND PGR.PlayerKey = players.PrimaryKey
	AND PGR.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.IsCompleted=1 AND groups.CompetitionKey=" . COMPETITION . ")
	AND PGR.RankDate = (SELECT DATE(groups.EndDate) FROM groups WHERE groups.PrimaryKey = PGR.GroupKey)) FirstRank,
(SELECT COUNT(1)
   FROM playergroupranking PGR
  WHERE PGR.Rank=2
    AND PGR.PlayerKey = players.PrimaryKey
	AND PGR.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.IsCompleted=1 AND groups.CompetitionKey=" . COMPETITION . ")
	AND PGR.RankDate = (SELECT DATE(groups.EndDate) FROM groups WHERE groups.PrimaryKey = PGR.GroupKey)) SecondRank,
(SELECT COUNT(1)
   FROM playergroupranking PGR
  WHERE PGR.Rank=3
    AND PGR.PlayerKey = players.PrimaryKey
	AND PGR.GroupKey IN (SELECT PrimaryKey FROM groups WHERE IsCompleted=1 AND groups.CompetitionKey=" . COMPETITION . ")
	AND PGR.RankDate = (SELECT DATE(groups.EndDate) FROM groups WHERE groups.PrimaryKey = PGR.GroupKey)) ThirdRank,
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
FROM playersenabled players
$where
GROUP BY NickName
$sort $limit";

$resultSet = $_databaseObject->queryPerf($sql,"Get players");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  unset($tempArray);
  $tempArray["id"]=$rowSet["PlayerKey"];
  if ($rowSet["PreviousRank"]) {
    if ($rowSet["PreviousRank"]>$rowSet["Rank"]) {
      $tempArray["cell"][0]= "<span class='var up'></span>". $rowSet["Rank"];
    }
    else if ($rowSet["PreviousRank"]<$rowSet["Rank"]) {
      $tempArray["cell"][0]= "<span class='var down'></span>". $rowSet["Rank"];
    }
    else {
      $tempArray["cell"][0]= "<span class='var eq'></span>". $rowSet["Rank"];
    }
  }
  else {
    $tempArray["cell"][0]= $rowSet["Rank"];
  }
  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }
  $tempArray["cell"][1]= "<img class='avat' border='0' src='" . $avatarPath . "'>".addslashes($rowSet["NickName"]);
  $tempArray["cell"][2]= $rowSet["Score"];
  $tempArray["cell"][3]= $rowSet["FirstRank"];
  $tempArray["cell"][4]= $rowSet["SecondRank"];
  $tempArray["cell"][5]= $rowSet["ThirdRank"];
  $tempArray["cell"][6]= $rowSet["MatchPlayed"];
  $tempArray["cell"][7]= $rowSet["MatchGood"];
  $tempArray["cell"][8]= $rowSet["MatchPerfect"];
  $tempArray["cell"][9]= "<a href='javascript:void(0);' player-key='" . $rowSet["PlayerKey"] . "'>Details ...</a>";


  $arr["rows"][]=$tempArray;
}


writeJsonResponse($arr);

require_once("end.file.php");
?>