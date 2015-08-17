#!/usr/local/bin/php
<?php
ini_set ("display_errors", "1");
error_reporting(E_ALL);
require_once(dirname(__FILE__)."/begin.file.php");
require_once(dirname(__FILE__)."/lib/p4fmailer.php");

$currentDate = strftime("%d %b %Y",time());
$filename= strftime(SQL_DB".-%Y%m%d",time()). ".sql";
system("mysqldump --host=".SQL_HOST." --user=".SQL_LOGIN." --password=".SQL_PWD." --ignore-table=".SQL_DB.".connectedusers --ignore-table=".SQL_DB.".cronjobs ".SQL_DB." > data/" . $filename );
system("gzip data/" . $filename);

$currentFileSize = filesize("data/". $filename . ".gz");

$yesterdayDate = strftime("%d %b %Y",time() - (60*60*24));
$yesterdayFilename= strftime(SQL_DB".-%Y%m%d",time()- (60*60*24)). ".sql.gz";
$yesterdayFilesize= 0;
if (file_exists("data/".$yesterdayFilename)) {
  $yesterdayFilesize = filesize("data/".$yesterdayFilename);
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

    $mail->AddAttachment("data/".$filename . ".gz");

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

if (file_exists("data/".$yesterdayFilename)) {
  if (@unlink("data/".$yesterdayFilename) === true) {
    echo "<br/>";
    echo "the file $yesterdayFilename was successfully deleted!";
  }
}
require_once(dirname(__FILE__)."/end.file.php");

?>