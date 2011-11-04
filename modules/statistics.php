<?php
AddScriptReference("highcharts");
AddScriptReference("dropdownchecklist");
AddScriptReference("statistics");
WriteScripts();

?>
<script type="text/javascript">

<?php
if (isset($_GET["View"])) {
  $view=$_GET["View"];
}
else {
  $view="Ranking";
}
?>

var chartTitle = '';
var defaultRankingCategories;
var defaultScoreCategories;
var defaultDayByDayRankingCategories;
var defaultDayByDayScoreCategories;
var defaultMinMaxAvgCategories;
$(document).ready(function() {
	initialiseChart();
});

<?php
$categories = "[";

  $sql = "SELECT GROUP_CONCAT(groups.Code) GroupName FROM groups WHERE CompetitionKey=" . COMPETITION . " AND IsCompleted='1' GROUP BY EndDate ORDER BY EndDate";
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

echo "defaultRankingCategories=" . $categories .";";
echo "defaultScoreCategories=" . $categories.";";

$categories = "[";

  $sql = "SELECT groups.Code GroupName FROM groups WHERE CompetitionKey=" . COMPETITION . " AND IsCompleted='1' ORDER BY EndDate, DayKey";
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

echo "defaultDayByDayRankingCategories=" . $categories .";";
echo "defaultDayByDayScoreCategories=" . $categories.";";

echo "defaultMinMaxAvgCategories=[" . "'" . $_authorisation->getConnectedUser() . "'];";

$chartTitle ="";
switch ($view)
{
  case "Ranking":
    $chartTitle = "Suivi des classements";
    break;
  case "Score":
    $chartTitle = "Suivi des points";
    break;
}
echo "chartTitle='" . utf8_encode($chartTitle) . "'";
?>
</script>

<div>
<center><script type="text/javascript">
<?php
echo "var _currentView = '" . $view . "';";
?>
$(document).ready(function() {
	$("#ValueChoice").dropdownchecklist({
		icon: {},
		width: 180,
		maxDropHeight: 150,
		closeRadioOnClick:true
	}).show(function () {
			$("#ValueChoice").hide();
		});
    $("#PlayerChoice").dropdownchecklist({
        icon: {},
        width: 250,
        maxDropHeight: 150
    }).show(function () {
        	$("#PlayerChoice").hide();
        });
    $("#RefreshStats").click(function () {
	   RefreshStats();
    });

    window.setTimeout(RefreshStats,500);
    $("#MainStatsContainer").show();
});
var _currentChartType;
function initialiseChart (){

	switch ($("#ValueChoice").val()[0]) {
		case "MinMaxAvg":
			currentView = "MinMaxAvg" + _currentView;
			break;
		case "Group":
			currentView = "Group" + _currentView;
			break;
		default:
			currentView = _currentView;
			break;
	}

	if (_currentChartType!= null && _currentChartType!=_currentView)
		chart.destroy();

	switch (currentView) {
		case 'Ranking':
			CreateRankingChart(chartTitle,defaultRankingCategories);
			_currentChartType = 'Ranking';
			break;
		case 'Score':
			CreateScoreChart(chartTitle,defaultScoreCategories);
			_currentChartType = 'Score';
			break;
		case 'GroupRanking':
			CreateRankingChart(chartTitle,defaultDayByDayRankingCategories);
			_currentChartType = 'Ranking';
			break;
		case 'GroupScore':
			CreateScoreChart(chartTitle,defaultDayByDayScoreCategories);
			_currentChartType = 'Score';
			break;
  		case 'MinMaxAvgScore':
    		CreateMinMaxAvgScoreChart(chartTitle,defaultMinMaxAvgCategories);
			_currentChartType = 'MinMaxAvgScore';
			break;
  		case 'MinMaxAvgRanking':
    		CreateMinMaxAvgRankingChart(chartTitle,defaultMinMaxAvgCategories);
			_currentChartType = 'MinMaxAvgRanking';
			break;
	}

}

var numberOfSelectedUsers=0;
function RefreshStats () {
	initialiseChart();
	var maxSeries = chart.series.length;

	while (chart.series.length>0) {
        chart.series[0].remove();
    }
   var selectedPlayers = $("#PlayerChoice").val();
   var viewMode = $("#ValueChoice").val()[0];
   numberOfSelectedUsers = selectedPlayers.length;
   for (var currentIndex=0;currentIndex<selectedPlayers.length; currentIndex++) {
	$.ajax({
		  url: "get.player.statistics.php?PlayerKey=" + selectedPlayers[currentIndex] + "&View="+_currentView+"&ViewMode="+viewMode+"&Position="+currentIndex,
		  dataType: 'json',
		  data: "",
		  success: callbackPost,
		  error: callbackPostError
		});
   }
}

function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
{
	$.log(XMLHttpRequest);
	$.log(textStatus);
	$.log(errorThrown);
}

function callbackPost (data){
	numberOfSelectedUsers--;
	chart.addSeries(data);
	if ($("#ValueChoice").val()[0]=="MinMaxAvg")
		if (numberOfSelectedUsers==0){
			var categories = new Array ();
			for (var currentIndex=0;currentIndex<chart.series.length; currentIndex++) {
				  categories.push(chart.series[currentIndex].name);
			}
			categories.sort(function(a, b){
				 var nameA=a.toLowerCase();
				 var nameB=b.toLowerCase();
				 if (nameA < nameB) //sort string ascending
				  return -1;
				 if (nameA > nameB)
				  return 1;
				 return 0; //default return value (no sorting)
				});
			chart.xAxis[0].setCategories(categories);
		}
}
</script>
<span
	style="margin-left: 15px; color: FFF; font-weight: bold; vertical-align: middle; padding-top: 10px; padding-right: 3px;">Vue:</span>
<select id="ValueChoice" style="z-index: 999; display: none;">
	<option selected="selected" value="Global"><?php echo __encode("général");?></option>
	<option value="Group"><?php echo __encode("par journée");?></option>
	<option value="MinMaxAvg"><?php echo __encode("Mini - Maxi - Moyenne");?></option>
</select>
<span
	style="margin-left: 15px; color: FFF; font-weight: bold; vertical-align: middle; padding-top: 10px; padding-right: 3px;">Joueur(s):</span>
<?php
$sql = "SELECT PrimaryKey PlayerKey, NickName FROM playersenabled players ORDER BY NickName";
$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");
$content = "<select id='PlayerChoice'  multiple='multiple' style='z-index:999;display:none;'>";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  if ($_authorisation->getConnectedUserKey() == $rowSet["PlayerKey"]) {
    $content .= "<option selected='selected' value='".$rowSet["PlayerKey"]."'>".$rowSet["NickName"]."</option>";
  }
  else {
    $content .= "<option value='".$rowSet["PlayerKey"]."'>".$rowSet["NickName"]."</option>";
  }
}
$content .= "</select>";
echo __encode($content);
?> <input type="button" name="RefreshStats" id="RefreshStats"
	value="Actualiser" /></center>
</div>

<div id="containerCharts" style="width: 930px; height: 500px;"></div>
