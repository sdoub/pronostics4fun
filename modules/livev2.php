<?php

if (isset($_GET['DayKey'])) {
	$query= "SELECT
		groups.PrimaryKey GroupKey,
		groups.Description GroupDescription
		FROM groups
		WHERE groups.DayKey=" . $_GET['DayKey'] . "
		 AND groups.CompetitionKey=" . COMPETITION;
}
else {
	$scheduleDate = time();

	$query= "   SELECT   groups.PrimaryKey GroupKey,
		groups.Description GroupDescription,
		ABS(UNIX_TIMESTAMP(matches.ScheduleDate)-$scheduleDate)
			FROM matches
			INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
		 WHERE DATE(matches.ScheduleDate)=DATE(FROM_UNIXTIME($scheduleDate))
			 ORDER BY 3
	";
}

$resultSet = $_databaseObject->queryPerf($query,"Get players");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$_groupKey =$rowSet["GroupKey"];
$_groupDescription =$rowSet["GroupDescription"];

AddScriptReference("countdown");
AddScriptReference("color");
AddScriptReference("scrollpane");
AddScriptReference("bsmselect");
AddScriptReference("cluetip");
AddScriptReference("progressbar");
AddScriptReference("live");
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

if (count($rowsSetP4FCh)>0 && $rowsSetP4FCh[0]["HomeScore"]==null) {
	$playerChpKeys = $rowsSetP4FCh[0]["PlayerHomeKey"] . "," . $rowsSetP4FCh[0]["PlayerAwayKey"];
	if (count($rowsSetP4FCup)>0 && $rowsSetP4FCup[0]["HomeScore"]==null) 
		$playerCupKeys = $rowsSetP4FCup[0]["PlayerHomeKey"] . "," . $rowsSetP4FCup[0]["PlayerAwayKey"];

?>
<div style="height:150px;display:block;">
	<div style="padding-right:80px;margin-bottom:20px;text-align:center;font-size:16px;font-weight:bold;font-family:Georgia,Arial,Helvetica,sans-serif;font-variant: small-caps;color:#ffffff;">Duel P4F - <?php echo $_groupDescription;?></div>
	<div id="divP4FChp" style="float:left;background-color:#6D8AA8;width:300px;height:70px;margin-left:100px;" rel="get.player.group.detail.php?GroupKey=<?php echo $_groupKey;?>&PlayerKeys=<?php echo $playerChpKeys; ?>&Live=1">
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
			<span id="ScoreHomeChampionship" data-player-key="<?php echo $rowsSetP4FCh[0]["PlayerHomeKey"]?>"></span>
			<span> - </span>
			<span id="ScoreAwayChampionship" data-player-key="<?php echo $rowsSetP4FCh[0]["PlayerAwayKey"]?>"></span>
			
		</div>
	</div>
	<div id="divP4FCup" style="float:right;background-color:#6D8AA8;width:300px;height:70px;;margin-right:100px;" rel="get.player.group.detail.php?GroupKey=<?php echo $_groupKey;?>&PlayerKeys=<?php echo $playerCupKeys; ?>&Live=1">
<?php if (count($rowsSetP4FCurrentCupRound)>0) {?>
		<div style="text-align:center;font-size:12px;font-weight:bold;font-family:Georgia,Arial,Helvetica,sans-serif;font-variant: small-caps;color:#ffffff;">
			Coupe - <?php echo $rowsSetP4FCurrentCupRound[0]["RoundDescription"]; ?>
		</div>
<?php if (count($rowsSetP4FCup)>0) { ?>
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
			<span id="ScoreHomeCup" data-player-key="<?php echo $rowsSetP4FCup[0]["PlayerHomeKey"]?>"></span>
			<span> - </span>
			<span id="ScoreAwayCup" data-player-key="<?php echo $rowsSetP4FCup[0]["PlayerAwayKey"]?>"></span>
		</div>
<?php } else { ?>
		<div style="position:absolute;width:100px;top:71px;right:231px;text-align:center;font-size:24px;color:#ffffff;">
			Eliminé
		</div>
<?php } ?>
		<?php } else { ?>
		<div style="text-align:center;font-size:12px;font-weight:bold;font-family:Georgia,Arial,Helvetica,sans-serif;font-variant: small-caps;color:#ffffff;">
			Aucune Coupe en cours
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
<div style="text-align:center;font-size:16px;font-weight:bold;font-family:Georgia,Arial,Helvetica,sans-serif;font-variant: small-caps;">
<?php echo $_groupDescription;?> en direct
</div>
<div id ="RefreshInfo" style="text-align:left;font-size:11px;font-family:Arial,Helvetica,sans-serif;">
<?php
$queryLastRefresh= "SELECT UNIX_TIMESTAMP(MAX(ResultDate)) LastRefreshDate, UNIX_TIMESTAMP(MAX(ResultDate))+UNIX_TIMESTAMP(MAX(ResultDate))+ 300 - UNIX_TIMESTAMP(NOW()) NextRefreshDate, UNIX_TIMESTAMP(NOW()) CurrentDate, MIN(results.LiveStatus) LiveStatus,UNIX_TIMESTAMP(MAX(ResultDate))+ 300 - UNIX_TIMESTAMP(NOW()) DiffDate
FROM results
INNER JOIN matches ON results.MatchKey=matches.PrimaryKey AND matches.GroupKey=$_groupKey
WHERE results.LiveStatus > 0";


$resultSetLastRefresh = $_databaseObject -> queryPerf ($queryLastRefresh, "Get last refresh date");
$rowSetLastRefresh = $_databaseObject -> fetch_assoc ($resultSetLastRefresh);

  $lastRefreshFormattedDate = strftime("%A %d %B %Y à %H:%M:%S",$rowSetLastRefresh['LastRefreshDate']);
  $nextRefreshFormattedDate = strftime("%H:%M:%S",$rowSetLastRefresh['NextRefreshDate']);
  if (!$rowSetLastRefresh['LastRefreshDate']) {
    $lastRefreshFormattedDate = " - - ";
  }
  echo "Dernière mise à jour : <span style='font-weight:bold;' id='lastRefresh'>$lastRefreshFormattedDate</span><br/>";

	function timeBetween($start_date,$end_date)
	{
		$diff = $end_date-$start_date;
 		$seconds = 0;
 		$hours   = 0;
 		$minutes = 0;

		if($diff % 86400 <= 0){$days = $diff / 86400;}  // 86,400 seconds in a day
		if($diff % 86400 > 0)
		{
			$rest = ($diff % 86400);
			$days = ($diff - $rest) / 86400;
     		if($rest % 3600 > 0)
			{
				$rest1 = ($rest % 3600);
				$hours = ($rest - $rest1) / 3600;
        		if($rest1 % 60 > 0)
				{
					$rest2 = ($rest1 % 60);
           		$minutes = ($rest1 - $rest2) / 60;
           		$seconds = $rest2;
        		}
        		else{$minutes = $rest1 / 60;}
     		}
     		else{$hours = $rest / 3600;}
		}

		if($days > 0){$days = $days;}
		else{$days = 0;}
		if($hours > 0){$hours = $hours;}
		else{$hours = 0;}
		if($minutes > 0){$minutes = $minutes;}
		else{$minutes = 0;}
		$seconds = $seconds; // always be at least one second
        $result = array();
		$result["days"]=$days;
		$result["hours"]=$hours;
		$result["minutes"]=$minutes;
		$result["seconds"]=$seconds;
        return $result;
}
if ($rowSetLastRefresh['DiffDate']>0) {
  $diffTime = timeBetween($rowSetLastRefresh['LastRefreshDate'],$rowSetLastRefresh['NextRefreshDate']);
}
else {
  $diffTime = array();
  $diffTime["days"]=0;
  $diffTime["hours"]=0;
  $diffTime["minutes"]=0;
  $diffTime["seconds"]=10;
}
  $style="";
  if ($rowSetLastRefresh['LiveStatus']==10 || !$rowSetLastRefresh['LiveStatus']) {
    $style="display:none;";
      $diffTime["minutes"]=0;
  }
  echo "<span id='NextRefreshSpan' style='$style'>Prochaine mise à jour dans <span style='font-weight:bold;' id='nextRefresh'>" . $diffTime["minutes"] . ":" . $diffTime["seconds"] . "'</span></span>";
?>
</div>

<div class="altBloc" style="width:715px;margin:20px 0px 31px 0;">

<div style="width:725px;">
<ul id="matches" style="list-style: none; ">
<li style="width: 95px; float: left;">
	<a href="javascript:void(0);" id="linkRefresh" style="text-decoration:none;float:left;padding-top: 5px; padding-left: 27px;display:none;"><span id="refreshvalue" style="color:#FFFFFF;">60'</span>&nbsp;<img style="border:none;" src="<?php echo ROOT_SITE;?>/images/refresh_white.gif"/></a>
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



  echo '<option value="'. $rowSet["PlayerKey"] . '" '.$stylePlayer . ' >'. $rowSet["NickName"] . '</option>';
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
matches.IsBonusMatch,
matches.Status,
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

  <div class='liveteamHome' ><img src='" . ROOT_SITE . "/images/teamFlags/$teamHomeKey.png' width='20px' height='20px'></img></div>
  <div class='liveteamsep' >-</div>
  <div class='liveteamAway' ><img src='" . ROOT_SITE . "/images/teamFlags/$teamAwayKey.png' width='20px' height='20px'></img></div>
  <div class='livescoreHome'>$teamHomeScore</div>
  <div class='livescoresep' >-</div>
  <div class='livescoreAway' >$teamAwayScore</div>
";

  switch ($rowSet["LiveStatus"]) {
    case 2:
      $_isMatchInProgress = true;
    case 10:
      echo "<div class='livestatus' style='width:100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;'>" . getStatus($rowSet["LiveStatus"]) . "</div>";
      break;
    case 0:
      if ($rowSet["Status"]==1) { //Postponed
        echo "<div class='livestatus' style='width:100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;'>Reporté</div>";
      } else {
        echo "<div class='livestatus' style='width:100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;' >".$rowSet["ActualTime"]."'</div>";
        $scheduleDate = $rowSet["ScheduleDate"];
        echo "<div style='display:none;'  countdown='true' year='". date("Y",$scheduleDate) ."' month='". date("n",$scheduleDate) ."' day='". date("j",$scheduleDate) ."' hour='". date("G",$scheduleDate) ."' minute='". date("i",$scheduleDate) ."'></div>";
      }
      break;
    default:
      echo "<div class='livestatus' style='width:100%;text-align:center;font-size:9px;padding-top:25px;_padding-top:0px;position: absolute;bottom: 0;'>" . $rowSet["ActualTime"] . "'</div>";
      $_isMatchInProgress = true;
      break;
  }
  echo"</li>";


}
switch ($nbrOfMatch) {
  case 1:
    $rowWidth = 170;
    break;
  case 2:
    $rowWidth = 115 * $nbrOfMatch;
    break;
  case 3:
  case 4:
    $rowWidth = 88 * $nbrOfMatch;
    break;
  case 6:
    $rowWidth = 80 * $nbrOfMatch;
    break;
  case 8:
    $rowWidth = 75 * $nbrOfMatch;
    break;
  default;
    $rowWidth = 72 * $nbrOfMatch;
    break;
}

?>

</ul>
</div>
<script>

var _isMatchInProgress = <?php if ($_isMatchInProgress) { echo "true";} else {echo "false";} ?>;

function pad(number, length) {

    var str = '' + number;
    while (str.length < length) {
        str = '0' + str;
    }

    return str;

}
$(document).ready(function($) {
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
	tickInterval: 1
	});
});

