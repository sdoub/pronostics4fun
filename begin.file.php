<?php
define('VALID_ACCESS_CONFIG_',		true);
define('BASE_PATH',realpath('.'));
define('BASE_URL', dirname($_SERVER["SCRIPT_NAME"]));
require __DIR__ . '/vendor/autoload.php';
// setup Propel
require_once __DIR__ . '/generated-conf/config.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$defaultLogger = new Logger('defaultLogger');
$filedate=strftime("%Y%m%d",time());
$defaultLogger->pushHandler(new StreamHandler('log/app-'.$filedate.'.log', Logger::DEBUG));
$serviceContainer->setLogger('defaultLogger', $defaultLogger);

if ($_SERVER['PHP_SELF']!="/index.php")
	$defaultLogger->addInfo('Page: '.$_SERVER['PHP_SELF']);

//$con = \Propel\Runtime\Propel::getWriteConnection('default');
//$con->useDebug(true);
//	echo \Propel\Runtime\Propel::getConnection()->getLastExecutedQuery();

//include_once(BASE_PATH . "/lib/mobile.detect.php");
//$uagent_info = new uagent_info();

session_start();


include_once(BASE_PATH . "/config/config.php");

define('VALID_ACCESS_DATABASE_',		true);

include_once(BASE_PATH . "/classes/database.php");

$_dbOptions = array (
    'ERROR_DISPLAY' => false
    );

$_databaseObject = new mysql (SQL_HOST, SQL_LOGIN,  SQL_PWD, SQL_DB, $_dbOptions);

include_once(BASE_PATH . "/lib/functions.php");

saveStartTime();

// @ error reporting setting  ( modify as needed )
//ini_set("display_errors", 0);
//error_reporting(NULL);

//@ validate inclusion
define('VALID_ACCESS_AUTHENTICATION_',		true);

//@ load dependency files
include_once(BASE_PATH . '/classes/authentication.php');
//@ new acl instance
$_authorisation = new Authorization;

if(isset($_GET['login']) && isset($_GET['pwd']))
{
	$_authorisation->signin($_GET['login'],$_GET['pwd'],false);
}

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

setlocale (LC_TIME, 'fr_FR.utf8','fra');


//TODO: Récupérer l'information de la base de données
//TODO: Créer une session pour stocker la session courante
//if (isset($_GET["BetaEuro"]) || isset($_GET["BetaCDM"])) {
	DEFINE ('COMPETITION','11');
	$_themePath = "/themes/LIGUE1";
	$_competitionType =1;
	$_competitionName="Ligue 1";
/*} else {
	DEFINE ('COMPETITION','9');
	$_themePath = "/themes/LIGUE1";
	$_competitionType =1;
	$_competitionName="Ligue 1";
} */

//echo $_SESSION['exp_user']['expires'];
//echo "<br/>";
//echo time();
