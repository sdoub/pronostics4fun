<?php

AddScriptReference("scrollpane");
AddScriptReference("new.home.connected");

WriteScripts();
?>

<div id="newsTitle" style="background:url('<?php echo ROOT_SITE; ?>/images/actu.png') no-repeat scroll left top
		#D7E1F6;height:360px;margin-bottom:30px;_width:920px;">
<!--  h3 style="text-align:left;color:#365F89;padding-left:25px;padding-top:10px;"><?php echo __encode("Actualité ...");?></h3> -->
<div>&nbsp;</div>
<div style="font-size:16px;color:#365F89;font-weight:bold;margin-left:140px;margin-top:5px;"><?php echo __encode("Tout ce qui se passe sur Pronostics4Fun ...");?></div>
<div id="news" class="flexcroll" style="background:#6D8AA8;height:300px;overflow: auto;margin-top:15px;margin-left:15px;margin-right:15px;margin-bottom:15px;">
<style>
#news ul li {
	border-bottom:1px solid #cccccc;
	padding-left:10px;
	padding-bottom:5px;
	color:#FFFFFF;
	min-height:50px;
	_height:50px;
}

#news ul li img {
	width:50px;
	height:50px;
	padding-right:10px;
	float:left;
}

#forecastsTitle ul li {
	height:62px;
	color:#FFFFFF;
	padding-left:15px;
	padding-top:8px;
	background:url('<?php echo ROOT_SITE; ?>/images/forecasts.row.home.png') no-repeat scroll left top transparent;

}
</style>
<ul>

<?php

$query = "SELECT NewsKey, NewsInfos,InfoType, NewsPicture,NewsDate FROM (
SELECT news.PrimaryKey NewsKey, news.Information NewsInfos,InfoType, '' NewsPicture,UNIX_TIMESTAMP(news.InfoDate) NewsDate
FROM news
WHERE CompetitionKey=" .COMPETITION . "
UNION ALL
SELECT playersenabled.PrimaryKey,
playersenabled.NickName NewsInfos,
3 InfoType,
playersenabled.AvatarName NewsPicture,
UNIX_TIMESTAMP(playersenabled.CreationDate) NewsDate
FROM playersenabled
WHERE playersenabled.CreationDate > CURDATE() - INTERVAL 365 DAY
) NewsList
ORDER BY NewsList.NewsDate desc
";
$resultSet = $_databaseObject->queryPerf($query,"Get news");

$cnt = 0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $playerKey = $rowSet["NewsKey"];
  echo '<li class="" player-key="' . $playerKey . '">';
  if ($rowSet["InfoType"]=="3"){
    echo "<div class='player'>";

    $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
    if (!empty($rowSet["NewsPicture"])) {
      $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["NewsPicture"];
    }
    echo '<img class="avat" src="' . $avatarPath . '" ></img>';
  	setlocale(LC_TIME, "fr_FR");
	$creationFormattedDate = strftime("%A %d %B %Y",$rowSet['NewsDate']);
    echo "<strong>" . __encode($rowSet['NewsInfos'] ."</strong>". __encode(" est inscrit depuis le ") . $creationFormattedDate);
    echo "</div>";
  }
  else {
  	setlocale(LC_TIME, "fr_FR");
	$newsFormattedDate = strftime("%A %d %B %Y à %H:%M",$rowSet['NewsDate']);
    echo "<div style='float:right;border-left:1px solid #D7E1F6;border-bottom:1px solid #D7E1F6;
    	background: #365F89;color: #FFFFFF;font: bold 11px/ normal Tahoma, Verdana;'>";
    echo "Le " . __encode($newsFormattedDate);
    echo "</div>";
    echo "<div style='padding-top:5px;'>";

    switch ($rowSet["InfoType"]) {
      case "1":
        echo '<img  src="' . ROOT_SITE . '/images/news.png" ></img>';
        break;
      case "2":
        echo '<img  src="' . ROOT_SITE . '/images/stats.png" ></img>';
        break;
      case "5":
        echo '<img  src="' . ROOT_SITE . '/images/calendar.png" ></img>';
        break;
      case "6":
        echo '<img  src="' . ROOT_SITE . '/images/TropheeGold.png" ></img>';
        break;
      case "7":
        echo '<img  src="' . ROOT_SITE . '/images/podium.png" ></img>';
        break;
      case "8":
        echo '<img  src="' . ROOT_SITE . '/images/star_48.png" ></img>';
        break;
  }

  echo __encode($rowSet['NewsInfos']);



echo "</div>";

  }



  echo "</li>";
}
?>
</ul>
</div>

