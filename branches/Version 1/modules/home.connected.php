<?php

AddScriptReference("countdown");
AddScriptReference("smartTab");
AddScriptReference("home.connected");

WriteScripts();
?>

<div id="mainCol">

<div class="altBloc">

<div id="tabs" class="stContainer">
<center><h4><?php echo __encode("Dernières news");?></h4></center>
  			<ul class="tabs">
  				<li>
  				  <a class="tabs" href="#tabs-2">
                    <span><?php echo __encode("L'actualité..."); ?><br />
                    <small><?php echo __encode("Tout ce qui se passe sur Pronostics4Fun...");?></small>
                    </span>
            	  </a>
            	</li>
  				<li>
  				  <a class="tabs" href="#tabs-1">
                    <span>Les matchs...<br />
                    <small><?php echo __encode("Les pronostics à venir!");?></small></span>
                  </a>
                </li>
  			</ul>

  			<div id="tabs-2" >
  			<ul class="news">
<?php

$query = "SELECT NewsKey, NewsInfos,InfoType, NewsPicture,NewsDate FROM (
SELECT news.PrimaryKey NewsKey, news.Information NewsInfos,InfoType, '' NewsPicture,UNIX_TIMESTAMP(news.InfoDate) NewsDate
FROM news
WHERE CompetitionKey=" . COMPETITION . "
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
  $moduloRow = $cnt % 6;
  $class= "";
  if ($cnt>5) {
    $class= " newsHidden";
  }
  switch ($moduloRow) {
    case 0:
      echo '<li class="first'.$class.'" player-key="' . $playerKey . '"> ';
      break;
    case 1:
    case 2:
    case 3:
    case 4:
      echo '<li class="'.$class.'"player-key="' . $playerKey . '">';
      break;
    case 5:
      echo '<li class="last'.$class.'" player-key="' . $playerKey . '">';
      break;
  }
  $cnt++;
  if ($rowSet["InfoType"]=="3"){
echo "<div class='player'>";

$avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["NewsPicture"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["NewsPicture"];
  }

  echo '<img class="avat" src="' . $avatarPath . '" ></img>';

  	setlocale(LC_TIME, "fr_FR");
	$creationFormattedDate = strftime("%A %d %B %Y",$rowSet['NewsDate']);

  echo __encode($rowSet['NewsInfos'] . __encode(" est inscrit depuis le ") . $creationFormattedDate);

echo "</div>";
  }
  else {
  	setlocale(LC_TIME, "fr_FR");
	$newsFormattedDate = strftime("%A %d %B %Y à %H:%M",$rowSet['NewsDate']);
    echo "<div style='float:right;border-left:1px solid #D7E1F6;border-bottom:1px solid #D7E1F6;
    	background: #365F89;
	color: #FFFFFF;
	font: bold 11px/ normal Tahoma, Verdana;
    '>";
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

  echo __encode($rowSet['NewsInfos']); //. __encode(" est inscrit depuis le ") . $newsFormattedDate;



echo "</div>";

  }



  echo "</li>";
}




?></ul>
  		<div style="margin:0px;">
<?php
echo '<input type="submit" style="float:left;"
	value="' . __encode('<...plus anciennes').'" class="newslink" id="btnPrev" name="btn" ubound="6">
<input type="submit" style="float:right;"
	value="' . __encode('plus récentes ...>') .'" class="newslink newslinkDisabled" id="btnNext" name="btn" lbound="0">
';
?>
</div>
        </div>
          			<div id="tabs-1" >
            	<ul>