function everyMinute(periods) {
	var htmlTimer= "";
	//$.log(periods);
	if (periods[3]>0) {
		if (periods[3]==1)
			htmlTimer = "1 jour";
		else
			htmlTimer = periods[3]+" jours";
	}
	else {
		if (periods[4]==0) {
			htmlTimer = pad(periods[5],2)+ ":" + pad(periods[6],2)+"'";
		}
		else {
			htmlTimer = $(this).context.innerHTML;
		}
	}

	var parentCountDisplay = $($(this).context.parentNode);
	var matchKey = $(parentCountDisplay).attr('match-key');
    $('div.livestatus',$('li[match-key='+matchKey+']')).each(function () {
		$(this).text(htmlTimer);
    });
}
var _isAlreadyExipred = false;
function countDownHasExpired(){
	if (!_isAlreadyExipred) {
    	_isMatchInProgress = true;
    	$('#NextRefreshSpan').css("display","inline");
    	$('#ticker').countdown('change', {until: '+1m', alwaysExpire:true});
    	_isAlreadyExipred=true;
	}
 }
</script>
<div id="playerDetail" class="flexcroll" style="width:<?php echo $rowWidth+20;?>;">

<ul>

<?php
switch ($_competitionType) {
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
(SELECT CASE COUNT(*) WHEN 8 THEN 60 WHEN 7 THEN 40 WHEN 6 THEN IF (groups.Code='1/8',10,40) WHEN 5 THEN 20 WHEN 4 THEN IF (groups.Code='1/4',20,0) WHEN 3 THEN IF (groups.Code='1/4',10,0) ELSE 0 END
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
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0)) Score,
(SELECT CASE COUNT(*) WHEN 6 THEN 40 WHEN 5 THEN 20 WHEN 4 THEN IF (groups.Code='1/4',20,0) WHEN 3 THEN IF (groups.Code='1/4',10,0) ELSE 0 END
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
	echo '<li id="li_' . $playerKey . '" class="playerforecastrow" player-score="'.$rowSet["Score"].'" player-key="'. $playerKey .'" style="width:'.$rowWidth.'px;'.$stylePlayer.'" >
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
			breeak;
		default:
		 	 $bonusUrl .= "/images/bonus.".(string)$rowSet["GroupScore"] .".png";
			 //$bonusUrl .= "/images/bullet.bonus.100.png";
			 break;
	}

	echo '<div class="popupscroll" href="#" style="float:left;border-right:1px solid;width:92px;';
	echo 'background:url('.$bonusUrl.') no-repeat right center;" >';
	echo '<img title="Masquer ce joueur" player-key="'.$playerKey.'" class="HidePlayer" ';
	echo 'style="float:left;width:15px;height:15px;" src="' . ROOT_SITE .'/images/close.png"></img>';
	echo '<span class="ellipsis textOverflow" displayWidth="70" style="_width=68px;" ';
	echo '>'. $rowSet["FullNickName"] .'</span><br/>';
	echo '<span class="Bonus" style="font-size:9px;font-style:italic;" >';
	echo 'Score : <u>' . ($rowSet["Score"]+$rowSet["GroupScore"]) . ' pts</u></span></div>';
  echo ' <div style="float:right;margin-right:14px;"> ';

  $queryForecats= "SELECT
  matches.PrimaryKey MatchKey,
