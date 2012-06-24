<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/lib/match.php");
include_once(dirname(__FILE__). "/lib/ranking.php");
include_once(dirname(__FILE__). "/lib/score.php");
include_once(dirname(__FILE__). "/lib/p4fmailer.php");

$_databaseObject->close();

$_queries =array();
$arr = array();
if (1==1) {
  $teamHomeKey = 1;
  $teamAwayKey = 2;
  $externalKey = 2003324;
  $matchKey = 1;
  $isLive = 0;

  switch ($_competitionType) {
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
        $_queries[] =$query;
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