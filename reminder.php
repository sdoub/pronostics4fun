#!/usr/local/bin/php
<?php
require_once(dirname(__FILE__)."/begin.file.php");

error_reporting(E_ALL);
ini_set('display_errors', 'on');

$_test=false;
if (isset($_GET["Test"])) {
  $_test = true;
}

$_day = 1;
if (isset($_GET["Day"])) {
  $_day = $_GET["Day"];
}

date_default_timezone_set('Europe/Paris');

require_once(dirname(__FILE__)."/lib/p4fmailer.php");
//include_once (dirname(__FILE__)."/lib/safeIO.php");
$cronJob = CronjobsQuery::Create()->findOneByJobname('Reminder');

$emailCountSent = 0;
$emailCountError = 0;
$emailSent = false;

$todayDate = new DateTime();
// has already executed today?
if ($cronJob->getLastexecution()->format("Y-m-d")!=$todayDate->format("Y-m-d") || $_test){

	$players = PlayersQuery::Create()
		->filterByReceivealert(true)
		->filterByIsreminderemailsent(false)
		->filterByIsemailvalid(true)
		->filterByIsenabled(true)
		->find();

  $todayDate = new DateTime();
	$todayDate->setTime(0,0);
	$minScheduleDate = $todayDate->format("U");
	$todayDate->add(new DateInterval('P'.$_day.'D'));
	$reminderDate = $todayDate->format("U");
	$todayDate->add(new DateInterval('P1D'));
	$maxScheduleDate = $todayDate->format("U");

  echo strftime("%A %d %B %Y",$reminderDate);
	echo "<br/>";
	$matches = MatchesQuery::Create()
		->filterByScheduledate(array(
    	'min' => $minScheduleDate, 
    	'max' => $maxScheduleDate,
  	))
		->orderByScheduledate()
		->orderByPrimarykey()
		->find();
	$matchlist=array();
	foreach($matches as $match) {
		$matchlist[]=$match->getPrimarykey();
	}	
	foreach($players as $player) {
		$playerForecast=ForecastsQuery::Create()
			->filterByMatchkey($matchlist)
			->filterByPlayerkey($player->getPrimarykey())
			->count();
		echo $player->getNickname() ." number of forecasts : ".$playerForecast."<br/>";
		if ($playerForecast!=count($matchlist)) {
			$tomorrowFormattedDate =strftime("%A %d %B %Y",$reminderDate);
			echo $tomorrowFormattedDate;
			//$tomorrowFormattedDate = $reminderDate->format("l j F Y");
      $emailSent = true;
      
      $mail             = new P4FMailer();
      try {
        $mail->CharSet = 'UTF-8';
        $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
        $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
        $mail->Subject    = "Pronostics4Fun - Alert pronostics pour les matchs du ".$tomorrowFormattedDate;
        $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test
        $mail->MsgHTML(file_get_contents('http://pronostics4fun.com/reminder.sumup.php?PlayerKey='.$player->getPrimarykey()));

        if (SQL_LOGIN!="sdoub" && SQL_LOGIN!="root") {
          $address= $player->getEmailaddress();
        }
        else {
          $address = "sebastien.dubuc@gmail.com";
        }
        $nickName = $player->getNickname();
        $mail->AddAddress($address, $nickName);
        //$mail->AddAttachment($_themePath."/images/Logo.png");      // attachment
        if (!$_test){
					$mail->Send();
				}
        echo "Message sent to $nickName!<br/>";
        $currentFormattedDate = strftime("%A %d %B %Y, %H:%M",time());
        echo "Email sent to $nickName at $currentFormattedDate<br/>";
        $emailCountSent++;
      } catch (phpmailerException $e) {
        echo $e->errorMessage(); 
				$defaultLogger->addError($e->errorMessage());
        $emailCountError++;
      } catch (Exception $e) {
				$defaultLogger->addError($e->getMessage());
        echo $e->getMessage(); //Boring error messages from anything else!
        $emailCountError++;
      }
      unset($mail);
			$player->setIsreminderemailsent(true);
			$player->save();
			//$query ="UPDATE players SET IsReminderEmailSent=1 WHERE PrimaryKey=" . $rowSet["PlayerKey"];
      //$_databaseObject->queryPerf($query,"Reset reminder information");
    }
  }
} else {
	echo "Job has already been executed today";
}
if ($emailSent==false){
	$logInfo = "Pas d'email a envoyé!";
} else {
	$logInfo = "$emailCountSent Email sent | $emailCountError email with error";
}
echo $logInfo;
$cronJob->setLaststatus(2);
$cronJob->setLastexecutioninformation($logInfo);
$cronJob->save();
	//$sqlUpdateCronJobLog =" UPDATE cronjobs SET LastStatus=2, LastExecutionInformation='$emailCountSent Email sent | $emailCountError email with error' WHERE JobName='Reminder'";
  //$_databaseObject->queryPerf($sqlUpdateCronJobLog,"Update Cron job information");

require_once(dirname(__FILE__)."/end.file.php");
?>