matches.TeamHomeKey,
matches.TeamAwayKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE $playerKey=playermatchresults.PlayerKey
AND playermatchresults.MatchKey =matches.PrimaryKey
AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE $playerKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT MAX(matches.GroupKey) FROM matches WHERE matches.PrimaryKey=matches.PrimaryKey)
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score,
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
<h4>Classement en direct</h4>
</div>
</div>
<div class="node-in">
<div id="tabsRanking"><a id="globalrankinglink" class="selected" >Général</a><a id="grouprankinglink"><?php  if ($_competitionType==1) {echo "Journée";} else {echo "Groupe";} ?></a></div>
<div id="ContainerRanking" class="panel flexcroll" >
<ol id="globalranking">


<?php
switch ($_competitionType) {
  case 2:
$sql = "SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND RankDate<CURDATE() AND PRK.CompetitionKey=" . COMPETITION . " ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
    ),0)
    + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")
    ),0)
    + (SELECT CASE COUNT(*) WHEN 8 THEN 60 WHEN 7 THEN 40 WHEN 6 THEN IF (groups.Code='1/8',10,40) WHEN 5 THEN 20 WHEN 4 THEN IF (groups.Code='1/4',20,0) WHEN 3 THEN IF (groups.Code='1/4',10,0) ELSE 0 END
     FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND groups.IsCompleted = '0'
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)
      ) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName";