</div>
<div >
<div id="forecastsTitle" style="float:left;background:url('<?php echo ROOT_SITE; ?>/images/forecasts.home2.png') no-repeat scroll left top
		#D7E1F6;height:250px;width:450px;margin-bottom:30px;">
<div style="text-align:left;color:#365F89;top-margin:5px;left-margin:10px;">
<div style="font-size:16px;text-transform: uppercase;font-weight:bold;margin-left:180px;margin-top:15px;"><?php echo __encode("Pronostics à venir ...");?></div>
</div>
<div class="flexcroll" style="height:180px;width:420px;overflow: auto; margin-top:25px;margin-left:15px;margin-right:15px;margin-bottom:15px;">
<ul>
<?php

$query= "SELECT Description,UNIX_TIMESTAMP( BeginDate) unixBeginDate, UNIX_TIMESTAMP(EndDate) unixEndDate,
BeginDate, EndDate,DATEDIFF(BeginDate,NOW()) RemainingDays,IF (BeginDate>NOW(),0,1) hasStarted,
groups.Status,
(SELECT COUNT(DISTINCT forecasts.PlayerKey)
   FROM forecasts
  INNER JOIN matches ON matches.PrimaryKey=forecasts.MatchKey
  WHERE matches.GroupKey=groups.PrimaryKey) players,
(SELECT COUNT(matches.PrimaryKey)
   FROM matches
   LEFT JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus<>10
  WHERE matches.GroupKey=groups.PrimaryKey) OpenedMatch,
(SELECT COUNT(matches.PrimaryKey)
   FROM matches
  INNER JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus=10
  WHERE matches.GroupKey=groups.PrimaryKey) ClosedMatch,
(SELECT COUNT(*)
   FROM forecasts
  INNER JOIN matches ON matches.PrimaryKey=forecasts.MatchKey
   LEFT JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus<>10
  WHERE matches.GroupKey=groups.PrimaryKey
    AND forecasts.PlayerKey=".$_authorisation->getConnectedUserKey().") forecasts
 FROM groups
WHERE groups.CompetitionKey=" . COMPETITION . " AND IsCompleted=0
ORDER BY groups.DayKey, groups.BeginDate";


$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get all groups of the current competition");

