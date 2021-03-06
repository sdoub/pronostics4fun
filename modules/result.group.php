<?php

if (isset($_GET["GroupKey"])) {
  $query = "SELECT PrimaryKey GroupKey, Description FROM groups WHERE groups.PrimaryKey= " . $_GET["GroupKey"];
}
else {
  $query = "SELECT PrimaryKey GroupKey, Description FROM groups WHERE groups.PrimaryKey= (SELECT MAX(PrimaryKey) FROM groups LastGroup WHERE IsCompleted=1 AND CompetitionKey=" . COMPETITION . ")";
}

$resultSet = $_databaseObject -> queryPerf ($query, "Get group info");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);

$_groupDescription=$rowSet["Description"];
$_groupKey= $rowSet["GroupKey"];

if (!$_groupKey) {
  $query = "SELECT MIN(PrimaryKey) GroupKey, Description FROM groups WHERE groups.CompetitionKey= " . COMPETITION ;

  $resultSet = $_databaseObject -> queryPerf ($query, "Get group info");
  $rowSet = $_databaseObject -> fetch_assoc ($resultSet);

  $_groupDescription=$rowSet["Description"];
  $_groupKey= $rowSet["GroupKey"];

}

AddScriptReference("countdown");
AddScriptReference("scrollpane");
AddScriptReference("bsmselect");
AddScriptReference("cluetip");
AddScriptReference("progressbar");
AddScriptReference("result.group");
AddScriptReference("overflow");
AddScriptReference("dropdownchecklist");
AddScriptReference("spotlight");
WriteScripts();
$connectPlayerKey =  $_authorisation->getConnectedUserKey();

$sqlQueryP4FChamp = "SELECT 
PlayerHomeKey, HomePlayer.NickName HomePlayerNickName, HomePlayer.AvatarName HomePlayerAvatar,
PlayerAwayKey, AwayPlayer.NickName AwayPlayerNickName, AwayPlayer.AvatarName AwayPlayerAvatar,
HomeScore, AwayScore, playerdivisionmatches.DivisionKey
FROM playerdivisionmatches 
INNER JOIN players HomePlayer ON HomePlayer.PrimaryKey=playerdivisionmatches.PlayerHomeKey
INNER JOIN players AwayPlayer ON AwayPlayer.PrimaryKey=playerdivisionmatches.PlayerAwayKey
WHERE GroupKey=$_groupKey AND (PlayerHomeKey=$connectPlayerKey OR PlayerAwayKey=$connectPlayerKey) ";

$rowsSetP4FCh = $_databaseObject -> queryGetFullArray ($sqlQueryP4FChamp, "Get p4f championship info");
$homeAvatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
if (!empty($rowsSetP4FCh[0]["HomePlayerAvatar"])) {
	$homeAvatarPath= ROOT_SITE. '/images/avatars/'.$rowsSetP4FCh[0]["HomePlayerAvatar"];
}
$awayAvatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
if (!empty($rowsSetP4FCh[0]["AwayPlayerAvatar"])) {
	$awayAvatarPath= ROOT_SITE. '/images/avatars/'.$rowsSetP4FCh[0]["AwayPlayerAvatar"];
}

$sqlQueryP4FCup = "SELECT 
PlayerHomeKey, HomePlayer.NickName HomePlayerNickName, HomePlayer.AvatarName HomePlayerAvatar,
PlayerAwayKey, AwayPlayer.NickName AwayPlayerNickName, AwayPlayer.AvatarName AwayPlayerAvatar,
HomeScore, AwayScore, playercupmatches.CupRoundKey, cuprounds.Description RoundDescription
FROM playercupmatches 
INNER JOIN players HomePlayer ON HomePlayer.PrimaryKey=playercupmatches.PlayerHomeKey
LEFT JOIN players AwayPlayer ON AwayPlayer.PrimaryKey=playercupmatches.PlayerAwayKey
INNER JOIN cuprounds ON cuprounds.PrimaryKey=playercupmatches.CupRoundKey
WHERE GroupKey=$_groupKey AND (PlayerHomeKey=$connectPlayerKey OR PlayerAwayKey=$connectPlayerKey) ";

$rowsSetP4FCup = $_databaseObject -> queryGetFullArray ($sqlQueryP4FCup, "Get p4f championship info");

$sqlQueryP4FCurrentCup = "SELECT 
DISTINCT playercupmatches.CupRoundKey, cuprounds.Description RoundDescription
FROM playercupmatches 
INNER JOIN cuprounds ON cuprounds.PrimaryKey=playercupmatches.CupRoundKey
WHERE GroupKey=$_groupKey ";

$rowsSetP4FCurrentCupRound = $_databaseObject -> queryGetFullArray ($sqlQueryP4FCurrentCup, "Get p4f championship info");

$homeAvatarPathCup = ROOT_SITE. '/images/DefaultAvatar.jpg';
if (!empty($rowsSetP4FCup[0]["HomePlayerAvatar"])) {
	$homeAvatarPathCup= ROOT_SITE. '/images/avatars/'.$rowsSetP4FCup[0]["HomePlayerAvatar"];
}
$awayAvatarPathCup = ROOT_SITE. '/images/DefaultAvatar.jpg';
if (!empty($rowsSetP4FCup[0]["AwayPlayerAvatar"])) {
	$awayAvatarPathCup= ROOT_SITE. '/images/avatars/'.$rowsSetP4FCup[0]["AwayPlayerAvatar"];
}

