<?php

/* AddScriptReference("gracket"); */
AddScriptReference("cluetip");
AddScriptReference("dropdown");
AddScriptReference("p4f.cup.draw");
WriteScripts();
$_seasonKey=5;
if (isset($_GET["SeasonKey"]))
  $_seasonKey = $_GET["SeasonKey"];

$query = "SELECT DISTINCT CupRoundKey,
								 playercupmatches.GroupKey,
								 cuprounds.Description,
								 groups.Description GroupName
            FROM playercupmatches
						INNER JOIN cuprounds ON cuprounds.PrimaryKey=playercupmatches.CupRoundKey
						INNER JOIN groups ON groups.PrimaryKey=playercupmatches.GroupKey
           WHERE SeasonKey=$_seasonKey
           ORDER BY CupRoundKey,playercupmatches.PrimaryKey";
$resultSet = $_databaseObject->queryPerf($query,"Get round");
$cupRounds = array();
$currentRoundKey = "";
$currentRoundDesc = "";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
	$roundInfo = array();
	$roundInfo["Key"]=$rowSet["CupRoundKey"];
	$roundInfo["Description"]=$rowSet["Description"];
	$roundInfo["GroupKey"]=$rowSet["GroupKey"];
	$roundInfo["GroupName"]=$rowSet["GroupName"];
	$cupRounds[]=$roundInfo;
	$currentRoundKey = $rowSet["CupRoundKey"];
	$currentRoundDesc = $rowSet["Description"];
}

$query = "SELECT playercupmatches.PlayerHomeKey, HomePlayer.NickName HomeNickName, 
                 playercupmatches.PlayerAwayKey, AwayPlayer.NickName AwayNickName,
                 CupRoundKey, playercupmatches.HomeScore, playercupmatches.AwayScore,
								 playercupmatches.GroupKey
            FROM playercupmatches
           LEFT JOIN players HomePlayer ON HomePlayer.PrimaryKey =playercupmatches.PlayerHomeKey
           LEFT JOIN players AwayPlayer ON AwayPlayer.PrimaryKey =playercupmatches.PlayerAwayKey
           WHERE SeasonKey=$_seasonKey
             AND CupRoundKey NOT IN (9)
           ORDER BY playercupmatches.PrimaryKey";
$resultSet = $_databaseObject->queryPerf($query,"Get round");
$rounds = array();
$matches = array();
$roundKey = 0;
$_groupKey ="";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  if ($_groupKey=="")
		$_groupKey = $rowSet["GroupKey"];
	if ($roundKey==0)
    $roundKey = $rowSet["CupRoundKey"];

  if ($roundKey!=$rowSet["CupRoundKey"]) {
    $rounds[]=$matches;
    $matches = array();
    $roundKey = $rowSet["CupRoundKey"];
  }
  $match = array();
  $match[0]["name"] = $rowSet["HomeNickName"];
  $match[0]["id"] = $rowSet["PlayerHomeKey"];
  $match[0]["seed"] = $rowSet["PlayerHomeKey"];
  $match[0]["displaySeed"] = "";
	$match[0]["score"]="";
  if ($rowSet["HomeScore"])
  {
    $match[0]["score"] = $rowSet["HomeScore"];
    if ($rowSet["HomeScore"]>$rowSet["AwayScore"])
      $match[0]["displaySeed"] = "<img src='images/trophy.gold.png' style='width:10px;height:10px;'/>";
  }

  if ($rowSet["PlayerAwayKey"] == -1) {
    $match[0]["displaySeed"] = "<img src='images/trophy.gold.png' style='width:10px;height:10px;'/>";
    if ($rowSet["HomeScore"]==0 && $_seasonKey==1)
      $match[0]["displaySeed"] = "<span title='disqualifié pour non participation'>*</span>";
    $match[1]["name"] = "-";
    $match[1]["id"] = $rowSet["PlayerAwayKey"];
    $match[1]["seed"] = $rowSet["PlayerAwayKey"];
    $match[1]["displaySeed"] = "";
		$match[1]["score"]="";
  } else {
    $match[1]["name"] = $rowSet["AwayNickName"];
    $match[1]["id"] = $rowSet["PlayerAwayKey"];
    $match[1]["seed"] = $rowSet["PlayerAwayKey"];
    $match[1]["displaySeed"] = "";
		$match[1]["score"]="";
    if ($rowSet["AwayScore"])
    {
      $match[1]["score"] = $rowSet["AwayScore"];
      if ($rowSet["AwayScore"]>$rowSet["HomeScore"])
        $match[1]["displaySeed"] = "<img src='images/trophy.gold.png' style='width:10px;height:10px;'/>";
      if ($rowSet["AwayScore"]==$rowSet["HomeScore"])
        $match[1]["displaySeed"] = "<img src='images/trophy.gold.png' style='width:10px;height:10px;'/>";

    }

  }

  $matches[]=$match;
	$matches["CupRoundKey"] = $rowSet["CupRoundKey"];
	$matches["GroupKey"] = $rowSet["GroupKey"];

}
$rounds[]=$matches;

