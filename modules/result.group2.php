<?php

if (isset($_GET["GroupKey"])) {
  $query = "SELECT PrimaryKey GroupKey, Description, DayKey FROM groups WHERE groups.PrimaryKey= " . $_GET["GroupKey"];
} else {
  $query = "SELECT PrimaryKey GroupKey, Description, DayKey FROM groups WHERE groups.PrimaryKey= (SELECT MAX(PrimaryKey) FROM groups LastGroup WHERE IsCompleted=1 AND CompetitionKey=" . COMPETITION . ")";
}

$resultSet = $_databaseObject -> queryPerf ($query, "Get group info");

$rowSet = $_databaseObject -> fetch_assoc ($resultSet);

$_groupDescription=$rowSet["Description"];
$_groupKey= $rowSet["GroupKey"];
$_lastCompletedDayKey= $rowSet["DayKey"];

if (!$_groupKey) {
  $query = "SELECT PrimaryKey GroupKey, Description, DayKey FROM groups WHERE groups.CompetitionKey= " . COMPETITION . " AND DayKey=1";
  $resultSet = $_databaseObject -> queryPerf ($query, "Get group info");
  $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
  $_groupDescription=$rowSet["Description"];
  $_groupKey= $rowSet["GroupKey"];
  $_lastCompletedDayKey= $rowSet["DayKey"];
}

$sql = "SELECT MAX(DayKey) DayKey FROM groups WHERE CompetitionKey=" . COMPETITION . " AND (IsCompleted='1' OR EXISTS (SELECT 1 FROM matches INNER JOIN results ON matches.PrimaryKey=results.MatchKey WHERE matches.GroupKey=groups.PrimaryKey)) ORDER BY PrimaryKey";
$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$_maxDayKey= $rowSet["DayKey"];

AddScriptReference("highcharts");
AddScriptReference("statistics");
AddScriptReference("scrollpane");
AddScriptReference("result.group2");
WriteScripts();
?>
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.isotope.js"></script>
<div id="toolbar" class="ui-widget-header ui-corner-all" style="padding: 10px 4px;">

<button id="beginning" style="float: left;position: absolute;top: 13px;">Journée Précédente</button>
<div style="width: 855px;padding-left: 35px;"><div id="slider" ></div></div>
<button id="end" style="float: right;position: absolute;top: 13px;right: 35px;">Journée Suivante</button>
<div id="GroupDescription" style="text-align:center;padding-top:10px;"><h1><?php echo $_groupDescription; ?></h1></div>
</div>
<div id="accordion">
	<h3 id="tab1"><a href="#">Détail de la journée</a></h3>
	<div id="tab1Content">
      <div id="groupContainer">
	  </div>
	</div>
	<h3 id="tab2"><a href="#">Votre évolution pendant la journée</a></h3>
	<div id="tab2Content">
		<center><div id="containerCharts"></div></center>
        <div id="detailChart"></div>
	</div>
</div>
<style>
	.ui-menu { position: absolute; width: 150px;;z-index:999; }
</style>
<script>
var currentDayKey = '<?php echo $_lastCompletedDayKey; ?>';
var currentDayLoaded = 0;
var arrSeries = new Array();