if (count($rowsSetP4FCh)>0 && $rowsSetP4FCh[0]["HomeScore"]!=null) {
	$playerChpKeys = $rowsSetP4FCh[0]["PlayerHomeKey"] . "," . $rowsSetP4FCh[0]["PlayerAwayKey"];
	if (count($rowsSetP4FCup)>0 && $rowsSetP4FCup[0]["HomeScore"]!=null) 
		$playerCupKeys = $rowsSetP4FCup[0]["PlayerHomeKey"] . "," . $rowsSetP4FCup[0]["PlayerAwayKey"];

?>
<div style="height:150px;display:block;">
	<div style="padding-right:80px;margin-bottom:20px;text-align:center;font-size:16px;font-weight:bold;font-family:Georgia,Arial,Helvetica,sans-serif;font-variant: small-caps;color:#ffffff;">Duel P4F - </div>
	<div id="divP4FChp" style="float:left;background-color:#6D8AA8;width:300px;height:70px;margin-left:100px;" rel="get.player.group.detail.php?GroupKey=<?php echo $_groupKey;?>&PlayerKeys=<?php echo $playerChpKeys; ?>">
		<div style="text-align:center;font-size:12px;font-weight:bold;font-family:Georgia,Arial,Helvetica,sans-serif;font-variant: small-caps;color:#ffffff;">
			Championnat - Division <?php echo $rowsSetP4FCh[0]["DivisionKey"]; ?>
		</div>
		<div style="height:30px;float:left;">
			<div style="text-align:center;width:150px;">
				<img style="width: 27px;height: 27px;margin-left: 50px;margin-right: 50px;padding: 3px;" src="<?php echo $homeAvatarPath; ?>"/>
				<div style="font-size:12px;color:#ffffff;"><?php echo $rowsSetP4FCh[0]["HomePlayerNickName"]?></div>
			</div>
		</div>
		<div style="height:30px;float:right;">
			<div style="text-align:center;width:150px;">
				<img style="width: 27px;height: 27px;margin-left: 50px;margin-right: 50px;padding: 3px;" src="<?php echo $awayAvatarPath; ?>"/>
				<div style="font-size:12px;color:#ffffff;"><?php echo $rowsSetP4FCh[0]["AwayPlayerNickName"];?></div>
			</div>
		</div>
		<div style="position:absolute;width:100px;top:71px;left:231px;text-align:center;font-size:24px;color:#ffffff;">
			<span id="ScoreHomeChampionship" data-player-key="<?php echo $rowsSetP4FCh[0]["PlayerHomeKey"]?>"><?php echo $rowsSetP4FCh[0]["HomeScore"];?></span>
			<span> - </span>
			<span id="ScoreAwayChampionship" data-player-key="<?php echo $rowsSetP4FCh[0]["PlayerAwayKey"]?>"><?php echo $rowsSetP4FCh[0]["AwayScore"];?></span>
			
		</div>
	</div>
	<div id="divP4FCup" style="float:right;background-color:#6D8AA8;width:300px;height:70px;;margin-right:100px;" rel="get.player.group.detail.php?GroupKey=<?php echo $_groupKey;?>&PlayerKeys=<?php echo $playerCupKeys; ?>">
<?php if (count($rowsSetP4FCurrentCupRound)>0) {?>
		<div style="text-align:center;font-size:12px;font-weight:bold;font-family:Georgia,Arial,Helvetica,sans-serif;font-variant: small-caps;color:#ffffff;">
			Coupe - <?php echo $rowsSetP4FCurrentCupRound[0]["RoundDescription"]; ?>
		</div>
<?php if (count($rowsSetP4FCup)>0) { ?>
	<?php if ($rowsSetP4FCup[0]["PlayerAwayKey"]>-1) { ?>
		<div style="height:30px;float:left;">
			<div style="text-align:center;width:150px;">
				<img style="width: 27px;height: 27px;margin-left: 50px;margin-right: 50px;padding: 3px;" src="<?php echo $homeAvatarPathCup; ?>"/>
				<div style="font-size:12px;color:#ffffff;"><?php echo $rowsSetP4FCup[0]["HomePlayerNickName"]?></div>
			</div>
		</div>
		<div style="height:30px;float:right;">
			<div style="text-align:center;width:150px;">
				<img style="width: 27px;height: 27px;margin-left: 50px;margin-right: 50px;padding: 3px;" src="<?php echo $awayAvatarPathCup; ?>"/>
				<div style="font-size:12px;color:#ffffff;"><?php echo $rowsSetP4FCup[0]["AwayPlayerNickName"];?></div>
			</div>
		</div>
		<div style="position:absolute;width:100px;top:71px;right:231px;text-align:center;font-size:24px;color:#ffffff;">
			<span id="ScoreHomeCup" data-player-key="<?php echo $rowsSetP4FCup[0]["PlayerHomeKey"]?>"><?php echo $rowsSetP4FCup[0]["HomeScore"];?></span>
			<span> - </span>
			<span id="ScoreAwayCup" data-player-key="<?php echo $rowsSetP4FCup[0]["PlayerAwayKey"]?>"><?php echo $rowsSetP4FCup[0]["AwayScore"];?></span>
		</div>
	<?php } else { ?>
		<?php if ($rowsSetP4FCup[0]["HomeScore"]>0 ) {?>
			<div style="position:absolute;width:290px;top:71px;right:136px;text-align:center;font-size:23px;color:#ffffff;">
				Qualifié pour le second tour
			</div>
		<?php } else { ?>
			<div style="position:absolute;width:100px;top:71px;right:231px;text-align:center;font-size:24px;color:#ffffff;">
				Eliminé
			</div>
	  <?php } ?>
		<?php } ?>
<?php } else { ?>
		<div style="position:absolute;width:100px;top:71px;right:231px;text-align:center;font-size:24px;color:#ffffff;">
			Eliminé
		</div>
<?php } ?>
		<?php } else { ?>
		<div style="text-align:center;font-size:12px;font-weight:bold;font-family:Georgia,Arial,Helvetica,sans-serif;font-variant: small-caps;color:#ffffff;">
			Pas de tour de coupe
		</div>

		<?php } ?>
		
	</div>
</div>
<script>
$(document).ready(function($) {

	$("#divP4FChp").cluetip(
			{positionBy:'fixed',
				showTitle:false,
				width:715,
				ajaxCache:false,
				cluetipClass:'p4f',
				arrows:false,
				sticky:true,
			 topOffset:75,
			 leftOffset:-400,
			  onShow:function (ct, ci) {
					$("#cluetip-close").hide();
					$('#divP4FChp,#cluetip').spotlight({color:'#ffffff',onHide: function(){
						$("#cluetip-close").trigger("click");
					}	});

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
			});
<?php if (count($rowsSetP4FCup)>0) { ?>
	$("#divP4FCup").cluetip(
			{positionBy:'fixed',
				showTitle:false,
				width:715,
				ajaxCache:false,
				cluetipClass:'p4f',
				arrows:false,
				sticky:true,
			 topOffset:75,
			 leftOffset:-600,
			  onShow:function (ct, ci) {
					$("#cluetip-close").hide();
					$('#divP4FCup,#cluetip').spotlight({color:'#ffffff',onHide: function(){
						$("#cluetip-close").trigger("click");
					}	});

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
			});
	<?php } ?>
});
</script>
<?php } ?>

<div id="mainCol">
<?php
$groupList = "<div id='ContainerGroup' style='float: left; position: absolute; left: 505px; top: 10px; z-index: 999;'>
<select id='ValueChoice' style='z-index: 999; '>";

  $sql = "SELECT groups.PrimaryKey GroupKey, groups.Code GroupName, groups.Description FROM groups WHERE CompetitionKey=" . COMPETITION . " AND (IsCompleted='1' OR EXISTS (SELECT 1 FROM matches INNER JOIN results ON matches.PrimaryKey=results.MatchKey WHERE matches.GroupKey=groups.PrimaryKey)) ORDER BY PrimaryKey";
  $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
    $selected = "";
    if ($rowSet["GroupKey"]==$_groupKey)
      $selected ="selected='selected'";


    $groupList .= "<option ".$selected." value='" . $rowSet["GroupKey"] . "'>". $rowSet["Description"].'</option>';
  }

$groupList .= '</select></div>';
echo $groupList;
?>
<div style="text-align:center;font-size:16px;font-weight:bold;font-family:Georgia,Arial,Helvetica,sans-serif;font-variant: small-caps;">
 <?php echo __encode("Résultat de la ". $_groupDescription);?>
</div>
<div class="altBloc" style="width:715px;margin:20px 0px 31px 0;">

<div style="width:725px;">
<ul id="matches" style="list-style: none; ">
<li style="width: 95px; float: left;">

    <select id="playerscb" style="visibility:hidden;height:10px;" multiple="multiple" name="players[]">
<?php $query= "SELECT
players.PrimaryKey PlayerKey,
players.NickName
 FROM playersenabled players
ORDER BY players.NickName";

$resultSet = $_databaseObject->queryPerf($query,"Get players");
$livePlayerList="";


while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $playerKey = $rowSet["PlayerKey"];
  $stylePlayer = 'selected="selected"';
  if (isset($_COOKIE["listLivePlayerHidden"])) {
	$listItemHidden = explode(",",$_COOKIE["listLivePlayerHidden"]);
	if (in_array($playerKey,$listItemHidden)) {
	  $stylePlayer="";
	}
  }



  echo '<option value="'. $rowSet["PlayerKey"] . '" '.$stylePlayer . ' >'. __encode($rowSet["NickName"]) . '</option>';
}
?>
    </select>
