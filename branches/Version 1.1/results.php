<?php
require_once(dirname(__FILE__)."/begin.file.php");
error_reporting(E_ALL | E_STRICT);

date_default_timezone_set('Europe/Paris');

require_once(dirname(__FILE__)."/lib/p4fmailer.php");

include_once (dirname(__FILE__)."/lib/safeIO.php");

$currentTime = time();

$query= "SELECT players.PrimaryKey PlayerKey,
players.EmailAddress,
players.NickName,
(SELECT MAX(PrimaryKey) FROM groups WHERE IsCompleted=1 AND CompetitionKey=" . COMPETITION . " AND DATE(groups.EndDate)=DATE(NOW())-1) GroupKey
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
    $mail             = new PHPMailer(true);

    try {

      $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

      $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

      $sqlGroup = "SELECT Description FROM groups WHERE PrimaryKey=" . $rowSet["GroupKey"];
      $resultSetGroup = $_databaseObject->queryPerf($sqlGroup,"Get group information");

      $rowSetGroup = $_databaseObject -> fetch_assoc ($resultSetGroup);
      $_groupDescription = $rowSetGroup['Description'];
      unset($rowSetGroup,$resultSetGroup,$sqlGroup);

      $mail->Subject    = "Pronostics4Fun - Résultats des pronostics de la ".__decode($_groupDescription);

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
      setlocale(LC_TIME, "fr_FR");
      $currentFormattedDate = strftime("%A %d %B %Y, %H:%M",time());


    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }

    unset($mail);

    $query ="UPDATE players SET IsResultEmailSent=1 WHERE PrimaryKey=" . $playerKey;
    $_databaseObject->queryPerf($query,"Reset reminder information");

  }
}
//else
//{
//  echo "Pas d'email a envoyé!";
//  $stringData = "Pas d'email a envoyé!";
//  $myFile ="/home/a3174115/public_html/Ligue12010/log/emails.log";
//  chmod($myFile,0666);
//  $sio = new safeIO;
//  $isOpen=$sio->open($myFile,APPEND);
//  if ($isOpen) {
//    echo "Ecriture dans le fichier<br/>";
//    $sio->write( $stringData,strlen($stringData));
//    $sio->close();
//  }
//}
writePerfInfo();

require_once(dirname(__FILE__)."/end.file.php");
?>