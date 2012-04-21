<?php
require_once("begin.file.php");
$playerKey = $_GET["PlayerKey"];
$viewMode = $_GET["ViewMode"];
$view = $_GET["View"];

$sqlPlayer = "SELECT PrimaryKey,NickName FROM playersenabled players
		WHERE PrimaryKey=" . $playerKey;
$resultSetPlayer = $_databaseObject->queryPerf($sqlPlayer,"Get matches linked to selected group");
$rowSetPlayer = $_databaseObject -> fetch_assoc ($resultSetPlayer);
$arr["name"] = utf8_encode(__decode($rowSetPlayer["NickName"]));

$serie = "{name: '" . $playerKey . "', data: [";

switch ($view)
{
  case "Ranking":
    switch ($viewMode) {
      case "Global":
        $sql = "
        SELECT UNIX_TIMESTAMP(RankDate) RankDate,(SELECT MAX(UNIX_TIMESTAMP(matches.ScheduleDate))+7200
        FROM matches WHERE DATE(matches.ScheduleDate)=RankDate) EndDate, Rank,
         (SELECT MAX(Rank) FROM playerranking WHERE CompetitionKey=" . COMPETITION . " AND PlayerKey=$playerKey) MaxRank,
         (SELECT MIN(Rank) FROM playerranking WHERE CompetitionKey=" . COMPETITION . " AND PlayerKey=$playerKey) MinRank
FROM playerranking WHERE CompetitionKey=" . COMPETITION . " AND PlayerKey=$playerKey order by RankDate";
        $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

        $serie = array();
        $serie1XValues = array();
        $serie1YValues = array();
        while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
          $plot = array();
          $rankDate = ((int)$rowSet['EndDate']*1000+((is_est((int)$rowSet['EndDate'])?2:1)*3600*1000));
          $rank=-$rowSet['Rank'];
          $plot["x"] = $rankDate;
          $plot["y"] = $rank;

          if ($rowSet['Rank']==$rowSet['MaxRank']) {
            $plotDataLabels = array();
            $plotDataLabels["enabled"] ='true';
            $plotDataLabels["backgroundColor"] ='rgba(252, 255, 197, 0.2)';
            $plotDataLabels["borderRadius"] = 2;
            $plotDataLabels["borderColor"] = '#AAA';
            $plotDataLabels["borderWidth"] = 0.5;
            $plotDataLabels["padding"] = 3;
            $plotDataLabels["shadow"] = 'true';
            $plotDataLabels["y"] = -20;
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.worst.".$rowSet['MaxRank'].".png)";
            //$plot["dataLabels"] = $plotDataLabels;
            $plot["marker"] = $plotMarker;
          } else if ($rowSet['Rank']==$rowSet['MinRank']) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.best.".$rowSet['MinRank'].".png)";
            $plot["marker"] = $plotMarker;
          }
          $serie[]=$plot;
          $serie1XValues[]=$rankDate;
          $serie1YValues[]=$rowSet['Rank'];
        }

        break;
      case "Group":
        $sql = "SELECT tmp.PlayerKey, tmp.GroupName, (SELECT MAX(UNIX_TIMESTAMP(matches.ScheduleDate))+7200 FROM matches WHERE DATE(matches.ScheduleDate)=MAX(tmp.RankDate)) EndDate, tmp.Rank from (
SELECT PlayerKey, groups.Code GroupName,playergroupranking.RankDate, playergroupranking.Rank
FROM playergroupranking
INNER JOIN groups ON groups.PrimaryKey=playergroupranking.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
  AND groups.IsCompleted='1' AND playergroupranking.RankDate=Date(groups.EndDate)