</li>

<?php
$query= "SELECT
matches.PrimaryKey MatchKey,
matches.TeamHomeKey,
matches.TeamAwayKey,
TeamHome.Code TeamHomeName,
TeamAway.Code TeamAwayName,
matches.GroupKey,
matches.Status,
matches.IsBonusMatch,
groups.Description GroupName,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3)) TeamHomeScore,
(SELECT COUNT(*) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3)) TeamAwayScore,
IFNULL(results.LiveStatus,0) LiveStatus,
IFNULL(results.ActualTime,0) ActualTime
FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
LEFT JOIN results ON results.MatchKey=matches.PrimaryKey
WHERE matches.GroupKey=$_groupKey
ORDER BY matches.ScheduleDate ASC, matches.TeamHomeKey";

$resultSet = $_databaseObject->queryPerf($query,"Get matches to be played by current day");
$_isMatchInProgress =false;
$nbrOfMatch = 0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
$nbrOfMatch++;
  $matchKey = $rowSet["MatchKey"];
  $groupName = $rowSet["GroupName"];
  $teamHomeKey = $rowSet["TeamHomeKey"];
  $teamAwayKey = $rowSet["TeamAwayKey"];
  $teamHomeName = $rowSet["TeamHomeName"];
  $teamAwayName = $rowSet["TeamAwayName"];
  $teamHomeScore = $rowSet["TeamHomeScore"];
  $teamAwayScore = $rowSet["TeamAwayScore"];
  if ($rowSet["LiveStatus"]==0){
    $teamHomeScore = "&nbsp;";
    $teamAwayScore = "&nbsp;";
  }

  $classBonus ="";
  if ($rowSet["IsBonusMatch"]==1) {
    $classBonus =" matchesliveBonus";
  }

  echo "  				<li class='matcheslive$classBonus' match-key='$matchKey' rel='get.live.match.detail.php?MatchKey=$matchKey' >

  <div class='liveteamHome' ><img src='" . ROOT_SITE . "/images/teamFlags/$teamHomeKey.png' ></img></div>
  <div class='liveteamsep' >-</div>
  <div class='liveteamAway' ><img src='" . ROOT_SITE . "/images/teamFlags/$teamAwayKey.png' ></img></div>
  <div class='livescoreHome'>$teamHomeScore</div>
  <div class='livescoresep' >-</div>
  <div class='livescoreAway' >$teamAwayScore</div>
