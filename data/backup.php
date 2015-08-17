#!/usr/local/bin/php
<?
ini_set ("display_errors", "1");
error_reporting(E_ALL);
require_once(dirname(__FILE__)."/../begin.file.php");
require_once(dirname(__FILE__)."/../lib/p4fmailer.php");

$currentDate = strftime("%d %b %Y",time());
$filename= strftime("pronostilxp4f-%Y%m%d",time()). ".sql";
system("mysqldump --host=mysql51-39.perso --user=pronostilxp4f --password=jQjspq2q --ignore-table=pronostilxp4f.connectedusers --ignore-table=pronostilxp4f.cronjobs pronostilxp4f > " . $filename );
system("gzip " . $filename);

$currentFileSize = filesize($filename . ".gz");

$yesterdayDate = strftime("%d %b %Y",time() - (60*60*24));
$yesterdayFilename= strftime("pronostilxp4f-%Y%m%d",time()- (60*60*24)). ".sql.gz";
$yesterdayFilesize= 0;
if (file_exists($yesterdayFilename)) {
  $yesterdayFilesize = filesize($yesterdayFilename);
}

if (abs($yesterdayFilesize-$currentFileSize)>5) {

  $mail = new P4FMailer();
  try {

    $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

    $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

    $mail->Subject    = "Pronostics4Fun - Sauvegarde de la base du ". $currentDate;

    $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!
    Base d'hier: $yesterdayFilesize
    Base d'aujourd'hui: $currentFileSize";

    $mail->MsgHTML(file_get_contents('http://pronostics4fun.com/database.stats.php'));

    $mail->AddAddress("sebastien.dubuc@gmail.com", "Sébastien Dubuc");

    $mail->AddAttachment($filename . ".gz");

    $mail->Send();
  } catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
  } catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
  }

  unset($mail);
  echo "Email has been sent!";
} else {
  echo "No email has been sent, because there is no changes compare to yesterday!";
  echo "<br/>";
  echo "$filename : $currentFileSize";
  echo "<br/>";
  echo "$yesterdayFilename : $yesterdayFilesize";
}

if (file_exists($yesterdayFilename)) {
  if (@unlink($yesterdayFilename) === true) {
    echo "<br/>";
    echo "the file $yesterdayFilename was successfully deleted!";
  }
}
require_once(dirname(__FILE__)."/../end.file.php");

?>