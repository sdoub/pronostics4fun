#!/usr/local/bin/php
<?php
require_once("begin.file.php");

$query= "SELECT COUNT(*) NbrOfAccount FROM players WHERE IsEnabled=1";

$resultSet = $_databaseObject -> queryPerf ($query, "Check number of account enabled");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$activeAccounts = $rowSet["NbrOfAccount"];
$logMessage= "";
$query= "UPDATE players SET IsEnabled=0 WHERE LastConnection <= CURDATE()-INTERVAL 30 DAY";

if ($_databaseObject -> queryPerf ($query, "Disabled the exipred accounts")) {
  $query= "SELECT COUNT(*) NbrOfAccount FROM players WHERE IsEnabled=1";

  $resultSet = $_databaseObject -> queryPerf ($query, "Check number of account enabled");
  $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
  $newActiveAccounts = $rowSet["NbrOfAccount"];

  $logMessage = $activeAccounts - $newActiveAccounts . " accounts have been disabled";

}
else {
  $logMessage = "An error has occurred";
}

echo $logMessage;

writePerfInfo();

$sqlUpdateCronJobLog =" UPDATE cronjobs SET LastStatus=2, LastExecutionInformation='$logMessage' WHERE JobName='CheckAccount'";
$_databaseObject->queryPerf($sqlUpdateCronJobLog,"Update Cron job information");

require_once("end.file.php");
?>