";

  switch ($rowSet["LiveStatus"]) {
    case 2:
      $_isMatchInProgress = true;
    case 10:
      echo "<div class='livestatus' style='width: 100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;'>" . getStatus($rowSet["LiveStatus"]) . "</div>";
      break;
    case 0:
      if ($rowSet["Status"]==1) { //Postponed
        echo "<div class='livestatus postponed' style='width: 100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;'>" . __encode("Reporté") . "</div>";
      } else {
        echo "<div class='livestatus' style='width: 100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;' >".$rowSet["ActualTime"]."'</div>";
        $scheduleDate = $rowSet["ScheduleDate"];
        echo "<div style='display:none;'  countdown='true' year='". date("Y",$scheduleDate) ."' month='". date("n",$scheduleDate) ."' day='". date("j",$scheduleDate) ."' hour='". date("G",$scheduleDate) ."' minute='". date("i",$scheduleDate) ."'></div>";
      }
      break;
    default:
      echo "<div class='livestatus' style='width: 100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;'>" . $rowSet["ActualTime"] . "'</div>";
      $_isMatchInProgress = true;
      break;
  }
  echo"</li>";


}

switch ($nbrOfMatch) {
  case 1:
    $rowWidth = 167;
    break;
  case 2:
    $rowWidth = 112 * $nbrOfMatch;
    break;
  case 3:
  case 4:
    $rowWidth = 85 * $nbrOfMatch;
    break;
  case 6:
    $rowWidth = 76 * $nbrOfMatch;
    break;
  case 8:
    $rowWidth = 73 * $nbrOfMatch;
    break;
  default;
    $rowWidth = 70 * $nbrOfMatch;
    break;
}

?>

</ul>
</div>
<script>
var _groupKey = '<?php echo $_groupKey; ?>';
$(document).ready(function($) {

	$("#ValueChoice").dropdownchecklist({icon: {}, width: 150,maxDropHeight: 250, closeRadioOnClick:true,
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
    		if (_groupKey != groupKey) {
    			_groupKey = groupKey;

    			var url = "<?php echo 'index.php?Page=' . $_currentPage . '&GroupKey=';?>";
    			url += groupKey;
    			window.location.replace( url );
    		}
		} });

	$("li",$("#matches")).cluetip(
			{positionBy:'auto',
				showTitle:false,
				width:560,
				ajaxCache:false,
				cluetipClass:'p4f',
				arrows:true,
				sticky:false,
				onShow:function (ct, ci) {
					var half1Time = $("#half1").attr('time');
					var half2Time = $("#half2").attr('time');
					$("#half1").progressBar(half1Time, {
						width:250,
						max:45,
						showText: false,
						showTooltip: true,
						tooltipFormat: " '",
						barImage: 'images/progressbg_black250px.gif',
						complete: function () {
							$("#half2").progressBar(half2Time);
						}
					});

					$("#half2").progressBar(0, {width:250,
						max:45,
						showText: false,
						tooltipFormat: " '",
						barImage: 'images/progressbg_black250px.gif'
					});

					$('div[countdownMatchDetail]').each(function (){
						var nextDate = new Date();
						nextMatch = new Date($(this).attr("year"), $(this).attr("month"), $(this).attr("day"), $(this).attr("hour"),$(this).attr("minute"),0);
						var expiryHtml;
						expiryHtml='<div class="over">Periode</div>';
						$(this).css("dislay","none");
						$(this).countdown({until: nextMatch,
						layout: '{hnn}{sep}{mnn}',
						alwaysExpire : true,
						onTick: function (periods) {
							var htmlTimer= "";
							if (periods[3]>0) {
								if (periods[3]==1)
									htmlTimer = "1 jour";
								else
									htmlTimer = periods[3]+" jours";
							}
							else {
								htmlTimer = $(this).context.innerHTML;
							}
						    $('#liveStatusMatchDetail').text(htmlTimer);
						    },
						tickInterval: 5
						});
					});
			}});
});
$('div[countdown]').each(function (){
	var nextDate = new Date();
	nextMatch = new Date($(this).attr("year"), $(this).attr("month"), $(this).attr("day"), $(this).attr("hour"),$(this).attr("minute"),0);
	var expiryHtml;
	expiryHtml='<div class="over">Periode</div>';
	$(this).css("dislay","none");
	$(this).countdown({until: nextMatch,
	layout: '{hnn}{sep}{mnn}',
	alwaysExpire : false,
	onTick: everyMinute,
	onExpiry: countDownHasExpired,
	tickInterval: 5
	});
});

function everyMinute(periods) {
	var htmlTimer= "";
	if (periods[3]>0) {
		if (periods[3]==1)
			htmlTimer = "1 jour";
		else
			htmlTimer = periods[3]+" jours";
	}
	else {
		htmlTimer = $(this).context.innerHTML;
	}

	var parentCountDisplay = $($(this).context.parentNode);
	var matchKey = $(parentCountDisplay).attr('match-key');
    $('div.livestatus',$('li[match-key='+matchKey+']')).each(function () {
		$(this).text(htmlTimer);
    });
}

