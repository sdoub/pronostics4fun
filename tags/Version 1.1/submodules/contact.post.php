<?php

//@ validate inclusion
//define('VALID_ACCESS_SENDEMAIL_',		true);

//@ load dependency files
//require_once('classes/sendemail.php');


require_once("lib/PHPMailer/class.phpmailer.php");

//@ new acl instance
//@ session not active
if($_SERVER['REQUEST_METHOD']=='GET')
{
  //$forecast->getMatchInfo($_GET["matchKey"],$_authorisation->getConnectedUserKey());
  //@ first load
  $arr["status"] = false;
  $arr["message"] = __encode('<form id="frmContact">
<div>
<label >De : ' . $_authorisation->getConnectedUser() . '</label>
<label style="padding-top:15px;padding-bottom:15px">A : contact@pronostics4fun.com</label>
<label >Message</label>
<textarea rows="10" name="body" id="body" class="textfield" type="text"></textarea>
<input name="btn" id="btn" class="buttonfield" value="Envoyer" type="submit">
</div>
</form>');
  //$arr["timeExpired"] = $forecast->_matchinfo['ScheduleDate']<time();
  //echo json_encode() '{"status":false,"message":"'.str_replace('"',"'",$acl->form()).'"}';
}
else
{
  $mail             = new PHPMailer(true);
  try {
  //  $cMail = new cPHPezMail();

  //Don't try to add invalid e-mail address format
  //  $cMail->SetFrom($_authorisation->getConnectedUserInfo('EmailAddress'), $_authorisation->getConnectedUser());
  //
  //  $cMail->AddTo('contact@pronostics4fun.com', 'Pronostics4Fun - Contact');
  //
  //  $cMail->SetSubject('Email From web site Pronostics4Fun ...');

  //  $imageHeader = '<img border="0" src="data:image/png;base64,' ;
  //  $filename = "images/Logo.png"; // fichier image a inclure
  //  $linesz = filesize( $filename )+1; // retrieve la taille du fichier
  //  $fp = fopen( $filename, 'r' ); // ouvre le fichier
  //
  //  $imageHeader .= chunk_split( base64_encode(fread( $fp, $linesz)));
  //  fclose( $fp ); // ferme le fichier image
  //  $imageHeader .= '"/>';

  $mail->SetFrom($_authorisation->getConnectedUserInfo('EmailAddress'), $_authorisation->getConnectedUser());

  $mail->AddReplyTo($_authorisation->getConnectedUserInfo('EmailAddress'), $_authorisation->getConnectedUser());

  $mail->Subject    = "Email From web site Pronostics4Fun ...";

  $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test

  $mail->MsgHTML(__encode('<html><body><div>
  <img src="'. ROOT_SITE.'/images/Logo.png" /><p>' . htmlspecialchars($_POST['body'], ENT_QUOTES) . '</p>
  </div>
  </body>
  </html>'));

  $mail->AddAddress('contact@pronostics4fun.com', 'Pronostics4Fun - Contact');

  $mail->AddAttachment("images/Logo.png");      // attachment

  $mail->Send();
  } catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
  } catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
  }
  //  $cMail->SetBodyHTML(__encode('<html><body><div>
  //  <img src="'. ROOT_SITE.'/images/Logo.png" /><p>' . htmlspecialchars($_POST['body'], ENT_QUOTES) . '</p>
  //  </div>
  //  </body>
  //  </html>'));
  //
  //  $cMail->SetCharset('windows-1252');
  //  $cMail->SetEncodingBit("8bit");

  //$to      = 'contact@pronostics4fun.com';
  //$subject = 'Email From web site Pronostics4Fun ...';
  //$message = "This is a multi-part message in MIME format.\r\n--==Multipart_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X\r\nContent-Type: multipart\/alternative;\r\n boundary=\"==Alternative_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X\"\r\n\r\n--==Alternative_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X\r\nContent-Type: text\/html; charset=TIS-620\r\nContent-Transfer-Encoding: 8bit\r\n\r\n<HTML><DIV>\r\n  <img src=\"http:\/\/pronostics4fun.netii.net\/CDM2010\/images\/logo.png\" border=\"0\"><p>rrrrr<\/p>\r\n  <\/DIV>\r\n  <\/HTML>\r\n--==Alternative_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X--\r\n\r\n--==Multipart_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X--\r\n";
  //$headers = "MIME-Version: 1.0\r\nX-Mailer: cPHPezMail,1.2\r\nFrom: sdoub <sebastien.dubuc@gmail.com>\r\nContent-Type: multipart\/mixed;\r\n boundary=\"==Multipart_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X\"";
  //$arr["emailLog3"] = mail($to, $subject, $message, $headers);
  //
  //send your e-mail
  //  $emailResponse = $cMail->Send();
  unset($mail);
//  $arr["emailLog"] =$emailResponse;
  $arr["status"] = false;

  $arr["message"] = __encode('<form id="frmContactValidated">
<label>Votre email a été envoyé.</label>
<div id="footerContact" ><input type="submit"
	value="Fermer" class="buttonfield" id="btnClose" name="btnClose"></div>
</form>');
  //  }
  //  else
  //  {
  //    $arr["emailLog"] =$emailResponse;
  //    $arr["status"] = false;
  //    $arr["message"] = __encode("Une erreur est survenue durant l'envoi!");
  //
  //  }


}
$arr["perfAndError"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
echo json_encode($arr);

//@ destroy instance

?>