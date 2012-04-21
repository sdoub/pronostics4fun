<?php
define('VALID_ACCESS_CONFIG_',		true);
define('BASE_PATH',realpath('.'));
define('BASE_URL', dirname($_SERVER["SCRIPT_NAME"]));

include_once(BASE_PATH . "/lib/mobile.detect.php");

$uagent_info = new uagent_info();

if (ROOT_SITE=="http://www.pronostics4fun.com") {
  ini_set('session.save_path','/homez.462/pronostilx/www/sessions');
}
session_start();

include_once(BASE_PATH . "/config/config.php");

//session_cache_expire(60*60*1); //1 hour
//session_set_cookie_params(60*60*1); //1 hour

define('VALID_ACCESS_DATABASE_',		true);


include_once(BASE_PATH . "/classes/database.php");

$_dbOptions = array (
    'ERROR_DISPLAY' => false
    );

$_databaseObject = new mysql (SQL_HOST, SQL_LOGIN,  SQL_PWD, SQL_DB, $_dbOptions);

include_once(BASE_PATH . "/lib/functions.php");

saveStartTime();



// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_ALL);

//@ validate inclusion
define('VALID_ACCESS_AUTHENTICATION_',		true);

//@ load dependency files
include_once(BASE_PATH . '/classes/authentication.php');
//@ new acl instance
$_authorisation = new Authorization;

//@ if logoff
if(isset($_GET['logoff']))
{
  $_authorisation->signout();
  //header("location:index.php");
}

if ($_databaseObject ->isConnected) {
//@check session status
$_isAuthenticated = $_authorisation->IsAuthenticated();
}
else {
  $_isAuthenticated = false;
}

//@ is authorized?
if($_isAuthenticated) {
	$_authorisation-> renew_session();
	require_once("Visite.php");
}
else
{
   //header("location:index.php");	//@ redirect
}



?>