function countDownHasExpired(){
	$.log('countDownHasExpired');
	_isMatchInProgress = true;
//	var parentCountDisplay = $($(this).context.parentNode);
//	var matchKey = $(parentCountDisplay).attr('match-key');
//	var currentMatch = new Date();
//	nextMatch = new Date(currentMatch.getFullYear(), currentMatch.getMonth()+1, currentMatch.getDate(), 23,30,0);
//
//	$.ajax({
//		type: "POST",
//		url: "get.live.match.info.php?MatchKey=" + matchKey,
//		data: { matchKey: matchKey},
//		  dataType: 'json',
//		  success: callbackRefreshMatchInfo,
//		  error: callbackPostError
//		});
//	$(parentCountDisplay).prepend("<div style='float:right;' countup='true' match-key='" + matchKey + "'></div>");
//	$('div[countup]',parentCountDisplay).fadeIn('slow',function () {
//		$(this).countdown({until: nextMatch,
//		layout: "{snn} sec",
//		onTick: refreshMatchInfo,
//		tickInterval: 1
//		});
//	});
 }
</script>
<div id="playerDetail" class="flexcroll" style="width:<?php echo $rowWidth+20;?>;">

<ul>

<?php
switch ($_competitionType){
  case 2:
$sql = "select @rownum:=@rownum+1 as rank, A.* from
(SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
CONCAT(SUBSTR(players.NickName,1,10),IF (LENGTH(nickname)>9,'...','')) NickName,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0)) Score,
(SELECT CASE COUNT(*) WHEN 8 THEN 60 WHEN 7 THEN 40 WHEN 6 THEN IF (groups.Code='1/8',10,40) WHEN 5 THEN IF (groups.Code='1/8',0,20) WHEN 4 THEN IF (groups.Code='1/4',20,0) WHEN 3 THEN IF (groups.Code='1/4',10,0) ELSE 0 END
FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)
      GroupScore
FROM playersenabled players
GROUP BY NickName
ORDER BY NickName) A, (SELECT @rownum:=0) r";
  break;

  case 3:
$sql = "select @rownum:=@rownum+1 as rank, A.* from
(SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
CONCAT(SUBSTR(players.NickName,1,10),IF (LENGTH(nickname)>9,'...','')) NickName,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0)) Score,
(SELECT CASE COUNT(*) WHEN 8 THEN 60 WHEN 7 THEN 40 WHEN 6 THEN IF (groups.Code='1/8',10,40) WHEN 5 THEN IF (groups.Code='1/8',0,20) WHEN 4 THEN IF (groups.Code='1/4',20,0) WHEN 3 THEN IF (groups.Code='1/4',10,0) ELSE 0 END
       FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)
      GroupScore
FROM playersenabled players
GROUP BY NickName
ORDER BY NickName) A, (SELECT @rownum:=0) r";
  break;
  default:
  $sql = "select @rownum:=@rownum+1 as rank, A.* from
(SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
CONCAT(SUBSTR(players.NickName,1,10),IF (LENGTH(nickname)>9,'...','')) NickName,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0)) Score,
(SELECT CASE COUNT(*) WHEN 10 THEN 100 WHEN 9 THEN 60 WHEN 8 THEN 40 WHEN 7 THEN 20 ELSE 0 END
		 FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)+
          (SELECT CASE COUNT(*) WHEN 0 THEN 0 ELSE 0 END FROM votes
        INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND votes.playerKey=players.PrimaryKey)
      GroupScore
FROM playersenabled players
GROUP BY NickName
ORDER BY NickName) A, (SELECT @rownum:=0) r";
break;
}

$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
$rank=0;
$previousRank=0;
$realRank=0;
$previousScore=0;
$livePlayerList="";