foreach ($rowsSet as $rowSet)
{

  $status = "";
  if ($rowSet["unixBeginDate"]==0) {
    $status = __encode("Non programmé");
  }
  else if ($rowSet["hasStarted"]==1) {
    $status = __encode("En cours ...");
  } else if ($rowSet["RemainingDays"]==0) {
    $status = __encode("Début aujourd'hui");
  } else if ($rowSet["RemainingDays"]==1) {
    $status = __encode("Début demain");
  } else {
    $status = __encode("Début dans ") . $rowSet["RemainingDays"] . " jours";
  }

  if ($rowSet["unixBeginDate"]==0) {
    $groupStatus = __encode("Non ouvert ");
    $colorStatus = "#f54949";
  } else if ($rowSet["ClosedMatch"]==0) {
    if ($rowSet["Status"]==0) {
      $groupStatus = __encode("Non ouvert ");
      $colorStatus = "#f54949";
    } else {
      $groupStatus = __encode("Ouvert ");
      $colorStatus = "#B3D207";
    }
  } else if ($rowSet["ClosedMatch"]>0 && $rowSet["ClosedMatch"]<10) {
    $groupStatus = __encode("Partiellement fermé ");
    $colorStatus = "#e09051";
  } else {
    $groupStatus = __encode("Clôturé ");
    $colorStatus = "#f54949";
  }


  echo '<li style="cursor:pointer;">';
  echo "<div style='float:right;margin-right:12px;padding-left:3px;padding-right:3px;border-left:1px solid #D7E1F6;border-bottom:1px solid #D7E1F6;
      	background: #365F89;color: #FFFFFF;font: bold 11px/ normal Tahoma, Verdana;'>";
  echo $status;
  echo "</div>";

  setlocale(LC_TIME, "fr_FR");
  if (strftime("%d",$rowSet['unixBeginDate']) == strftime("%d",$rowSet['unixEndDate'])){
    $groupDateFormatted = strftime("%d %B %Y",$rowSet['unixEndDate']);
    $groupDateFormatted = __encode(" (".$groupDateFormatted.")");
  }
  else {
    $groupDateFormatted = strftime("%d-",$rowSet['unixBeginDate']);
    $groupDateFormatted .= strftime("%d %B %Y",$rowSet['unixEndDate']);
    $groupDateFormatted = __encode(" (".$groupDateFormatted.")");
  }

  if ($rowSet["unixBeginDate"]==0){
    $groupDateFormatted ="";
  }
  echo '<span style="font-weight:bold;">'  . $rowSet["Description"] . $groupDateFormatted . '</span><br/>';
  if ($rowSet["Status"]>0) {
  echo '<span style="padding-left:10px;">' . $rowSet["players"] . __encode(" participants") . '</span><br/>';
  }
  echo '<span style="padding-left:10px;">' .  __encode("Pronostics : ") . '</span>
  <span style="color:'.$colorStatus.'">'.$groupStatus.'</span>';
  if ($rowSet["Status"]>0) {
    echo '<span title="'.__encode("Match pronostiqué / Pronostics ouvert").'">('. $rowSet["forecasts"] . "/" . $rowSet["OpenedMatch"] . ')</span>';
  }
  if ($rowSet["forecasts"] != $rowSet["OpenedMatch"] && $rowSet["RemainingDays"]<=2) {
    echo '<span title="'. __encode("Moins de 2 jours pour donner vos pronostics!") . '" style="width:20px;height:20px;background:url(\''. ROOT_SITE . '/images/warning.small.png\') no-repeat scroll left top transparent;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>';
  }
  echo '</li>';
}

?>

</ul>
</div>

</div>
<style>

#globalRankingTitle table td {
width:38px;
font-size:16px;
font-weight:bold;
color:#FFFFFF;
}

#globalRankingTitle table tr {
height:38px;
}
</style>
<div id="mod-classements" style="margin-left:0px;float:right;">
<div id="globalRankingTitle" style="text-align:left;color:#365F89;background:url('<?php echo ROOT_SITE; ?>/images/top5.png') no-repeat scroll left top #D7E1F6;height:250px;width:430px;margin-bottom:30px;">
<div>&nbsp;</div>
<div style="font-size:16px;text-transform: uppercase;font-weight:bold;margin-left:110px;margin-top:0px;"><?php echo __encode("Top 5");?></div>
<div class="node-in" >
<div class="panel" style="padding-top:10px;padding-left:30px;padding-right:15px;">
<ol>


<?php

$sql = "SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND PRK.CompetitionKey=" .COMPETITION ." ORDER BY RankDate desc LIMIT 0,1) Rank,
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey AND PRK.CompetitionKey=" .COMPETITION ." ORDER BY RankDate desc LIMIT 1,1) PreviousRank,
players.PrimaryKey PlayerKey,
CONCAT(SUBSTR(players.NickName,1,10),IF (LENGTH(nickname)>9,'...','')) NickName,
players.NickName FullNickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score
FROM playersenabled players
GROUP BY NickName
ORDER BY Rank, NickName
LIMIT 0,5";

$resultSet = $_databaseObject->queryPerf($sql,"Get players ranking");
$cnt = 0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $playerKey=$rowSet["PlayerKey"];
  switch ($cnt) {
    case 0:
      echo '<li class="first" player-key="' . $playerKey . '">';
      break;
    case 4:
      echo '<li class="last" player-key="' . $playerKey . '">';
      break;
    default:
      echo '<li player-key="' . $playerKey . '">';
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


  echo '<p><a class="popupscroll" href="#">'. $rowSet["FullNickName"] .'<em>'. $rowSet["Score"] . ' pts</em><span class="var ' . $variation . '"></span></a></p>';
  echo '</li>';


}
?>
</ol>

</div>
</div>
</div>
</div>

