<?php
require_once(dirname(__FILE__)."/begin.file.php");

date_default_timezone_set('Europe/Paris');

$cronJob = CronjobsQuery::Create()->findOneByJobname('ResetReminder');

$todayDate = new DateTime();
// has already executed today?
if ($cronJob->getLastexecution()->format("Y-m-d")!=$todayDate->format("Y-m-d")){
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