<?php
$currentTime= time();
$query = "SELECT TeamHome.PrimaryKey TeamHomeKey,
       TeamHome.Name TeamHomeName,
       TeamAway.PrimaryKey TeamAwayKey,
       TeamAway.Name TeamAwayName,
	   UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
       IFNULL(results.LiveStatus,0) Status,
       matches.PrimaryKey MatchKey,
       forecasts.TeamHomeScore ForecastTeamHomeScore,
       forecasts.TeamAwayScore ForecastTeamAwayScore,
       (SELECT COUNT(1) FROM forecasts WHERE forecasts.MatchKey =matches.PrimaryKey) NbrOfPlayers,
       (SELECT COUNT(1) FROM playermatchresults WHERE playermatchresults.MatchKey=results.MatchKey AND playermatchresults.IsPerfect=1) NbrOfPerfect,
       (SELECT COUNT(1) FROM playermatchresults WHERE playermatchresults.MatchKey=results.MatchKey AND playermatchresults.Score>=5) NbrOfCorrect,
       (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamHomeKey AND events.EventType IN (1,2,3)) TeamHomeScore,
	   (SELECT COUNT(1) FROM events WHERE events.ResultKey=results.PrimaryKey AND events.TeamKey=matches.TeamAwayKey AND events.EventType IN (1,2,3)) TeamAwayScore,
	   (SELECT IFNULL(playermatchresults.Score,0)FROM playermatchresults WHERE playermatchresults.PlayerKey=" . $_authorisation->getConnectedUserKey() . " AND playermatchresults.MatchKey=matches.PrimaryKey) PlayerScore,
	   matches.IsBonusMatch
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey = matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey = matches.TeamAwayKey
LEFT JOIN forecasts ON matches.PrimaryKey = forecasts.MatchKey AND forecasts.PlayerKey=" . $_authorisation->getConnectedUserKey() . "
LEFT JOIN results ON matches.PrimaryKey=results.MatchKey
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey= " . COMPETITION . "
WHERE DATE(matches.ScheduleDate)>DATE(FROM_UNIXTIME($currentTime))-1
ORDER BY matches.ScheduleDate
LIMIT 0,";
if (time()>1276264800) {
  $query .= "6";
}
else {
  $query .= "5";
}
$resultSet = $_databaseObject->queryPerf($query,"Get matches");

$cnt = 0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $bonus = " bonus" . $rowSet["IsBonusMatch"];
  switch ($cnt) {
    case 0:
      echo '<li class="first' . $bonus . '">';
      break;
    case 4:
      if (time()<1276264800) {
        echo '<li class="last' . $bonus . '">';
	  }
	  else {
	    echo '<li class="' . $bonus . '" >';
      }
      break;
    case 5:
      echo '<li class="last' . $bonus . '">';
      break;
    default:
      echo '<li class="' . $bonus . '" >';
      break;
  }
  $cnt++;
echo "<div class='team'>";
  echo '<img class="home" src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamHomeKey'] . '.png" ></img>';
echo "<span class='home'>";
  echo $rowSet['TeamHomeName'] ;
if ($rowSet["Status"]==10) {
  echo " (<b>" . $rowSet["TeamHomeScore"] . "</b>)";
}
  echo "</span>";

echo '<img class="away" src="' . ROOT_SITE . '/images/teamFlags/' . $rowSet['TeamAwayKey'] . '.png" ></img>';
echo "<span class='away'>";
if ($rowSet["Status"]==10) {
  echo "(<b>" . $rowSet["TeamAwayScore"] . "</b>) ";
}
echo $rowSet['TeamAwayName'];

  echo "</span>";

echo "<span class='sep'>|</span>";


echo "</div>";

$scheduleDate = $rowSet["ScheduleDate"];

echo "<div style='float:right;' status=". $rowSet["Status"]." countdown='true' year='". date("Y",$scheduleDate) ."' month='". date("n",$scheduleDate) ."' day='". date("j",$scheduleDate) ."' hour='". date("G",$scheduleDate) ."' minute='". date("i",$scheduleDate) ."'></div>";


if ($rowSet["Status"]==0) {
  echo "<div class='detail'>Vos Pronostics : <b><a match-key='" . $rowSet["MatchKey"] . "' href='javascript:void(0);' title='" . __encode("Modifier vos pronostics") ."'>" . $rowSet["ForecastTeamHomeScore"] . " - " . $rowSet["ForecastTeamAwayScore"] . "</a><img style='border:none;width:15px;height:15px;' src='". ROOT_SITE ."/images/pen.png' /></b></div>";
}
else
{
  echo "<div class='detail'>Vos Pronostics : <b>" . $rowSet["ForecastTeamHomeScore"] . " - " . $rowSet["ForecastTeamAwayScore"] . "</b>(" . $rowSet["PlayerScore"] . " points)</div>";
}

if ($rowSet["Status"]==0) {
  echo "<div class='detail2'>" . $rowSet["NbrOfPlayers"] . " participants</div>";
}
else
{
  echo "<div class='detail3'>" . $rowSet["NbrOfCorrect"] . " score(s) correct dont " . $rowSet["NbrOfPerfect"] . " perfect(s)</div>";
}
  echo "</li>";
}




?>

   </ul>
        	</div>
  		</div>

