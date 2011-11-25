<script type="text/javascript" src="<?php echo ROOT_SITE;?>/js/jquery.smartTab.js"></script>
<link href="<?php echo ROOT_SITE;?>/css/smart_tab_vertical.css" rel="stylesheet" type="text/css">
 <script type="text/javascript" src="<?php echo ROOT_SITE;?>/js/jquery.countdown.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE;?>/js/jquery.countdown-fr.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_SITE;?>/css/jquery.countdown.css" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_SITE;?>/css/ranking.css" />

<div id="mainCol">

<div class="altBloc">
<div id="tabs" class="stContainer">
  			<ul class="tabs">

<?php
$scheduleDate = time();
$query= "SELECT
matches.PrimaryKey MatchKey,
matches.TeamHomeKey,
matches.TeamAwayKey,
TeamHome.Name TeamHomeName,
TeamAway.Name TeamAwayName,
matches.GroupKey,
groups.Description GroupName,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate
 FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
WHERE DATE(matches.ScheduleDate)=DATE(FROM_UNIXTIME($scheduleDate))
ORDER BY matches.ScheduleDate ASC";

$resultSet = $_databaseObject->queryPerf($query,"Get matches to be played by current day");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $matchKey = $rowSet["MatchKey"];
  $groupName = $rowSet["GroupName"];
  $teamHomeKey = $rowSet["TeamHomeKey"];
  $teamAwayKey = $rowSet["TeamAwayKey"];
  $teamHomeName = $rowSet["TeamHomeName"];
  $teamAwayName = $rowSet["TeamAwayName"];



  echo "  				<li>
  				  <a class='tabs' href='#tabs-$matchKey' match-key='$matchKey'>
<div style='background: #365F89; color:#FFF;text-align:center;float:center;'>$groupName</div>
<div style='float:right;position:absolute;text-align:right;width:150px;left:50px;font-size:9px' countdownvisible='true'></div>
<table >
<tr class='match' >
<td class='teamFlag' style='width: 40px;'><img src='" . ROOT_SITE . "/images/teamFlags/$teamHomeKey.png' width='30px' height='30px'></img></td>
<td class='teamHome' style='width: 150px;font-size: 12px;'>$teamHomeName</td>
<td style='width: 50px;font-weight: bold;text-align:center;' name='TeamHomeScore'>&nbsp;</td>
<td rowspan='2' style='font-size: 18px;text-align:right;' name='ActualTime' nowrap='nowrap'>&nbsp;</td>
</tr>
<tr >
<td class='teamFlag'><img src='" . ROOT_SITE . "/images/teamFlags/$teamAwayKey.png' width='30px' height='30px'></img></td>
<td class='teamAway' style='font-size: 12px;'>$teamAwayName</td>
<td style='width: 30px;font-weight: bold;text-align:center;' name='TeamAwayScore'>&nbsp;</td>
</tr>
</table>
<div style='float:left;position:absolute;text-align:right;width:150px;left:10px;font-size:9px; margin-top:-5px;_margin-top:-10px;' name='PlayerScore'></div>
                  </a>
                </li>";


}


?>
  			</ul>
<?php

$resultSet = $_databaseObject->queryPerf($query,"Get matches to be played by current day");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $matchKey = $rowSet["MatchKey"];
  $groupName = $rowSet["GroupName"];
  $teamHomeKey = $rowSet["TeamHomeKey"];
  $teamAwayKey = $rowSet["TeamAwayKey"];
  $teamHomeName = $rowSet["TeamHomeName"];
  $teamAwayName = $rowSet["TeamAwayName"];

echo "<div id='tabs-$matchKey' match-key='$matchKey' class='tabs' style='background:#D7E1F6;'>";
$scheduleDate = $rowSet["ScheduleDate"];

echo "<div style='display:none;'  countdown='true' year='". date("Y",$scheduleDate) ."' month='". date("n",$scheduleDate) ."' day='". date("j",$scheduleDate) ."' hour='". date("G",$scheduleDate) ."' minute='". date("i",$scheduleDate) ."'></div>";

