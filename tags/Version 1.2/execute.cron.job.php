<?php
if (!isset($_GET["CronJobId"])) {
  echo 'Please enter a CronJobId valid!';
  exit;
  // for testing purpose
  //$_cronJobId = "1";
}
else {
  $_cronJobId = $_GET["CronJobId"];
}

include_once(dirname(__FILE__)."/begin.file.php");

$_logInfo = "";
$_error=false;
$_errorMessage="";

switch ($_cronJobId)
{
  case "1": //refresh.matches
    $jobName='RefreshMatches';
    $fileName = BASE_PATH . "/refresh.matches.php";
    break;
  case "2": // get.matches
    $jobName='GetMatches';
    $fileName = BASE_PATH . "/get.matches.php";

    break;
}

include_once($fileName);

?>