<script>
//<img style="width:280px;height:231px;" src="<?php echo ROOT_SITE;?>/images/podium.home.png"/>
/*
 * <canvas id="globalRankingCanvas" style="width:430px;height:250px">
 </canvas>


 <div style="float:left;width:150px;height:80px;">
 <div style="font-size:16px;color:#365F89;float:left;text-transform: uppercase;font-weight:bold;margin-left:20px;margin-top:15px;tex-align:left;"><?php echo __encode("Podium");?></div></div>
 <div style="float:left;width:125px;height:80px;"><img style="width:50px;height:50px;padding-top:15px;" src="<?php echo ROOT_SITE; ?>/images/avatars/21_1281953186.jpg"></img></div>
 <div style="float:left;width:125px;height:80px;"></div>
 <div style="float:left;width:150px;height:110px;"><img style="width:50px;height:50px;padding-left:50px;" src="<?php echo ROOT_SITE; ?>/images/avatars/19_1281972613.png"></img>
 <div style="font-weight:bold;color:#FFFFFF;padding-top:10px;padding-left:60px;">Sdoub</div>
 <div style="background-image: url(<?php echo ROOT_SITE; ?>/images/sprite.png);
     background-repeat: no-repeat;background-position: -14px -366px;color:#FFFFFF;margin-top:10px;margin-left:50px;padding-bottom:15px;">125 pts</div>
 </div>
 <div style="float:left;width:125px;height:110px;"><span style="padding-left:20px;font-weight:bold;color:#FFFFFF;">Alix1005</span>
 <div style="background-image: url(<?php echo ROOT_SITE; ?>/images/sprite.png);
     background-repeat: no-repeat;background-position: -13px -263px;color:#FFFFFF;margin-top:15px;margin-left:15px;padding-bottom:15px;font-size: 1.3em;">135 pts</div></div>
 <div style="float:left;width:125px;height:110px;"><img style="width:50px;height:50px;padding-right:30px;padding-top:30px;" src="<?php echo ROOT_SITE; ?>/images/avatars/23_1282145831.png"></img></div>
 <div style="float:left;width:150px;height:70px;"></div>
 <div style="float:left;width:125px;height:70px;"></div>
 <div style="float:left;width:125px;height:70px;"><div style="font-weight:bold;color:#FFFFFF;">Gigot</div>
 <div style="background-image: url(<?php echo ROOT_SITE; ?>/images/sprite.png);
     background-repeat: no-repeat;background-position: -15px -315px;color:#FFFFFF;padding-top:10px;">115 pts</div></div>

 */
$.requireScript('<?php echo ROOT_SITE; ?>/js/jquery.corner.js', function() {
	$("#newsTitle").corner();
	$("#forecastsTitle").corner();
	$("#globalRankingTitle").corner();
	$("div.flexcroll").jScrollPane({
		showArrows: true,
		horizontalGutter: 10
	});
});

//$.log($("#globalRankingCanvas")[0].getContext('2d'));

//$.requireScript('<?php echo ROOT_SITE; ?>/js/jcanvas.min.js', function() {
//
//	 $("#globalRankingCanvas").drawText({
//		  fillStyle: "#D7E1F6",
//		  strokeStyle: "#000",
//		  strokeWidth: 0,
//		  text: "Sdoub",
//		  align: "right",
//		  baseline: "middle",
//		  font: "normal 12pt Helvetica",
//		  x: 100,
//		  y: 45
//		});
//
//	 $("#globalRankingCanvas").drawText({
//		  fillStyle: "#D7E1F6",
//		  strokeStyle: "#000",
//		  strokeWidth: 0,
//		  text: "Alix1005",
//		  align: "center",
//		  baseline: "middle",
//		  font: "normal 12pt Helvetica",
//		  x: 150,
//		  y: 20
//		});
//
//	 $("#globalRankingCanvas").drawText({
//		  fillStyle: "#D7E1F6",
//		  strokeStyle: "#000",
//		  strokeWidth: 0,
//		  text: "Aurel",
//		  align: "left",
//		  baseline: "middle",
//		  font: "normal 12pt Helvetica",
//		  x: 200,
//		  y: 70
//		});
//
//	$("#globalRankingCanvas").drawImage({
//		  source: "<?php echo ROOT_SITE;?>/images/podium.home.png",
//		  x: 65, y: 5,
//		  width:180 ,
//		  height:148,
//		  fromCenter: false
//		});
//
//
//
//});

$("#forecastsTitle li").click(function() {
	window.location.replace( '<?php echo ROOT_SITE;?>/index.php?Page=1');

});


</script>