echo '<script type="text/javascript" src="' . ROOT_SITE . '/js/flexigrid.js"></script>';
echo '<link rel="stylesheet" type="text/css" href="' . ROOT_SITE . '/css/flexigrid.css" />';
echo "<table id='flexGridRanking$matchKey'  style='display:none; '></table>";

echo '<script type="text/javascript">

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
			width: 365,
			height: 380,
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
</script>
</div>
';
}
?>

<!--   			<div id="tabs-2" class="tabs"  style="background:url('<?php echo ROOT_SITE;?>/images/afriquedusud.png') no-repeat center top #D7E1F6;">
  			</div>
  			 -->

</div>

<script>

$(document).ready(function(){
// Smart Tab
	$('#tabs').smartTab({autoProgress: false,transitionEffect:'fade'});
	$('div[countdown]').each(function (){
		var nextDate = new Date();
		nextMatch = new Date($(this).attr("year"), $(this).attr("month"), $(this).attr("day"), $(this).attr("hour"),$(this).attr("minute"),0);
		var expiryHtml;
		expiryHtml='<div class="over">Periode</div>';
		$(this).css("dislay","none");
		$(this).countdown({until: nextMatch,
		layout: 'dans {hnn}{sep}{mnn}',
		alwaysExpire : true,
		onTick: everyMinute,
		onExpiry: countDownHasExpired,
		tickInterval: 5
		});
	});

	function everyMinute(periods) {
		var htmlTimer= $(this).context.innerHTML;
		var parentCountDisplay = $($(this).context.parentNode);
		var matchKey = $(parentCountDisplay).attr('match-key');
	    $('div[countdownvisible]',$('a[match-key='+matchKey+']')).each(function () {
    		$(this).text(htmlTimer);
	    });
	}

	function addDays(myDate,days) {
	    return new Date(myDate.getTime() + days*24*60*60*1000);
	}

	 function countDownHasExpired(){
		var parentCountDisplay = $($(this).context.parentNode);
		var matchKey = $(parentCountDisplay).attr('match-key');
		var currentMatch = new Date();
		nextMatch = new Date(currentMatch.getFullYear(), currentMatch.getMonth()+1, currentMatch.getDate(), 23,30,0);

		$.ajax({
			type: "POST",
			url: "get.live.match.info.php?MatchKey=" + matchKey,
			data: { matchKey: matchKey},
			  dataType: 'json',
			  success: callbackRefreshMatchInfo,
			  error: callbackPostError
			});
		$(parentCountDisplay).prepend("<div style='float:right;' countup='true' match-key='" + matchKey + "'></div>");
		$('div[countup]',parentCountDisplay).fadeIn('slow',function () {
			$(this).countdown({until: nextMatch,
			layout: "{snn} sec",
			onTick: refreshMatchInfo,
			tickInterval: 1
			});
		});
	 }

	 function refreshMatchInfo (periods) {
		if (periods[6] == 1) {
		 var parentCountDisplay = $($(this).context.parentNode);
			var matchKey = $(parentCountDisplay).attr('match-key');
		 //var matchKey = $(this).attr('match-key');
			$.ajax({
				type: "POST",
				url: "get.live.match.info.php?MatchKey=" + matchKey,
				data: { matchKey: matchKey},
				  dataType: 'json',
				  success: callbackRefreshMatchInfo,
				  error: callbackPostError
				});
	   }
	 }

	 function callbackRefreshMatchInfo (data)
	 {
	    $('div[countdownvisible]',$('a[match-key='+data.MatchKey+']')).each(function () {
	    	if (data.LiveStatus)
    			$(this).html(data.LiveStatus);
	    	else
	    		$(this).text("");
	    });

	    $('div[countup][match-key='+data.MatchKey+']').each(function () {
	    	var currentMatch = new Date();
	    	if (data.Status==10)
	    	{
		    	$(this).hide().countdown({until:currentMatch,alwaysExpire : false});
	    		$(this).countdown('pause');
	    	}

	    });
	    $('td[name=TeamHomeScore]',$('a[match-key='+data.MatchKey+']')).each(function () {
    		$(this).text(data.TeamHomeScore);
	    });
	    $('td[name=TeamAwayScore]',$('a[match-key='+data.MatchKey+']')).each(function () {
    		$(this).text(data.TeamAwayScore);
	    });
	    $('td[name=ActualTime]',$('a[match-key='+data.MatchKey+']')).each(function () {
    		$(this).text(data.ActualTime + " '");
	    });
	    $('div[name=PlayerScore]',$('a[match-key='+data.MatchKey+']')).each(function () {
    		$(this).text("Score : " + data.PlayerScore + " points");
	    });
	    $('div[class=ghDivBox] thead',$('div[id=tabs-'+data.MatchKey+']')).each(function () {
	    	$(this).find("th:eq(1) div").text(data.Forecasts);
	    	$(this).find("th:eq(2) div").text(data.PlayerScore);
			$(this).find("th:eq(3) div").text(data.GroupRank);
			$(this).find("th:eq(4) div").text(data.GlobalRank);


	    });
	    $("#flexGridRanking" + data.MatchKey).flexReload();

		if (data.Status!=10) {
	    	$('a[match-key='+data.MatchKey+']').trigger("click");
		}
// Refresh global ranking
		$.ajax({
			type: "POST",
			url: "get.live.global.ranking.php",
			data: { matchKey: ""},
			  dataType: 'json',
			  success: callbackRefreshLiveRanking,
			  error: callbackPostErrorRank
			});


	 }
	 function callbackRefreshLiveRanking (data)
	 {
		 $.each
         (
          data.rows,
          function(i,row)
                 {

        	  var htmlContent = '<a class=\'popupscroll\' href=\'javascript:void(0);\'><img class=\'avat\' src=\'' + row.AvatPath+ '\'></img></a><p>';
        	  htmlContent += '<a class=\'popupscroll\' href=\'javascript:void(0);\'>' + row.NickName + '<em>' + row.Score + ' pts</em>';
        	  htmlContent += '<span class=\'var ' + row.Variation + '\' title=\' ' + row.PreviousRank +'|' + row.Rank + '\'></span></a></p></li>';

        	  $("li:eq("+ i +")",$("#mod-classements")).fadeOut('slow',
                	  function () {
            	  			$(this).html(htmlContent).fadeIn('slow');
                 		}
       		);
                 }
	    );
	 }

	 function callbackPostErrorRank (XMLHttpRequest, textStatus, errorThrown)
	 {
	 	$.log(XMLHttpRequest);
	 	$.log(textStatus);
	 	$.log(errorThrown);
	 }

	 function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
	 {
	 	$.log(XMLHttpRequest);
	 	$.log(textStatus);
	 	$.log(errorThrown);
	 }
});
</script>
</div>
<div class="mainBloc">
<div id="countup" style="display: none;"></div>
<div id="mod-classements" class="node2">
<div class="head">
<div>
<h4><?php echo __encode("Classement Général - Live"); ?></h4>
</div>
</div>
<div class="node-in">
<div class="panel">
<ol>


