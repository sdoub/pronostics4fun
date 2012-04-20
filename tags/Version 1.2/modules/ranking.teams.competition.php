<style>
.day {
	font-size: 14px;
	text-align: center;
	background-color: #6D8AA8;
	color: #FFFFFF;
	font-weight: bold;
}

.match {
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}

.teamRank
{
background: #D7E1F6; 
color: #FFF; 
text-align: center
}

.equipeRankUp {
	font-size: 12px;
	color: #365F89;
	
	font-weight: bold;
}
.equipeRankUp .teamRank
{
background: #9AC100; 
color: #FFF; 
text-align: center
}

.equipeRank {
	font-size: 12px;
	color: #365F89;
	font-weight: normal;
}
.equipeRank .teamRank
{
background: #52758E; 
color: #FFF; 
text-align: center
}

.equipeRankDown {
	font-size: 12px;
	color: #365F89;
	font-weight: normal;
}
.equipeRankDown .teamRank
{
background: #DE0000; 
color: #FFF; 
text-align: center
}


.teamAway {
	text-align: left;
	width: 150px;
}

.teamFlag {
	width: 30px;
}

.teamFlag img {
	width: 30px;
	height: 30px;
}

.score {
	width: 80px;
	text-align: center;
}

.teamHome {
	width: 150px;
	text-align: right;
}

.time {
	width: 60px;
	padding-left: 20px;
}


</style>
<div id="mainCol">

<center>
<div style="padding-top:20px;">
<table id="TeamsRanking">
	<thead>
	<tr class="day" >
		<th width="40px">&nbsp;</th>
		<th width="140px">Equipe</th>
		<th width="40px">Points</th>
		<th width="40px">Jou&eacute;</th>
		<th width="40px">G</th>
		<th width="40px">N</th>
		<th width="40px">P</th>
		<th width="40px">Bp</th>
		<th width="40px">Bc</th>
		<th width="40px">Diff.</th>
	</tr>
	</thead>
<tbody>
<?php

$arrTeams = GetTeamsRanking();
	$head = 0;
	$htmlGrid = "";

	while (list ($key, $value) = each ($arrTeams)) {
	  $head++;
	  if ($head>17) {
	    $htmlGrid .= '<tr class="equipeRankDown">';
	  }
	  else if ($head<=4) {
	    $htmlGrid .='<tr class="equipeRankUp">';
	  } 
	  else {
	    $htmlGrid .='<tr class="equipeRank">';
	  }

	  $htmlGrid .='<td class="teamRank" style="">' . $value['TeamRank'] . '</td>';
      $htmlGrid .='<td style="padding-left: 3px;"><img src="' . ROOT_SITE . '/images/teamFlags/' . $value['TeamKey'] . '.png" width="20px" height="20px" title="' . $value['TeamName']  . '"></img>' . $value['TeamName'] . '</td>';
      $htmlGrid .='<td style="text-align: center">' . $value['Score'] . '</td>';
      $htmlGrid .='<td style="text-align: center">' . $value['Played'] . '</td>';
      $htmlGrid .='<td style="text-align: center">' . $value['MatchWin'] . '</td>';
      $htmlGrid .='<td style="text-align: center">' . $value['MatchDraw'] . '</td>';
      $htmlGrid .='<td style="text-align: center">' . $value['MatchLost'] . '</td>';
      $htmlGrid .='<td style="text-align: center">' . $value['Goals'] . '</td>';
      $htmlGrid .='<td style="text-align: center">' . $value['GoalsAgainst'] . '</td>';
      $htmlGrid .='<td style="text-align: center">' . $value['GoalDifference']  . '</td>';
	  $htmlGrid .='</tr>';
	}
    echo $htmlGrid;
?>
</tbody>
</table>
</div>
<div style="font-size:9px;color:#FFFFFF;">
<?php 
echo __encode("Sous réserve d'homologation par la Commisssion d'Organisation des Compétitions de la LFP. 
<br/>En cas d'égalité de points, le classement des clubs ex-aequo est déterminé par la différence entre les buts marqués 
<br/>et les buts concédés par chacun d'eux au cours des matches joués pour l'ensemble de la division.
<br/>En cas de nouvelle égalité, avantage sera donné au club ayant marqué le plus grand nombre de buts.
<br/>En cas de nouvelle égalité, les clubs seront départagés à la différence de buts lors des rencontres disputées entre eux.");
?>
</div>
</center>


<script  type="text/javascript" >
$(document).ready(function() {
	$("#TeamsRanking > tbody tr:even").css("background-color", "#D7E1F6");

	//$("#playerDetail li:even").css("background-color", "#D7E1F6");
	//$("#playerDetail li:even").css("color","#365F89");
		

});

</script>
</div>