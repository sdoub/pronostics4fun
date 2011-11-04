<?php

//@ validate inclusion
define('VALID_ACCESS_FORECAST_',		true);

//@ load dependency files
require_once('classes/forecast.php');

//@ new acl instance
$forecast = new Forecast;

//@ session not active
if($_SERVER['REQUEST_METHOD']=='GET')
{
  $forecast->getMatchInfo($_GET["matchKey"],$_authorisation->getConnectedUserKey());
  //@ first load
  $arr["status"] = false;
  $arr["message"] = $forecast->form();
  $arr["timeExpired"] = $forecast->_matchinfo['ScheduleDate']<time();
  //echo json_encode() '{"status":false,"message":"'.str_replace('"',"'",$acl->form()).'"}';
}
else
{
  $getMatchStatus = $forecast->getMatchInfo($_POST["matchKey"],$_authorisation->getConnectedUserKey());
  if ($forecast->_matchinfo['ScheduleDate']>time()) {
    //$forecast->save($_POST["teamHomeScore"],$_POST["teamHomeScore"],$_authorisation->getConnectedUserKey());
    $status = $forecast->save($_POST["teamHomeScore"],$_POST["teamAwayScore"],$_authorisation->getConnectedUserKey());

    $arr["getMatchStatus"]= $getMatchStatus;
    $arr["status"] = $status;
    $arr["isBonus"] = $forecast->_matchinfo['IsBonusMatch'];
    $arr["teamHomeScore"] = $_POST["teamHomeScore"];
    $arr["teamAwayScore"] = $_POST["teamAwayScore"];
    if ($status){

      $arr["message"] = '<form id="frmForecastValidated">
<label>Vos pronostics ont &eacute;t&eacute; sauvegard&eacute;.</label>
<div id="footerForecast" ><input type="submit"
	value="Fermer" class="buttonfield" id="btnClose" name="btnClose"></div>
</form>';

    }
    else
    {
      $arr["message"] = 'Une erreur est survenue durant la sauvegarde!';
    }
  }
  else
  {
    $arr["status"] = false;
    $arr["message"] = __encode('Vous avez dépassé le délai imparti pour pronostiquer!');
  }
}
$arr["perfAndError"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
echo json_encode($arr);

//@ destroy instance
unset($forecast);
?>