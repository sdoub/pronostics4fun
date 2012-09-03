<?php
require_once("begin.file.php");
$_groupKey=$_GET["GroupKey"];

if ($_competitionType==3) {
$sql = "SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
    ),0)
    + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")
    ),0)
    + (SELECT CASE COUNT(*) WHEN 6 THEN 40 WHEN 5 THEN 20 WHEN 4 THEN IF (groups.Code='1/4',20,0) WHEN 3 THEN IF (groups.Code='1/4',10,0) ELSE 0 END
		 FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND groups.IsCompleted = '0'
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)
      ) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName";
} else {
  $sql = "SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
    ),0)
    + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")
    ),0)
    + (SELECT CASE COUNT(*) WHEN 10 THEN 100 WHEN 9 THEN 60 WHEN 8 THEN 40 WHEN 7 THEN 20 ELSE 0 END
		 FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND groups.IsCompleted = '0'
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)
     + (SELECT CASE COUNT(*) WHEN 0 THEN 0 ELSE 0 END FROM votes
        INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND votes.playerKey=players.PrimaryKey)
      ) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName";

}
$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
$rank=0;
$previousRank=0;
$realRank=0;
$previousScore=0;
$arr["playersGlobalRanking"] = array();
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $realRank++;
  if ($previousScore>$rowSet["Score"]||$previousScore==0) {
    $rank=$realRank;
  }

  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }
  $rankToBeDisplayed = $rank.'.';
  if ($previousRank==$rank) {
    $rankToBeDisplayed="-";
  }

  $previousScore=$rowSet["Score"];
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

  $player["PlayerKey"]=$rowSet["PlayerKey"];
  $player["Rank"]=$rank;
  $player["RankToBeDisplayed"]=$rankToBeDisplayed;
  $player["Variation"]=$variation;
  $player["Score"]=$rowSet["Score"];
  $arr["playersGlobalRanking"][]=$player;

  $previousRank=$rank;

}
if ($_competitionType==3) {
$sql = "SELECT
(SELECT PRK.Rank FROM playergroupranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey)
      ),0) +
      (SELECT
CASE COUNT(*)
WHEN 6 THEN 40
WHEN 5 THEN 20
WHEN 4 THEN IF (groups.Code='1/4',20,0)
WHEN 3 THEN IF (groups.Code='1/4',10,0)
ELSE 0 END
FROM playermatchresults
INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
WHERE groups.PrimaryKey=$_groupKey
AND playermatchresults.Score>=5
AND playermatchresults.playerKey=players.PrimaryKey
)) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName";
} else {
  $sql = "SELECT
(SELECT PRK.Rank FROM playergroupranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey)
      ),0) +
      (SELECT
CASE COUNT(*)
WHEN 10 THEN 100
WHEN 9 THEN 60
WHEN 8 THEN 40
WHEN 7 THEN 20
ELSE 0 END
FROM playermatchresults
INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
WHERE groups.PrimaryKey=$_groupKey
AND playermatchresults.Score>=5
AND playermatchresults.playerKey=players.PrimaryKey
)
+ (SELECT CASE COUNT(*) WHEN 0 THEN 0 ELSE 0 END FROM votes
        INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND votes.playerKey=players.PrimaryKey)) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName";

}
$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
$rank=0;
$previousRank=0;
$realRank=0;
$previousScore=0;
$arr["playersGroupRanking"] = array();
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $realRank++;
  if ($previousScore>$rowSet["Score"]||$previousScore==0) {
    $rank=$realRank;
  }

  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }
  $rankToBeDisplayed = $rank.'.';
  if ($previousRank==$rank) {
    $rankToBeDisplayed="-";
  }
  $previousScore=$rowSet["Score"];
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


  $player["PlayerKey"]=$rowSet["PlayerKey"];
  $player["Rank"]=$rank;
  $player["RankToBeDisplayed"]=$rankToBeDisplayed;
  $player["Variation"]=$variation;
  $player["Score"]=$rowSet["Score"];
  $arr["playersGroupRanking"][]=$player;


  $previousRank=$rank;

}

writeJsonResponse($arr);
require_once("end.file.php");

?>