while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $playerKey = $rowSet["PlayerKey"];
  $stylePlayer = "";
  if (isset($_COOKIE["listLivePlayerHidden"])) {
	$listItemHidden = explode(",",$_COOKIE["listLivePlayerHidden"]);
	if (in_array($playerKey,$listItemHidden)) {
	  $stylePlayer="display:none;";
	}
  }
      echo '<li id="li_' . $playerKey . '" class="playerforecastrow" style="width:'.$rowWidth.'px;" player-key="'. $playerKey .'" style="'.$stylePlayer.'" >
      <div style="width:100%" class="playerforecastrowcontent">';



  $realRank++;
  if ($previousScore>$rowSet["Score"]||$previousScore==0) {
    $rank=$realRank;
  }

  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }
  $rankToBeDisplayed = $rank.'.';
  if ($previousRank==$rank) {
    $rankToBeDisplayed="-";
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

	$bonusUrl = ROOT_SITE;
	switch ($rowSet["GroupScore"]) {
		case 0: 
		  $bonusUrl = "";
			break;
		default:
		  $bonusUrl .= "/images/bonus.".(string)$rowSet["GroupScore"] .".png";
		  //$bonusUrl .= "/images/bullet.bonus.100.png";
		  break;
	}

	
  //echo '<p style="float:left;"><a class="popupscroll" href="#">'. $rowSet["NickName"] .'</a></p><img class="avat" style="width:30px;height:30px;" src="' . $avatarPath .'"></img>';
  echo '<div class="popupscroll" href="#" style="float:left;border-right:1px solid;width:92px;';
	echo 'background:url('.$bonusUrl.') no-repeat right center;" ><img title="Masquer ce joueur" player-key="';
	echo $playerKey.'" class="HidePlayer" style="float:left;width:15px;height:15px;" src="';
	echo ROOT_SITE .'/images/close.png"></img><span class="ellipsis textOverflow" displayWidth="70" style="_width=65px;">';
	echo $rowSet["FullNickName"] .'</span><br/><span class="Score" style="font-size:9px;font-style:italic;" >';
	echo 'Score : <u>' . ($rowSet["Score"]+$rowSet["GroupScore"]) . ' pts</u></span>';
	echo '</div>';
  echo ' <div style="float:right;margin-right:0px;height:33px;"> ';

  $queryForecats= "SELECT
  matches.PrimaryKey MatchKey,
matches.TeamHomeKey,
matches.TeamAwayKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE $playerKey=playermatchresults.PlayerKey
AND playermatchresults.MatchKey =matches.PrimaryKey
AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) ) Score,
IFNULL((SELECT forecasts.TeamHomeScore FROM forecasts WHERE forecasts.PlayerKey=$playerKey AND forecasts.MatchKey=matches.PrimaryKey),'') TeamHomeForecast,
IFNULL((SELECT forecasts.TeamAwayScore FROM forecasts WHERE forecasts.PlayerKey=$playerKey AND forecasts.MatchKey=matches.PrimaryKey),'') TeamAwayForecast,
IFNULL(results.LiveStatus,0) LiveStatus,
IFNULL(results.ActualTime,0) ActualTime
FROM matches
LEFT JOIN results ON results.MatchKey =matches.PrimaryKey
WHERE matches.GroupKey=$_groupKey
GROUP BY matches.PrimaryKey
ORDER BY matches.ScheduleDate ASC, matches.TeamHomeKey";

 $resultSetForecasts = $_databaseObject->queryPerf($queryForecats,"Get matches to be played by current day");

while ($rowSetForecasts = $_databaseObject -> fetch_assoc ($resultSetForecasts))
{
  $matchKey = $rowSetForecasts["MatchKey"];
  $teamHomeKey = $rowSetForecasts["TeamHomeKey"];
  $teamAwayKey = $rowSetForecasts["TeamAwayKey"];
  $teamHomeForecast = $rowSetForecasts["TeamHomeForecast"];
  $teamAwayForecast = $rowSetForecasts["TeamAwayForecast"];
  $score = $rowSetForecasts["Score"];
  $matchStatus = $rowSetForecasts["LiveStatus"];
  $matchTime = $rowSetForecasts["ActualTime"];

  $separator = "-";
  if ($teamHomeForecast=="") {
    $separator = "&nbsp;";
  }
  $scoreWording = "pt";
  if ($score>1) {
    $scoreWording = "pts";
  }
  switch ($score)
  {
    case 0:
    case 1:
    case 2:
      $class = "Failed";
      break;
    default:
      $class = "Success";
      break;
  }
  if ($matchStatus==0 &&  $matchTime==0) {
    $class = "NotStarted";
    if ($teamHomeForecast!="" && $playerKey!=$_authorisation->getConnectedUserKey()){
	    $teamHomeForecast = "x";
	    $teamAwayForecast = "x";
    }
  }


  echo "  				<div class='forecastmatch' match-key='$matchKey' >

  <div class='forecastteamHome' >$teamHomeForecast</div>
  <div class='forecastsep' >$separator</div>
  <div class='forecastteamAway' >$teamAwayForecast</div>
  <div class='forecastScore $class' >$score&nbsp;$scoreWording</div>
                </div>";

}
  echo '<div></div></li>';
$previousRank=$rank;

}
?>
</ul>
</div>
</div>
<div class="mainBloc">
<div id="countup" style="display: none;"></div>
<div id="mod-classements" class="node2">
<div class="head">
<div>
<h4><?php echo __encode("Classement"); ?></h4>
</div>
</div>
<div class="node-in">
<div id="ContainerRanking" class="panel flexcroll" >
<ol id="groupranking" >
<?php
switch ($_competitionType) {
  case 2:
  $sql = "SELECT
(SELECT PRK.Rank FROM playergroupranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND RankDate<CURDATE() ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey)
      ),0) +
      (SELECT
CASE COUNT(*)
WHEN 8 THEN 60
WHEN 7 THEN 40
WHEN 6 THEN IF (groups.Code='1/8',10,40)
WHEN 5 THEN IF (groups.Code='1/8',0,20)
WHEN 4 THEN IF (groups.Code='1/4',20,0)
WHEN 3 THEN IF (groups.Code='1/4',10,0)
ELSE 0 END
FROM playermatchresults
INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
WHERE groups.PrimaryKey=$_groupKey
AND playermatchresults.Score>=5
AND playermatchresults.playerKey=players.PrimaryKey
)) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName";
break;
  case 3:
  $sql = "SELECT
(SELECT PRK.Rank FROM playergroupranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND RankDate<CURDATE() ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey)
      ),0) +
      (SELECT
CASE COUNT(*)
WHEN 8 THEN 60
WHEN 7 THEN 40
WHEN 6 THEN IF (groups.Code='1/8',10,40)
WHEN 5 THEN IF (groups.Code='1/8',0,20)
WHEN 4 THEN IF (groups.Code='1/4',20,0)
WHEN 3 THEN IF (groups.Code='1/4',10,0)
ELSE 0 END
FROM playermatchresults
INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
WHERE groups.PrimaryKey=$_groupKey
AND playermatchresults.Score>=5
AND playermatchresults.playerKey=players.PrimaryKey
)) Score,
IFNULL((SELECT COUNT(playermatchresults.Score) FROM playermatchresults
  WHERE players.PrimaryKey=playermatchresults.PlayerKey
    AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey
    									FROM matches
    									INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
    									       AND groups.PrimaryKey=$_groupKey)
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)
      AND playermatchresults.Score>=5),0) ScoreCorrect,
