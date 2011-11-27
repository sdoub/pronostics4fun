#!/usr/local/bin/php
<?

require_once(dirname(__FILE__)."/../lib/PHPMailer/class.phpmailer.php");

$currentDate = strftime("%d %b %Y",time());
$filename= strftime("pronostilxp4f-%Y%m%d",time()). ".sql";
system("mysqldump --host=mysql51-39.perso --user=pronostilxp4f --password=jQjspq2q pronostilxp4f > " . $filename );
system("gzip " . $filename);

$mail             = new PHPMailer(true);
try {

  $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

  $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

  $mail->Subject    = "Pronostics4Fun - Sauvegarde de la base du ". $currentDate;

  $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test

  $mail->MsgHTML("Sauvegarde de la base !!");

  $mail->AddAddress("sebastien.dubuc@gmail.com", "Sébastien Dubuc");

  $mail->AddAttachment($filename . ".gz");

  $mail->Send();
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}

unset($mail);

?>