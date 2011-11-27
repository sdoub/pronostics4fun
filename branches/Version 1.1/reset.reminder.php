<?php
require_once(dirname(__FILE__)."/begin.file.php");

$sqlFixRank ="UPDATE players SET IsReminderEmailSent=0";
$_databaseObject->queryPerf($sqlFixRank,"Reset reminder information");

require_once(dirname(__FILE__)."/end.file.php");
?>