break;
  case 3:
$sql = "SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND RankDate<CURDATE() AND PRK.CompetitionKey=" . COMPETITION . " ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
    ),0)
    + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")
    ),0)
    + (SELECT CASE COUNT(*) WHEN 6 THEN 40 WHEN 5 THEN 20 WHEN 4 THEN IF (groups.Code='1/4',20,0) WHEN 3 THEN IF (groups.Code='1/4',10,0) ELSE 0 END
		 FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND groups.IsCompleted = '0'
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)
      ) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName";
break;
  default:
$sql = "SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND RankDate<CURDATE() AND PRK.CompetitionKey=" . COMPETITION . " ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
    ),0)
    + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")
    ),0)
    + (SELECT CASE COUNT(*) WHEN 10 THEN 100 WHEN 9 THEN 60 WHEN 8 THEN 40 WHEN 7 THEN 20 ELSE 0 END
		 FROM playermatchresults
        INNER JOIN matches ON playermatchresults.MatchKey=matches.PrimaryKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND groups.IsCompleted = '0'
          AND playermatchresults.Score>=5
          AND playermatchresults.playerKey=players.PrimaryKey)
     + (SELECT CASE COUNT(*) WHEN 0 THEN 0 ELSE 0 END FROM votes
        INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND votes.playerKey=players.PrimaryKey)
      ) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName";