GROUP BY PlayerKey,RankDate,GroupName
ORDER BY RankDate,groups.DayKey) tmp
WHERE tmp.PlayerKey=". $playerKey ."
GROUP BY tmp.PlayerKey, tmp.GroupName
ORDER BY tmp.RankDate";

        $rowsSet = $_databaseObject -> queryGetFullArray ($sql, "Get players and score");
        $maxRank = 0;
        $minRank = 50;
        foreach ($rowsSet as $rowSet)
        {
          if ($rowSet['Rank']>$maxRank)
          $maxRank=$rowSet['Rank'];
          if ($rowSet['Rank']<$minRank)
          $minRank=$rowSet['Rank'];

        }

        $serie = array();
        $serie1XValues = array();
        $serie1YValues = array();
        foreach ($rowsSet as $rowSet) {
          $plot = array();
          $rankDate = ((int)$rowSet['EndDate']*1000+((is_est((int)$rowSet['EndDate'])?2:1)*3600*1000));
          $rank=-$rowSet['Rank'];
          $plot["x"] = $rankDate;
          $plot["y"] = $rank;

          if ($rowSet['Rank']==$maxRank) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.worst.".$maxRank.".png)";
            $plot["marker"] = $plotMarker;
          } else if ($rowSet['Rank']==$minRank) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.best.".$minRank.".png)";
            $plot["marker"] = $plotMarker;
          }
          $serie[]=$plot;
          $serie1XValues[]=$rankDate;
          $serie1YValues[]=$rowSet['Rank'];
        }

        break;
    }
    break;
  case "Score":
    switch ($viewMode) {
      case "Global":
        $sql = "
        SELECT SUM(Score) Score, UNIX_TIMESTAMP(ScoreDate) ScoreDate, SUM(WithBonus) WithBonus FROM (
SELECT SUM(Score) Score,DATE(ScheduleDate) ScoreDate, 0 WithBonus FROM `playermatchresults`
INNER JOIN matches ON matches.PrimaryKey=playermatchresults.MatchKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey = " . COMPETITION . "
WHERE playermatchresults.PlayerKey=$playerKey
GROUP BY DATE(matches.ScheduleDate)
UNION ALL
SELECT SUM(Score),DATE(EndDate),SUM(Score) FROM `playergroupresults`
INNER JOIN groups ON groups.PrimaryKey=playergroupresults.GroupKey AND groups.CompetitionKey = " . COMPETITION . "
WHERE playergroupresults.PlayerKey=$playerKey
GROUP BY DATE(EndDate)
) TMP
GROUP BY ScoreDate
ORDER BY ScoreDate ";

        $rowsSet = $_databaseObject -> queryGetFullArray ($sql, "Get players and score");

        $serie = array();
        $serie1XValues = array();
        $serie1YValues = array();
        $cumulScore = 0;
        foreach ($rowsSet as $rowSet) {
          $plot = array();
          $scoreDate = ((int)$rowSet['ScoreDate']*1000+((is_est((int)$rowSet['ScoreDate'])?2:1)*3600*1000));
          $cumulScore+=$rowSet['Score'];
          $plot["x"] = $scoreDate;
          $plot["y"] = $cumulScore;

          if ($rowSet['WithBonus']>62) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.bonus.100.png)";
            $plot["marker"] = $plotMarker;
          } else if ($rowSet['WithBonus']>42) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.bonus.60.png)";
            $plot["marker"] = $plotMarker;
          } else if ($rowSet['WithBonus']>22) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.bonus.40.png)";
            $plot["marker"] = $plotMarker;
          } else if ($rowSet['WithBonus']>10) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.bonus.20.png)";
            $plot["marker"] = $plotMarker;
          }
          $serie[]=$plot;
          $serie1XValues[]=$scoreDate;
          $serie1YValues[]=$cumulScore;
        }
        break;
      case "Group":
        $sql = "SELECT SUM(Score) Score, UNIX_TIMESTAMP(ScoreDate) ScoreDate, SUM(WithBonus) WithBonus FROM (
