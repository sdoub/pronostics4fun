<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/classes/cup.php");
include_once(dirname(__FILE__). "/lib/p4f.cup.php");

$arr = CreateCup();
$arr["perfAndError"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
writeJsonResponse($arr);

include_once(dirname(__FILE__)."/end.file.php");
?>