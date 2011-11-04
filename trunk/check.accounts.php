#!/usr/local/bin/php
<?php
require_once("begin.file.php");

$query= "SELECT COUNT(*) NbrOfAccount FROM players WHERE IsEnabled=1";

$resultSet = $_databaseObject -> queryPerf ($query, "Check number of account enabled");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$activeAccounts = $rowSet["NbrOfAccount"];

$query= "UPDATE players SET IsEnabled=0 WHERE LastConnection <= CURDATE()-INTERVAL 120 DAY";

if ($_databaseObject -> queryPerf ($query, "Disabled the exipred accounts")) {
  $query= "SELECT COUNT(*) NbrOfAccount FROM players WHERE IsEnabled=1";

  $resultSet = $_databaseObject -> queryPerf ($query, "Check number of account enabled");
  $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
  $newActiveAccounts = $rowSet["NbrOfAccount"];

  echo $activeAccounts - $newActiveAccounts . " accounts have been disabled";
}
else {
  echo "An error has occurred";
}

writePerfInfo();

require_once("end.file.php");
?>