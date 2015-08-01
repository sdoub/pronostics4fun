<?php
require_once(dirname(__FILE__)."/begin.file.php");
error_reporting(E_ALL | E_STRICT);

date_default_timezone_set('Europe/Paris');

require_once(dirname(__FILE__)."/lib/p4fmailer.php");

include_once (dirname(__FILE__)."/lib/safeIO.php");

$currentTime = time();

$query= " SELECT matches.PrimaryKey MatchKey,
                 teamHome.Code TeamHomeName,
                 teamAway.Code TeamAwayName
            FROM matches
           INNER JOIN teams teamHome ON matches.TeamHomeKey=teamHome.PrimaryKey
           INNER JOIN teams teamAway ON matches.TeamAwayKey=teamAway.PrimaryKey
           INNER JOIN results ON results.MatchKey = matches.PrimaryKey AND results.LiveStatus < 10
           WHERE matches.Status=0
             AND (UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(matches.ScheduleDate))>11400";

$resultSet = $_databaseObject->queryPerf($query,"Data error");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
  $matchKey="";
  if ($rowSet["MatchKey"]) {
    $mail             = new PHPMailer(true);
    try {
      $mail->CharSet = 'UTF-8';
      $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
      $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
      $mail->Subject    = "Pronostics4Fun - Data error";
      $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test
      $mail->Priority = 1;
      //$mail->MsgHTML(file_get_contents('http://pronostics4fun.com/result.sumup.php?PlayerKey='.$rowSet["PlayerKey"].'&GroupKey='.$rowSet["GroupKey"]));
      $emailBody = "Data error on match -> " . $rowSet["TeamHomeName"] ."-" . $rowSet["TeamAwayName"];
      $mail->MsgHTML($emailBody);

      $address= "admin@pronostics4fun.com";
      $nickName = "P4F - Administrateur";

      $mail->AddAddress($address, $nickName);

      $mail->Send();
      echo "Message sent to $nickName!<br/>";

    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }

    unset($mail);

  }
}

use Propel\Runtime\Propel;
$con = Propel::getWriteConnection(\Map\GroupsTableMap::DATABASE_NAME);
$sql = "SELECT * FROM groups WHERE groups.PrimaryKey IN "
        ."(SELECT matches.GroupKey FROM matches"
        ." GROUP BY matches.GroupKey"
        ." HAVING COUNT(1)>10)";
$stmt = $con->prepare($sql);
$stmt->execute();

use Propel\Runtime\Formatter\ObjectFormatter;
$con = Propel::getWriteConnection(\Map\GroupsTableMap::DATABASE_NAME);
$formatter = new ObjectFormatter();
$formatter->setClass('\Groups'); //full qualified class name
$groups = $formatter->format($con->getDataFetcher($stmt));

if (count($groups)>0)
{

    $mail             = new P4FMailer();
    try {
      $mail->CharSet = 'UTF-8';
      $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
      $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
      $mail->Subject    = "Pronostics4Fun - Data group error";
      $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test
      $mail->Priority = 1;
      //$mail->MsgHTML(file_get_contents('http://pronostics4fun.com/result.sumup.php?PlayerKey='.$rowSet["PlayerKey"].'&GroupKey='.$rowSet["GroupKey"]));
			$emailBody ='<h2>Too many matches !</h2>';
			foreach ($groups as $group) {
				$emailBody.= "Data error on group -> " . $group->getDescription();
				$emailBody.='<br/>';
			}
      $mail->MsgHTML($emailBody);

      $address= "admin@pronostics4fun.com";
      $nickName = "P4F - Administrateur";

      $mail->AddAddress($address, $nickName);

      if ($mail->Send())
      	echo "Message sent to $nickName!<br/>";
			else
				echo "Message not sent to $nickName! due to: ".$mail->ErrorInfo."<br/>";

    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }

    unset($mail);
} else {
	echo 'All groups are correct !';
}
writePerfInfo();

$sqlUpdateCronJobLog =" UPDATE cronjobs SET LastStatus=2, LastExecutionInformation='Executed' WHERE JobName='DataError'";
$_databaseObject->queryPerf($sqlUpdateCronJobLog,"Update Cron job information");

require_once(dirname(__FILE__)."/end.file.php");
?>