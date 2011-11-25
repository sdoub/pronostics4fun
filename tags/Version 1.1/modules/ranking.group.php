<?php

$query = "SELECT PrimaryKey GroupKey, Description FROM groups WHERE groups.PrimaryKey= (SELECT MAX(PrimaryKey) FROM groups LastGroup WHERE IsCompleted=1 AND CompetitionKey=" . COMPETITION . ")";

$resultSet = $_databaseObject -> queryPerf ($query, "Get group info");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);

$_groupDescription=$rowSet["Description"];
$_groupKey= $rowSet["GroupKey"];

AddScriptReference("flexigrid");
AddScriptReference("scrollpane");
AddScriptReference("dropdownchecklist");

WriteScripts();

$groupList = "<div id='ContainerGroup' style='float: left; position: absolute; left: 495px; top: 13px; z-index: 999;'>
<select id='ValueChoice' style='z-index: 999; display: none;'>";

  $sql = "SELECT groups.PrimaryKey GroupKey, groups.Code GroupName, groups.Description FROM groups WHERE CompetitionKey=" . COMPETITION . " AND (IsCompleted='1' OR EXISTS (SELECT 1 FROM matches INNER JOIN results ON matches.PrimaryKey=results.MatchKey WHERE matches.GroupKey=groups.PrimaryKey)) ORDER BY PrimaryKey";
  $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");


  $addComma = false;
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
    $selected = "";
    if ($rowSet["GroupKey"]==$_groupKey)
      $selected ="selected='selected'";


    $groupList .= "<option ".$selected." value='" . $rowSet["GroupKey"] . "'>". $rowSet["Description"].'</option>';
  }

$groupList .= '</select></div>';
echo $groupList;
echo '<table id="flexGridRanking" style="display:none"></table>';

echo '<script type="text/javascript">

	var columnSize = 202;
	if($.browser.msie)
	  columnSize = 182;

	$(document).ready(function() {
	$("#flexGridRanking").flexigrid
		(
			{
			url: "get.ranking.group.php?GroupKey=' . $_groupKey . '",
			dataType: "json",
			colGroupModel : [
				{display: "&nbsp;", name : "Rank", width : 60, align: "left"},
				{display: "&nbsp;", name : "NickName", width : 200, align: "left"},
				{display: "Score", name : "Score", width : columnSize, align: "center"},
				{display: "' . __encode("Pronostics") . '", name : "Forecasts", width : columnSize, align: "center"},
				{display: "&nbsp;", name : "Detail", width : 50, align: "center"}
				],
			colModel : [
				{display: "' . __encode("Rang") . '", name : "Rank", width : 60, sortable : true, align: "left"},
				{display: "' . __encode("Joueur") . '", name : "NickName", width : 200, sortable : true, align: "left"},
				{display: "' . __encode("Points") . '", name : "Score", width : 60, sortable : true, align: "right"},
				{display: "' . __encode("Bonus") . '", name : "Bonus", width : 60, sortable : true, align: "right"},
				{display: "' . __encode("Total") . '", name : "Bonus", width : 60, sortable : true, align: "right"},
				{display: "' . __encode("Joués") . '", name : "MatchPlayed", width : 60, sortable : true, align: "right"},
				{display: "' . __encode("Corrects") . '", name : "MatchGood", width : 60, sortable : true, align: "right"},
				{display: "' . __encode("Perfects") . '", name : "MatchPerfect", width : 60, sortable : true, align: "right"},
				{display: "&nbsp;", name : "Detail", width : 50, sortable : false, align: "left"}
				],
			searchitems : [
				{display: "Joueur", name : "NickName"}
				],
			sortname: "Rank",
			sortorder: "asc",
			usepager: true,
			title: "' . __encode('Classement - <span/>' ) . $_groupDescription . '",
			useRp: true,
			rp: 40,
			striped:false,
			resizable: false,
			showTableToggleBtn: false,
			width: 940,
			height: 415,
			onSuccess:refreshScrollBar,
			pagestat: "' . __encode("Affichage de {from} à {to} sur {total} joueurs") . '",
			singleSelect:true,
			 pagetext: "Page",
			 outof: "sur",
			 findtext: "Rechercher",
			 procmsg: "Traitement, patientez ...",
			 nomsg: "'. __encode("Pas de données") . '"
			}
		);
		});
</script>';

?>
<style>

.ui-dropdownchecklist{
z-index:999;
}
</style>
<script type="text/javascript">
function refreshScrollBar() {
	$("div.bDiv").jScrollPane({
		showArrows: true,
		horizontalGutter: 10
	});
}
var _groupKey = <?php echo $_groupKey; ?>;
$(document).ready(function() {
	$("#ValueChoice").dropdownchecklist({icon: {}, width: 180,maxDropHeight: 250, closeRadioOnClick:true,
		onComplete: function(selector){
		var groupName = "";
		var groupKey = "";
		for( i=0; i < selector.options.length; i++ ) {
            if (selector.options[i].selected && (selector.options[i].value != "")) {
            	groupKey = selector.options[i].value;
            	groupName = $(selector.options[i]).html();
            	//groupName = selector.options[i].text();
            }
        }
		_groupKey = groupKey;
		$("#flexGridRanking").flexOptions({url : 'get.ranking.group.php?GroupKey=' + groupKey });
		$("#flexGridRanking").flexReload();

		} });

	$('a[player-key]').live('click', function() {
		var playerKey = $(this).attr('player-key');
		$.openPopupLayer({
			name: "playerPopup",
			width: 532,
			height: 552,
			url: "submodule.loader.php?SubModule=9&playerKey="+playerKey+"&groupKey="+_groupKey
		});

	});


});

</script>