echo '<div id="" style="color: #fff;font-size: xx-small;padding-bottom: 10px;">* : disqualifié au premier tour pour non participation</div>';
echo '<div id="" style="width:940px;overflow:hidden;">
<div id="cupDraw" ></div><div id="cupDraw3rd" ></div></div>';
?>
<div id="cupContainer">
	<div id="cupHeader">
		<div class="prev">
			<a href="#">Précedent</a>
		</div>
		<div class="cupRoundTitle">
			<a href="#" data-jq-dropdown="#jq-dropdown-1"><?php echo $currentRoundDesc; ?></a>
		</div>
		<div class="next">
			<a href="#">Suivant</a>
		</div>
		<div id ="cupSubHeader" style="text-align:center;font-size:9px;" rel="get.player.group.detail.php?GroupKey="> </div>
	</div>
	<ul>
<?php
$htmlMatches = "";
for ($roundIndex = 0; $roundIndex < count($rounds); $roundIndex++) {
  $matches = $rounds[$roundIndex];
	for ($matchIndex = 0; $matchIndex < count($matches)-2; $matchIndex++){
		$liClass="";
		if (count($matches)-2 ==1)
			$liClass .= " oneMatch"; 
		
		if ($matchIndex % 2 >0)
			$liClass .= " odd";
		$matches[$matchIndex];
		//var_dump($matches);
		$groupKey = $matches["GroupKey"];
		$cupRoundKey = $matches["CupRoundKey"];
		$firstPlayerKey = $matches[$matchIndex][0]["id"];
		$firstPlayerName = $matches[$matchIndex][0]["name"];
		$firstPlayerScore = $matches[$matchIndex][0]["score"];
		$secondPlayerKey = $matches[$matchIndex][1]["id"];
		$secondPlayerName = $matches[$matchIndex][1]["name"];
		$secondPlayerScore = $matches[$matchIndex][1]["score"];
		
		if ($firstPlayerScore>$secondPlayerScore) {
			$winnerPlayerKey=$firstPlayerKey;
			$winnerPlayer = $firstPlayerName;
			$firstPlayerState = '';
			$secondPlayerState = 'loser';
		} else {
			$winnerPlayerKey=$secondPlayerKey;
			$winnerPlayer = $secondPlayerName;
			$firstPlayerState = 'loser';
			$secondPlayerState = '';
		}
		if ($cupRoundKey==1 && $firstPlayerScore==0 && $secondPlayerKey=-2 && count($rounds)>1)
			$firstPlayerName.="<sup>*</sup>";

		$winner = '';
		$winner.='<a href="#" >';
		$winner.='<div class="avatar">';
		$winner.='<img src="/avatar.php?PlayerKey='.$winnerPlayerKey.'"/>';
		$winner.='</div>';
		$winner.=$winnerPlayer;
		$winner.='</a>';
		$tooltip = 'rel="get.player.group.detail.php?GroupKey='.$groupKey.'&PlayerKeys='.$firstPlayerKey.','.$secondPlayerKey.'"';
		// is match played
		if ($firstPlayerScore==null && $secondPlayerScore==null){
			$winnerPlayer = '';
			$firstPlayerState = '';
			$secondPlayerState = '';
			$winner = '';
			$tooltip = '';
		}
		$htmlMatches.='<li class="'.$liClass.'" style="display:none;" data-cup-round-key="'.$cupRoundKey.'" data-group-key="'.$groupKey.'" '.$tooltip.'>';
		$htmlMatches.='<div class ="player spacerRight ';
		if ($matchIndex % 2 >0)
			$htmlMatches.='winner';
		if ($cupRoundKey>4)
			$htmlMatches.=' noscrollbar';
		$htmlMatches.='">';
		if ($matchIndex % 2 >0)
			$htmlMatches.=$winner;
		$htmlMatches.='</div>';
		$htmlMatches.='<div class="player first ';
		$htmlMatches.=$firstPlayerState;
		$htmlMatches.='">';
		$htmlMatches.='<a href="#" style="">';
		$htmlMatches.='<div class="avatar">';
		$htmlMatches.='<img src="/avatar.php?PlayerKey='.$firstPlayerKey.'"/>';
		$htmlMatches.='</div>';
		$htmlMatches.=$firstPlayerName;
		$htmlMatches.='</a>';
		$htmlMatches.='<div class="score">';
		$htmlMatches.=$firstPlayerScore;
		$htmlMatches.='</div>';
		$htmlMatches.='</div>';
		$htmlMatches.='<div class ="player spacerRight ';
		if ($matchIndex % 2 == 0)
			$htmlMatches.='winner';
		if ($cupRoundKey>4)
			$htmlMatches.=' noscrollbar';
		$htmlMatches.='">';
		if ($matchIndex % 2 == 0)
			$htmlMatches.=$winner;
		$htmlMatches.='</div>';
		$htmlMatches.='<div class="player opponent ';
		$htmlMatches.=$secondPlayerState;
		$htmlMatches.='">';
		$htmlMatches.='<a href="#">';
		$htmlMatches.='<div class="avatar">';
		$htmlMatches.='<img src="/avatar.php?PlayerKey='.$secondPlayerKey.'"/>';
		$htmlMatches.='</div>';
		$htmlMatches.=$secondPlayerName;
		$htmlMatches.='</a>';
		$htmlMatches.='<div class="score">';
		$htmlMatches.=$secondPlayerScore;
		$htmlMatches.='</div>';
		$htmlMatches.='</div>';
		$htmlMatches.='</li>';
	} 
}
echo $htmlMatches;
?>
	</ul>
