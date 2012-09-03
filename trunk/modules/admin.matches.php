<?php
AddScriptReference("validate");
AddScriptReference("cluetip");
AddScriptReference("scrollpane");
AddScriptReference("numberinput");
AddScriptReference("admin.matches");

WriteScripts();
?>


<form id='frmForecast'><div class="flexcroll" style='overflow: auto; width: 860px; height: 450px;margin:auto;'>

<table width="100%">
<?php

	$sql = "SELECT matches.PrimaryKey MatchKey,
	groups.Description GroupName,
	TeamHome.PrimaryKey TeamHomeKey,
       TeamHome.Name TeamHomeName,
forecasts.TeamHomeScore ForecastTeamHomeScore,
       TeamAway.PrimaryKey TeamAwayKey,
       TeamAway.Name TeamAwayName,
forecasts.TeamAwayScore ForecastTeamAwayScore,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.Status,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore,
IFNULL(results.livestatus,0) LiveStatus,
matches.IsBonusMatch,
(SELECT COUNT(1) FROM forecasts WHERE forecasts.MatchKey =matches.PrimaryKey) NbrOfPlayers
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=" . $_authorisation->getConnectedUserKey() . "
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
 ORDER BY matches.ScheduleDate DESC,matches.PrimaryKey";
	$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

	$scheduleMonth = "00";
	$scheduleDay = "00";
	$cuurentGroup="";
	while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
	{
	  $tempScheduleMonth=strftime("%m",$rowSet['ScheduleDate']);
	  $tempScheduleDay=strftime("%d",$rowSet['ScheduleDate']);
	  if (!($scheduleMonth==$tempScheduleMonth && $scheduleDay==$tempScheduleDay && $rowSet["GroupName"]==$cuurentGroup))
	  {
	    $scheduleFormattedDate = __encode(strftime("%A %d %B %Y",$rowSet['ScheduleDate']));
	    echo '<tr class="day"
      	    style="">
      	  <td colspan="10">' . $rowSet["GroupName"] . '&nbsp;->&nbsp;' . $scheduleFormattedDate . '</td>
      	</tr>';
	  }

	  echo '<tr class="match" match-key="' . $rowSet['MatchKey'] . '" status="' . $rowSet["LiveStatus"] . '">
      	  <td width="20px"></td><td class="time' . $rowSet["IsBonusMatch"] . '">' . strftime("%H:%M",$rowSet['ScheduleDate']) . '</td>
      	  <td class="teamHome">' . $rowSet['TeamHomeName'] . '</td>
      	  <td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamHomeKey'] . '.png" width="30px" height="30px"></img></td>';

      echo '<td width="30px;">

		<input READONLY rel="get.live.match.detail.php?MatchKey='. $rowSet['MatchKey'] .'"
			type="text" class="textfield" id="TeamHomeScore' . $rowSet['TeamHomeKey'] . '" value="' .$rowSet["TeamHomeScore"] . '"
			name="TeamHomeScore" maxlength="1" size="3em" /></td>
		<td width="30px;"><input READONLY rel="get.live.match.detail.php?MatchKey='. $rowSet['MatchKey'] .'"
			type="text" class="textfield" id="TeamAwayScore' . $rowSet['TeamHomeKey'] . '" value="' .$rowSet["TeamAwayScore"] . '"
			name="TeamAwayScore" maxlength="1"
			size="3em" /></td>
			';
      	  echo '<td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamAwayKey'] . '.png"></img></td>
      	  <td class="teamAway">' . $rowSet['TeamAwayName'] . '</td>';


 $sqlTendancy = "SELECT SUM(ForecastsHome) ForecastsHome,
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
WHERE MatchKey=" . $rowSet['MatchKey'];
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


      	  echo '<td rel="get.match.stats.detail.php?MatchKey='. $rowSet['MatchKey'] .'">

      	  <table width="200px;font-size:9px;">
			<tr>
				<td id="tendancyHome"  width="' . $forecastsHomeWidth . '%">' . $forecastsHome .'%</td>
				<td id="tendancyNull"  width="' . $forecastsDrawWidth . '%">' . $forecastsDraw .'%</td>
				<td id="tendancyAway"  width="' . $forecastsAwayWidth . '%">' . $forecastsAway .'%</td>
			</tr>
		</table>

      	  </td>';
      	  echo "<td><div class='detail2' rel='get.match.players.php?MatchKey=". $rowSet['MatchKey'] ."'>" . $rowSet["NbrOfPlayers"] . " participants</div></td>";
      echo '<td><input type="button" value="Refresh" class="buttonfield" match-key="'. $rowSet['MatchKey'] .'" id="btn'. $rowSet['MatchKey'] .'" name="btn'. $rowSet['MatchKey'] .'"/></td>';
      	  echo '</tr>';

	  $scheduleMonth = strftime("%m",$rowSet['ScheduleDate']);
	  $scheduleDay = strftime("%d",$rowSet['ScheduleDate']);
      $cuurentGroup=$rowSet['GroupName'];
	}
	unset($rowSet,$resultSet,$sql);
?>
  </table>
  </div>
  </form>

  <script>
		var _matchKey="";


   $(document).ready(function($) {
	   $("div.flexcroll").jScrollPane({
			showArrows: true,
			horizontalGutter: 10
		});
	   $("div.detail2[rel]").css("cursor","help");
	   $("div.detail2[rel]").cluetip(
				{positionBy:'fixed',
					showTitle:false,
					width:350,
					ajaxCache:false,
					cluetipClass:'p4f',
					arrows:false,
					sticky:false,
					topOffset: 30,
					leftOffset: -250
	});

		$(":input[rel]").cluetip(
				{positionBy:'fixed',
					showTitle:false,
					width:560,
					ajaxCache:true,
					cluetipClass:'p4f',
					arrows:false,
					sticky:false,
					activation:'focus',
					topOffset: 40,
					leftOffset: -280
	});
		$("input[name=TeamHomeScore]").numberInput();
		$("input[name=TeamAwayScore]").numberInput();

		$("input.buttonField").click (function () {
			var matchKey = $(this).attr('match-key');
			$(this).parent();

			var addParameter = "";
			if (!confirm ("Voulez-vous rafarichir les données à partir de la source ?")) {
				addParameter += "&DontGetMatchInfo=true"
			}

			if (!confirm ("Voulez-vous recalculer les résultats ?")) {
				addParameter += "&DontComputeScore=true"
			}

			$.ajax({
				type: "POST",
				url: "manual.refresh.match.php?MatchKey="+matchKey+addParameter,
				data: { },
				dataType: 'json',
				success: function (data){
					var matchKey = data.MatchData.MatchKey;
					$("tr[match-key="+matchKey+"] input").val(data.MatchData.TeamHomeScore)
					},
				error: callbackPostError
			});

			return false;
		});

  });
	function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
	{
		$.log(XMLHttpRequest);
		$.log(textStatus);
		$.log(errorThrown);
	}
  </script>