<?php

$sql = "SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName
LIMIT 0,5";

$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
$rank=0;
$realRank=0;
$previousScore=0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  switch ($cnt) {
    case 0:
      echo '<li class="first">';
      break;
    case 4:
      echo '<li class="last">';
      break;
    default:
      echo '<li>';
      break;
  }
  $cnt++;

  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }

  echo '<a class="popupscroll" href="#"><img class="avat" src="' . $avatarPath .'"></img></a>';

  $realRank++;
    if ($previousScore>$rowSet["Score"]||$previousScore==0) {
    $rank=$realRank;
  }
  $previousScore=$rowSet["Score"];
  if ($rowSet["PreviousRank"]) {
    if ($rowSet["PreviousRank"]>$rank) {
      $variation = "up";
    }
    else if ($rowSet["PreviousRank"]<$rank) {
      $variation = "down";
    }
    else {
      $variation = "eq";
    }
  }
  else
  {
    $variation = "eq";
  }


  echo '<p><a class="popupscroll" href="#">'. $rowSet["NickName"] .'<em>'. $rowSet["Score"] . ' pts</em><span class="var ' . $variation . '" ></span></a></p>';
  echo '</li>';


}
?>
</ol>

</div>
<!-- <a href="index.php?Page=3">Voir tous le classement</a></div>-->
</div>
</div>
</div>
</div>