<?php
require_once("begin.file.php");


$sql = "SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName
LIMIT 0,5";

$resultSet = $_databaseObject->queryPerf($sql,"Get players");
$rank=0;
$realRank=0;
$previousScore=0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $realRank++;
  if ($previousScore>$rowSet["Score"]||$previousScore==0) {
    $rank=$realRank;
  }
  $previousScore=$rowSet["Score"];
  unset($tempArray);
  if ($rowSet["PreviousRank"]) {
    if ($rowSet["PreviousRank"]>$rank) {
      $variation = "up";
    }
    else if ($rowSet["PreviousRank"]<$rank) {
      $variation = "down";
    }
    else {
      $variation = "eq";
    }
  }
  else
  {
    $variation = "eq";
  }

  $tempArray["Rank"]=$rank;
  $tempArray["PreviousRank"]=$rowSet["PreviousRank"];
  $tempArray["Variation"]=$variation;

  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }

  $tempArray["AvatPath"]=$avatarPath;
  $tempArray["NickName"]= $rowSet["NickName"];
  $tempArray["Score"]= $rowSet["Score"];

  $arr["rows"][]=$tempArray;
}

writeJsonResponse($arr);

require_once("end.file.php");
?>