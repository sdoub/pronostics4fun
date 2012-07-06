<?php
require_once("begin.file.php");

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = 'Goals';
if (!$sortorder) $sortorder = 'desc';
if ($sortname=="Rank") $sortname = 'Goals';

$sort = "ORDER BY $sortname $sortorder, TeamPlayerName asc";

//$sort = "ORDER BY (FirstRank*100)+(SecondRank*10)+ThirdRank desc,FirstRank,SecondRank,ThirdRank,NickName ";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "LIMIT $start, $rp";

$query = $_POST['query'];
$qtype = $_POST['qtype'];

$where = "";
if ($query)
$where = " AND $qtype LIKE '%$query%' ";


$sql = "SELECT
    COUNT(DISTINCT TeamPlayerName) Total
FROM
    (SELECT
        IF(`EventType` = 2, 1, 0) `Penalty`,
            1 `GrandTotal`,
            `teamplayers`.`FullName` TeamPlayerName,
            `teamplayers`.`PrimaryKey` TeamPlayerKey,
            `teams`.`PrimaryKey` TeamKey,
            `teams`.`Name` TeamName
    FROM `teamplayers`
    INNER JOIN `events` ON `events`.`TeamPlayerKey` = `teamplayers`.`PrimaryKey` AND events.EventType IN (1,2)
    INNER JOIN `results` ON `results`.`PrimaryKey` = `events`.`ResultKey` AND `results`.`LiveStatus` = 10
    INNER JOIN `teams` ON `teams`.`PrimaryKey` = `events`.`TeamKey`
    INNER JOIN `matches` ON `matches`.`PrimaryKey` = `results`.`MatchKey`
    INNER JOIN `groups` ON `groups`.`PrimaryKey` = `matches`.`GroupKey` AND `groups`.`CompetitionKey` = " . COMPETITION . "
    WHERE
        (`teams`.`PrimaryKey` = `matches`.`TeamHomeKey` OR `teams`.`PrimaryKey` = `matches`.`TeamAwayKey`)
    order by `events`.`EventTime`) `TMP`
    WHERE `TMP`.`GrandTotal`>0
$where";

$resultSetTotal = $_databaseObject->queryPerf($sql,"Get number of scorer");
$totalRow = $_databaseObject -> fetch_assoc ($resultSetTotal);
$total = $totalRow["Total"];

$arr["page"] = $page;
$arr["total"] = $total;
$arr["rows"] = array();

$sql = "SET NAMES utf8";
$_databaseObject->query($sql);

$sql = "SELECT
    TeamPlayerName,
    TeamPlayerKey,
    TeamKey,
    TeamName,
    SUM(`TMP`.`Penalty`) `Penalty`,
    SUM(`TMP`.`Assists`) `Assists`,
    SUM(`TMP`.`GrandTotal`) `Goals`,
    COUNT(DISTINCT DaysGoal)-IF (SUM(IF(DaysGoal=0,1,0))>0,1,0) Days
FROM
    (SELECT
        IF(`EventType` = 2, 1, 0) `Penalty`,
        IF(`EventType`=1 OR `EventType`=2, 1, 0) `GrandTotal`,
        IF(`EventType`=4, 1, 0) `Assists`,
        `teamplayers`.`FullName` TeamPlayerName,
        `teamplayers`.`PrimaryKey` TeamPlayerKey,
        `teams`.`PrimaryKey` TeamKey,
        `teams`.`Name` TeamName,
        IF(`EventType`=1 OR `EventType`=2, matches.PrimaryKey, 0) `DaysGoal`,
        matches.PrimaryKey MatchKey,
        (SELECT COUNT(*) FROM events eventsScore WHERE eventsScore.ResultKey=results.PrimaryKey AND eventsScore.TeamKey=matches.TeamHomeKey AND eventsScore.EventType IN (1,2,3)) TeamHomeScore,
		(SELECT COUNT(*) FROM events eventsScore WHERE eventsScore.ResultKey=results.PrimaryKey AND eventsScore.TeamKey=matches.TeamAwayKey AND eventsScore.EventType IN (1,2,3) ) TeamAwayScore
    FROM `teamplayers`
    INNER JOIN `events` ON `events`.`TeamPlayerKey` = `teamplayers`.`PrimaryKey`
    INNER JOIN `results` ON `results`.`PrimaryKey` = `events`.`ResultKey` AND `results`.`LiveStatus` = 10
    INNER JOIN `teams` ON `teams`.`PrimaryKey` = `events`.`TeamKey`
    INNER JOIN `matches` ON `matches`.`PrimaryKey` = `results`.`MatchKey`
    INNER JOIN `groups` ON `groups`.`PrimaryKey` = `matches`.`GroupKey` AND `groups`.`CompetitionKey` = " . COMPETITION . "
    WHERE
        (`teams`.`PrimaryKey` = `matches`.`TeamHomeKey` OR `teams`.`PrimaryKey` = `matches`.`TeamAwayKey`)
    order by `events`.`EventTime`) `TMP`
