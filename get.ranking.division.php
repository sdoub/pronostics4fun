<?php
require_once("begin.file.php");

$_seasonKey = $_GET["SeasonKey"];
$_divisionKey = $_GET["DivisionKey"];

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = 'NickName';
if (!$sortorder) $sortorder = 'asc';

$sortname .= ", NickName";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "LIMIT $start, $rp";

$query = $_POST['query'];
$qtype = $_POST['qtype'];

$where = " WHERE EXISTS (SELECT 1 FROM playerdivisionranking PDR WHERE PDR.PlayerKey=players.PrimaryKey AND PDR.DivisionKey=" . $_divisionKey . " AND PDR.SeasonKey=" . $_seasonKey . ")";
if ($query)
  $where = " AND $qtype LIKE '%$query%' ";


$sql = "SELECT count(1) As Total FROM players $where";
$resultSetTotal = $_databaseObject->queryPerf($sql,"Get number of players");
$totalRow = $_databaseObject -> fetch_assoc ($resultSetTotal);
$total = $totalRow["Total"];

$arr["page"] = $page;
$arr["total"] = $total;
$arr["rows"] = array();

$sql = "SELECT
(SELECT PDR.Rank FROM playerdivisionranking PDR WHERE players.PrimaryKey=PDR.PlayerKey AND PDR.DivisionKey=$_divisionKey AND PDR.SeasonKey=$_seasonKey ORDER BY PDR.RankDate desc LIMIT 0,1) Rank,
(SELECT PDR.Rank FROM playerdivisionranking PDR WHERE players.PrimaryKey=PDR.PlayerKey AND PDR.DivisionKey=$_divisionKey AND PDR.SeasonKey=$_seasonKey ORDER BY PDR.RankDate desc LIMIT 1,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName,
players.AvatarName,
(SELECT PDR.Score FROM playerdivisionranking PDR WHERE players.PrimaryKey=PDR.PlayerKey AND PDR.DivisionKey=$_divisionKey AND PDR.SeasonKey=$_seasonKey ORDER BY PDR.RankDate desc LIMIT 0,1) Score,
(SELECT PDR.Won FROM playerdivisionranking PDR WHERE players.PrimaryKey=PDR.PlayerKey AND PDR.DivisionKey=$_divisionKey AND PDR.SeasonKey=$_seasonKey ORDER BY PDR.RankDate desc LIMIT 0,1) Won,
(SELECT PDR.Drawn FROM playerdivisionranking PDR WHERE players.PrimaryKey=PDR.PlayerKey AND PDR.DivisionKey=$_divisionKey AND PDR.SeasonKey=$_seasonKey ORDER BY PDR.RankDate desc LIMIT 0,1) Drawn,
(SELECT PDR.Lost FROM playerdivisionranking PDR WHERE players.PrimaryKey=PDR.PlayerKey AND PDR.DivisionKey=$_divisionKey AND PDR.SeasonKey=$_seasonKey ORDER BY PDR.RankDate desc LIMIT 0,1) Lost,
(SELECT PDR.PointsDifference FROM playerdivisionranking PDR WHERE players.PrimaryKey=PDR.PlayerKey AND PDR.DivisionKey=$_divisionKey AND PDR.SeasonKey=$_seasonKey ORDER BY PDR.RankDate desc LIMIT 0,1) Difference
FROM players
$where
$sort $limit";

$resultSet = $_databaseObject->queryPerf($sql,"Get division players");

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
    if ($rowSet["Rank"]==0)
      $tempArray["cell"][0]= "-";
    else
      $tempArray["cell"][0]= $rowSet["Rank"];
  }
  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }

  $tempArray["cell"][1]= "<img class='avat' border='0' src='" . $avatarPath . "'>".addslashes($rowSet["NickName"]);
  $tempArray["cell"][2]= $rowSet["Score"];
  $tempArray["cell"][3]= $rowSet["Won"];
  $tempArray["cell"][4]= $rowSet["Drawn"];
  $tempArray["cell"][5]= $rowSet["Lost"];
  $tempArray["cell"][6]= $rowSet["Difference"];

  $arr["rows"][]=$tempArray;
}

WriteJsonResponse($arr);

require_once("end.file.php");
?>