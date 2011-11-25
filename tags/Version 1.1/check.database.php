<?php
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_ALL);

DEFINE ('SQL_LOGIN','a6248919_p4f');
DEFINE ('SQL_PWD','aurelie040697');
DEFINE ('SQL_DB','a6248919_p4f');
DEFINE ('SQL_HOST','mysql7.000webhost.com');
DEFINE ('ROOT_SITE', "http://pronostics4fun.com");
DEFINE ('REFRESH_LIVE_FROM_SERVER','0');
DEFINE ('COMPETITION','2');
DEFINE ('SHIFT_HOUR','0');
DEFINE ('WITH_PERF_AND_ERROR',false);
date_default_timezone_set('Europe/Paris');

//define('VALID_ACCESS_DATABASE_',		true);
//require_once("classes/database.php");

$datetime= strftime("%A %d %B %Y %H:%M:%S",time());
$filename= strftime("%d%m%Y",time()) . ".log";
$myFile = dirname(__FILE__)."/log/" . $filename;
$fh = fopen($myFile, 'a') or die("can't open file");
chmod($myFile,0666);
$stringData = "$datetime\t";

$_dbOptions = array (
    'ERROR_DISPLAY' => true
    );

$error ="Success";
try {
$_databaseObject = mysql_connect (SQL_HOST,
                     SQL_LOGIN,
                      SQL_PWD);
}
catch (Exception $ex) {$error = "failed -> Error: (" . mysql_errno() . ") " . mysql_error();}
$stringData .= $error . "\n";
fwrite($fh, $stringData);
fclose($fh);
echo $stringData;
//new mysql (SQL_HOST, SQL_LOGIN,  SQL_PWD, SQL_DB, $_dbOptions);

mysql_close($_databaseObject);
//$_databaseObject -> close ();

unset($_databaseObject);



?>