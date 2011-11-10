<?php

AddScriptReference("validate");
AddScriptReference("cluetip");
AddScriptReference("scrollpane");
AddScriptReference("numberinput");
AddScriptReference("spin");
AddScriptReference("forecasts.agenda");

WriteScripts();
?>

<script type="text/javascript" src="<?php echo ROOT_SITE;?>/js/jquery.ibutton.js"></script>

<link type="text/css" href="<?php echo ROOT_SITE;?>/css/jquery.ibutton.p4f.css" rel="stylesheet" media="all" />

<style>
.title-trigger {
	cursor:pointer;
}

#sameMatchHistory {
	background : url(<?php echo ROOT_SITE;?>/images/arrows.png) no-repeat scroll top left #365F89;
	background-position : 5px -47px;
}

#matchesHistory {
	background : url(<?php echo ROOT_SITE;?>/images/arrows.png) no-repeat scroll top left #365F89;
	background-position : 5px -47px;
}

#sameMatchHistory.active {
	background : url(<?php echo ROOT_SITE;?>/images/arrows.png) no-repeat scroll top left #365F89;
	background-position : 5px -25px;
}

#matchesHistory.active {
	background : url(<?php echo ROOT_SITE;?>/images/arrows.png) no-repeat scroll top left #365F89;
	background-position : 5px -25px;
}

.container-history {
	overflow: hidden;
	clear: both;
}
</style>

<center>
<input type="submit"
	value="Voter pour le match bonus" class="buttonfield" id="btnVote" name="btnVote">
	<div style="height:20px;">&nbsp;</div>
</center>
<div style="margin-left:40px;">
<label for="displayHelp" style="font-weight:bold;color:#FFFFFF;float:left;"><?php echo __encode("Afficher l'aide aux pronostics : ")?></label><input type="checkbox" id="displayHelp" name="displayHelp" checked="checked"></input>

</div>
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
(SELECT COUNT(1) FROM forecasts WHERE forecasts.MatchKey=matches.PrimaryKey) NbrOfPlayers,
DATEDIFF(matches.ScheduleDate,NOW()) RemainingDays
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.Status IN (1,2)
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=" . $_authorisation->getConnectedUserKey() . "
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
WHERE matches.ScheduleDate>=NOW()
 ORDER BY matches.ScheduleDate,matches.PrimaryKey";


//WHERE UNIX_TIMESTAMP(matches.ScheduleDate)>=" . time() . " AND matches.ScheduleDate<=NOW()+INTERVAL 20 DAY
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
	    setlocale(LC_TIME, "fr_FR");
	    $scheduleFormattedDate = __encode(strftime("%A %d %B %Y",$rowSet['ScheduleDate']));

	    $status ="";
	    if ($rowSet["RemainingDays"]==0) {
          $status = __encode("D�but aujourd'hui");
        } else if ($rowSet["RemainingDays"]==1) {
          $status = __encode("D�but demain");
        } else {
          $status = __encode("D�but dans ") . $rowSet["RemainingDays"] . " jours";
        }

	    echo '<tr class="day"
      	    style="">
      	  <td colspan="10">' . $rowSet["GroupName"] . '&nbsp;->&nbsp;' . $scheduleFormattedDate . '<span style="float:right;margin-right:10px;font-size:10px;background:#365F89;">'.$status.'</span></td>
      	</tr>';
	  }

	  echo '<tr class="match" match-key="' . $rowSet['MatchKey'] . '" status="' . $rowSet["LiveStatus"] . '">
      	  <td width="20px"></td><td class="time' . $rowSet["IsBonusMatch"] . '">' . strftime("%H:%M",$rowSet['ScheduleDate']) . '</td>
      	  <td class="teamHome">' . $rowSet['TeamHomeName'] . '</td>
      	  <td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamHomeKey'] . '.png" width="30px" height="30px"></img></td>';

      echo '<td width="50px;">

		<input rel="get.match.stats.detail.php?MatchKey='. $rowSet['MatchKey'] .'"
			type="text" class="textfield" id="TeamHomeScore' . $rowSet['TeamHomeKey'] . '" value="' .$rowSet["ForecastTeamHomeScore"] . '"
			name="TeamHomeScore" maxlength="1" size="3em" /></td>
		<td width="50px;"><input rel="get.match.stats.detail.php?MatchKey='. $rowSet['MatchKey'] .'"
			type="text" class="textfield" id="TeamAwayScore' . $rowSet['TeamHomeKey'] . '" value="' .$rowSet["ForecastTeamAwayScore"] . '"
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
      	  echo "<td style='width:100px;'><div class='detail2' rel='get.match.players.php?MatchKey=". $rowSet['MatchKey'] ."'>" . $rowSet["NbrOfPlayers"] . " participants</div></td>";
      echo '</tr>';

	  $scheduleMonth = strftime("%m",$rowSet['ScheduleDate']);
	  $scheduleDay = strftime("%d",$rowSet['ScheduleDate']);
      $cuurentGroup=$rowSet['GroupName'];
	}
	unset($rowSet,$resultSet,$sql);
