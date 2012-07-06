<?php
AddScriptReference("highcharts");
AddScriptReference("dropdownchecklist");
AddScriptReference("p4f.statistics");
WriteScripts();

?>
<script type="text/javascript">

var chartTitle = '';
var chartSubTitle = '';
var defaultCategories;
$(document).ready(function() {
	initialiseChart();
});

<?php

$categories = "[";

  $sql = "SELECT GROUP_CONCAT(groups.Code) GroupName FROM groups WHERE CompetitionKey=" . COMPETITION . " AND IsCompleted='1' GROUP BY DayKey ORDER BY EndDate";
  $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");


  $addComma = false;
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
    if ($addComma) {
      $categories .= ",";
    }
    $categories .= "'" . $rowSet["GroupName"] . "'";
    $addComma = true;
  }

  $categories .= "]";

echo "defaultCategories=" . $categories .";";

echo "defaultGoalsCategories=['0-15','16-30','31-45','>45','45-60','61-75','76-90','>90'];";
echo "chartTitle='" . utf8_encode("Pronostics") . "';";
echo "chartSubTitle='';";
?>
</script>

<div>
<center><script type="text/javascript">
<?php
echo "var _currentView = 'Forecasts';";
?>

$(document).ready(function() {
    $("#ViewChoice").dropdownchecklist({
        icon: {},
        width: 250,
        closeRadioOnClick:true,
        maxDropHeight: 150
    });

	$("#GroupChoice").dropdownchecklist({
		icon: {},
		width: 180,
		maxDropHeight: 150,
		closeRadioOnClick:true,
		firstItemChecksAll: true
	});
    $("#RefreshStats").click(function () {
	   RefreshStats();
    });
    window.setTimeout(RefreshStats,500);
    $("#MainStatsContainer").show();
});

var _currentChartType;
function initialiseChart (){

	switch ($("#ViewChoice").val()[0]) {
	case "Forecasts":
		currentView = "Forecasts";
		break;
	case "Results":
		currentView = "Results";
		break;
	case "Points":
		currentView = "Points";
		break;

	default:
		currentView = _currentView;
		break;
}
	if (_currentChartType!= null && _currentChartType!=_currentView)
		chart.destroy();

	switch (currentView) {
	case 'Forecasts':
		CreateForecastsChart(chartTitle,chartSubTitle,defaultCategories);
		_currentChartType = 'Forecasts';
		break;
	case 'Results':
		CreateResultsChart(chartTitle,chartSubTitle,defaultCategories);
		_currentChartType = 'Results';
		break;
	case 'Points':
		CreatePointsChart(chartTitle,chartSubTitle,defaultCategories);
		_currentChartType = 'Points';
		break;
	}
}

function RefreshStats () {
	initialiseChart();
	var maxSeries = chart.series.length;

	while (chart.series.length>0) {
        chart.series[0].remove();
    }

    var selectedGroups = $("#GroupChoice option:selected");
	var selectedGroupsValues = "";
    if (selectedGroups.length>0 && selectedGroups[0].value=="All") {
    	selectedGroupsValues = "All";
    }
    else
    {
      $.each(selectedGroups,
      function(i,option) {
   			if (i==0) {
   				selectedGroupsValues =option.value;
   			}
   			else
   				selectedGroupsValues +=','+option.value;
       });
     }

    var selectedView = $("#ViewChoice option:selected");

	$.ajax({
		  url: "get.p4f.statistics.php?Group=" +selectedGroupsValues+ "&View=" + selectedView[0].value,
		  dataType: 'json',
		  data: "",
		  success: callbackPost,
		  error: callbackPostError
		});
}

function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
{
	$.log(XMLHttpRequest);
	$.log(textStatus);
	$.log(errorThrown);
}

function callbackPost (data){

	$.each(data.series, function(i,serie) {
		chart.addSeries(serie);
//		var chartSubTitle=' <?php echo utf8_encode("%1 buts marqués dont %2 penalty (soit %3 buts/match)") ?>';
//		chartSubTitle = chartSubTitle.replace("%1",serie.total);
//		chartSubTitle = chartSubTitle.replace("%2",data.penalty);
//		chartSubTitle = chartSubTitle.replace("%3",Math.round(data.total/data.totalMatches*100)/100);
		chart.setTitle({text:data.chartTitle},null);
//		chart.renderer.text('<?php echo utf8_encode("1ère période"); ?>', 240, 490, {
//	        font: '14px Arial, Verdana, sans-serif'
//	    }, 0, 'center').attr({
//	        zIndex: 20
//	    }).css({color:'#FFFFFF'}).add();
//		chart.renderer.text('<?php echo utf8_encode("2ème période"); ?>', 650, 490, {
//	        font: '14px Arial, Verdana, sans-serif'
//	    }, 0, 'center').attr({
//	        zIndex: 20
//	    }).css({color:'#FFFFFF'}).add();
//
//	    chart.renderer.rect(480,45,410,450,0).attr ({'stroke-width':0,fill:'#6d8aa8',zIndex:-99}).add();

    });
}
</script>
<span
	style="margin-left: 15px; color: FFF; font-weight: bold; vertical-align: middle; padding-top: 10px; padding-right: 3px;">Vue:</span>
<select id="ViewChoice" style="z-index: 999; display: none;">
	<option selected="selected" value="Forecasts"><?php echo __encode("Pronostics");?></option>
	<option value="Results"><?php echo __encode("Résultats");?></option>
	<option value="Points"><?php echo __encode("Points");?></option>
</select>
<span
	style="margin-left: 15px; color: FFF; font-weight: bold; vertical-align: middle; padding-top: 10px; padding-right: 3px;"><?php echo __encode("Journées : ");?></span>
<?php
$sql = "SELECT PrimaryKey GroupKey, Description FROM groups WHERE CompetitionKey=" . COMPETITION . " AND IsCompleted=1 ORDER BY groups.DayKey";
$resultSet = $_databaseObject->queryPerf($sql,"Get groups");
$content = "<select id='GroupChoice'  multiple='multiple' style='z-index:999;'>";
$content .= "<option selected='selected' value='All'>".__encode("Toutes")."</option>";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $content .= "<option value='".$rowSet["GroupKey"]."'>".$rowSet["Description"]."</option>";
}
$content .= "</select>";
echo __encode($content);
?>

 <input type="button" name="RefreshStats" id="RefreshStats"
	value="Actualiser" />

</center>
</div>
<div id="containerCharts" style="width: 930px; height: 500px;"></div>
