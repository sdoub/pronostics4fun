<?php
require_once(dirname(__FILE__)."/begin.file.php");
error_reporting(E_ALL | E_STRICT);

date_default_timezone_set('Europe/Paris');

require_once(dirname(__FILE__)."/lib/PHPMailer/class.phpmailer.php");

include_once (dirname(__FILE__)."/lib/safeIO.php");

$query= "SELECT * FROM cronjobs
WHERE DATE(NOW())!=DATE(cronjobs.LastExecution) AND JobName='Reminder'";

$resultSetCronJob = $_databaseObject->queryPerf($query,"Get players for sending their results");
$emailCountSent = 0;
$emailCountError = 0;
while ($rowSetCronJob = $_databaseObject -> fetch_assoc ($resultSetCronJob)) {


  $query= "SELECT players.PrimaryKey PlayerKey,
players.EmailAddress,
players.NickName,
players.ActivationKey,
UNIX_TIMESTAMP((CURDATE()+ INTERVAL 1 DAY)) tomorrowDate
FROM playersenabled players
WHERE EXISTS (
			SELECT 1
			  FROM matches
       WHERE DATE(matches.ScheduleDate)=(CURDATE()+ INTERVAL 1 DAY)
         AND NOT EXISTS (SELECT 1 FROM forecasts
                          WHERE forecasts.MatchKey=matches.PrimaryKey
                            AND forecasts.PlayerKey=players.PrimaryKey)
			)
AND EXISTS (
            SELECT 1
            FROM matches
            WHERE DATE(matches.ScheduleDate)=(CURDATE()+ INTERVAL 1 DAY)
            )
AND players.ReceiveAlert=1
AND players.IsReminderEmailSent=0
";

  $resultSet = $_databaseObject->queryPerf($query,"Get players with missing forecasts for tomorrow");
  $emailSent = false;
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
    if ($rowSet["PlayerKey"]) {
      $tomorrowFormattedDate = strftime("%A %d %B %Y",$rowSet['tomorrowDate']);

      $emailSent = true;
      echo "email to : ". $rowSet["NickName"]."<br/>";
      $mail             = new P4FMailer();

      try {

        //$mail->AddCustomHeader("Precedence: bulk");
        //pronostics4fun.com. IN TXT "v=spf1 a mx ptr include:gmail.com ~all"
        $mail->CharSet = 'UTF-8';
        $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

        $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

        $mail->Subject    = "Pronostics4Fun - Alert pronostics pour les matchs du ".$tomorrowFormattedDate;

        $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test

        $mail->MsgHTML(file_get_contents('http://pronostics4fun.com/reminder.sumup.php?PlayerKey='.$rowSet["PlayerKey"]));

        if (SQL_LOGIN!="sdoub") {
          $address= $rowSet["EmailAddress"];
        }
        else {
          $address = "sebastien.dubuc@gmail.com";
        }
        $nickName = $rowSet["NickName"];

        $mail->AddAddress($address, $nickName);

        $mail->AddAttachment($_themePath."/images/Logo.png");      // attachment

        $mail->Send();
        echo "Message sent to $nickName!<br/>";
        $currentFormattedDate = strftime("%A %d %B %Y, %H:%M",time());

        echo "Email sent to $nickName at $currentFormattedDate\n";
        $emailCountSent++;
      } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
        $emailCountError++;
      } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
        $emailCountError++;
      }

      unset($mail);

      $query ="UPDATE players SET IsReminderEmailSent=1 WHERE PrimaryKey=" . $rowSet["PlayerKey"];
      $_databaseObject->queryPerf($query,"Reset reminder information");

    }
  }

  if ($emailSent==false){
    echo "Pas d'email a envoyé!";
  }
  $sqlUpdateCronJobLog =" UPDATE cronjobs SET LastStatus=2, LastExecutionInformation='$emailCountSent Email sent | $emailCountError email with error' WHERE JobName='Reminder'";
  $_databaseObject->queryPerf($sqlUpdateCronJobLog,"Update Cron job information");
}
require_once(dirname(__FILE__)."/end.file.php");
?>