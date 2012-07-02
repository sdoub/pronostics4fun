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


  $sqlTendancy = "SELECT SUM(ForecastsHome) ForecastsHome,
       SUM(ForecastsDraw) ForecastsDraw,
       SUM(ForecastsAway) ForecastsAway,
       SUM(ForecastsHome+ ForecastsDraw+ForecastsAway) NbrOfForecasts,
       (SELECT COUNT(1) FROM forecasts WHERE forecasts.MatchKey=".$_POST["matchKey"].") NbrOfPlayers
FROM
(SELECT forecasts.MatchKey,
       IF (forecasts.TeamHomeScore > forecasts.TeamAwayScore,1,0) ForecastsHome,
       IF (forecasts.TeamHomeScore = forecasts.TeamAwayScore,1,0) ForecastsDraw,
       IF (forecasts.TeamHomeScore < forecasts.TeamAwayScore,1,0) ForecastsAway
FROM forecasts
) TempStats
WHERE MatchKey=" . $_POST["matchKey"];
        $resultSetTendancy = $_databaseObject->queryPerf($sqlTendancy,"Recuperation des tendances du match");

        if(!$resultSetTendancy) return false;

        $forecastsHome = 0;
        $forecastsDraw = 0;
        $forecastsAway = 0;
        while ($rowSetTendancy = $_databaseObject -> fetch_assoc ($resultSetTendancy))
        {
          if ($rowSetTendancy['NbrOfForecasts']!=0){
            $forecastsHome = Round($rowSetTendancy['ForecastsHome'] / $rowSetTendancy['NbrOfForecasts'] * 100,2);
            $forecastsDraw = Round($rowSetTendancy['ForecastsDraw'] / $rowSetTendancy['NbrOfForecasts'] * 100,2);
            $forecastsAway = Round($rowSetTendancy['ForecastsAway'] / $rowSetTendancy['NbrOfForecasts'] * 100,2);
            $nbrOfPlayers = $rowSetTendancy['NbrOfPlayers'];
          }
        }
        unset($rowSetTendancy,$resultSetTendancy,$sqlTendancy);

        $forecastsHomeWidth = $forecastsHome;
        $forecastsDrawWidth = $forecastsDraw;
        $forecastsAwayWidth = $forecastsAway;

        if ($forecastsHome==0 && $forecastsDraw==0 && $forecastsAway==0) {
          $forecastsHomeWidth = 33;
          $forecastsDrawWidth = 33;
          $forecastsAwayWidth = 33;
        }




    $arr["getMatchStatus"]= $getMatchStatus;
    $arr["status"] = $status;
    $arr["isBonus"] = $forecast->_matchinfo['IsBonusMatch'];
    $arr["teamHomeScore"] = $_POST["teamHomeScore"];
    $arr["teamAwayScore"] = $_POST["teamAwayScore"];
    $arr["nbrOfPlayers"] = $nbrOfPlayers;

    $arr["forecastsHomeWidth"] = $forecastsHomeWidth;
    $arr["forecastsDrawWidth"] = $forecastsDrawWidth;
    $arr["forecastsAwayWidth"] = $forecastsAwayWidth;

    $arr["forecastsHome"] = $forecastsHome;
    $arr["forecastsDraw"] = $forecastsDraw;
    $arr["forecastsAway"] = $forecastsAway;

    if ($status){

      $arr["message"] = '<form id="frmForecastValidated">
<label>Vos pronostics ont été sauvegardé.</label>
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
    $arr["message"] = 'Vous avez dépassé le délai imparti pour pronostiquer!';
  }
}
$arr["perfAndError"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
writeJsonResponse($arr);

//@ destroy instance
unset($forecast);
?>