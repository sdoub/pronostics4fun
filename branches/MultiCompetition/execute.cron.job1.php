<?php
error_reporting(E_STRICT);
chdir('./public_html/Ligue12010/');
$datetime= strftime("%A %d %B %Y %H:%M:%S",time());
$filename= strftime("CronJob - %d%m%Y",time()) . ".log";
$myFile = dirname(__FILE__)."/log/" . $filename;
$fh = fopen($myFile, 'a') or die("can't open file");
chmod($myFile,0666);
$stringData = "$datetime\t";
$stringData .=  "Call with success\t";
try {
$_cronJobId= "1";
include_once(dirname(__FILE__)."/execute.cron.job.php");

}
catch (Exception $ex) {
  $stringData.="Erreur de connexion:" . $ex->getMessage() . "\n";
}
fwrite($fh, $stringData);
fclose($fh);


?>