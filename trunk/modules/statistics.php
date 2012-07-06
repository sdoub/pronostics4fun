<?php
AddScriptReference("highcharts");
AddScriptReference("dropdownchecklist");
AddScriptReference("statistics");
WriteScripts();

?>
<script type="text/javascript" src="http://www.highcharts.com/highslide/highslide-full.min.js"></script>
<script type="text/javascript" src="http://www.highcharts.com/highslide/highslide.config.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="http://www.highcharts.com/highslide/highslide.css" />

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

$query = "SELECT MIN(UNIX_TIMESTAMP(DATE(matches.ScheduleDate))) BeginDate, MAX(UNIX_TIMESTAMP(DATE(matches.ScheduleDate)))+86400 EndDate, groups.Description
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN results ON results.MatchKey=matches.PrimaryKey AND LiveStatus=10
GROUP BY DATE(matches.ScheduleDate)
ORDER BY matches.ScheduleDate
";
$resultSet = $_databaseObject->queryPerf($query,"Get players and score");
$separator ="";
$plotsBand = "";
$arrPlotBands = array();
$currentPlotBand = 0;
$previousGroup = "";
$previousBeginDate = 0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
  $beginDate = ((int)$rowSet['BeginDate']*1000+((is_est((int)$rowSet['BeginDate'])?2:1)*3600*1000));
  $endDate = ((int)$rowSet['EndDate']*1000+((is_est((int)$rowSet['EndDate'])?2:1)*3600*1000));

  $plotsBand = "{ from: " . $beginDate.", to: " . $endDate.",color: 'rgba(228, 228, 225, 0.1)',";
  $plotsBand .= "label: {y:65,text: '". $rowSet['Description']."',rotation:270,align:'center',verticalAlign:'bottom',style: {color: 'rgba(255, 255, 255, 0.5)'}}}";

  if ($previousGroup == $rowSet['Description'])
  {
    $beginDate = $previousBeginDate;
    $plotsBand = "{ from: " . $beginDate.", to: " . $endDate.",color: 'rgba(228, 228, 225, 0.1)',";
      $plotsBand .= "label: {y:65,text: '". $rowSet['Description']."',rotation:270,align:'center',verticalAlign:'bottom',style: {color: 'rgba(255, 255, 255, 0.5)'}}}";
  }
  else {
    $previousBeginDate = $beginDate;
    $currentPlotBand ++;
  }


  $arrPlotBands[$currentPlotBand] = $plotsBand;
  $previousGroup = $rowSet['Description'];

}
$plotsBand = implode(",", $arrPlotBands);
//print_r( $plotsBand);
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
echo "chartTitle='" . $chartTitle . "'";
?>
</script>

<div>
<center><script type="text/javascript">
<?php
echo "var _currentView = '" . $view . "';";
?>
chartImagePath = "<?php echo ROOT_SITE;?>";

var _groups = [<?php echo $plotsBand;?>];

$(document).ready(function() {
	$("#ValueChoice").dropdownchecklist({
		icon: {},
		width: 180,
		maxDropHeight: 150,
		closeRadioOnClick:true
	});
    $("#PlayerChoice").dropdownchecklist({
        icon: {},
        width: 250,
        maxDropHeight: 150,
		firstItemChecksAll: false
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
		case "Group":
			currentView = "Group" + _currentView;
			break;
		default:
			currentView = _currentView;
			break;
	}

		if (_currentChartType!= null && _currentChartType!=currentView)
		{
		chart.destroy();
		}

	switch (currentView) {
		case 'Ranking':
			CreateRankingChartWithTimeScale (chartTitle,_groups, false);
			//CreateRankingChart(chartTitle,defaultRankingCategories);
			_currentChartType = 'Ranking';
			break;
		case 'Score':
			CreateScoreChartWithTimeScale(chartTitle,_groups, false);
			_currentChartType = 'Score';
			break;
		case 'GroupRanking':
			CreateRankingChartWithTimeScale (chartTitle,_groups, true);
			_currentChartType = 'GroupRanking';
			break;
		case 'GroupScore':
			CreateScoreChartWithTimeScale(chartTitle,_groups, true);
			_currentChartType = 'GroupScore';
			break;
  		case 'MinMaxAvgScore':
    		CreateMinMaxAvgScoreChart(chartTitle,defaultMinMaxAvgCategories);
			_currentChartType = 'MinMaxAvgScore';
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
	chart.addSeries(data);
}
</script>
<span
	style="margin-left: 15px; color: FFF; font-weight: bold; vertical-align: middle; padding-top: 10px; padding-right: 3px;">Vue:</span>
<select id="ValueChoice" style="z-index: 999; display: none;">
	<option selected="selected" value="Global"><?php echo "général";?></option>
	<option value="Group"><?php echo "par journée";?></option>
</select>
<span
	style="margin-left: 15px; color: FFF; font-weight: bold; vertical-align: middle; padding-top: 10px; padding-right: 3px;">Joueur(s):</span>
<?php
$sql = "SELECT PrimaryKey PlayerKey, NickName FROM playersenabled players ORDER BY NickName";
$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");
$content = "<select id='PlayerChoice'  multiple='multiple' style='z-index:999;display:none;'>";
//$content .= "<option selected='selected' value='All'>".__encode("Tous")."</option>";
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
