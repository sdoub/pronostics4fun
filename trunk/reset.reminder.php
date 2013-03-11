<?php
require_once(dirname(__FILE__)."/begin.file.php");

$query= "SELECT * FROM cronjobs
WHERE DATE(NOW())!=DATE(cronjobs.LastExecution) AND JobName='ResetReminder'";

$resultSet = $_databaseObject->queryPerf($query,"Get players for sending their results");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {

  $sqlFixRank ="UPDATE players SET IsReminderEmailSent=0";
  $_databaseObject->queryPerf($sqlFixRank,"Reset reminder information");

  $sqlFixRank =" UPDATE cronjobs SET LastStatus=2, LastExecutionInformation='Executed' WHERE JobName='ResetReminder'";
  $_databaseObject->queryPerf($sqlFixRank,"Reset reminder information");
}
require_once(dirname(__FILE__)."/end.file.php");
?>