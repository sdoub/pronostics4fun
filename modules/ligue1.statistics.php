<?php
AddScriptReference("highcharts");
AddScriptReference("dropdownchecklist");
AddScriptReference("ligue1.statistics");
WriteScripts();

?>
<script type="text/javascript">
<?php
		if (isset($_GET["View"])) {
		  $view=$_GET["View"];
		}
		else {
		  $view="Goals";
		}
		?>
var chartTitle = '';
var chartSubTitle = '';
var defaultGoalsCategories;
$(document).ready(function() {
	initialiseChart();
});

<?php

echo "defaultGoalsCategories=['0-15','16-30','31-45','>45','45-60','61-75','76-90','>90'];";
if ($view=="Goals") {
echo "chartTitle='Répartition des buts par 1/4 heure';";
} else {
echo "chartTitle='Répartions des Scores';";
}
echo "chartSubTitle='';";
?>
</script>

<div>
<center><script type="text/javascript">
<?php
echo "var _currentView = '" . $view . "';";
?>

$(document).ready(function() {
	$("#GroupChoice").dropdownchecklist({
		icon: {},
		width: 180,
		maxDropHeight: 150,
		closeRadioOnClick:true,
		firstItemChecksAll: true
	});
    $("#TeamChoice").dropdownchecklist({
        icon: {},
        width: 250,
        maxDropHeight: 150,
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

	currentView = _currentView;

	if (_currentChartType!= null && (_currentChartType!=_currentView ))
		chart.destroy();

	switch (currentView) {
	case 'Goals':
		CreateGoalsChart(chartTitle,chartSubTitle,defaultGoalsCategories);
		_currentChartType = 'Goals';
		break;
	case 'ScoreLigue1':
		CreateScoreChart(chartTitle);
		_currentChartType = 'ScoreLigue1';
		break;
	}
}

function RefreshStats () {
	initialiseChart();
	if (chart.series!=null) {
    	var maxSeries = chart.series.length;

    	while (chart.series.length>0) {
            chart.series[0].remove();
        }
	}

	try {
    var selectedGroups = $("#GroupChoice option:selected");
	var selectedGroupsValues = "";
    if (selectedGroups[0].value=="All") {
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
	}
	catch (e){}
    var selectedTeams = $("#TeamChoice option:selected");
	var selectedTeamsValues = "";
    if (selectedTeams[0].value=="All") {
    	selectedTeamsValues = "All";
    }
    else
    {
      $.each(selectedTeams,
      function(i,option) {
   			if (i==0) {
   				selectedTeamsValues =option.value;
   			}
   			else
   				selectedTeamsValues +=','+option.value;
       });
     }
    _selectedTeamKeys = selectedTeamsValues;
    _selectedGroupKeys = selectedGroupsValues;
	$.ajax({
		  url: "get.ligue1.statistics.php?&View=" + _currentView + "&Group=" +selectedGroupsValues+ "&Team=" + selectedTeamsValues ,
		  dataType: 'json',
		  data: "",
		  success: callbackPostStats,
		  error: callbackPostError
		});
}

function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
{
	$.log(XMLHttpRequest);
	$.log(textStatus);
	$.log(errorThrown);
}

function callbackPostStats (data){

	$.each(data.series, function(i,serie) {
	  chart.addSeries(serie);
	});
    if (_currentView=="Goals") {
    	var chartSubTitle='%1 buts marqués dont %2 penalty (soit %3 buts/match)';
    	chartSubTitle = chartSubTitle.replace("%1",data.total);
    	chartSubTitle = chartSubTitle.replace("%2",data.penalty);
    	chartSubTitle = chartSubTitle.replace("%3",Math.round(data.total/data.totalMatches*100)/100);
    	chart.setTitle(null,{text:chartSubTitle});
    	chart.renderer.text('1ère période', 240, 490).attr({
            zIndex: 20
        }).css({color:'#FFFFFF',
        	font: '14px Arial, Verdana, sans-serif'}).add();
    	chart.renderer.text('2ème période', 650, 490).attr({
            zIndex: 20
        }).css({color:'#FFFFFF',
        	font: '14px Arial, Verdana, sans-serif'}).add();

        chart.renderer.rect(463,45,427,450,0).attr ({'stroke-width':0,fill:'<?php if ($_competitionType==1) { echo "#6d8aa8"; } else {echo "#465723";}?>',zIndex:-99}).add();
    }
    else {
    	chart.setTitle({text:data.chartTitle},{text:data.chartSubTitle});
    }


}
</script>
<span
	style="margin-left: 15px; color: FFF; font-weight: bold; vertical-align: middle; padding-top: 10px; padding-right: 3px;"><?php echo __encode("Journées : ");?></span>
<?php
$sql = "SELECT PrimaryKey GroupKey, Description FROM groups WHERE CompetitionKey=" . COMPETITION . " AND IsCompleted=1 ORDER BY groups.DayKey";
$resultSet = $_databaseObject->queryPerf($sql,"Get groups");
$content = "<select id='GroupChoice'  multiple='multiple' style='z-index:999;display:none;'>";
$content .= "<option selected='selected' value='All'>".__encode("Toutes")."</option>";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $content .= "<option selected='selected' value='".$rowSet["GroupKey"]."'>".$rowSet["Description"]."</option>";
}
$content .= "</select>";
echo __encode($content);
?>
<span
	style="margin-left: 15px; color: FFF; font-weight: bold; vertical-align: middle; padding-top: 10px; padding-right: 3px;"><?php echo __encode("Club(s) : ");?></span>
<?php
$sql = "SELECT PrimaryKey TeamKey, Name TeamName FROM teams
WHERE EXISTS (SELECT 1 FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey = " . COMPETITION . " WHERE matches.TeamHomeKey=teams.PrimaryKey)
OR EXISTS (SELECT 1 FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey = " . COMPETITION . " WHERE matches.TeamAwayKey=teams.PrimaryKey)
ORDER BY teams.Name";
$resultSet = $_databaseObject->queryPerf($sql,"Get teams");
$content = "<select id='TeamChoice'  multiple='multiple' style='z-index:999;display:none;'>";
$content .= "<option selected='selected' value='All'>".__encode("Tous")."</option>";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $content .= "<option selected='selected' value='".$rowSet["TeamKey"]."'>".$rowSet["TeamName"]."</option>";
}
$content .= "</select>";
echo __encode($content);
?> <input type="button" name="RefreshStats" id="RefreshStats"
	value="Actualiser" />

</center>
</div>
<div id="containerCharts" style="width: 930px; height: 500px;"></div>
