<?php
AddScriptReference("flexigrid");
AddScriptReference("scrollpane");

WriteScripts();

echo '<table id="flexGridRanking" style="display:none"></table>';

echo '<script type="text/javascript">

	var columnSizeHeader = new Array();
	var columnSize = new Array();
	columnSizeHeader[0]=50;
	columnSizeHeader[1]=150;
	columnSizeHeader[2]=294;
	columnSizeHeader[3]=112;
	columnSizeHeader[4]=172;
	columnSizeHeader[5]=50;

	columnSize[0] = 50;
	columnSize[1] = 150;
	columnSize[2] = 50;
	columnSize[3] = 50;
	columnSize[4] = 50;
	columnSize[5] = 50;
	columnSize[6] = 50;
	columnSize[7] = 30;
	columnSize[8] = 30;
	columnSize[9] = 30;
	columnSize[10] = 50;
	columnSize[11] = 50;
	columnSize[12] = 50;
	columnSize[13] = 50;

	if($.browser.msie) {
	columnSizeHeader[0]=60;
	columnSizeHeader[1]=150;
	columnSizeHeader[2]=284;
	columnSizeHeader[3]=122;
	columnSizeHeader[4]=182;
	columnSizeHeader[5]=50;

	columnSize[0] = 60;
	columnSize[1] = 150;
	columnSize[2] = 60;
	columnSize[3] = 60;
	columnSize[4] = 60;
	columnSize[5] = 50;
	columnSize[6] = 50;
	columnSize[7] = 40;
	columnSize[8] = 40;
	columnSize[9] = 40;
	columnSize[10] = 60;
	columnSize[11] = 60;
	columnSize[12] = 60;
	columnSize[13] = 50;

	}

	$(document).ready(function() {
	$("#flexGridRanking").flexigrid
		(
			{
			url: "get.ranking.php",
			dataType: "json",
			colGroupModel : [
				{display: "&nbsp;", name : "Rank", width : columnSizeHeader[0], align: "left"},
				{display: "&nbsp;", name : "NickName", width : columnSizeHeader[1], align: "left"},
				{display: "' . __encode("Score") . '", name : "Score", width : columnSizeHeader[2], align: "center"},
				{display: "' . __encode("Podium") . '", name : "Podium", width : columnSizeHeader[3], align: "center"},
				{display: "' . __encode("Pronostics") . '", name : "Forecasts", width : columnSizeHeader[4], align: "center"},
				{display: "&nbsp;", name : "Detail", width : columnSizeHeader[5], align: "center"}
				],
			colModel : [
				{display: "' . __encode("Rang") . '", name : "Rank", width : columnSize[0], sortable : true, align: "left"},
				{display: "' . __encode("Joueur") . '", name : "NickName", width : columnSize[1], sortable : true, align: "left"},
				{display: "' . __encode("Total") . '", name : "Score", width : columnSize[2], sortable : true, align: "right"},
				{display: "' . __encode("Points") . '", name : "ScoreWithoutBonus", width : columnSize[3], sortable : true, align: "right"},
				{display: "' . __encode("Bonus") . '", name : "BonusScore", width : columnSize[4], sortable : true, align: "right"},
				{display: "' . __encode("Max.") . '", name : "ScoreMax", width : columnSize[5], sortable : true, align: "right"},
				{display: "' . __encode("Min.") . '", name : "ScoreMin", width : columnSize[6], sortable : true, align: "right"},
				{display: "' . __encode("1<sup>er</sup>") . '", name : "FirstRank", width : columnSize[7], sortable : true, align: "right"},
				{display: "' . __encode("2<sup>ème</sup>") . '", name : "SecondRank", width : columnSize[8], sortable : true, align: "right"},
				{display: "' . __encode("3<sup>ème</sup>") . '", name : "ThirdRank", width : columnSize[9], sortable : true, align: "right"},
				{display: "' . __encode("Joués") . '", name : "MatchPlayed", width : columnSize[10], sortable : true, align: "right"},
				{display: "' . __encode("Corrects") . '", name : "MatchGood", width : columnSize[11], sortable : true, align: "right"},
				{display: "' . __encode("Perfects") . '", name : "MatchPerfect", width : columnSize[12], sortable : true, align: "right"},
				{display: "&nbsp;", name : "Detail", width : columnSize[13], sortable : false, align: "left"}
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