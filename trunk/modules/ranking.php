<?php
AddScriptReference("flexigrid");
AddScriptReference("scrollpane");

WriteScripts();

echo '<table id="flexGridRanking" style="display:none"></table>';

echo '<script type="text/javascript">

	var columnSize = 202;
	if($.browser.msie)
	  columnSize = 182;

	$(document).ready(function() {
	$("#flexGridRanking").flexigrid
		(
			{
			url: "get.ranking.php",
			dataType: "json",
			colGroupModel : [
				{display: "&nbsp;", name : "Rank", width : 60, align: "left"},
				{display: "&nbsp;", name : "NickName", width : 200, align: "left"},
				{display: "&nbsp;", name : "Score", width : 100, align: "right"},
				{display: "' . __encode("Podium") . '", name : "Podium", width : columnSize, align: "center"},
				{display: "' . __encode("Pronostics") . '", name : "Forecasts", width : columnSize, align: "center"},
				{display: "&nbsp;", name : "Detail", width : 50, align: "center"}
				],
			colModel : [
				{display: "' . __encode("Rang") . '", name : "Rank", width : 60, sortable : true, align: "left"},
				{display: "' . __encode("Joueur") . '", name : "NickName", width : 200, sortable : true, align: "left"},
				{display: "' . __encode("Points") . '", name : "Score", width : 100, sortable : true, align: "right"},
				{display: "' . __encode("1er") . '", name : "FirstRank", width : 60, sortable : true, align: "right"},
				{display: "' . __encode("2ème") . '", name : "SecondRank", width : 60, sortable : true, align: "right"},
				{display: "' . __encode("3ème") . '", name : "ThirdRank", width : 60, sortable : true, align: "right"},
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
			title: "' . __encode('Classement Général') . '",
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
<script type="text/javascript">
function refreshScrollBar() {
	$("div.bDiv").jScrollPane({
		showArrows: true,
		horizontalGutter: 10
	});
}
$(document).ready(function() {

	$('a[player-key]').live('click', function() {
		var playerKey = $(this).attr('player-key');
		$.openPopupLayer({
			name: "playerPopup",
			width: 532,
			height: 552,
			url: "submodule.loader.php?SubModule=8&playerKey="+playerKey
		});

	});

});

</script>