SELECT SUM(Score) Score,DATE(groups.EndDate) ScoreDate, 0 WithBonus FROM `playermatchresults`
INNER JOIN matches ON matches.PrimaryKey=playermatchresults.MatchKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey = " . COMPETITION . "
WHERE playermatchresults.PlayerKey=$playerKey
GROUP BY DATE(groups.EndDate)
UNION ALL
SELECT SUM(Score),DATE(groups.EndDate),SUM(Score) FROM `playergroupresults`
INNER JOIN groups ON groups.PrimaryKey=playergroupresults.GroupKey AND groups.CompetitionKey = " . COMPETITION . "
WHERE playergroupresults.PlayerKey=$playerKey
GROUP BY DATE(groups.EndDate)
) TMP
GROUP BY ScoreDate
ORDER BY ScoreDate ";

    $rowsSet = $_databaseObject -> queryGetFullArray ($sql, "Get players and score");
            $maxScore = 0;
        $minScore = 150;
        foreach ($rowsSet as $rowSet)
        {
          if ($rowSet['Score']>$maxScore)
          $maxScore=$rowSet['Score'];
          if ($rowSet['Score']<$minScore)
          $minScore=$rowSet['Score'];

        }
        $serie = array();
        $serie1XValues = array();
        $serie1YValues = array();
        foreach ($rowsSet as $rowSet) {
          $plot = array();
          $scoreDate = ((int)$rowSet['ScoreDate']*1000+((is_est((int)$rowSet['ScoreDate'])?2:1)*3600*1000));
          $plot["x"] = $scoreDate;
          $plot["y"] = (int)$rowSet['Score'];

          if ($rowSet['WithBonus']>62) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.bonus.100.png)";
            $plot["marker"] = $plotMarker;
          } else if ($rowSet['WithBonus']>42) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.bonus.60.png)";
            $plot["marker"] = $plotMarker;
          } else if ($rowSet['WithBonus']>22) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.bonus.40.png)";
            $plot["marker"] = $plotMarker;
          } else if ($rowSet['WithBonus']>10) {
            $plotMarker = array();
            $plotMarker["symbol"] = "url(".ROOT_SITE."/images/bullet.bonus.20.png)";
            $plot["marker"] = $plotMarker;
          }

          if ($rowSet['Score']==$maxScore) {
            $plotDataLabels = array();
            $plotDataLabelStyle = array();
            $plotDataLabels["enabled"] ='true';
            $plotDataLabels["backgroundColor"] ='rgba(25, 129, 29, 0.8)';
            $plotDataLabels["borderRadius"] = 0;
            $plotDataLabels["borderColor"] = '#FFFFFF';
            $plotDataLabels["borderWidth"] = 1;
            $plotDataLabels["padding"] = 5;
            $plotDataLabels["shadow"] = 'true';
            $plotDataLabels["y"] = -15;
            $plotDataLabels["x"] = 15;
            $plotDataLabelStyle["color"] = "#FFFFFF";
            $plotDataLabelStyle["fontSize"] = "8px";
            $plotDataLabelStyle["fontWeight"] = "bold";
            $plotDataLabels["style"] =$plotDataLabelStyle;
            $plot["dataLabels"] = $plotDataLabels;
          }
          if ($rowSet['Score']==$minScore) {
            $plotDataLabels = array();
            $plotDataLabelStyle = array();
            $plotDataLabels["enabled"] ='true';
            $plotDataLabels["backgroundColor"] ='rgba(200, 47, 36, 0.8)';
            $plotDataLabels["borderRadius"] = 0;
            $plotDataLabels["borderColor"] = '#FFFFFF';
            $plotDataLabels["borderWidth"] = 1;
            $plotDataLabels["padding"] = 5;
            $plotDataLabels["shadow"] = 'true';
            $plotDataLabels["y"] = 20;
            $plotDataLabelStyle["color"] = "#FFFFFF";
            $plotDataLabelStyle["fontSize"] = "8px";
            $plotDataLabelStyle["fontWeight"] = "bold";
            $plotDataLabels["style"] =$plotDataLabelStyle;
            $plot["dataLabels"] = $plotDataLabels;
          }

          $serie[]=$plot;
          $serie1XValues[]=$scoreDate;
          $serie1YValues[]=$rowSet['Score'];
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
$arr["step"] = "false";
$arr["perfAndQueries"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
require_once("end.file.php");


echo json_encode($arr);
?>