<script>

var nextMatch = new Date();
nextMatch = new Date(2010, 5, 11, 16,0,0);

/*
 *     layout: '<div class="image{d10}"></div><div class="image{d1}"></div>' +
 '<div class="imageDay"></div><div class="imageSpace"></div>' +
 '<div class="image{h10}"></div><div class="image{h1}"></div>' +
 '<div class="imageSep"></div>' +
 '<div class="image{m10}"></div><div class="image{m1}"></div>' +
 '<div class="imageSep"></div>' +
 '<div class="image{s10}"></div><div class="image{s1}"></div>'
 */
$(document).ready(function(){
// Smart Tab
	$('#tabs').smartTab({autoProgress: false,transitionEffect:'fade'});
	$('div[countdown]').each(function (){
		var nextDate = new Date();
		nextMatch = new Date($(this).attr("year"), $(this).attr("month"), $(this).attr("day"), $(this).attr("hour"),$(this).attr("minute"),0);
		var expiryHtml;
		if ($(this).attr("status")=="10") {
			expiryHtml='<div class="over">Termin&eacute;</div>';
		} else {
			expiryHtml='<div class="over"><a href="index.php?Page=4">En cours ...</a></div>';
		}
		$(this).countdown({until: nextMatch,
		layout: '<b>{dn} {dl} {hnn}{sep}{mnn}</b>',
		expiryText: expiryHtml,
		alwaysExpire : true
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
	$('#mod-classements li').click(function(){
		$.openPopupLayer({
			name: "playerPopup",
			width: 480,
			height: 500,
			url: "submodule.loader.php?SubModule=8&playerKey="+$(this).attr("player-key")
		});

	});

	$('#btnPrev').click(function(){

		var btnPrev = $(this);

		var nbrOfItems = $('#tabs-2 li').size();
		if ($(this).attr('ubound')<nbrOfItems) {
			var ubound = parseInt($(this).attr('ubound'));
			var lbound = parseInt(ubound - 6);
			var newUBound = ubound+6;
			btnPrev.attr('ubound',newUBound);
			var btnNext = $('#btnNext');
			btnNext.attr('lbound',lbound+6);
			if (lbound==0)
				btnNext.toggleClass('newslinkDisabled');
			if (newUBound>nbrOfItems)
				btnPrev.addClass('newslinkDisabled');

			$('#tabs-2 ul').find('li:lt('+newUBound+')').each(function (index) {
				if (index>=lbound)
					$(this).toggleClass('newsHidden');
			});
		}

	});

	$('#btnNext').click(function(){


		var btnNext = $(this);
		var nbrOfItems = $('#tabs-2 li').size();
		if ($(this).attr('lbound')>0) {
			var lbound = parseInt($(this).attr('lbound'));
			var ubound = parseInt(lbound) + 6;
			var newLBound = parseInt(lbound-6);
			btnNext.attr('lbound',newLBound);
			var btnPrev = $('#btnPrev');
			btnPrev.attr('ubound',ubound-6);
			if (newLBound==0)
				btnNext.toggleClass('newslinkDisabled');
			if ((newLBound+6)<nbrOfItems)
				btnPrev.removeClass('newslinkDisabled');
			$('#tabs-2 ul').find('li:lt('+ubound+')').each(function (index) {
				if (index>=newLBound)
					$(this).toggleClass('newsHidden');
			});
		}

	});
});
</script>



</div>
<div class="mainBloc">
<?php
	$sql = "SELECT
(SELECT playerranking.Rank FROM playerranking WHERE playerranking.CompetitionKey=" . COMPETITION . " AND players.PrimaryKey=playerranking.PlayerKey ORDER BY RankDate desc LIMIT 0,1) Rank,
(SELECT playerranking.Rank FROM playerranking WHERE playerranking.CompetitionKey=" . COMPETITION . " AND players.PrimaryKey=playerranking.PlayerKey ORDER BY RankDate desc LIMIT 1,1) PreviousRank,
players.PrimaryKey PlayerKey,
players.NickName,
players.AvatarName,
SUM(IFNULL((SELECT SUM(playermatchresults.Score) FROM playermatchresults WHERE players.PrimaryKey=playermatchresults.PlayerKey
      AND playermatchresults.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")
      AND playermatchresults.MatchKey IN (SELECT results.MatchKey FROM results WHERE results.LiveStatus=10)),0) + IFNULL((SELECT SUM(playergroupresults.Score) FROM playergroupresults WHERE players.PrimaryKey=playergroupresults.PlayerKey
      AND playergroupresults.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")),0)) Score,
(SELECT COUNT(1)
   FROM playergroupranking PGR
  WHERE PGR.Rank=1
    AND PGR.PlayerKey = players.PrimaryKey
	AND PGR.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.IsCompleted=1 AND groups.CompetitionKey=" . COMPETITION . ")
	AND PGR.RankDate = (SELECT MAX(playergroupranking.RankDate) FROM playergroupranking WHERE playergroupranking.PlayerKey=PGR.PlayerKey AND playergroupranking.GroupKey = PGR.GroupKey)) FirstRank,
(SELECT COUNT(1)
   FROM playergroupranking PGR
  WHERE PGR.Rank=2
    AND PGR.PlayerKey = players.PrimaryKey
	AND PGR.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.IsCompleted=1 AND groups.CompetitionKey=" . COMPETITION . ")
	AND PGR.RankDate = (SELECT MAX(playergroupranking.RankDate) FROM playergroupranking WHERE playergroupranking.PlayerKey=PGR.PlayerKey AND playergroupranking.GroupKey = PGR.GroupKey)) SecondRank,
(SELECT COUNT(1)
   FROM playergroupranking PGR
  WHERE PGR.Rank=3
    AND PGR.PlayerKey = players.PrimaryKey
	AND PGR.GroupKey IN (SELECT PrimaryKey FROM groups WHERE IsCompleted=1 AND groups.CompetitionKey=" . COMPETITION . ")
	AND PGR.RankDate = (SELECT MAX(playergroupranking.RankDate) FROM playergroupranking WHERE playergroupranking.PlayerKey=PGR.PlayerKey AND playergroupranking.GroupKey = PGR.GroupKey)) ThirdRank,
(SELECT COUNT(1) FROM forecasts WHERE forecasts.PlayerKey = players.PrimaryKey
AND forecasts.MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . ")) MatchPlayed,
(SELECT COUNT(1) FROM playermatchresults PMR
  WHERE PMR.PlayerKey = players.PrimaryKey
    AND PMR.MatchKey IN (SELECT results.MatchKey
                           FROM results
                          INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
                                AND matches.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.IsCompleted=1 AND groups.CompetitionKey=" . COMPETITION . ")
                          WHERE results.LiveStatus=10)
    AND PMR.Score>=5) MatchGood,
(SELECT COUNT(1) FROM playermatchresults PMR
  WHERE PMR.IsPerfect=1
    AND PMR.PlayerKey = players.PrimaryKey
    AND PMR.MatchKey IN (SELECT results.MatchKey
                           FROM results
                          INNER JOIN matches ON matches.PrimaryKey=results.MatchKey
                                AND matches.GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.IsCompleted=1 AND groups.CompetitionKey=" . COMPETITION . ")
                          WHERE results.LiveStatus=10)) MatchPerfect
FROM playersenabled players
WHERE players.PrimaryKey=" . $_authorisation->getConnectedUserKey();

	$resultSet = $_databaseObject->queryPerf($sql,"Get bonus and player score");

	$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
	$playerScore=$rowSet["Score"];
	$playerRank=$rowSet["Rank"];
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

?>

<div id="PlayerRanking" style="text-align: center; font-weight: bold; padding-top: 25px;"><?php echo __encode('Votre position actuelle : ')?><b><?php echo $playerRank; ?></b><span class="var <?php echo $variation; ?>">&nbsp;</span></div>
<div id="mod-classements" class="node2" style="padding-top: 15px;">
<div class="head">
<div>
<h4><?php echo __encode("Classement Général"); ?></h4>
</div>
</div>
<div class="node-in">
<div class="panel">
<ol>


<?php

$sql = "SELECT
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 0,1) Rank,
(SELECT PRK.Rank FROM playerranking PRK WHERE players.PrimaryKey=PRK.PlayerKey  ORDER BY RankDate desc LIMIT 1,1) PreviousRank,
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


  echo '<p><a class="popupscroll" href="#">'. $rowSet["NickName"] .'<em>'. $rowSet["Score"] . ' pts</em><span class="var ' . $variation . '"></span></a></p>';
  echo '</li>';


}
?>
</ol>

</div>
<a href="index.php?Page=3">Voir tout le classement</a></div>
</div>
</div>
</div>
