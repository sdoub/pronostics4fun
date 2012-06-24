<?php
AddScriptReference("flexigrid");
AddScriptReference("scrollpane");

WriteScripts();

echo '<table id="flexGridRanking" style="display:none"></table>';

echo '<script type="text/javascript">

	var columnSize = 191;
	if($.browser.msie)
	  columnSize = 201;

	$(document).ready(function() {
	$("#flexGridRanking").flexigrid
		(
			{
			url: "get.ranking.top.assist.php",
			dataType: "json",
			colGroupModel : [
				{display: "&nbsp;", name : "Rank", width : 60, align: "left"},
				{display: "&nbsp;", name : "NickName", width : 311, align: "left"},
				{display: "&nbsp;", name : "Assists", width : columnSize, align: "right"},
				{display: "&nbsp;", name : "Detail", width : 50, align: "center"}
				],
			colModel : [
				{display: "' . __encode("Rang") . '", name : "Rank", width : 60, sortable : true, align: "left"},
				{display: "' . __encode("Passeur") . '", name : "FullName", width : 150, sortable : true, align: "left"},
				{display: "' . __encode("Club") . '", name : "TeamName", width : 150, sortable : true, align: "left"},
				{display: "' . __encode("Passes décisives") . '", name : "Assists", width : 120, sortable : true, align: "right"},
				{display: "' . __encode("Journée") . '", name : "Days", width : 60, sortable : true, align: "right"},
				{display: "&nbsp;", name : "Detail", width : 50, sortable : false, align: "left"}
				],
			searchitems : [
				{display: "Passeur", name : "TeamPlayerName"},
				{display: "Club", name : "TeamName"}
				],
			sortname: "Rank",
			sortorder: "desc",
			usepager: true,
			title: "' . __encode('Classement des passeurs') . '",
			useRp: true,
			rp: 10,
			striped:false,
			resizable: false,
			showTableToggleBtn: false,
			width: 940,
			height: 415,
			onSuccess:refreshScrollBar,
			pagestat: "' . __encode("Affichage de {from} à {to} sur {total} passeurs") . '",
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
<script type="text/javascript">
function refreshScrollBar() {
	$("div.bDiv").jScrollPane({
		showArrows: true,
		horizontalGutter: 10
	});
}
$(document).ready(function() {

	$('a[team-player-key]').live('click', function() {
		var teamPlayerKey = $(this).attr('team-player-key');
		var teamKey = $(this).attr('team-key');
		$.openPopupLayer({
			name: "teamPlayerPopup",
			width: 532,
			height: 552,
			url: "submodule.loader.php?SubModule=13&teamPlayerKey="+teamPlayerKey+"&teamKey="+teamKey
		});

	});

});

</script>