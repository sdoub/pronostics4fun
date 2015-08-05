<?php
require_once("begin.file.php");
$_playerKeys = $_POST["PlayerKeys"];
$_emailBody = $_POST["EmailBody"];
$_emailsubject = $_POST["Subject"];

error_reporting(E_STRICT);

date_default_timezone_set('Europe/Paris');

//require_once("lib/PHPMailer/class.phpmailer.php");

//try {

  //$mail = new PHPMailer(true);
  $mail = new PHPMailer();
  $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

  $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

  $mail->Subject    = $_emailsubject;

  $query= "SELECT players.PrimaryKey PlayerKey,
players.EmailAddress,
players.NickName
FROM playersenabled players
WHERE PrimaryKey IN (" . $_playerKeys . ")";

  $resultSet = $_databaseObject->queryPerf($query,"Get players with missing forecasts for tomorrow");
  $mail->AddAddress("admin@pronostics4fun.com", 'Pronostics4Fun - Administrateur');
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
    $mail->AddBCCAddress($rowSet["EmailAddress"], $rowSet["NickName"]);
    echo $rowSet["EmailAddress"];
  }

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
  $mail->Password   = "xxxx";            // GMAIL password

  $mail->AltBody    = "Pour visualiser le contenu de cet email, vous messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test



  $mail->MsgHTML($_emailBody);

  $mail->AddAttachment("images/Logo.png");      // attachment
  if ($mail->Send()) {
    $arr["ok"] = true;
  }
  else
  {
    $arr["ok"] = false;
  }
  //unset($mail);
//} catch (phpmailerException $e) {
//  echo $e->errorMessage(); //Pretty error messages from PHPMailer
//  $arr["Error"] = $e->errorMessage(); //Pretty error messages from PHPMailer
//} catch (Exception $e) {
//  echo $e->getMessage(); //Pretty error messages from PHPMailer
//  $arr["Error"] = $e->getMessage(); //Boring error messages from anything else!
//}


writeJsonResponse($arr);
require_once("end.file.php");
?>