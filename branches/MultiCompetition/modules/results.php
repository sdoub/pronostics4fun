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

.equipeRankUp {
	font-size: 12px;
	color: #365F89;
	font-weight: bold;
}

.equipeRankDown {
	font-size: 12px;
	color: #365F89;
	font-weight: normal;
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

.time0 {
	width: 60px;
	padding-left: 20px;
}
.time1 {
	width: 60px;
	padding-left: 20px;
	background: url("<?php echo ROOT_SITE; ?>/images/star_25.png") no-repeat scroll left center transparent;

}

</style>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_SITE;?>/css/ranking.css" />

<?php
$_groupKey = $_GET['GroupKey'];
$sql = "SELECT Description FROM groups WHERE PrimaryKey=" . $_groupKey. " AND CompetitionKey=" . COMPETITION;
$resultSet = $_databaseObject->queryPerf($sql,"Get groups");

$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$_groupDescription = $rowSet['Description'];
unset($rowSet,$resultSet,$sql);

?>
<div id="mainCol">
<h3 style="font-size: 30px; font-family: Georgia; color: #365F89"><?php  echo $_groupDescription; ?></h3>

<div class="altBloc">
<table>
	<tr>
		<td colspan="6">&nbsp;</td>
		<?php if ($_groupKey>100) {?>
		<td>&nbsp;</td>
		<?php }?>
		<td
			style="width: 100px; text-align: center; font-family: georgia; font-size: 12px; font-weight: bold; color: #FFFFFF">Pronostics</td>
		<td
			style="width: 100px; text-align: center; font-family: georgia; font-size: 12px; font-weight: bold; color: #FFFFFF">Points</td>
	</tr>
	<?php

	$sql = "SELECT matches.PrimaryKey MatchKey,TeamHome.PrimaryKey TeamHomeKey,
       TeamHome.Name TeamHomeName,
forecasts.TeamHomeScore ForecastTeamHomeScore,
       TeamAway.PrimaryKey TeamAwayKey,
       TeamAway.Name TeamAwayName,
forecasts.TeamAwayScore ForecastTeamAwayScore,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.Status,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<9) TeamHomeScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<9) TeamAwayScore,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3) AND events.half<5) TeamHomeScore90,
(SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3) AND events.half<5) TeamAwayScore90,
IFNULL(results.livestatus,0) LiveStatus,
playermatchresults.Score,
matches.IsBonusMatch
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=" . $_authorisation->getConnectedUserKey() . "
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
LEFT JOIN playermatchresults ON playermatchresults.PlayerKey=" . $_authorisation->getConnectedUserKey() . " AND playermatchresults.MatchKey=matches.PrimaryKey
WHERE matches.GroupKey=" . $_groupKey . "
ORDER BY matches.ScheduleDate";
	$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

	$scheduleMonth = "00";
	$scheduleDay = "00";
	while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
	{
	  $tempScheduleMonth=strftime("%m",$rowSet['ScheduleDate']);
	  $tempScheduleDay=strftime("%d",$rowSet['ScheduleDate']);
	  if (!($scheduleMonth==$tempScheduleMonth && $scheduleDay==$tempScheduleDay))
	  {
	    setlocale(LC_TIME, "fr_FR");
	    $scheduleFormattedDate = __encode(strftime("%A %d %B %Y",$rowSet['ScheduleDate']));
	    echo '<tr class="day"
      	    style="">
      	  <td colspan="6">' . $scheduleFormattedDate . '</td>';
	    if ($_groupKey>100) {
	      echo '<td>90\'</td>';
	    }
      	echo  '<td colspan="2">&nbsp;</td>
      	</tr>';
	  }

	  echo '<tr class="match" match-key="' . $rowSet['MatchKey'] . '" status="' . $rowSet["LiveStatus"] . '">
      	  <td class="time' . $rowSet["IsBonusMatch"] . '">' . strftime("%H:%M",$rowSet['ScheduleDate']) . '</td>
      	  <td class="teamHome">' . $rowSet['TeamHomeName'] . '</td>
      	  <td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamHomeKey'] . '.png" width="30px" height="30px"></img></td>';

      if ($rowSet["LiveStatus"]==10) {
	    echo '<td class="score">' . $rowSet["TeamHomeScore"] . "-" . $rowSet["TeamAwayScore"] .'</td>';
      }
      else {
        echo '<td class="score">' . getStatus($rowSet["LiveStatus"]) .  '</td>';
      }
      	  echo '<td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamAwayKey'] . '.png"></img></td>
      	  <td class="teamAway">' . $rowSet['TeamAwayName'] . '</td>';

	      if ($_groupKey>100) {
      	    echo '<td class="score">' . $rowSet["TeamHomeScore90"] . "-" . $rowSet["TeamAwayScore90"] .'</td>';
      	  }
      	  echo '<td nowrap><div style="text-align:center;">';

      	  if (!($rowSet['ForecastTeamHomeScore']=="")) {
	    if ($rowSet["LiveStatus"]==10) {
	      echo $rowSet['ForecastTeamHomeScore'] . "-" . $rowSet['ForecastTeamAwayScore'];
	    }
	    else {
	      echo "<a href='javascript:void(0);' match-key='" . $rowSet['MatchKey'] . "' >" . $rowSet['ForecastTeamHomeScore'] . "-" . $rowSet['ForecastTeamAwayScore'] . "</a>";
	    }

	  }
	  else
	  {
	    echo __encode("<a href='javascript:void(0);' match-key='" . $rowSet['MatchKey'] . "' >Pas de pronos.</a>");
	  }

	  echo '</div></td>';
	  if ($rowSet["LiveStatus"]==10) {
        echo '<td style="text-align:right;padding-right:15px;">' . $rowSet["Score"]. '</td>';
	  }
	  else
	  {
	     echo '<td>&nbsp;</td>';
	  }

      echo '</tr>';

	  $scheduleMonth = strftime("%m",$rowSet['ScheduleDate']);
	  $scheduleDay = strftime("%d",$rowSet['ScheduleDate']);

	}
	unset($rowSet,$resultSet,$sql);



	?>