WHERE 1=1
$where
GROUP BY TeamPlayerName,TeamPlayerKey,TeamKey,TeamName
HAVING SUM(`TMP`.`GrandTotal`)>0
$sort";

$resultSet = $_databaseObject->queryPerf($sql,"Get players");
$rank=0;
$realRank=0;
$previousScore = "0";
$currentRecord =0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $realRank++;
  if (strcmp($previousScore,(int)$rowSet["Goals"])!=0) {
    $rank=$realRank;
  }
  $previousScore=(int)$rowSet["Goals"];
  if ($currentRecord>=$start && $currentRecord<$start+$rp) {
    unset($tempArray);
    $tempArray["id"]=$rowSet["TeamPlayerKey"];
    $tempArray["cell"][0]= $rank;
    $teamLogoPath = ROOT_SITE. '/images/teamFlags/'.$rowSet["TeamKey"].'.png';
    $tempArray["cell"][1]= $rowSet["TeamPlayerName"];
    $tempArray["cell"][2]= "<img class='avat' border='0' src='" . $teamLogoPath . "'>".addslashes($rowSet["TeamName"]);
    $tempArray["cell"][3]= $rowSet["Goals"];
    $tempArray["cell"][4]= $rowSet["Penalty"];
    $tempArray["cell"][5]= $rowSet["Assists"];
    $tempArray["cell"][6]= $rowSet["Days"];


    $sql="SELECT
    SUM(IF (".$rowSet["TeamKey"]."!=TeamHomeKey,0,IF(HomeScore>AwayScore,3, IF (HomeScore=AwayScore,1,0)))) HomeResult,
    SUM(IF (".$rowSet["TeamKey"]."!=TeamAwayKey,0,IF(HomeScore<AwayScore,3, IF (HomeScore=AwayScore,1,0)))) AwayResult
FROM (
SELECT SUM(HomeScore) HomeScore ,SUM(AwayScore) AwayScore, MatchKey, TeamHomeKey, TeamAwayKey FROM
(
SELECT 1 HomeScore,0 AwayScore, results.MatchKey, matches.TeamHomeKey, matches.TeamAwayKey
from events
INNER JOIN results ON results.PrimaryKey=events.ResultKey
 INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
 INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=". COMPETITION ."
 WHERE events.TeamKey=matches.TeamHomeKey
 AND events.EventType IN (1,2,3)
UNION ALL
SELECT 0,1, results.MatchKey, matches.TeamHomeKey, matches.TeamAwayKey
from events
INNER JOIN results ON results.PrimaryKey=events.ResultKey
 INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
 INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=". COMPETITION ."
 WHERE events.TeamKey=matches.TeamAwayKey
 AND events.EventType IN (1,2,3)) TMP
 GROUP BY MatchKey, TeamHomeKey, TeamAwayKey) TMP2
 WHERE MatchKey IN (
 SELECT results.MatchKey
   FROM results
  INNER JOIN events ON results.PrimaryKey=events.ResultKey AND events.TeamPlayerKey=" . $rowSet["TeamPlayerKey"] . " AND events.EventType IN (1,2)
  )";

    $resultSetPts = $_databaseObject->queryPerf($sql,"Get pts gains when this team player put a goal");

    $rowSetPts = $_databaseObject -> fetch_assoc ($resultSetPts);

    $tempArray["cell"][7]= (int)$rowSetPts["HomeResult"] + (int)$rowSetPts["AwayResult"];
    $tempArray["cell"][8]= "<a href='javascript:void(0);' team-player-key='" . $rowSet["TeamPlayerKey"] . "' team-key='".$rowSet["TeamKey"]."'>Details ...</a>";


    $arr["rows"][]=$tempArray;
  }
  $currentRecord++;
}


writeJsonResponse($arr);

require_once("end.file.php");
?>