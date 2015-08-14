<?php
require_once(dirname(__FILE__)."/begin.file.php");

$_test=false;
if (isset($_GET["Test"])) {
  $_test = true;
}

date_default_timezone_set('Europe/Paris');

$cronJob = CronjobsQuery::Create()->findOneByJobname('ResetReminder');

$todayDate = new DateTime();
// has already executed today?
if ($cronJob->getLastexecution()->format("Y-m-d")!=$todayDate->format("Y-m-d") || $_test){
	$nbUpdatedRows = PlayersQuery::create()
  	->update(array('Isreminderemailsent' => 0));
	echo "Executed - $nbUpdatedRows players updated";
	$cronJob->setLastexecutioninformation("Executed - $nbUpdatedRows players updated");
} else {
	echo "Job has already been executed today";
}

$cronJob->setLaststatus(2);
$cronJob->save();

require_once(dirname(__FILE__)."/end.file.php");
?>