?>
  </table>
  </div>
  <div id="footerForecast" style="text-align:right;margin-right:30px;" ><input type="submit"
	value="Valider" class="buttonfield" id="btn" name="btn"></div>
  </form>

  <script>
		var _matchKey="";
  $(function() {
	//*/ submit handler
	$("#frmForecast").submit( function() {

		$("tr").each(function (index) {
			try {
			if ($(this).attr('match-key')) {

				var matchKey = $(this).attr('match-key');
				_matchKey =matchKey;
				var teamHomeScore ="";
				$(this).find("input[name=TeamHomeScore]").each (function (index) {
					teamHomeScore= $(this).val();
				});
				var teamAwayScore ="";
				$(this).find("input[name=TeamAwayScore]").each (function (index) {
					teamAwayScore= $(this).val();
				});
				if (teamAwayScore && teamHomeScore){

					$(this).find("td:eq(0)").html("<img src='<?php echo ROOT_SITE;?>/images/wait.gif' />");

					$.ajax({
						type: "POST",
						url: 'submodule.post.php?SubModule=3',
						  dataType: 'json',
						  data: { matchKey: matchKey, teamHomeScore: teamHomeScore, teamAwayScore: teamAwayScore},
						  success: function (data) {
							  if(data.status==true){
								$("tr[match-key="+matchKey+"]").find("td:eq(0)").fadeOut('slow', function(){
									$(this).html("<img style='width:20px;height:20px;' src='<?php echo ROOT_SITE;?>/images/ok.png' />").fadeIn();
								}).html();
							  }
								else {
									$("tr[match-key="+matchKey+"]").find("td:eq(0)").html("<img title='"+data.message+"' style='width:20px;height:20px;' src='<?php echo ROOT_SITE;?>/images/error.png' />");
								}
						  }
											  ,
						  error: function (XMLHttpRequest, textStatus, errorThrown)
						  {
													$.log(XMLHttpRequest);
													$.log(textStatus);
													$.log(errorThrown);
												}

						});

									}
			}
			}
			catch(ex) {$.log(ex);}
		});
		return false;
	});

	function callbackForecast(data) {

		if(data.status==true){

			$("tr[match-key="+_matchKey+"]").find("td:eq(0)").html("<img src='<?php echo ROOT_SITE;?>/images/wait.gif' />");

			$(waitId).fadeOut('slow', function(){
					$(wrapperId).slideUp('slow',function(){
						$(this).html(data.message).slideDown('fast',function(){
							$.log('attach submit');
							// */ submit handler
							$("#frmForecastValidated").submit( function() {


								$.closePopupLayer();
								try {
								$('div[match-key='+_matchKey+']').removeClass("ToBeDone" + data.isBonus);
								$('div[match-key='+_matchKey+']').addClass("AlreadyDone" + data.isBonus);
								}
								catch (exception)
								{
									$.log(exception);
								}
								try {
									$('a[match-key='+_matchKey+']').html(data.teamHomeScore + " - " + data.teamAwayScore);
								}
								catch (exception)
								{
									$.log(exception);
								}
								return false;
							});

							$("#btnClose").focus();
							// */
						});
					});
				}).html();

		} else {
			$(waitId).html(data.message).slideDown('fast', function(){
				$(teamHomeScoreId).focus();
			});
		}
	}
	$("input[name=TeamHomeScore]").numberInput();
	$("input[name=TeamAwayScore]").numberInput();
  });
   $(document).ready(function($) {
	   $.spin.imageBasePath = '<?php echo ROOT_SITE;?>/js/images/';

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
	   	$(":input[rel]").spin({max:9, min:0,btnCss: {cursor: 'pointer', padding: 0, margin: '5px 0px 0px 0px', verticalAlign: 'top'}});


	   	$("#displayHelp").iButton({
	   	    labelOn: "Oui"
	   	     , labelOff: "Non",
	   	  change : function ($input){
	   		if($input.is(":checked")) {
				attachToolTip ();
			}
			else {
				$(":input[rel]").cluetip('destroy');
			}
	   	}
	   	   });
	function attachToolTip () {

		$(":input[rel]").cluetip(
				{positionBy:'fixed',
					showTitle:false,
					width:560,
					ajaxCache:true,
					cluetipClass:'p4f',
					arrows:false,
					sticky:true,
					activation:'focus',
					topOffset: 40,
					leftOffset: -280,
					dropShadow: false,
					closeText:'<img src="<?php echo ROOT_SITE;?>/images/close.png" />',
					onShow:function (ct, ci) {
						$('#matchesHistoryContainer').slideDown();
						$('#sameMatchHistoryContainer').hide();
						$('#matchesHistory').addClass('active');
						$('#sameMatchHistory').removeClass('active');
					}
				});
	}

	attachToolTip();

	$("#btnVote").click(function() {

		$.openPopupLayer({
			name: "VotePopup",
			width: "500",
			height: "500",
			url: "submodule.loader.php?SubModule=14"
		});
	});

	$("#matchesHistory").live ('click',function () {
		if ($('#matchesHistoryContainer').is(':hidden')) {
		    $('#matchesHistoryContainer').slideDown();
		    $('#sameMatchHistoryContainer').slideUp();
			$('#matchesHistory').addClass('active');
			$('#sameMatchHistory').removeClass('active');
		} else {
			$('#matchesHistoryContainer').slideUp();
			$('#sameMatchHistoryContainer').slideUp();
			$('#sameMatchHistory').removeClass('active');
			$('#matchesHistory').removeClass('active');
		}
		return false; //Prevent the browser jump to the link anchor
	});

	$("#sameMatchHistory").live ('click',function () {
		if ($('#sameMatchHistoryContainer').is(':hidden')) {
     		$('#sameMatchHistoryContainer').slideDown();
  	    	$('#matchesHistoryContainer').slideUp();
			$('#sameMatchHistory').addClass('active');
			$('#matchesHistory').removeClass('active');
		} else {
			$('#sameMatchHistoryContainer').slideUp();
			$('#matchesHistoryContainer').slideUp();
			$('#sameMatchHistory').removeClass('active');
			$('#matchesHistory').removeClass('active');
		}

	return false; //Prevent the browser jump to the link anchor
	});

  });



  </script>