IFNULL((SELECT COUNT(playermatchresults.Score) FROM playermatchresults
  WHERE players.PrimaryKey=playermatchresults.PlayerKey
    AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey
    									FROM matches
    									INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
    									       AND groups.PrimaryKey=$_groupKey)
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)
      AND playermatchresults.Score>=15),0) ScorePerfect
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName";
break;
  default:
  $sql = "SELECT
(SELECT PRK.Rank FROM playergroupranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND RankDate<CURDATE() ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey)
      ),0) +
      (SELECT
CASE COUNT(*)
WHEN 10 THEN 100
WHEN 9 THEN 60
WHEN 8 THEN 40
WHEN 7 THEN 20
ELSE 0 END
FROM playermatchresults
INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
WHERE groups.PrimaryKey=$_groupKey
AND playermatchresults.Score>=5
AND playermatchresults.playerKey=players.PrimaryKey
)+
(SELECT CASE COUNT(*) WHEN 0 THEN 0 ELSE 0 END FROM votes
        INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND votes.playerKey=players.PrimaryKey)) Score,
IFNULL((SELECT COUNT(playermatchresults.Score) FROM playermatchresults
  WHERE players.PrimaryKey=playermatchresults.PlayerKey
    AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey
    									FROM matches
    									INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
    									       AND groups.PrimaryKey=$_groupKey)
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)
      AND playermatchresults.Score>=5),0) ScoreCorrect,
IFNULL((SELECT COUNT(playermatchresults.Score) FROM playermatchresults
  WHERE players.PrimaryKey=playermatchresults.PlayerKey
    AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey
    									FROM matches
    									INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
    									       AND groups.PrimaryKey=$_groupKey)
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)
      AND playermatchresults.Score>=15),0) ScorePerfect
FROM playersenabled players
GROUP BY players.PrimaryKey
ORDER BY Score DESC, ScoreCorrect DESC, ScorePerfect DESC";
  break;
}
$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
$rank=0;
$previousRank=0;
$realRank=0;
$previousScore=0;
$previousMatchGood = 0;
$previousMatchPerfect = 0;

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  echo '<li id="GRP_'.$rowSet["PlayerKey"].'" player-key="'.$rowSet["PlayerKey"].'" rel="get.player.group.detail.php?GroupKey='.$_groupKey.'&PlayerKeys='.$rowSet["PlayerKey"].'&Mode=Ligue1">';


  $realRank++;
  if ($rowSet["Score"]!=$previousScore || $rowSet["Score"]>0) {
    $rank=$realRank;
  } elseif ($rowSet["ScoreCorrect"]!=$previousMatchGood){
    $rank=$realRank;
  } elseif ($rowSet["ScorePerfect"]!=$previousMatchPerfect){
    $rank=$realRank;
  }

  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }
  $rankToBeDisplayed = $rank.'.';
  if ($previousRank==$rank) {
    $rankToBeDisplayed="-";
  }
  echo "<span id='rankSpan'>$rankToBeDisplayed</span>";
  echo '<a class="popupscroll" href="#"><img class="avat" src="' . $avatarPath .'"></img></a>';

  $previousScore=$rowSet["Score"];
  $previousMatchGood=$rowSet["ScoreCorrect"];
  $previousMatchPerfect=$rowSet["ScorePerfect"];
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


  echo '<p>
  <a class="popupscroll textOverflow ellipsis" displayWidth="70" href="#" style="_width:80px;">'. $rowSet["FullNickName"] .'</a>
  <em>'. $rowSet["Score"] . ' pts</em>
  <span class="var ' . $variation . '" ></span>
  </p>';
  echo '</li>';
$previousRank=$rank;

}
?>
</ol>
</div>
	<script>
