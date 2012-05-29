<?php

$matchKey = $_GET["matchKey"];
$div="";
$div.='<div>';
$div.='<script type="text/javascript" src="' . ROOT_SITE . '/js/flexigrid.js"></script>';
$div.='<link rel="stylesheet" type="text/css" href="' . ROOT_SITE .$_themePath . '/css/flexigrid.css" />';
$div.="<table id='flexGridRanking$matchKey'  style='display:none; '></table>";

$div.='<script type="text/javascript">

	var columnSize = 202;
	if($.browser.msie)
	  columnSize = 182;

	$(document).ready(function() {

	$("#flexGridRanking' . $matchKey . '").flexigrid
		(
			{
			url: "get.live.match.ranking.php?MatchKey=' . $matchKey . '",
			dataType: "json",
			colGroupModel : [
				{display: "' . __encode($_authorisation->getConnectedUser()) . '", name : "NickName", width : 100, align: "left"},
				{display: " ", name : "Forecasts", width : 40, align: "center"},
				{display: " ", name : "Score", width : 45, align: "right"},
				{display: " ", name : "GroupRank", width : 53, align: "right"},
				{display: " ", name : "GlobalRank", width : 53, align: "right"}
				],

			colModel : [
				{display: "' . __encode("Joueur") . '", name : "NickName", width : 100, sortable : true, align: "left"},
				{display: "' . __encode("Pronos.") . '", name : "Forecasts", width : 40, sortable : true, align: "center"},
				{display: "' . __encode("Points") . '", name : "Score", width : 45, sortable : true, align: "right"},
				{display: "' . __encode("Clas. grp.") . '", name : "GroupRank", width : 53, sortable : true, align: "right"},
				{display: "' . __encode("Clas. gén.") . '", name : "GlobalRank", width : 53, sortable : true, align: "right"}
				],
			searchitems : [
				{display: "Joueur", name : "NickName"}
				],
			sortname: "Score",
			sortorder: "desc",
			usepager: true,
			useRp: true,
			rp: 9,
			striped:false,
			resizable: false,
			showTableToggleBtn: false,
			width: 355,
			height: 400,
			pagestat: "' . __encode("Affichage de {from} à {to} sur {total} joueurs") . '",
			singleSelect:true,
			 pagetext: "Page",
			 outof: "sur",
			 findtext: "Rechercher",
			 procmsg: "Traitement, patientez ...",
			 nomsg: "'. __encode("Pas de données") . '"
			}
		);

	$.ajax({
			type: "POST",
			url: "get.live.match.info.php?MatchKey='.$matchKey.'",
			data: { matchKey: '.$matchKey.'},
			  dataType: "json",
			  success: callbackRefreshMatchInfo,
			  error: callbackPostError
			});

	});


	function callbackRefreshMatchInfo (data)
	 {
	    $("div[class=ghDivBox] thead").each(function () {
	    	$(this).find("th:eq(1) div").text(data.Forecasts);
	    	$(this).find("th:eq(2) div").text(data.PlayerScore);
			$(this).find("th:eq(3) div").text(data.GroupRank);
			$(this).find("th:eq(4) div").text(data.GlobalRank);
	    });
	    //$("#flexGridRanking'.$matchKey.'").flexReload();

	 }

 function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
	 {
	 	$.log(XMLHttpRequest);
	 	$.log(textStatus);
	 	$.log(errorThrown);
	 }

</script>
</div>
';

$arr["status"] = false;
$arr["message"] =$div;

WriteJsonResponse($arr);
?>