#!/usr/local/bin/php
<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/lib/match.php");
include_once(dirname(__FILE__). "/lib/ranking.php");
include_once(dirname(__FILE__). "/lib/score.php");
include_once(dirname(__FILE__). "/lib/p4fmailer.php");

$dayKey="";
if (isset($_GET["DayKey"])) {
  $dayKey=$_GET["DayKey"];
  $whereClause = " WHERE groups.DayKey IN ($dayKey)";
}
else {
  $whereClause = " WHERE groups.Status=3 AND groups.EndDate + INTERVAL 5 DAY <NOW()";
  // for testing purpose
  //$dayKey="1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27";
  //$dayKey="28";
}

$currentTime = time();

$query= "SELECT
matches.PrimaryKey MatchKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.TeamHomeKey,
matches.TeamAwayKey,
matches.ExternalKey,
groups.Description
 FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
 " . $whereClause;

//WHERE $currentTime >= (UNIX_TIMESTAMP(matches.ScheduleDate)) AND $currentTime <= (UNIX_TIMESTAMP(matches.ScheduleDate)+11400)";

$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get matches to be refreshed");
$_databaseObject->close();

$_queries = array();

foreach ($rowsSet as $rowSet)
{
  $_logInfo .= "Refresh match with key ".$rowSet["MatchKey"] ;
  try {
    GetMatchCompleteInfo($rowSet["TeamHomeKey"],$rowSet["TeamAwayKey"],$rowSet["ExternalKey"],$rowSet["MatchKey"]);
  }
  catch (Exception $e) {
    $_error=true;
    $_errorMessage =$e;
  }
}


$previousGroupKey = 0;
foreach ($rowsSet as $rowSet)
{
  if ($previousGroupKey != $rowSet["GroupKey"]) {
    $mail = new P4FMailer();

    try {

      $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

      $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

      $_groupDescription = $rowSet['Description'];
      $mail->Subject    = "Pronostics4Fun - Mise à jour des stats de la ".__decode($_groupDescription);

      $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test

      $mail->MsgHTML(file_get_contents(ROOT_SITE.'/result.group.sumup.php?GroupKey='.$rowSet["GroupKey"]));

      $mail->AddAddress('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

      $mail->AddAttachment("images/Logo.png");      // attachment

      $mail->Send();

    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }

    unset($mail);
  }
  $previousGroupKey = $rowSet["GroupKey"];

}

$_databaseObject = new mysql (SQL_HOST, SQL_LOGIN,  SQL_PWD, SQL_DB, $_dbOptions);
foreach ($_queries as $query) {
  //print($query);
  $_databaseObject -> queryPerf ($query , "Execute query");
}

$query = "UPDATE groups SET groups.Status=4 " . $whereClause;
$_databaseObject->queryPerf($query,"update group");

$arr = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');

$totaltime = getElapsedTime();
//$_logInfo .= implode(',',$arr["errorLog"]);
echo json_encode($arr);
$_logInfo .= "This page loaded in $totaltime seconds.";
if (count($arr["errorLog"])>0) {
  if ($arr["errorLog"]!="") {
    $_error = true;
    $_errorMessage="An error occured during queries execution";
    print_r($arr["errorLog"]);
  }
}

//    $script_tz = date_default_timezone_get();
//
//    if (strcmp($script_tz, ini_get('date.timezone'))){
//      $_logInfo .= 'Script timezone differs from ini-set timezone.';
//      $_logInfo .= $script_tz;
//    } else {
//      $_logInfo .= 'Script timezone and ini-set timezone match.';
//      $_logInfo .= $script_tz;
//    }

echo $_logInfo;
require_once(dirname(__FILE__)."/end.file.php");
?>