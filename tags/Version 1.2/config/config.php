<?php
if(!defined('VALID_ACCESS_CONFIG_')) exit('Config.php -> direct access is not allowed.');

$posLocalAddress = strpos($_SERVER['SERVER_NAME'],"192.168.");
if ($_SERVER['SERVER_NAME']=="localhost" || ($posLocalAddress!="" && $posLocalAddress==0)) {

  $pathUrl = explode("/",$_SERVER["REQUEST_URI"]);

  /* -- Config de dev */
  DEFINE ('SQL_LOGIN','sdoub');
  DEFINE ('SQL_PWD','aurelie');
  DEFINE ('SQL_DB','Pronostics4Fun');
  DEFINE ('SQL_HOST','127.0.0.1');
  DEFINE ('ROOT_SITE', "http://".$_SERVER['SERVER_NAME']."/".$pathUrl[1]);
  DEFINE ('REFRESH_LIVE_FROM_SERVER','0');
  DEFINE ('COMPETITION','3');
  DEFINE ('SHIFTED_HOUR','0');
  DEFINE ('WITH_PERF_AND_ERROR',true);
  DEFINE ('EXTERNAL_WEB_SITE','www.lfp.fr');
  DEFINE ('IS_LOCAL',true);
  date_default_timezone_set('Europe/Paris');
} else {

  /* -- Config de prod sur OVH */
  DEFINE ('SQL_LOGIN','pronostilxp4f');
  DEFINE ('SQL_PWD','jQjspq2q');
  DEFINE ('SQL_DB','pronostilxp4f');
  DEFINE ('SQL_HOST','mysql51-39.perso');
  DEFINE ('ROOT_SITE', "http://".$_SERVER['SERVER_NAME']);
  DEFINE ('REFRESH_LIVE_FROM_SERVER','0');
  DEFINE ('COMPETITION','3');
  DEFINE ('SHIFTED_HOUR','0');
  DEFINE ('WITH_PERF_AND_ERROR',false);
  DEFINE ('EXTERNAL_WEB_SITE','www.lfp.fr');
  DEFINE ('IS_LOCAL',false);
  date_default_timezone_set('Europe/Paris');
}
?>