<?php
require_once("begin.file.php");

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = 'Assists';
if (!$sortorder) $sortorder = 'desc';
if ($sortname=="Rank") $sortname = 'Assists';

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
    INNER JOIN `events` ON `events`.`TeamPlayerKey` = `teamplayers`.`PrimaryKey` AND events.EventType IN (4)
    INNER JOIN `results` ON `results`.`PrimaryKey` = `events`.`ResultKey` AND `results`.`LiveStatus` = 10
    INNER JOIN `teams` ON `teams`.`PrimaryKey` = `events`.`TeamKey`
    INNER JOIN `matches` ON `matches`.`PrimaryKey` = `results`.`MatchKey`
    INNER JOIN `groups` ON `groups`.`PrimaryKey` = `matches`.`GroupKey` AND `groups`.`CompetitionKey` = 2
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
        IF(`EventType`=4, matches.PrimaryKey, 0) `DaysGoal`,
        matches.PrimaryKey MatchKey
    FROM `teamplayers`
    INNER JOIN `events` ON `events`.`TeamPlayerKey` = `teamplayers`.`PrimaryKey` AND `events`.EventType IN (4)
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
HAVING SUM(`TMP`.`Assists`)>0
$sort";

$resultSet = $_databaseObject->queryPerf($sql,"Get players");
$rank=0;
$realRank=0;
$previousScore = "0";
$currentRecord =0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $realRank++;
  if (strcmp($previousScore,(int)$rowSet["Assists"])!=0) {
    $rank=$realRank;
  }
  $previousScore=(int)$rowSet["Assists"];
  if ($currentRecord>=$start && $currentRecord<$start+$rp) {
    unset($tempArray);
    $tempArray["id"]=$rowSet["TeamPlayerKey"];
    $tempArray["cell"][0]= $rank;
    $teamLogoPath = ROOT_SITE. '/images/teamFlags/'.$rowSet["TeamKey"].'.png';
    $tempArray["cell"][1]= $rowSet["TeamPlayerName"];
    $tempArray["cell"][2]= "<img class='avat' border='0' src='" . $teamLogoPath . "'>".addslashes($rowSet["TeamName"]);
    $tempArray["cell"][3]= $rowSet["Assists"];
    $tempArray["cell"][4]= $rowSet["Days"];
    $tempArray["cell"][5]= "<a href='javascript:void(0);' team-player-key='" . $rowSet["TeamPlayerKey"] . "' team-key='".$rowSet["TeamKey"]."'>Details ...</a>";


    $arr["rows"][]=$tempArray;
  }
  $currentRecord++;
}


writeJsonResponse($arr);

require_once("end.file.php");
?>