break;
}

$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
$rank=0;
$previousRank=0;
$realRank=0;
$previousScore=0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  echo '<li id="G_'.$rowSet["PlayerKey"].'" player-key="'.$rowSet["PlayerKey"].'">';


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
  echo "<span id='rankSpan'>$rankToBeDisplayed</span>";
  echo '<a class="popupscroll" href="#"><img class="avat" src="' . $avatarPath .'"></img></a>';

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
<ol id="groupranking" style="display:none;">


<?php
switch ($_competitionType) {
  case 2 :
$sql = "SELECT
(SELECT PRK.Rank FROM playergroupranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND RankDate<CURDATE() AND PRK.GroupKey=$_groupKey ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
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
WHEN 5 THEN 20
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
  case 3 :
$sql = "SELECT
(SELECT PRK.Rank FROM playergroupranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND RankDate<CURDATE() AND PRK.GroupKey=$_groupKey ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey)
      ),0) +
      (SELECT
CASE COUNT(*)
WHEN 6 THEN 40
WHEN 5 THEN 20
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
  default:
$sql = "SELECT
(SELECT PRK.Rank FROM playergroupranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND RankDate<CURDATE() AND PRK.GroupKey=$_groupKey ORDER BY RankDate desc LIMIT 0,1) PreviousRank,
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
)
+ (SELECT CASE COUNT(*) WHEN 0 THEN 0 ELSE 0 END FROM votes
        INNER JOIN matches ON matches.PrimaryKey=votes.MatchKey
        INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
        WHERE groups.PrimaryKey=$_groupKey
          AND votes.playerKey=players.PrimaryKey)) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score DESC, NickName";
}
$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
$rank=0;
$previousRank=0;
$realRank=0;
$previousScore=0;

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  echo '<li id="GRP_'.$rowSet["PlayerKey"].'" player-key="'.$rowSet["PlayerKey"].'" rel="get.player.group.detail.php?GroupKey='.$_groupKey.'&PlayerKeys='.$rowSet["PlayerKey"].'&Mode=Ligue1">';
{


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
  echo "<span id='rankSpan'>$rankToBeDisplayed</span>";
  echo '<a class="popupscroll" href="#"><img class="avat" src="' . $avatarPath .'"></img></a>';

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
				width:715,
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

	$("#globalrankinglink").click(function() {
		$("#groupranking").fadeOut('fast',function () {
			$("#globalranking").fadeIn('fast');
			$("#globalrankinglink").toggleClass('selected');
			$("#grouprankinglink").toggleClass('selected');
			$("#mod-classements .ellipsis").ellipsis();
		}).html();
	});

	$("#grouprankinglink").click(function() {
		$("#globalranking").fadeOut('fast',function () {
			$("#groupranking").fadeIn('fast');
			$("#globalrankinglink").toggleClass('selected');
			$("#grouprankinglink").toggleClass('selected');
			$("#mod-classements .ellipsis").ellipsis();
		}).html();
	});

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
                case size: return "Tout les joueurs";
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

$('#ticker').countdown(
	{
		until: '+<?php echo $diffTime["minutes"];?>m +<?php echo $diffTime["seconds"];?>s',
		format: 'YOWDHMS',
		onTick: everySecondRefresh,
		onExpiry: RunRefresh,
		tickInterval: 1
	}

);

//until: untilDate,
//onTick: everySecondRefresh,
//onExpiry: RunRefresh,


function everySecondRefresh(periods) {
	$("#refreshvalue").html( pad(periods[5],2) + ":" + pad(periods[6],2) + "'");
	$("#nextRefresh").html( pad(periods[5],2) + ":" + pad(periods[6],2) + "'");
	//$("#linkRefresh").css("display","none");
	if (periods[5]==0 && periods[6]==30) {
		$.ajax({
			type: "POST",
			url: "refresh.matches.php",
			data: { groupKey: <?php echo $_groupKey;?>},
			dataType: 'json'
		});
	}
}

$("#linkRefresh").click(function(){
	var colorBlendOptions = [
	                     	{ param: "opacity", alpha:[10, 80, 30, 100], duration: 5000 , cycles:10}
	                     ];
	$(".livescoreHome",$("li[match-key=528]")).colorBlend("stop","all");
	$(".livescoreHome",$("li[match-key=528]")).colorBlend(colorBlendOptions).colorBlend("opacity",[100]); //"opacity", [10, 80, 30, 100], 5000);
});

function RunRefresh() {

	if (_isMatchInProgress) {
		$('#WaitingLayer').fadeIn();
		$.ajax({
			type: "POST",
			url: "get.live.info.php?GroupKey=<?php echo $_groupKey;?>",
			data: { groupKey: <?php echo $_groupKey;?>},
			dataType: 'json',
			success: callbackRefreshInfo,
			error: callbackPostError
		});

		$.ajax({
			type: "POST",
			url: "get.live.ranking.php?GroupKey=<?php echo $_groupKey;?>",
			data: { groupKey: <?php echo $_groupKey;?>},
			dataType: 'json',
			success: callbackRefreshRanking,
			error: callbackPostError
		});
	}
}

function callbackRefreshInfo (data)
{
	$.each ( data.players, function(i,player) {
		var playerDetail = $("li[player-key="+player.PlayerKey+"]", $("#playerDetail"));
		var playerMatchDetail =$("div.popupscroll",playerDetail);
		if (player.PlayerBonus==0)
			$(playerMatchDetail).css("background", "url() no-repeat right center");
		else
			$(playerMatchDetail).css("background", "url(/images/bonus."+player.PlayerBonus+".png) no-repeat right center");

		var homePlayerP4FCh = $("#ScoreHomeChampionship").attr('data-player-key'); 
		if (homePlayerP4FCh==player.PlayerKey)
			$("#ScoreHomeChampionship").html(player.PlayerScore);
		var awayPlayerP4FCh = $("#ScoreAwayChampionship").attr('data-player-key'); 
		if (awayPlayerP4FCh==player.PlayerKey)
			$("#ScoreAwayChampionship").html(player.PlayerScore);

		var homePlayerP4FCup = $("#ScoreHomeCup").attr('data-player-key'); 
		if (homePlayerP4FCup==player.PlayerKey)
			$("#ScoreHomeCup").html(player.PlayerScore);
		var awayPlayerP4FCup = $("#ScoreAwayCup").attr('data-player-key'); 
		if (homePlayerP4FCup==player.PlayerKey)
			$("#ScoreAwayCup").html(player.PlayerScore);
		
    playerDetail.attr('player-score',player.PlayerScore);
		$(".Score",playerMatchDetail).html("Score : <u>" + (player.PlayerScore+player.PlayerBonus) + " pts</u>");
		_isMatchInProgress = false;
     	$.each (player.matches, function (i,match) {
			if (match.LiveStatus==1 || match.LiveStatus==2 || match.LiveStatus==3)
				_isMatchInProgress = true;

			//if (match.LiveStatus!=10) {
     			RefreshMatchInfo(match.MatchKey,match.TeamHomeScore,match.TeamAwayScore, match.LiveStatus,match.LiveStatusWording,match.ActualTime);
     			RefreshForecastInfo(player.PlayerKey,match.MatchKey,match.TeamHomeForecast,match.Separator,match.TeamAwayForecast, match.Score,match.ScoreWording,match.Class);
			//}
         });
     });

	$("#lastRefresh").html( data.LastRefresh);
	 if (_isMatchInProgress) {
		 $('#ticker').countdown('change', {until: data.NextRefresh});
	 }
	 else {
		 $('#NextRefreshSpan').css("display","none");
	 }


}
function callbackRefreshRanking (data)
{
	var newGroupRankingOrder = new Array();
	var newGlobalRankingOrder = new Array();

	$.each ( data.playersGroupRanking, function(i,player) {

		newGroupRankingOrder.push("GRP_"+player.PlayerKey);

		var playerDetail = $("li[player-key="+player.PlayerKey+"]", $("#groupranking"));
		//$(playerDetail).remove().appendTo($("#groupranking ol"));
		$("em",playerDetail).html(player.Score + " pts");
		$("span.var",playerDetail).removeClass("up");
		$("span.var",playerDetail).removeClass("eq");
		$("span.var",playerDetail).removeClass("down");
		$("span.var",playerDetail).addClass(player.Variation);
		$("#rankSpan",playerDetail).html(player.RankToBeDisplayed);

     });

	$.each ( data.playersGlobalRanking, function(i,player) {

		newGlobalRankingOrder.push("G_"+player.PlayerKey);

		var playerDetail = $("li[player-key="+player.PlayerKey+"]", $("#globalranking"));
		//$(playerDetail).remove().appendTo($("#globalranking ol"));
		$("em",playerDetail).html(player.Score + " pts");
		$("span.var",playerDetail).removeClass("up");
		$("span.var",playerDetail).removeClass("eq");
		$("span.var",playerDetail).removeClass("down");
		$("span.var",playerDetail).addClass(player.Variation);
		$("#rankSpan",playerDetail).html(player.RankToBeDisplayed);

     });
	restoreOrderRanking("#groupranking",newGroupRankingOrder);
	restoreOrderRanking("#globalranking",newGlobalRankingOrder);
	$('#WaitingLayer').fadeOut();
}

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

/*
function replaces extended characters of 'text' with its character
entities.
call the function by default with output = false. if you switch it
to true
it will format the source code for output in a textarea.
*/
function replaceExtChars(text,output) {
  text = text.replace(eval('/&/g'), '&amp;');
  text = text.replace(eval('/é/g'), '&eacute;');
//  fromTo = new
//  Array('&AElig;','Æ','&Aacute;','Á','&Acirc;','Â',' &Agrave;','À','&Aring;','Å','&Atilde;',
//  'Ã','&Auml; ','Ä','&Ccedil;','Ç','&ETH;','Ð','&Eacute;','É','& Ecirc;','Ê',
//  '&Egrave;','È','&Euml;','Ë','&Iacute;' ,'Í','&Icirc;','Î','&Igrave;','Ì',
//  '&Iuml;','Ï','&N tilde;','Ñ','&Oacute;','Ó','&Ocirc;','Ô','&Ograve; ','Ò',
//  '&Oslash;','Ø','&Otilde;','Õ','&Ouml;','Ö',' &THORN;','Þ','&Uacute;','Ú',
//  '&Ucirc;','Û','&Ugrave ;','Ù','&Uuml;','Ü','&Yacute;','Ý','&aacute;','á',
//  '&acirc;','â','&aelig;','æ','&agrave;','à','&aring ;','å','&atilde;','ã',
//  '&auml;','ä','&brvbar;','¦', '&ccedil;','ç','&cent;','¢','&copy;','©',
//  '&deg;',' °','&eacute;','é','&ecirc;','ê','&egrave;','è','&e th;','ð','&euml;',
//  'ë','&frac12;','½','&frac14;','¼ ','&frac34;','¾','&gt;','>','&gt','>','&iacute;',
//  ' í','&icirc;','î','&iexcl;','¡','&igrave;','ì','&iq uest;','¿','&iuml;','ï','&laquo;',
//  '«','&lt;','<',' &lt','<','&mdash;','','&micro;','µ','&middot;','· ','&ndash;',
//  '','&not;','¬','&ntilde;','ñ','&oacut e;','ó','&ocirc;','ô','&ograve;','ò',
//  '&oslash;','ø','&otilde;','õ','&ouml;','ö','&para;', '¶','&plusmn;','±',
//  '&pound;','£','&quot;','\"','&r aquo;','»','&reg;','®','&sect;','§','­','*',
//  '&sup1 ;','¹','&sup2;','²','&sup3;','³','&szlig;','ß','&t horn;','þ',
//  '&tilde;','','&trade;','','&uacute;', 'ú','&ucirc;','û','&ugrave;','ù',
//  '&uuml;','ü','&ya cute;','ý','&yen;','¥','&yuml;','ÿ');
//
//  if (output) {
//    fromTo[fromTo.length] = '&amp;';
//    fromTo[fromTo.length] = '&';
//  }
//
//  for (i=0; i < fromTo.length; i=i+2)
//  	text = text.replace(eval('/'+fromTo[i+1]+'/g'), fromTo[i]);
  return (text);
}

function compareStrings (value1,value2, length) {
  var tempValue1 = value1;
  var tempValue2 = value2;

  if (value1.length>=length)
	  tempValue1 = tempValue1.substring(0,length);
  if (value2.length>=length)
	  tempValue2 = tempValue2.substring(0,length);

  var reg=new RegExp("^"+tempValue1+".*$","i");  // le "i" sert a ne pas tenire compte de la casse (MAJ/min)
  if(tempValue2.match(reg))
     return true;
  else
	 return false;

}


function RefreshValueWithAlert (htmlElement, newValue) {
	var valueChanged = false;
	var oldValue = $(htmlElement).html();
	if (!compareStrings(oldValue,newValue,5)) {
		//$.log("ValueToBeChanged");
		valueChanged = true;
		$(htmlElement).html(newValue);
    	var colorBlendOptions = [{ param: "opacity", alpha:[10, 80, 30, 100], duration: 5000 , cycles:1}];
    	$(htmlElement).colorBlend("stop","all");
    	$(htmlElement).colorBlend(colorBlendOptions).colorBlend("opacity",[100]);

	}

	return valueChanged ;
}


function RefreshForecastInfo (PlayerKey, MatchKey, TeamHomeForecast,Separator, TeamAwayForecast, Score, ScoreWording, classScore)
{
	var playerDetail = $("li[player-key="+PlayerKey+"]", $("#playerDetail"));
	var playerMatchDetail =$("div[match-key="+MatchKey+"]",playerDetail);

	RefreshValueWithAlert($(".forecastteamHome",playerMatchDetail),TeamHomeForecast);
	RefreshValueWithAlert($(".forecastsep",playerMatchDetail),Separator);
	RefreshValueWithAlert($(".forecastteamAway",playerMatchDetail),TeamAwayForecast);
	if (RefreshValueWithAlert($(".forecastScore",playerMatchDetail),Score + "&nbsp;" + ScoreWording)) {
      $(".forecastScore",playerMatchDetail).removeClass("Success");
      $(".forecastScore",playerMatchDetail).removeClass("Failed");
      $(".forecastScore",playerMatchDetail).removeClass("NotStarted");
      $(".forecastScore",playerMatchDetail).addClass(classScore);
	}
}

function RefreshMatchInfo (MatchKey, TeamHomeScore, TeamAwayScore, Status,StatusWording, ActualTime)
{


	var statusText ="";
	if (Status==10 || Status==2) {
		statusText = StatusWording;
	}
	else if (Status==1 || Status==3) {
		statusText = "" + ActualTime + "'";
	}
	if (statusText!="")
	{
		RefreshValueWithAlert($(".livestatus",$("li[match-key="+MatchKey+"]")),statusText);
		RefreshValueWithAlert($(".livescoreHome",$("li[match-key="+MatchKey+"]")),TeamHomeScore);
		RefreshValueWithAlert($(".livescoreAway",$("li[match-key="+MatchKey+"]")),TeamAwayScore);
	}

}

	function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
	{
		$.log(XMLHttpRequest);
		$.log(textStatus);
		$.log(errorThrown);
	}

  $("#RefreshInfo").bind( "click",function () {
		_isMatchInProgress = true;
		RunRefresh();
	});

	var homePlayerP4FCh = $("#ScoreHomeChampionship").attr('data-player-key'); 
	var playerHomeDetail = $("li[player-key="+homePlayerP4FCh+"]", $("#playerDetail"));
	$("#ScoreHomeChampionship").html(playerHomeDetail.attr('player-score'));
	var awayPlayerP4FCh = $("#ScoreAwayChampionship").attr('data-player-key'); 
	var playerAwayDetail = $("li[player-key="+awayPlayerP4FCh+"]", $("#playerDetail"));
	$("#ScoreAwayChampionship").html(playerAwayDetail.attr('player-score'));

	var homePlayerP4FCup = $("#ScoreHomeCup").attr('data-player-key'); 
	var playerHomeDetailCup = $("li[player-key="+homePlayerP4FCup+"]", $("#playerDetail"));
	$("#ScoreHomeCup").html(playerHomeDetailCup.attr('player-score'));
	var awayPlayerP4FCup = $("#ScoreAwayCup").attr('data-player-key'); 
	var playerAwayDetailCup = $("li[player-key="+awayPlayerP4FCup+"]", $("#playerDetail"));
	$("#ScoreAwayCup").html(playerAwayDetailCup.attr('player-score'));

});
</script>	