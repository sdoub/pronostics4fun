<?php
require_once("begin.file.php");

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = 'DayKey';
if (!$sortorder) $sortorder = 'DESC';

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "LIMIT $start, $rp";

$query = $_POST['query'];
$qtype = $_POST['qtype'];

$where = " WHERE 1=1 ";
if ($_GET['SpecialFilter']) {
  $keys = explode(",", $_GET['SpecialFilter']);
  $seasonKeys = array();
  $divisionKeys = array();
  $playerKeys = array();
  foreach ($keys as $key) {
    $itemKey = substr($key, 0,3);
    $itemValue = substr($key, 3);
    switch ($itemKey) {
      case 200:
        $seasonKeys[] = $itemValue;
        break;
      case 300:
        $divisionKeys[] = $itemValue;
        break;
      case 100:
        $playerKeys[] = $itemValue;
        break;
      default:
        break;
    };
  }

  if (count($seasonKeys)>0) {
    $where .= " AND PDM.SeasonKey IN (" . implode(",", $seasonKeys) . ")";
  }
  if (count($divisionKeys)>0) {
    $where .= " AND PDM.DivisionKey IN (" . implode(",", $divisionKeys) . ")";
  }
  if (count($playerKeys)>0) {
    $playerList = implode(",", $playerKeys);
    $where .= " AND (PDM.PlayerHomeKey IN (" . $playerList . ") OR PDM.PlayerAwayKey IN (" . $playerList . "))";
  }

} else {


  if ($query)
    $where .= " AND $qtype LIKE '%$query%' ";
  else
    $where .= " AND (PDM.PlayerHomeKey=".$_authorisation->getConnectedUserKey()." OR PDM.PlayerAwayKey=".$_authorisation->getConnectedUserKey().")";
}
$sql = "SELECT count(1) As Total FROM playerdivisionmatches PDM $where";
$resultSetTotal = $_databaseObject->queryPerf($sql,"Get number of players");
$totalRow = $_databaseObject -> fetch_assoc ($resultSetTotal);
$total = $totalRow["Total"];

$arr["page"] = $page;
$arr["total"] = $total;
$arr["rows"] = array();

$sql = "
SELECT PDM.PrimaryKey, groups.PrimaryKey GroupKey, groups.DayKey, 
seasons.Code SeasonCode, divisions.Code DivisionCode,
HomePlayer.PrimaryKey HomePlayerKey,
HomePlayer.NickName HomeNickName,
HomePlayer.AvatarName HomeAvatarName,
AwayPlayer.PrimaryKey AwayPlayerKey,
AwayPlayer.NickName AwayNickName,
AwayPlayer.AvatarName AwayAvatarName,
PDM.HomeScore,PDM.AwayScore
FROM playerdivisionmatches PDM
INNER JOIN players HomePlayer ON HomePlayer.PrimaryKey=PDM.PlayerHomeKey
INNER JOIN players AwayPlayer ON AwayPlayer.PrimaryKey=PDM.PlayerAwayKey
INNER JOIN groups ON groups.PrimaryKey=PDM.GroupKey
INNER JOIN seasons ON seasons.PrimaryKey=PDM.SeasonKey
INNER JOIN divisions ON divisions.PrimaryKey=PDM.DivisionKey
$where
$sort $limit";

$resultSet = $_databaseObject->queryPerf($sql,"Get player matches");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  unset($tempArray);
  $tempArray["id"]=$rowSet["PrimaryKey"];
  if ($rowSet["HomeScore"]>0 || $rowSet["AwayScore"]>0)
		$tempArray["rel"]="get.player.group.detail.php?Mode=P4F&GroupKey=".$rowSet["GroupKey"]."&PlayerKeys=".$rowSet["HomePlayerKey"].",".$rowSet["AwayPlayerKey"];
  $tempArray["cell"][0]= $rowSet["SeasonCode"];
  $homeAvatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["HomeAvatarName"])) {
    $homeAvatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["HomeAvatarName"];
  }
  $awayAvatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AwayAvatarName"])) {
    $awayAvatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AwayAvatarName"];
  }

  $winner = "<img src='images/trophy.gold.png' style='width:10px;height:10px;'/>";
  $homeWinner = "";
  $awayWinner = "";
  if ($rowSet["HomeScore"] || $rowSet["AwayScore"])
    if ($rowSet["HomeScore"]>$rowSet["AwayScore"])
      $homeWinner = $winner;
    elseif ($rowSet["HomeScore"]<$rowSet["AwayScore"])
      $awayWinner = $winner;
  $tempArray["cell"][1]= $rowSet["DivisionCode"];
  $tempArray["cell"][2]= "J".$rowSet["DayKey"];
  $tempArray["cell"][3]= $homeWinner .'<span class="ellipsis" displayWidth="70" style="_width=68px;">'.addslashes($rowSet["HomeNickName"])."</span><img class='avatarSmallLeft' border='0' src='" . $homeAvatarPath . "'>";
//  $tempArray["cell"][3]= $rowSet["HomeNickName"];
  $tempArray["cell"][4]= $rowSet["HomeScore"];
  $tempArray["cell"][5]= $rowSet["AwayScore"];
//  $tempArray["cell"][6]= $rowSet["AwayNickName"];
  $tempArray["cell"][6]= "<img class='avatarSmallRight' border='0' src='" . $awayAvatarPath . "'>".'<span class="ellipsis" displayWidth="70" style="_width=68px;">'.addslashes($rowSet["AwayNickName"]).'</span>'.$awayWinner;



  $arr["rows"][]=$tempArray;
}

WriteJsonResponse($arr);

require_once("end.file.php");
?>