function getGroupRanking (dayKey) {

	if ($("div.Day"+dayKey).length == 0) {
		$('#WaitingLayer').fadeIn();
		$.ajax({
		type: "POST",
		url: "get.group.detail.php?DayKey="+dayKey,
		data: { DayKey: dayKey},
		dataType: 'json',
		success: function (data) {
			$("#GroupDescription h1").html(data.GroupDescription);
			var elementToBeAdded ="<div id='mod-classements' class='groupItem ranking Day"+data.DayKey+"' group-description='"+data.GroupDescription+"'><ol>";
            $.each(data.Rankings, function(i,ranking) {
            	  elementToBeAdded += '<li id="GRP_'+ranking.PlayerKey+'" player-key="'+ranking.PlayerKey+'">';
            	  elementToBeAdded += "<span id='rankSpan'>"+ranking.RankToBeDisplayed+"</span>";
            	  elementToBeAdded += '<a class="popupscroll" href="#"><img class="avat" src="'+ ranking.AvatarPath +'"></img></a>';
            	  elementToBeAdded += '<p><a class="popupscroll textOverflow ellipsis" displayWidth="120" href="#" style="_width:110px;">'+ ranking.NickName + '</a>';

                  if (ranking.BonusScore == 0) {
                	elementToBeAdded += '<em>'+ ranking.Score + ' pts</em>';
                  } else {
                	elementToBeAdded += '<em>'+ ranking.GlobalScore + ' pts <br/><sub style="padding-left:10px;font-size:9px;">dont '+ ranking.BonusScore + ' pts bonus</sub></em>';
                  }

                  elementToBeAdded += '</p>';
                  elementToBeAdded += '<div class="Correct">'+ranking.CorrectScore+' correct(s)</div><div class="Perfect">'+ranking.PerfectScore+' perfect(s)</div><div class="Bad">'+ranking.BadScore+' faux</div>';
                  elementToBeAdded += '</li>';
              });

            elementToBeAdded +="</ol></div>";
            $.each(data.Matches, function(i,match) {

            	elementToBeAdded += '<div class="groupItem element Day'+data.DayKey+'"><div class="elementContent">';
                if (match.IsBonusMatch==1) {
                	elementToBeAdded += '<div style="top:-10px;left:-10px;"><img src="<?php echo ROOT_SITE; ?>/images/star_48.png"></img></div>';
                }
                if (match.HomeYellowCards>0) {
                	elementToBeAdded += '<div id="teamHomeYellowCard" style="background:url(<?php echo ROOT_SITE; ?>/images/yellow.card.png) repeat-x scroll center top transparent;">'+match.HomeYellowCards+'</div>';
                }
                if (match.HomeRedCards>0) {
	                elementToBeAdded += '<div id="teamHomeRedCard" style="background:url(<?php echo ROOT_SITE; ?>/images/red.card.png) repeat-x scroll center top transparent;">'+match.HomeRedCards+'</div>';
                }

                elementToBeAdded += '<div id="teamHomeFlag" style=""><img class="teamFlag" src="<?php echo ROOT_SITE; ?>/images/teamFlags/'+match.TeamHomeKey+'.png" ></img></div>';
                elementToBeAdded += '<div id="elementScore" style="">'+ match.TeamHomeScore +' - '+ match.TeamAwayScore +'</div>';
                elementToBeAdded += '<div id="teamHomeName" class="onlyLarge" style="">'+match.TeamHomeName+'</div>';
                elementToBeAdded += '<div id="teamAwayName" class="onlyLarge" style="">'+match.TeamAwayName+'</div>';
                if (match.AwayYellowCards>0) {
                	elementToBeAdded += '<div id="teamAwayYellowCard" style="background:url(<?php echo ROOT_SITE; ?>/images/yellow.card.png) repeat-x scroll center top transparent;">'+match.AwayYellowCards+'</div>';
                }

                if (match.AwayRedCards>0) {
                	elementToBeAdded += '<div id="teamAwayRedCard" style="background:url(<?php echo ROOT_SITE; ?>/images/red.card.png) repeat-x scroll center top transparent;">'+match.AwayRedCards+'</div>';
                }

                elementToBeAdded += '<div id="elementScoreForecast" class="'+match.ClassScore+'">('+ match.ForecastHomeScore +' - '+ match.ForecastAwayScore +')</div>';
                elementToBeAdded += '<div id="teamAwayFlag" style=""><img class="teamFlag" src="<?php echo ROOT_SITE; ?>/images/teamFlags/'+match.TeamAwayKey+'.png"></img></div>';
                elementToBeAdded += '<div id="resultForecast" class="score '+match.ClassScore+'">'+match.ScoreWording+'</div>';
                elementToBeAdded += '<div id="scorePerfect" style="">Perfect : '+match.PerfectScore+'</div>';
                elementToBeAdded += '<div id="scoreCorrect" style="">Correct : '+match.CorrectScore+'</div>';
                elementToBeAdded += '<div id="scoreBad" style="">Faux : '+match.BadScore+'</div></div>';
                elementToBeAdded += '<div class="tabs"><ul>';
                elementToBeAdded += '<li><a href="#tabs-1_'+match.MatchKey+'">Résultat</a></li>';
                elementToBeAdded += '<li><a href="#tabs-2_'+match.MatchKey+'">Détail du match</a></li></ul>';
                elementToBeAdded += '<div class="filterbutton"><button id="filter_'+match.MatchKey+'">Tout</button><button id="select_'+match.MatchKey+'">Filtrer</button></div>';
                elementToBeAdded += '<ul><li data-option-value="*"><a href="#" >Tout</a></li>';
$.log(match);
				if (match.Status>0) {
                  elementToBeAdded += '<li data-option-value=".success"><a href="#" >Correct</a></li>';
                  elementToBeAdded += '<li data-option-value=".perfect"><a href="#" >Perfect</a></li>';
                  elementToBeAdded += '<li data-option-value=".failed"><a href="#" >Faux</a></li>';
                  elementToBeAdded += '<li data-option-value=".draw"><a href="#" >Match nul</a></li>';
                  elementToBeAdded += '<li data-option-value=".teamHomeWin"><a href="#" >'+match.TeamHomeName+' vainqueur</a></li>';
                  elementToBeAdded += '<li data-option-value=".teamAwayWin"><a href="#" >'+match.TeamAwayName+' vainqueur</a></li>';
				}
                elementToBeAdded += '<li data-option-value=".noneForecast"><a href="#" >Pas de pronostics</a></li>';
                elementToBeAdded += '</ul>';
               	elementToBeAdded += '<div id="tabs-1_'+match.MatchKey+'" name="tabs-1_'+match.MatchKey+'" class="tabResult">';
                elementToBeAdded += '<div class="groupContainerResult"><ul>';
                $.each(match.Forecasts, function(i,forecast) {
                	elementToBeAdded += '<li class="elementResult '+forecast.Class+'"  >';
                	elementToBeAdded += '<img class="avat" src="'+forecast.AvatarPath+'"></img>';
                	elementToBeAdded += '<div class="playerName popupscroll textOverflow ellipsis" displayWidth="120" href="#" style="_width:110px;">'+forecast.PlayerNickName+'</div>';
                	elementToBeAdded += '<div class="forecastScore">'+forecast.TeamHomeForecast+' - '+forecast.TeamAwayForecast+'</div>';
                	elementToBeAdded += '<div class="forecastResult '+forecast.Class+'">'+forecast.Score+'&nbsp;'+forecast.ScoreWording+'</div>';
                	elementToBeAdded += '</li>';
                });
                elementToBeAdded += '</ul></div></div>';
                elementToBeAdded += '<div id="tabs-2_'+match.MatchKey+'" name="tabs-2_'+match.MatchKey+'">';
                elementToBeAdded += '<div class="teamHomeEvents" id="teamHomeEvents_'+match.MatchKey+'" name="teamHomeEvents_'+match.MatchKey+'" ><ul>';
                $.each(match.HomeEvents, function(i,event) {
                	elementToBeAdded += "<li class='"+event.ClassEvent+"'>"+ event.EventDescription + event.GoalType +"</li>";
                });
                elementToBeAdded += '</ul></div><div class="teamSeparator"></div>';
                elementToBeAdded += '<div class="teamAwayEvents" id="teamAwayEvents_'+data.MatchKey+'" name="teamAwayEvents_'+data.MatchKey+'" ><ul>';
                $.each(match.AwayEvents, function(i,event) {
                	elementToBeAdded += "<li class='"+event.ClassEvent+"'>"+ event.EventDescription + event.GoalType +"</li>";
                });
                elementToBeAdded += '</ul></div></div>';
                elementToBeAdded += '</div></div>';
            });

            var $newItems = $(elementToBeAdded);

            $('#groupContainer').isotope( 'insert', $newItems );

        	arrSeries[data.DayKey]= data.series;
    		$.log(arrSeries);
	        if ($('#tab2Content').is(':visible'))
   	 		  refreshChart();
		},

		error: function (XMLHttpRequest, textStatus, errorThrown) {
			$.log(XMLHttpRequest);
			$.log(textStatus);
			$.log(errorThrown);
			}

	});

	} else {

		$("#GroupDescription h1").html($("div.ranking.Day"+dayKey).attr('group-description'));
		 if ($('#tab2Content').is(':visible'))
  	 		refreshChart();
	}
}

