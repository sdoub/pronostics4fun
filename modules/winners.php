<?php
	AddScriptReference("scrollpane");
	AddScriptReference("json2html");
	AddScriptReference("winners");
	WriteScripts();
?>

<div class="mainTitle">
		Palmarès
</div>
<div id="winnersContainer">
	<ol id="winners">
	</ol>
</div>
<script>
	
	function getDivisionRows (data) {
		var returnedValue="";
		$.each(data, function (index,season){
			returnedValue+=json2html.transform(season,transforms.rowTitleSeason);
			$.each(season.divisions, function (index,division){
				returnedValue+=json2html.transform(division,transforms.winnerChpDivision);
			});

		});
		return returnedValue;
	}
	
var transforms = {
 season: [
   {"tag":"li","class":"competition${competitionType}","competition-key":"${competitionKey}","children":[
    {"tag":"div","class":"competitionTitle","html":"${season}","children":[
        {"tag":"img","src":"/images/${competitionType}.trophee.png","class":"${competitionType}","html":""}
      ]},
    {"tag":"table","border":"0","cellspacing":"0","cellpadding":"0","class":"winners","children":[
        {"tag":"thead","children":[
            {"tag":"tr","children":[
                {"tag":"th","class":"first","html":""},
                {"tag":"th","html":"1","children":[
                    {"tag":"sup","html":"er"}
                  ]},
                {"tag":"th","html":"2","children":[
                    {"tag":"sup","html":"ème"}
                  ]},
                {"tag":"th","html":"3","children":[
                    {"tag":"sup","html":"ème"}
                  ]}
              ]}
          ]},
        {"tag":"tbody","children":function() {
        return(json2html.transform(this.winners,transforms.winner)
							 +json2html.transform(this.winnersCup,transforms.winnerCup)
							 +getDivisionRows(this.winnersChp));
    }}
      ]}
  ]} 
 ],
 winner: [{"tag":"tr","children":function() {
        return(json2html.transform(this,transforms.rowTitle)+json2html.transform(this.players,transforms.playerWin));
    }}],
	rowTitle:[{"tag":"td","class":"first","children":[
                    {"tag":"span","class":"rowTitle","html":"${winTitle}"}
                  ]}],
	playerWin:[
		{"tag":"td","children":[
                    {"tag":"span","class":"player","html":"${name}"},
									  {"tag":"span","html":" (${score}pts)"}
     ]}
	],
	playerCupWin:[
		{"tag":"td","children":[
                    {"tag":"span","class":"player","html":"${name}"}
     ]}
	],
	playerChpWin:[
		{"tag":"td","children":[
                    {"tag":"span","class":"player","html":"${name}"},
									  {"tag":"span","html":" (${score}pts ${pointsDifference})"}
     ]}
	],
	winnerCup : [{"tag":"tr","children":function() {
        return(json2html.transform(this,transforms.rowTitle)+json2html.transform(this.players,transforms.playerCupWin));
    }}],
	rowTitleChp : [  {"tag":"td","class":"first","children":[
          {"tag":"span","html":"${division}"}
        ]}],
	rowTitleSeason : [{"tag":"tr","class":"winnersP4FChp","children":[
      {"tag":"td","class":"first","html":"Championnat P4F"},
      {"tag":"td","colspan":"3","html":"${seasonTitle}"}
    ]}],
	winnerChpDivision : [
  {"tag":"tr","class":"winnersP4FChpSeason","children":function() {
        return(json2html.transform(this,transforms.rowTitleChp)
							 +json2html.transform(this.players,transforms.playerChpWin));
    }}
]
};
	for (var competition = 8; competition > 0; competition--) {
		$.ajax({
		dataType: "json",
		async: true,
		url: "/data/winners.competition."+competition+".json",
		success: function(season) {
				if (season) {
					$('#winners').json2html(season, transforms.season);
					// call sort function and get sorted list
					var sortedLis =  $("#winners").find(" li").sort(
						function(a,b){
							var first  = $(a).attr('competition-key');
							var second  = $(b).attr('competition-key');
							return parseInt(second) - parseInt(first);
						});
					// empty the current list and add sorted list
					$("#winners").empty().append(sortedLis);
					if (competition==1){
						$('#winners').jScrollPane({
							showArrows: true,
							horizontalGutter: 10,
							autoReinitialise: true
						});
					}
				}
			}
		});
	}
$(document).ready(function() {

/*	$('#winnersContainer').jScrollPane({
		showArrows: true,
		horizontalGutter: 10,
		autoReinitialise: true
	});*/
/*$.each(winnersData,function (index,season){
	$.log(season);
});*/
});
</script>