$(document).ready(function($) {
	$("li",$("#groupranking")).cluetip(
			{positionBy:'bottomTop',
				showTitle:false,
				width:<?php echo $rowWidth+20; ?>,
				ajaxCache:false,
				cluetipClass:'p4f',
				arrows:false,
				sticky:false,
			  onShow:function (ct, ci) {
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
			});
});
</script>
<style type="text/css">
.ui-sortable-placeholder { border: 1px dotted #999;background:#f4f4f4; visibility: visible !important; width:100%!important; }
</style>
<script>

function redrawPlayerList () {
	$("#playerDetail li:visible").each(function (index) {
		if ((index % 2) == 0) {
			$(this).removeClass('resultRowOdd');
			$(this).addClass('resultRow');
		} else {
			$(this).removeClass('resultRow');
			$(this).addClass('resultRowOdd');
		}
	});
}

function HidePlayer (playerKey) {
	$("li[player-key=" + playerKey + "]:visible",$("#playerDetail")).fadeOut('slow', function () {
			redrawPlayerList();
			//$("#playerscb option[value=" +playerKey+"]").removeAttr("selected","selected").change();
		});
	getOrder();


}

function ShowPlayer (playerKey) {
		$("li[player-key=" + playerKey + "]:hidden").fadeIn('slow', function () {
	    	redrawPlayerList();
	    	//$("#playerscb option[value=" +playerKey+"]").attr("selected","selected").change();
	    });
		getOrder();

}

$(document).ready(function($) {
	//$("span .ellipsis").ellipsis();
	$("#playerDetail ul").sortable({
		placeholder: 'ui-sortable-placeholder',
		forcePlaceholderSize: true,
		cursor: "move",
		update: function(event, ui) {
			redrawPlayerList();
			getOrder();
	 }
	});

	$("#groupranking").sortable({ disabled: true });
	$("#globalranking").sortable({ disabled: true });

	$(".HidePlayer").live('click',function() {
		var playerKey = $(this).attr("player-key");
		HidePlayer(playerKey);
		$("#playerscb option[value=" +playerKey+"]").removeAttr("selected","selected").change();
		$("#playerDetail").jScrollPane({
			showArrows: true,
			horizontalGutter: 10
		});
	});

	$("#playerscb").dropdownchecklist({
        icon: {},
        width: 80,
        maxDropHeight: 350,
        textFormatFunction: function(options) {
            var selectedOptions = options.filter(":selected");
            var countOfSelected = selectedOptions.size();
            var size = options.size();
            switch(countOfSelected) {
                case 0: return "Personne";
                case 1: return selectedOptions.text();
                case size: return "Tous les joueurs";
                default: return countOfSelected + " Joueurs";
            }
        },
        onComplete: function(selector) {
        	var selectedOptions = $("#playerscb option:selected");

            $.each(
             selectedOptions,
             function(i,option) {
                 ShowPlayer($(option).val());
             });
            var selectedOptionsHidden = $("#playerscb option:not(:selected)");
            $.each(
            		selectedOptionsHidden,
                    function(i,option) {
                        HidePlayer($(option).val());
                    });
			$("#playerDetail").jScrollPane({
				showArrows: true,
				horizontalGutter: 10
			});
        }

    });
    $("div.ui-dropdownchecklist-dropcontainer").css("width","176px");
    $("div.ui-dropdownchecklist-dropcontainer").jScrollPane({
		showArrows: true,
		horizontalGutter: 10
	});
    restoreOrder();
	redrawPlayerList();
});

//functions
//set the list selector
var setSelector = "#playerDetail ul";
//set the cookie name
var setCookieName = "listOrderLive";
//set the cookie expiry time (days):
var exipryCookieDate = new Date();
exipryCookieDate.setDate(exipryCookieDate.getDate() + 90);

//function that writes the list order to a cookie
function getOrder() {
	// save custom order to cookie
	$.cookies.set(setCookieName, $(setSelector).sortable("toArray").toString(), { expiresAt: exipryCookieDate});

	var arrHiddenItems = new Array();
    var selectedOptionsHidden = $("#playerscb option:not(:selected)");
    $.each(
    		selectedOptionsHidden,
            function(i,option) {
    			arrHiddenItems.push($(option).val());
            });

	$.cookies.set("listLivePlayerHidden",arrHiddenItems.toString(), { expiresAt: exipryCookieDate});
}

//function that restores the list order from a cookie
function restoreOrder() {
	var list = $(setSelector);
	if (list == null) return
	// fetch the cookie value (saved order)
	var cookie = $.cookies.get(setCookieName);
	if (!cookie) return;

	// make array from saved order
	var IDs = cookie.split(',');

	// fetch current order
	var items = list.sortable("toArray");

	// make array from current order
	var rebuild = new Array();
	for ( var v=0, len=items.length; v<len; v++ ){
		rebuild[items[v]] = items[v];
	}

	for (var i = 0, n = IDs.length; i < n; i++) {

		// item id from saved order
		var itemID = IDs[i];

		if (itemID in rebuild) {

			// select item id from current order
			var item = rebuild[itemID];

			// select the item according to current order
			var child = $(setSelector).children("#" + item);

			// select the item according to the saved order
			var savedOrd = $(setSelector).children("#" + itemID);

			// remove all the items
			child.remove();

			// add the items in turn according to saved order
			// we need to filter here since the "ui-sortable"
			// class is applied to all ul elements and we
			// only want the very first!  You can modify this
			// to support multiple lists - not tested!
			$(setSelector).filter(":first").append(savedOrd);
		}
	}

	$("#playerDetail").jScrollPane({
		showArrows: true,
		horizontalGutter: 10
	});
}

</script>

</div>
</div>
</div>
</div>
<div id="ticker" style="display:none;"></div>
<script>
$(document).ready(function($) {
	$("#playerDetail").jScrollPane({
		showArrows: true,
		horizontalGutter: 5
	});
	$("#ContainerRanking").jScrollPane({
		showArrows: true,
		horizontalGutter: 5
	});
});

function restoreOrderRanking(pContainerSelector, pNewOrder) {
	var list = $(pContainerSelector);
	if (list == null) return

	// make array from saved order
	var IDs = pNewOrder;
	// fetch current order
	var items = list.sortable("toArray");
	// make array from current order
	var rebuild = new Array();
	for ( var v=0, len=items.length; v<len; v++ ){
		rebuild[items[v]] = items[v];
	}

	for (var i = 0, n = IDs.length; i < n; i++) {

		// item id from saved order
		var itemID = IDs[i];

		if (itemID in rebuild) {

			// select item id from current order
			var item = rebuild[itemID];
			// select the item according to current order
			var child = $(pContainerSelector).children("#" + item);
			// select the item according to the saved order
			var savedOrd = $(pContainerSelector).children("#" + itemID);
			// remove all the items
			child.remove();

			// add the items in turn according to saved order
			// we need to filter here since the "ui-sortable"
			// class is applied to all ul elements and we
			// only want the very first!  You can modify this
			// to support multiple lists - not tested!
			$(pContainerSelector).filter(":first").append(savedOrd);
		}
	}

}

function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
{
	$.log(XMLHttpRequest);
	$.log(textStatus);
	$.log(errorThrown);
}
</script>
