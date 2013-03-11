<?php
require_once(dirname(__FILE__)."/begin.file.php");
error_reporting(E_ALL | E_STRICT);

date_default_timezone_set('Europe/Paris');

require_once(dirname(__FILE__)."/lib/p4fmailer.php");

include_once (dirname(__FILE__)."/lib/safeIO.php");

$currentTime = time();

$query= "SELECT * FROM cronjobs
WHERE DATE(NOW())!=DATE(cronjobs.LastExecution) AND JobName='Results'";

$resultSetCronJob = $_databaseObject->queryPerf($query,"Get players for sending their results");
$emailCountSent = 0;
$emailCountError = 0;
while ($rowSetCronJob = $_databaseObject -> fetch_assoc ($resultSetCronJob)) {



  $query= "SELECT players.PrimaryKey PlayerKey,
players.EmailAddress,
players.NickName,
(SELECT MAX(PrimaryKey) FROM groups WHERE IsCompleted=1 AND CompetitionKey=" . COMPETITION . " AND DATE(groups.EndDate)=DATE(NOW())- INTERVAL 1 DAY) GroupKey
FROM playersenabled players
WHERE players.ReceiveResult=1
  AND players.IsResultEmailSent=0
  AND NOT EXISTS (
            SELECT 1
              FROM matches
             WHERE $currentTime >= (UNIX_TIMESTAMP(matches.ScheduleDate)) AND $currentTime <= (UNIX_TIMESTAMP(matches.ScheduleDate)+11400)
            )

";

  $resultSet = $_databaseObject->queryPerf($query,"Get players for sending their results");

  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
    $playerKey="";
    if ($rowSet["PlayerKey"]) {
      $playerKey = $rowSet["PlayerKey"];
      echo "email to : ". $rowSet["NickName"]."<br/>";
      $mail             = new P4FMailer();

      try {
        $mail->CharSet = 'UTF-8';

        $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

        $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

        $sqlGroup = "SELECT Description FROM groups WHERE PrimaryKey=" . $rowSet["GroupKey"];
        $resultSetGroup = $_databaseObject->queryPerf($sqlGroup,"Get group information");

        $rowSetGroup = $_databaseObject -> fetch_assoc ($resultSetGroup);
        $_groupDescription = $rowSetGroup['Description'];
        unset($rowSetGroup,$resultSetGroup,$sqlGroup);

        $mail->Subject    = "Pronostics4Fun - Résultats des pronostics - ".$_groupDescription;

        $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test

        $mail->MsgHTML(file_get_contents('http://pronostics4fun.com/result.sumup.php?PlayerKey='.$rowSet["PlayerKey"].'&GroupKey='.$rowSet["GroupKey"]));
        //$mail->MsgHTML($emailBody);

        //if (ROOT_SITE=="http://www.pronostics4fun.com") {
        $address= $rowSet["EmailAddress"];
        //}
        //else {
        //  $address = "sebastien.dubuc@gmail.com";
        //}
        $nickName = $rowSet["NickName"];

        $mail->AddAddress($address, $nickName);

        $mail->AddAttachment("images/Logo.png");      // attachment

        $mail->Send();
        echo "Message sent to $nickName!<br/>";
        $currentFormattedDate = strftime("%A %d %B %Y, %H:%M",time());


        $emailCountSent++;
      } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
        $emailCountError++;
      } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
        $emailCountError++;
      }

      unset($mail);

      $query ="UPDATE players SET IsResultEmailSent=1 WHERE PrimaryKey=" . $playerKey;
      $_databaseObject->queryPerf($query,"Reset reminder information");

    }
  }
  $sqlUpdateCronJobLog =" UPDATE cronjobs SET LastStatus=2, LastExecutionInformation='$emailCountSent Email sent | $emailCountError email with error' WHERE JobName='Results'";
  $_databaseObject->queryPerf($sqlUpdateCronJobLog,"Update Cron job information");

}


writePerfInfo();


require_once(dirname(__FILE__)."/end.file.php");
?>