function refreshChart () {
	if (currentDayLoaded!=currentDayKey) {
		while (chart.series.length>0) {
			chart.series[0].remove();
		}

		$.log(arrSeries);

		var series = arrSeries[currentDayKey];
      	$.each(series, function(i,serie) {
  			chart.addSeries(serie);
		});
		currentDayLoaded=currentDayKey;
	}
	$('#WaitingLayer').fadeOut();
}



$(function() {
	$( "#accordion" ).accordion({
		autoHeight: false,
		navigation: true,
    	change: function(event, ui) {
    		if (ui.newHeader[0].id == "tab2") {
    			$('#WaitingLayer').fadeIn('fast');
    			window.setTimeout(refreshChart, 2000);
    		}
    		if (ui.newHeader[0].id == "tab1") {
    			$( "#detailChart").dialog( "close" );
    			$('#groupContainer').isotope('reLayout');
    		}
		}
	});
	$( "#slider" ).slider({
		value:<?php echo $_lastCompletedDayKey; ?>,
		min: 1,
		max:<?php echo $_maxDayKey; ?>,
		step: 1,
		start: function(event, ui) {
			$.log(ui);
		},

		change: function(event, ui) {
			$( "#detailChart").dialog( "close" );
			$('#WaitingLayer').fadeIn('fast');
      		currentDayLoaded = currentDayKey;
			  var options = {}, key = "filter";
		  currentDayKey= ui.value;
		  getGroupRanking(ui.value);
		  value = ".Day"+ui.value;
		  options[ key ] = value;
 		  $('#groupContainer').isotope(
			options
		  );
 		 $('#WaitingLayer').fadeOut();
		}

	});

	$( "#beginning" ).button({
		text: false,
		icons: {
			primary: "ui-icon-seek-start"
		}
	}).click(function () {
		$( "#slider" ).slider( "value", $( "#slider" ).slider( "value") - 1 )
		}
	);

	$( "#end").button({
		text: false,
		icons: {
			primary: "ui-icon-seek-end"
		}
	}).click(function () {
		$( "#slider" ).slider( "value", $( "#slider" ).slider( "value") + 1 )
		}
	);
});