<?php
	$sql = "SELECT
SUM(playermatchresults.Score) Score,
IFNULL((SELECT playergroupresults.Score FROM playergroupresults WHERE playergroupresults.PlayerKey=playermatchresults.PlayerKey AND playergroupresults.GroupKey= $_groupKey),0) GroupScore,
IFNULL((SELECT playergroupranking.Rank FROM playergroupranking WHERE playergroupranking.PlayerKey=playermatchresults.PlayerKey AND playergroupranking.GroupKey= $_groupKey ORDER BY playergroupranking.RankDate desc LIMIT 0,1),0) GroupRank,
IFNULL((SELECT playergroupranking.Rank FROM playergroupranking WHERE playergroupranking.PlayerKey=playermatchresults.PlayerKey AND playergroupranking.GroupKey= $_groupKey ORDER BY playergroupranking.RankDate desc LIMIT 1,1),0) PreviousGroupRank
FROM playermatchresults
WHERE playermatchresults.PlayerKey=" . $_authorisation->getConnectedUserKey() . "
  AND playermatchresults.MatchKey IN (SELECT PrimaryKey FROM matches WHERE matches.GroupKey=$_groupKey  AND matches.PrimaryKey IN (SELECT MatchKey FROM results WHERE LiveStatus=10))";

	$resultSet = $_databaseObject->queryPerf($sql,"Get bonus and player score");

	$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
	$playerScore=$rowSet["Score"]+$rowSet["GroupScore"];
	$groupScore=$rowSet["GroupScore"];
	$groupRank=$rowSet["GroupRank"];
  if ($rowSet["PreviousGroupRank"]) {
    if ($rowSet["PreviousGroupRank"]>$rowSet["GroupRank"]) {
      $variation = "up";
    }
    else if ($rowSet["PreviousGroupRank"]<$rowSet["GroupRank"]) {
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

  if ($_groupKey>100) {
    $colspan =8;
    }
  else {
    $colspan =7;
  }
?>


<tr class="match"><td style="text-align:right;padding-right:15px;padding-top:10px;" colspan="<?php echo $colspan;?>">Bonus : </td><td style="text-align:right;padding-right:15px;padding-top:10px;">+<?php echo $groupScore?></td></tr>
<tr class="match"><td style="text-align:right;padding-right:15px;padding-top:10px;" colspan="<?php echo $colspan;?>">Total : </td><td style="text-align:right;padding-right:15px;border-top: 1px solid #FFF;padding-top:10px;"><?php echo $playerScore;?></td></tr>
</table>
  <div id="divOverlay" style="opacity:0;text-align:center;vertical-align: middle;padding-top:5px;padding-bottom:5px;color:black;cursor: pointer;"><p style="color:#000;">Cliquer ici pour voir le detail de ce match</p><p id="info"></p></div>
        <script type="text/javascript">
            $(document).ready(function() {
                var _matchKey='';
                $('tr[class=match][match-key][status=10]').mouseenter(function() {
                    var $divBottom = $(this);
                    _matchKey = $divBottom.attr('match-key');
                    var $divOverlay = $('#divOverlay');
                    var bottomTop = $divBottom.position().top; //()attr('offsetTop');
                    var bottomLeft = $divBottom.position().left; //.attr('offsetLeft');
                    var bottomWidth = $divBottom.css('width');
                    var bottomHeight = $divBottom.css('height')-2;
                    if ($.browser.msie)
                        bottomWidth = 550;

            		$divOverlay.css({
            		    position: 'absolute',
            		    top: bottomTop,
            		    left: bottomLeft,
            		    width: bottomWidth,
            		    height: bottomHeight,
            		    color:'#000',
            		    background: '#FFF'

            		});
            		$divOverlay.show().stop().animate({opacity:0.7}, function () {
            			$(this).mouseout(function() {
	            			$divOverlay.stop().animate({opacity:0}).hide();
	            		});
                		});

                });

                $('#divOverlay').click(function(){
        			var urlToOpen = "submodule.loader.php?SubModule=6&matchKey="+_matchKey;
        			$.openPopupLayer({
        				name: "matchPopup",
        				width: 350,
        				height: 400,
        				url:urlToOpen
        			});
        		});

            	$('a[match-key]').click(function(){
            		$.openPopupLayer({
            			name: "forecatstPopup",
            			width: 350,
            			height: 400,
            			url: "submodule.loader.php?SubModule=3&matchKey="+$(this).attr("match-key")
            		});

            	});
            	$('#mod-classements li[player-key]').click(function(){
            		$.openPopupLayer({
            			name: "groupDetailPopup",
            			width: 350,
            			height: 400,
            			url: "submodule.loader.php?SubModule=9&playerKey="+$(this).attr("player-key")+"&groupKey=<?php echo $_groupKey;?>"
            		});

            	});

            });
        </script>

</div>
<div class="mainBloc">

<div id="PlayerRanking" style="text-align:center;font-weight: bold;"><?php echo __encode('Votre position dans ce groupe : ')?><b><?php echo $groupRank?></b><span class="var <?php echo $variation; ?>">&nbsp;</span></div>
<div id="mod-classements" class="node2" style="padding: 13px 0;">
<div class="head">
<div>
<h4>Classement - <?php echo __encode($_groupDescription);?></h4>
</div>
</div>
<div class="node-in">
<div class="panel">
<ol>


<?php

$sql = "SELECT
(SELECT PGRK.Rank FROM playergroupranking PGRK WHERE players.PrimaryKey=PGRK.PlayerKey AND PGRK.GroupKey=$_groupKey ORDER BY RankDate desc LIMIT 0,1) Rank,
(SELECT PGRK.Rank FROM playergroupranking PGRK WHERE players.PrimaryKey=PGRK.PlayerKey AND PGRK.GroupKey=$_groupKey ORDER BY RankDate desc LIMIT 1,1) PreviousRank,
players.PrimaryKey PlayerKey,
CONCAT(SUBSTR(players.NickName,1,10),IF (LENGTH(nickname)>9,'...','')) NickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)
      ),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.PrimaryKey=$_groupKey AND groups.CompetitionKey=" . COMPETITION . ")),0)) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Score desc, NickName
LIMIT 0,5";

$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $playerKey = $rowSet["PlayerKey"];
  switch ($cnt) {
    case 0:
      echo '<li class="first" player-Key="' . $playerKey . '">';
      break;
    case 4:
      echo '<li class="last" player-Key="' . $playerKey . '">';
      break;
    default:
      echo '<li player-Key="' . $playerKey . '">';
      break;
  }
  $cnt++;
  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }

  echo '<a class="popupscroll" href="#"><img class="avat" src="' . $avatarPath .'"></img></a>';

  if ($rowSet["PreviousRank"]) {
    if ($rowSet["PreviousRank"]>$rowSet["Rank"]) {
      $variation = "up";
    }
    else if ($rowSet["PreviousRank"]<$rowSet["Rank"]) {
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


  echo '<p><a class="popupscroll" href="#">'. $rowSet["NickName"] .'<em>'. $rowSet["Score"] . ' pts</em><span class="var ' . $variation . '"></span></a></p>';
  echo '</li>';


}
?>
</ol>

</div>
<a href="index.php?Page=3&GroupKey=<?php echo $_groupKey;?>">Voir tout le classement</a></div>
</div>
</div>
</div>
