<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/lib/match.php");

$_databaseObject->close();

$_queries =array();
$arr = array();
  
if (isset($_POST["TeamHomeKey"])) {
	$teamHomeKey = $_POST["TeamHomeKey"];
  $teamAwayKey = $_POST["TeamAwayKey"];
  $externalKey = $_POST["ExternalKey"];
  $matchKey = $_POST["MatchKey"];
  $isLive = $_POST["Live"];
	$arr["Parameters"] = $_POST;
} else if (isset($_GET["TeamHomeKey"])) {
  $teamHomeKey = $_GET["TeamHomeKey"];
  $teamAwayKey = $_GET["TeamAwayKey"];
  $externalKey = $_GET["ExternalKey"];
  $matchKey = $_GET["MatchKey"];
  $isLive = $_GET["Live"];
	$arr["Parameters"] = $_GET;
}
if ($teamHomeKey && $teamAwayKey && $externalKey && $matchKey) {
  switch ($_competitionType) {
    case 2:
      $matchInfo = GetFifaMatchInfo($teamHomeKey,$teamAwayKey,$externalKey,$matchKey,$isLive=="1");
      foreach ($matchInfo["Queries"] as $query) {
        $_queries[] =$query;
      }
      break;
    case 3:
      $matchInfo = GetUefaMatchInfo($teamHomeKey,$teamAwayKey,$externalKey,$matchKey,$isLive=="1");
      foreach ($matchInfo["Queries"] as $query) {
        $_queries[] =$query;
      }
      break;
    default:
      $matchInfo = GetMatchInfo($teamHomeKey,$teamAwayKey,$externalKey,$matchKey,$isLive=="1");
      foreach ($matchInfo["Queries"] as $query) {
        $_queries[] =$query;
      }
      $matchInfo = GetMatchsLineupsInfo($teamHomeKey,$teamAwayKey,$externalKey,$matchKey,$isLive=="1",$matchInfo["HomeId"],$matchInfo["AwayId"]);
      foreach ($matchInfo["Queries"] as $query) {
				$_queries[] =utf8_encode($query);
      }

      break;
  }
  $arr["Status"] = "Success";
} else
{
  $arr["Status"] = "Failed";
}

$arr["Queries"] = $_queries;

writeJsonResponse($arr);
include_once(dirname(__FILE__)."/end.file.php");
?>