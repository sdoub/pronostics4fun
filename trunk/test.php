<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/lib/match.php");
include_once(dirname(__FILE__). "/lib/ranking.php");
include_once(dirname(__FILE__). "/lib/score.php");
include_once(dirname(__FILE__). "/lib/p4fmailer.php");

$_queries =array();
$arr = array();
if (1==1) {
  $groupKey = 93;

  GenerateMatchStates($_GET["GroupKey"]);

  $arr["Status"] = "Success";
} else
{
  $arr["Status"] = "Failed";
}

writeJsonResponse($arr);
include_once(dirname(__FILE__)."/end.file.php");
?>