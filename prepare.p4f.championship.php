<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/classes/championship.php");
include_once(dirname(__FILE__). "/lib/p4f.championship.php");

$arr = array();
$arr[] = CreateNextSeason(7, 8);
$arr[] = CalculateP4FDivisionsRanking(8);
writeJsonResponse($arr);

include_once(dirname(__FILE__)."/end.file.php");
?>