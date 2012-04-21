<?php
//@ validate inclusion
if(!defined('VALID_ACCESS_FORECAST_')) exit(basename(__FILE__) . ' -> direct access is not allowed.');

class Forecast
{
  var $_matchinfo = array ();


  public function getMatchInfo ($matchKey, $playerKey)
  {
    global $_databaseObject;

    $sql = "SELECT matches.PrimaryKey MatchKey,
teamHome.PrimaryKey TeamHomeKey,
teamHome.Name TeamHomeName,
forecasts.TeamHomeScore TeamHomeScore,
teamAway.PrimaryKey TeamAwayKey,
teamAway.Name TeamAwayName,
forecasts.TeamAwayScore TeamAwayScore,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.IsBonusMatch
FROM `matches` inner join teams teamHome on matches.TeamHomeKey=teamHome.PrimaryKey
               inner join teams teamAway on matches.TeamAwayKey=teamAway.PrimaryKey
               left join forecasts on matches.PrimaryKey=forecasts.MatchKey AND PlayerKey=$playerKey
WHERE matches.PrimaryKey = $matchKey
         ";
    $resultSet = $_databaseObject->queryPerf($sql,"Recuperation des infos du match");

    if(!$resultSet) return false;

    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
    {
      $this->_matchinfo = $rowSet;
      $return = true;
    }
    unset($rowSet,$resultSet,$sql);
    return $return;
  }


  public function form()
  {
    if (!empty($this->_matchinfo))
    {
      global $_databaseObject;
      $currentDate = time();
      if ($this->_matchinfo['ScheduleDate']>$currentDate)
      {
        $matchKey = $this->_matchinfo['MatchKey'];
        $sql = "SELECT SUM(ForecastsHome) ForecastsHome,
       SUM(ForecastsDraw) ForecastsDraw,
       SUM(ForecastsAway) ForecastsAway,
       SUM(ForecastsHome+ ForecastsDraw+ForecastsAway) NbrOfForecasts
FROM
(SELECT forecasts.MatchKey,
       IF (forecasts.TeamHomeScore > forecasts.TeamAwayScore,1,0) ForecastsHome,
       IF (forecasts.TeamHomeScore = forecasts.TeamAwayScore,1,0) ForecastsDraw,
       IF (forecasts.TeamHomeScore < forecasts.TeamAwayScore,1,0) ForecastsAway
FROM forecasts
) TempStats
WHERE MatchKey=$matchKey
         ";
        $resultSet = $_databaseObject->queryPerf($sql,"Recuperation des tendances du match");

        $forecastsHome = 0;
        $forecastsDraw = 0;
        $forecastsAway = 0;
        while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
        {
          if ($rowSet['NbrOfForecasts']!=0){
            $forecastsHome = Round($rowSet['ForecastsHome'] / $rowSet['NbrOfForecasts'] * 100,2);
            $forecastsDraw = Round($rowSet['ForecastsDraw'] / $rowSet['NbrOfForecasts'] * 100,2);
            $forecastsAway = Round($rowSet['ForecastsAway'] / $rowSet['NbrOfForecasts'] * 100,2);
          }
        }
        unset($rowSet,$resultSet,$sql);

        setlocale(LC_TIME, "fr_FR");
        $scheduleFormattedDate = __encode(strftime("%A %d %B %Y - %Hh%M",$this->_matchinfo['ScheduleDate']));

        $forecastsHomeWidth = $forecastsHome;
        $forecastsDrawWidth = $forecastsDraw;
        $forecastsAwayWidth = $forecastsAway;

        if ($forecastsHome==0 && $forecastsDraw==0 && $forecastsAway==0) {
          $forecastsHomeWidth = 33;
          $forecastsDrawWidth = 33;
          $forecastsAwayWidth = 33;
        }


        $htmlForm =	'<form id="frmForecast">
<div id="tendancyHearder"><label>Tendance des pronostics</label></div>
<div id="tendancyContent" align="center" >
<table >
	<tr>
		<td id="tendancyHome"  width="' . $forecastsHomeWidth . '%">' . $forecastsHome .'%</td>
		<td id="tendancyNull"  width="' . $forecastsDrawWidth . '%">' . $forecastsDraw .'%</td>
		<td id="tendancyAway"  width="' . $forecastsAwayWidth . '%">' . $forecastsAway .'%</td>
	</tr>
</table>
</div>
<div id="forecast">
<div id="scheduleDate' . $this->_matchinfo["IsBonusMatch"] . '"><label>' . $scheduleFormattedDate . '</label></div>
<table>
	<tr>
		<td><label> <img
			src="'.ROOT_SITE.'/images/teamFlags/'.$this->_matchinfo['TeamHomeKey'].'.png">' . $this->_matchinfo['TeamHomeName'] . '
		</label></td>
		<td >

		<input
			type="text" class="textfield" id="TeamHomeScore"
			name="TeamHomeScore" maxlength="1" size="3em" value="'.$this->_matchinfo['TeamHomeScore'].'"/></td>

		<td ><input
			type="text" value="'.$this->_matchinfo['TeamAwayScore'].'" class="textfield" id="TeamAwayScore"
			name="TeamAwayScore" maxlength="1"
			size="3em" /></td>
		<td ><label><img
			src="'.ROOT_SITE.'/images/teamFlags/'.$this->_matchinfo['TeamAwayKey'].'.png">' . $this->_matchinfo['TeamAwayName'] . '</label></td>
	</tr>
</table>
</div>
<div id="footerForecast" ><input type="submit"
	value="Valider" class="buttonfield" id="btn" name="btn"></div>
</form>';

      }
      else
      {
        $htmlForm = '<form id="frmForecastTooLate">
<label>Vous ne pouvez plus modifier vos pronostics car le temps imparti est &eacute;coul&eacute;.</label>
<div id="footerForecast" ><input type="submit"
	value="Fermer" class="buttonfield" id="btnClose" name="btnClose"></div>
</form>';

      }
    }
    else
    {
      $htmlForm = "une erreur a ete provoque";
    }
    return $htmlForm;
  }

  public function save ($teamHomeScore, $teamAwayScore, $playerKey)
  {
    global $_databaseObject;
    $return = false;
    if(strlen($teamHomeScore)>0 && strlen($teamAwayScore)>0 && strlen($playerKey)>0)
    {
      if ($teamHomeScore!=$this->_matchinfo["TeamHomeScore"] || $teamAwayScore!=$this->_matchinfo["TeamAwayScore"]) {
        $sql = "REPLACE INTO forecasts (MatchKey, PlayerKey, `TeamHomeScore`, `TeamAwayScore`)";
        $sql .= "VALUES (".$this->_matchinfo['MatchKey'].",".$playerKey.",".$teamHomeScore.", ".$teamAwayScore.")";

        if(!$_databaseObject->queryPerf($sql,"Insertion des pronostics"))
        {
          return false;
        }
        else
        {
          $return = true;
        }


        unset($sql);
      }
      else {
        $return = true;
      }
    }
    return $return;
  }
}
?>