</div>
<div id="jq-dropdown-1" class="jq-dropdown jq-dropdown-tip">
	<ul class="jq-dropdown-menu">
<?php
$html='';
for ($index=0;$index<count($cupRounds);$index++) {
	$html.='<li data-cup-round-key="'.$cupRounds[$index]["Key"].'" data-group-name="'.$cupRounds[$index]["GroupName"].'" data-group-key="'.$cupRounds[$index]["GroupKey"].'"><a href="#1">'.$cupRounds[$index]["Description"].'</a></li>';
}
echo $html;

?>
	</ul>
</div>
<script>
	var cluetipOptions = {positionBy:'auto',
				showTitle:false,
				width:715,
				ajaxCache:false,
				cluetipClass:'p4f',
				arrows:false,
				sticky:false,
			  onShow:function (ct, ci) {
/* 					$("#cluetip-close").hide();
					$('#divP4FChp,#cluetip').spotlight({color:'#ffffff',onHide: function(){
						$("#cluetip-close").trigger("click");
					}	});
 */
					$("#playerDetail2 li:visible").each(function (index) {
						if ((index % 2) == 0) {
							$(this).removeClass('resultRowOdd');
							$(this).addClass('resultRow');
						} else {
							$(this).removeClass('resultRow');
							$(this).addClass('resultRowOdd');
						}
					});
				}
			};
	$(document).ready(function() {
		$("li").cluetip(cluetipOptions);

		$("#jq-dropdown-1").appendTo("body");

		$("li",$("#jq-dropdown-1")).click (function() {
			var cupRoundKey = $(this).attr('data-cup-round-key');

			/* 			var cupRoundDesc = $(this).text();
			$("a",$("div.cupRoundTitle")).filter(function (index){
				return $(this).has('data-cup-round-key');
			}).html(cupRoundDesc);  */
			displayRound(cupRoundKey);

			$('#jq-dropdown-1').trigger("click");
		});

		var currentRoundKey = <?php echo $currentRoundKey; ?>;
		function displayRound(cupRoundKey){
			cupRoundKey = parseInt(cupRoundKey);
			var cupRoundDesc = $("li[data-cup-round-key='"+cupRoundKey+"']",$("#jq-dropdown-1")).text();
			var cupRoundGroupName = $("li[data-cup-round-key='"+cupRoundKey+"']",$("#jq-dropdown-1")).attr('data-group-name');
			var cupRoundGroupKey = $("li[data-cup-round-key='"+cupRoundKey+"']",$("#jq-dropdown-1")).attr('data-group-key');

			$("div.cupRoundTitle a").html(cupRoundDesc);
			$("#cupSubHeader").html(cupRoundGroupName); 
			$("#cupSubHeader").attr('rel','get.player.group.detail.php?GroupKey='+cupRoundGroupKey+'&PlayerKeys=-1');
			$("#cupSubHeader").cluetip(cluetipOptions);


			$( "li",$("#cupContainer") ).hide();
			$( "li",$("#cupContainer") ).filter(function( index ) {
				return $(this).attr('data-cup-round-key') == cupRoundKey;
			}).fadeIn();
			currentRoundKey = cupRoundKey;
			var nextRound = $( "li",$("#cupContainer") ).filter(function( index ) {
				return $(this).attr('data-cup-round-key') == (cupRoundKey+1);
			}).size();
			var prevRound = $( "li",$("#cupContainer") ).filter(function( index ) {
				return $(this).attr('data-cup-round-key') == (cupRoundKey-1);
			}).size();
			if (nextRound==0)
				$("div.next > a").addClass('disable');
			else
				$("div.next > a").removeClass('disable');
			if (prevRound==0)
				$("div.prev > a").addClass('disable');
			else
				$("div.prev > a").removeClass('disable');

		}
		displayRound(<?php echo $currentRoundKey; ?>);
		
		$("div.next > a").click(function (){
			if ($(this).hasClass('disable')) {
				
			} else {
				displayRound(currentRoundKey+1);
			}
		});
		$("div.prev > a").click(function (){
			if ($(this).hasClass('disable')) {
				
			} else {
				displayRound(currentRoundKey-1);
			}
		});

	});
</script>