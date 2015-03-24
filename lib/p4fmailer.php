<?php

require_once(dirname(__FILE__)."/../lib/PHPMailer/class.phpmailer.php");

class P4FMailer extends PHPMailer {
// Set default variables for all new objects
//public $Host     = "mail.pronostics4fun.com";
public $Mailer   = "smtp";                         // Alternative to IsSMTP()
public $SMTPDebug = 0;                     // enables SMTP debug information (for testing)
public $SMTPAuth   = true;                  // enable SMTP authentication
public $SMTPSecure = "ssl";                 // sets the prefix to the servier
public $Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
public $Port       = 465;                   // set the SMTP port for the GMAIL server
public $Username   = "pronostics4fun@gmail.com";  // GMAIL username
public $Password   = "p4fligue1";            // GMAIL password
public $CharSet = 'UTF-8';

}

?>