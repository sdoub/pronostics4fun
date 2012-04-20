<?php
require_once(dirname(__FILE__)."/begin.file.php");
error_reporting(E_ALL | E_STRICT);

date_default_timezone_set('Europe/Paris');

require_once(dirname(__FILE__)."/lib/PHPMailer/class.phpmailer.php");

include_once (dirname(__FILE__)."/lib/safeIO.php");

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
    setlocale(LC_TIME, "fr_FR");
    $tomorrowFormattedDate = strftime("%A %d %B %Y",$rowSet['tomorrowDate']);

    $emailSent = true;
    echo "email to : ". $rowSet["NickName"]."<br/>";
    $mail             = new PHPMailer(true);

    try {

      if (SQL_LOGIN=="sdoub") {
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host       = "pronotics4fun.com"; // SMTP server
        $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
        $mail->Username   = "sebastien.dubuc@gmail.com";  // GMAIL username
        $mail->Password   = "aurelie040697";            // GMAIL password
      }
      //$mail->AddCustomHeader("Precedence: bulk");
      //pronostics4fun.com. IN TXT "v=spf1 a mx ptr include:gmail.com ~all"

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

      $mail->AddAttachment("images/Logo.png");      // attachment

      $mail->Send();
      echo "Message sent to $nickName!<br/>";
      setlocale(LC_TIME, "fr_FR");
      $currentFormattedDate = strftime("%A %d %B %Y, %H:%M",time());

      echo "Email sent to $nickName at $currentFormattedDate\n";

    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }

    unset($mail);

    $query ="UPDATE players SET IsReminderEmailSent=1 WHERE PrimaryKey=" . $rowSet["PlayerKey"];
    $_databaseObject->queryPerf($query,"Reset reminder information");

  }
}

if ($emailSent==false){
  echo "Pas d'email a envoyé!";
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

require_once(dirname(__FILE__)."/end.file.php");
?>