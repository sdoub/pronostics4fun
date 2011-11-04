<?php
require_once("begin.file.php");

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = 'NickName';
if (!$sortorder) $sortorder = 'asc';

$sort = "ORDER BY (FirstRank*100)+(SecondRank*10)+ThirdRank desc,FirstRank,SecondRank,ThirdRank,NickName ";

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
players.PrimaryKey PlayerKey,
players.NickName,
players.AvatarName,
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
	AND PGR.RankDate = (SELECT DATE(groups.EndDate) FROM groups WHERE groups.PrimaryKey = PGR.GroupKey)) ThirdRank
FROM playersenabled players
$where
GROUP BY NickName
$sort $limit";

$resultSet = $_databaseObject->queryPerf($sql,"Get players");
$rank=0;
$realRank=0;
$previousScore = "0";

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $realRank++;
  if (strcmp($previousScore,((int)$rowSet["FirstRank"]*100) + ((int)$rowSet["SecondRank"]*10) + (int)$rowSet["ThirdRank"])!=0) {
    $rank=$realRank;
  }
  $previousScore=((int)$rowSet["FirstRank"]*100) + ((int)$rowSet["SecondRank"]*10) + (int)$rowSet["ThirdRank"];

  unset($tempArray);
  $tempArray["id"]=$rowSet["PlayerKey"];
  $tempArray["cell"][0]= $rank;

  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }
  $tempArray["cell"][1]= "<img class='avat' border='0' src='" . $avatarPath . "'>".addslashes($rowSet["NickName"]);
  $tempArray["cell"][2]= $rowSet["FirstRank"];
  $tempArray["cell"][3]= $rowSet["SecondRank"];
  $tempArray["cell"][4]= $rowSet["ThirdRank"];
  $tempArray["cell"][5]= (int)$rowSet["FirstRank"] + (int)$rowSet["SecondRank"] + (int)$rowSet["ThirdRank"];

  $arr["rows"][]=$tempArray;
}


writeJsonResponse($arr);

require_once("end.file.php");
?>