$('#groupContainer').isotope({
	  // options
	  itemSelector : '.groupItem',
	  layoutMode : 'masonry'
	});
// change size of clicked element

$('#groupContainer').delegate( '.element', 'click', function(){
  $(this).toggleClass('large');
	$(this).find("div.tabs" ).tabs({
		show: function(event, ui) {
			if (ui.index==0) {
				 $(this).find("div.radio").show();
				 $(this).find("div.groupContainerResult").isotope('reLayout');
			} else {
				 $(this).find("div.radio").hide();
			}
		 },
		 create: function(event, ui) {
				$(this).find("div.groupContainerResult").isotope({
					  itemSelector : '.elementResult',
					  layoutMode : 'fitRows'
				});
		 }
	});

    var currentMatch =this;
        $(currentMatch).find( "div.filterbutton button:first" )
			.button()
			.click(function() {
				alert( "Running the last action" );
				return false;
			}).next().button( {
					text: false,
					icons: {
						primary: "ui-icon-triangle-1-s"
					}
				}).click(function() {
					var menu = $(this).parent().next();
					if (menu.is(':hidden')) {
    					menu.show().position({
    						my: "right top",
    						at: "right bottom",
    						of: this
    					});
    					$(menu).find('li').unbind('click');
    					$(menu).find('li').bind("click", function() {
							$(currentMatch).find( "div.filterbutton button:first span" ).html($(this).find('a').html());
    						menu.hide();
    						  var options = {},
    						  key = "filter",
    					      value = $(this).attr('data-option-value');
    					  //   parse 'false' as false boolean
    					      value = value === 'false' ? false : value;
    					  	  options[ key ] = value;
    						  $(currentMatch).find("div.groupContainerResult").isotope(
    							  options
    						  );
    						return false;
    					});
				} else {
					menu.hide();
				}
					return false;
				}).parent().buttonset().next().hide().menu({select: function(event, ui) {
					$.log(ui);
					return false;
					}});

  $('#groupContainer').isotope('reLayout');

  $(this).find("div.radio label").click( function(){
	  $.log($(this));
	  var options = {},
	  key = $(this).parent().attr('data-option-key'),
      value = $(this).attr('data-option-value');
  //   parse 'false' as false boolean

      value = value === 'false' ? false : value;
  	  options[ key ] = value;
  	  $.log($(this).parent().parent());
	  $(this).parent().parent().find("div.groupContainerResult").isotope(
		  options
	  );
	  return false;
	});
});
//$("#mod-classements").jScrollPane({
//	showArrows: true,
//	horizontalGutter: 5
//});
var chart;
var pointDetail;
var pointDetailDate;
var currentUrl;
var currentUrlSelected;
$(document).ready(function() {
   chart = new Highcharts.Chart({
      chart: {
         renderTo: 'containerCharts',
         zoomType: 'x',
         type: 'spline'
      },
      title: {
         text: 'Evolution durant le jounée'
      },
      subtitle: {
         text: ''
      },
      xAxis: {
         type: 'datetime',
         maxZoom: 2 * 3600000, // fourteen days
         dateTimeLabelFormats: { // don't display the dummy year
    	  minute: '%H:%M',
    		hour: '%H:%M',
          month: '%e. %b',
            year: '%b'
         }
      },
      yAxis: [{
		min:-35,
		max:-1,
         title: {
            text: 'Classement',
         style: {
				color:'#DF5353'
			}
         },
         labels: {
        	 formatter: function() {
             return Math.abs(this.value) + (Math.abs(this.value)==1? ' er' : ' ème') ;
          },
             style: {
                color: '#DF5353'
             }
          }
      },{
	         title: {
      	text: 'Points',
      	style: {
				color:'#55BF3B'
			}
   	},
      labels: {
         style: {
            color: '#55BF3B'
         }
      },
   	min:0,
   	max:150,
   	opposite: true
	}],
      tooltip: {
    	  enabled:true,
			crosshairs : true,
			shared : true,
         formatter: function() {
    	  pointDetailDate = Highcharts.dateFormat('%a %e %b - %H:%M', this.points[0].x);
    	  currentUrl ="get.group.state.detail.php?DayKey="+currentDayKey+"&PlayerKey="+<?php echo $_authorisation->getConnectedUserKey();?>+"&StateDate="+this.points[0].x+"&CurrentTime="+new Date().getTimezoneOffset();
    	  $.ajax({
  			type: "POST",
  			url: currentUrl,
  			data: { DayKey: currentDayKey},
  			dataType: 'json',
  			success: function (data) {
  				pointDetail = data.detail;
//  				$( "#detailChart").dialog ("hide");
  			},
  			error: function (XMLHttpRequest, textStatus, errorThrown) {}
  		  });
    	  //$( "#detailChart").dialog( "open" );
          return '<b>'+ Highcharts.dateFormat('%a %e %b - %H:%M', this.points[0].x)+'</b><br/>'+
          this.points[0].series.name +': '+ Math.abs(this.points[0].y) + (Math.abs(this.points[0].y)==1? 'er' : 'eme')
+ '<br/>' +
this.points[1].series.name +': '+ this.points[1].y +' pts<br/><span style="font-size:8px;" ><i>Cliquer pour plus de détails ...</i></span>';
		}
         },
	      plotOptions: {
	            spline:{
	            	events:{
		                click: function() {
	            			$.log (this);
	            			currentUrlSelected = currentUrl;
	          				$( "#detailChart").html(pointDetail);
	          				$( "#detailChart").dialog( "option", "title",  pointDetailDate);
	            			$( "#detailChart" ).dialog( "open" );

	            			$("#buttonFullRanking").click ( function (){

	            				$.ajax({
	            		  			type: "POST",
	            		  			url: currentUrlSelected +"&FullRanking=true",
	            		  			data: { DayKey: currentDayKey},
	            		  			dataType: 'json',
	            		  			success: function (data) {
	            		  				$( "#detailChart").html(data.detail);
	            		  				//pointDetail = data.detail;
//	            		  				$( "#detailChart").dialog ("hide");
	            		  			},
	            		  			error: function (XMLHttpRequest, textStatus, errorThrown) {}
	            		  		  });

	            			});

//	            			hs.htmlExpand(, {
//	                             pageOrigin: {
//	                                x: this.pageX,
//	                                y: this.pageY
//	                             },
//	                             headingText: 'test',
//	                             maincontentText: Highcharts.dateFormat('%A, %b %e, %Y', this.x) +':<br/> '+
//	                                this.y +' visits',
//	                             width: 200
//	                          });
//			            	$.openPopupLayer({
//			        			name: "teamPlayerPopup",
//			        			width: 532,
//			        			height: 552,
//			        			url: "submodule.loader.php?SubModule=12&score="+score+"&teamKeys="+_selectedTeamKeys+"&groupKeys="+_selectedGroupKeys
//			        		});
	                	}
	            	}
	            }
	      	}
   });

   $( "#detailChart" ).dialog({
		autoOpen: false,
		show: "blind",
		hide: "drop",
		height: 280,
		position: ['right','bottom']
	});


});



$(document).ready(function() {

	var currentValue = <?php echo $_lastCompletedDayKey; ?>;

    var options = {}, key = "filter";

    getGroupRanking(currentValue);

	var value = ".Day"+currentValue;

	options[ key ] = value;

	$('#groupContainer').isotope(
		options
	);

	$('#WaitingLayer').fadeOut();

	$('li[player-key]').live('click', function() {

		var playerKey = $(this).attr('player-key');
		$.openPopupLayer({
			name: "playerPopup",
			width: 532,
			height: 552,
			url: "submodule.loader.php?SubModule=9&playerKey="+playerKey+"&dayKey="+currentDayKey
		});
	});
});

</script>