<?php

AddScriptReference("scrollpane");
AddScriptReference("new.home.connected");

WriteScripts();
?>

<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.editinplace.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jHtmlArea-0.7.0.min.js"></script>
<link rel="Stylesheet" type="text/css" href="<?php echo ROOT_SITE.$_themePath ; ?>/css/jHtmlArea.css" />

<?php
$query = "SELECT *, UNIX_TIMESTAMP(surveys.StartDate) UnixStartDate,UNIX_TIMESTAMP(surveys.EndDate) UnixEndDate FROM surveys
WHERE StartDate<=NOW() AND EndDate>=NOW()
ORDER BY StartDate
";
$resultSet = $_databaseObject->queryPerf($query,"Get survey");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $surveyKey = $rowSet["PrimaryKey"];
  $participants = explode(",", substr($rowSet["Participants"],1));
  $isValidated = array_value_exists2($participants, $_authorisation->getConnectedUserKey());
  if (sizeof($participants)>0) {
    $score1Percentage = round(100*$rowSet["Score1"] /sizeof($participants),2);
    $score2Percentage = round(100*$rowSet["Score2"] /sizeof($participants),2);
    $score3Percentage = round(100*$rowSet["Score3"] /sizeof($participants),2);
    $score4Percentage = round(100*$rowSet["Score4"] /sizeof($participants),2);
  } else {
    $score1Percentage = 0;
    $score2Percentage = 0;
    $score3Percentage = 0;
    $score4Percentage = 0;
  }

  $startFormattedDate = strftime("%d %B %Y",$rowSet['UnixStartDate']);
  $endFormattedDate = strftime("%d %B %Y",$rowSet['UnixEndDate']);

  $divHeight=0;
  $tendancy1="tendancyYes";
  $tendancy2="tendancyNo";
  if ($rowSet["Answer3"]!="") {
    $divHeight+=15;
    $tendancy1="tendancyMultiple";
    $tendancy2="tendancyMultiple";
  }

  if ($rowSet["Answer4"]!="")
    $divHeight+=15;


?>

<div id="surveyTitle<?php echo $surveyKey;?>" name="surveyTitle<?php echo $surveyKey;?>" style="background:url('<?php echo ROOT_SITE; ?>/images/survey.small.png') no-repeat scroll left top
		#D7E1F6;height:<?php echo 160+$divHeight;?>px;margin-bottom:30px;_width:920px;">
	<div style="font-size:18px;color:#365F89;font-weight:bold;margin-left:140px;margin-top:15px;padding-top:15px;">
	<?php echo __encode("Sondage (" . $startFormattedDate . " - " .$endFormattedDate . ")");?></div>
	<div id="survey<?php echo $surveyKey;?>" class="flexcroll" style="background:#6D8AA8;height:<?php echo 100+$divHeight;?>px;overflow: auto;margin-top:15px;margin-left:15px;margin-right:15px;margin-bottom:15px;">
		<p style="padding-top:10px;color:#FFFFFF;">
		<?php echo __encode($rowSet["Question"]);
		//__encode("Comme vous le savez probablement, le championnat d'europe des nations commencera le 06 Juin 2012. Au mettre titre que le championnat de Ligue1, voulez-vous donner des pronostics sur cette compétition?");
		?></p>
		<ul>
		<li>
<table style="font-size:12px;font-weight:bold;padding-left:30px;color:#FFFFFF;"><tr><td  width="150px;">
<?php if (!$isValidated) {?>
<input type="radio" name="survey<?php echo $surveyKey;?>" id="survey<?php echo $surveyKey;?>_1" value="1"/>
<?php }?>

<label for="survey<?php echo $surveyKey;?>_1" style="padding-right:20px;" >
<?php echo $rowSet["Answer1"];
//__encode("Oui");
?></label>
</td><td width="300px;">
					<table width="200px;font-size:9px;">
			<tr id="ResultSurvey<?php echo $surveyKey;?>_1" style="<?php if (!$isValidated) {echo "display:none;";}?>">
				<td id="<?php echo $tendancy1;?>" value="1"  width="<?php echo $score1Percentage;?>%"><?php echo $score1Percentage;?>%</td>
				<td id="tendancyHidden" value="2" width="<?php echo $score2Percentage;?>%">&nbsp;</td>
<?php if ($rowSet["Answer4"]!="") {?>
				<td id="tendancyHidden" value="3" width="<?php echo $score3Percentage;?>%">&nbsp;</td>
				<td id="tendancyHidden" value="4" width="<?php echo $score4Percentage;?>%">&nbsp;</td>
<?php }?>

			</tr>
		</table>
		</td></tr></table>
</li><li>
<table style="font-size:12px;font-weight:bold;padding-left:30px;color:#FFFFFF;"><tr><td  width="150px;">
<?php if (!$isValidated) {?>
<input type="radio" name="survey<?php echo $surveyKey;?>" id="survey<?php echo $surveyKey;?>_2" value="2"/>
<?php }?>

<label for="survey<?php echo $surveyKey;?>_2" style="padding-right:20px;" >
<?php echo $rowSet["Answer2"];?></label>
</td><td width="300px;">
					<table  width="200px;font-size:9px;">
			<tr id="ResultSurvey<?php echo $surveyKey;?>_2" style="<?php if (!$isValidated) {echo "display:none;";}?>">
				<td id="<?php echo $tendancy2;?>" value="2" width="<?php echo $score2Percentage;?>%"><?php echo $score2Percentage;?>%</td>
				<td id="tendancyHidden" value="1" width="<?php echo $score1Percentage;?>%">&nbsp;</td>
<?php if ($rowSet["Answer3"]!="") {?>
				<td id="tendancyHidden" value="3" width="<?php echo $score3Percentage;?>%">&nbsp;</td>
				<td id="tendancyHidden" value="4" width="<?php echo $score4Percentage;?>%">&nbsp;</td>
<?php }?>


			</tr>
		</table>
		</td></tr></table>
		</li>
<?php if ($rowSet["Answer3"]!="") {?>
<li>
<table style="font-size:12px;font-weight:bold;padding-left:30px;color:#FFFFFF;"><tr><td  width="150px;">
<?php if (!$isValidated) {?>
<input type="radio" name="survey<?php echo $surveyKey;?>" id="survey<?php echo $surveyKey;?>_3" value="3"/>
<?php }?>

<label for="survey<?php echo $surveyKey;?>_3" style="padding-right:20px;" >
<?php echo $rowSet["Answer3"];?></label>
</td><td width="300px;">
					<table  width="200px;font-size:9px;">
			<tr id="ResultSurvey<?php echo $surveyKey;?>_3" style="<?php if (!$isValidated) {echo "display:none;";}?>">
				<td id="tendancyMultiple" value="3" width="<?php echo $score3Percentage;?>%"><?php echo $score3Percentage;?>%</td>
				<td id="tendancyHidden" value="1" width="<?php echo $score1Percentage;?>%">&nbsp;</td>
				<td id="tendancyHidden" value="2" width="<?php echo $score2Percentage;?>%">&nbsp;</td>
<?php if ($rowSet["Answer4"]!="") {?>
				<td id="tendancyHidden" value="4" width="<?php echo $score4Percentage;?>%">&nbsp;</td>
<?php }?>
			</tr>
		</table>
		</td></tr></table>
		</li>
<?php }?>
<?php if ($rowSet["Answer4"]!="") {?>

<li>
<table style="font-size:12px;font-weight:bold;padding-left:30px;color:#FFFFFF;"><tr><td  width="150px;">
<?php if (!$isValidated) {?>
<input type="radio" name="survey<?php echo $surveyKey;?>" id="survey<?php echo $surveyKey;?>_4" value="4"/>
<?php }?>

<label for="survey4" style="padding-right:20px;" >
<?php echo $rowSet["Answer4"];?></label>
</td><td width="300px;">
       <table  width="200px;font-size:9px;">
			<tr id="ResultSurvey<?php echo $surveyKey;?>_4" style="<?php if (!$isValidated) {echo "display:none;";}?>">
				<td id="tendancyMultiple"  value="4" width="<?php echo $score4Percentage;?>%"><?php echo $score4Percentage;?>%</td>
				<td id="tendancyHidden" value="1" width="<?php echo $score1Percentage;?>%">&nbsp;</td>
				<td id="tendancyHidden" value="2" width="<?php echo $score2Percentage;?>%">&nbsp;</td>
				<td id="tendancyHidden" value="3" width="<?php echo $score3Percentage;?>%">&nbsp;</td>
			</tr>
		</table>
		</td></tr></table>
		</li>
<?php }?>

		</ul>
		<div id="TotalSurvey<?php echo $surveyKey;?>" style="text-align:right;color:#FFFFFF;padding-right:10px;<?php if (!$isValidated) {echo "display:none;";}?>">Total votes : <?php echo sizeof($participants);?></div>
	</div>
</div>
<?php } ?>
<?php if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1 && 1==0) {?>
<div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'pronostics4fun'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>

<?php }?>
<div id="mod-classements2" style="">
<div id="globalRankingTitle">
<div class="node-in2" >
<div class="panel2" style="padding-top:5px;padding-left:50px;padding-right:0px;">
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
$leftPosistion = 97;

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
	
	echo '<div style="position: absolute;color: #ffffff;left: '.$leftPosistion.'px;top: 37px;font-weight: bold;font-size: 15px;text-shadow: 0px 0px 8px #6D8AA8, 0px 0px 8px #6D8AA8;">';
	if ($cnt==1)
		$leftPosistion += 174;
	else
		$leftPosistion += 177;
	echo $cnt;
	echo '<sup>';
	if ($cnt==1)
		echo 'er';
	else
		echo 'ème';
	echo '</sup>';
	echo '</div>';
  echo '<p><a class="popupscroll" href="#">'. $rowSet["FullNickName"] .'<em>'. $rowSet["Score"] . ' pts</em></a></p>';
	echo '<span class="var ' . $variation . '"></span>';
  echo '</li>';


}
?>
</ol>

</div>
</div>
</div>
</div>

<div id="newsTitle" style="width:680px;margin-right:0px;float:left;">
<div>
<?php if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1)
{
?>
<button class="buttonfield" id='AddNews'>Ajouter une news</button>
<?php } ?>
&nbsp;</div>
<div id="newsContainer">Tout ce qui se passe sur Pronostics4Fun ...</div>
<div id="news" class="flexcroll">
<ul id="newsList">
</ul>
</div>
<script>
	var options = {
		url: "save.news.php",
		bg_over: "#365F89",
		field_type: "textarea",
		textarea_rows: "8",
		textarea_cols: "85",
		saving_image: "./images/wait.loader.gif",
		use_html : true,
		show_buttons : true,
		success : function (html) {
		},
		delegate : {
				// called while opening the editor.
				// return false to prevent editor from opening
				shouldOpenEditInPlace: function(aDOMNode, aSettingsDict, triggeringEvent) {
					},
				// return content to show in inplace editor
				willOpenEditInPlace: function(aDOMNode, aSettingsDict) {
						},
				didOpenEditInPlace: function(aDOMNode, aSettingsDict) {
							$("textarea.inplace_field").htmlarea(
								{
									css: '<?php echo ROOT_SITE.$_themePath ;?>/css/jHtmlArea.Editor.css',
									toolbar :  [["html"], ["bold", "italic", "underline", "strikethrough", "|", "subscript", "superscript"],
									["increasefontsize", "decreasefontsize"],
									["orderedlist", "unorderedlist"],
									["indent", "outdent"],
									["link", "unlink", "image", "horizontalrule"],
									[{
											// This is how to add a completely custom Toolbar Button
											css: "daynews",
											text: "End Day",
											action: function(btn) {
										var html = this.toHtmlString();


										 //Paste some specific HTML / Text value into the Editor
											this.pasteHTML('<img class="news" src="images/TropheeGold.png"/>');

									}
									},
									{
											// This is how to add a completely custom Toolbar Button
											css: "rankingnews",
											text: "Ranking",
											action: function(btn) {
										 // Paste some specific HTML / Text value into the Editor
											this.pasteHTML('<img class="news" src="images/podium.png"/>');

									}
									},
									{
											// This is how to add a completely custom Toolbar Button
											css: "statsnews",
											text: "Stats",
											action: function(btn) {
										 // Paste some specific HTML / Text value into the Editor
											this.pasteHTML('<img class="news" src="images/stats.png"/>');

									}
									},
									{
											// This is how to add a completely custom Toolbar Button
											css: "bonusnews",
											text: "Bonus",
											action: function(btn) {
										 // Paste some specific HTML / Text value into the Editor
											this.pasteHTML('<img class="news" src="images/star_48.png"/>');

									}
									},
									{
											// This is how to add a completely custom Toolbar Button
											css: "p4fnews",
											text: "p4f",
											action: function(btn) {
										 // Paste some specific HTML / Text value into the Editor
											this.pasteHTML('<img class="news" src="images/p4f.update.png"/>');

									}
									},
									{
											// This is how to add a completely custom Toolbar Button
											css: "calendarnews",
											text: "Calendrier",
											action: function(btn) {
										 // Paste some specific HTML / Text value into the Editor
											this.pasteHTML('<img class="news" src="images/calendar.png"/>');

									}
									}]
									]
							}
						);
			},

			// called while closing the editor
			// return false to prevent the editor from closing
			shouldCloseEditInPlace: function(aDOMNode, aSettingsDict, triggeringEvent) {},
			// return value will be shown during saving
			willCloseEditInPlace: function(aDOMNode, aSettingsDict) {},
			didCloseEditInPlace: function(aDOMNode, aSettingsDict) {},

			missingCommaErrorPreventer:''
		}

	};

	var _newsPages = 0;
	var _totalNews = 1;
	var _isNewsLoading = false;
	function getNews () {
		if (!_isNewsLoading && _newsPages * 10 < _totalNews) {
			_isNewsLoading = true;
			$('#WaitingLayer').fadeIn();
			_newsPages++;
			$.ajax({
				type: "POST",
				url: "get.news.php?Page="+_newsPages,
				data: { Page: _newsPages},
				dataType: 'json',
				success: function (data) {
					_totalNews = data.TotalNews;
					/*<li class="news" player-key="1120">
						<div class="newsDate" news-key="1120">Le mercredi 15 avril 2015 à 21:32</div>
						<div style="background-color: transparent;" class="news" id="news.1120">
							<img class="news" src="themes/LIGUE1/images/p4f.cup.png">
								Les résultats des 16<sup>ème</sup> de finales de coupe sont disponibles ! 
								Etes-vous qualifié pour les huitièmes de finale, la réponse c'est par 
								<a href="index.php?Page=9&amp;Competition=Cup&amp;SeasonKey=8&amp;PHPSESSID=b61a1a017bb71f8f05c3fe72ac5f700f">ici</a>.
								<br>
						</div>
					</li>

					*/
					var elementToBeAdded ="";
					$.each(data.News, function(i,news) {
						elementToBeAdded += '<li class="news" player-key="'+news.id+'">';
						if (news.formattedDate)
							elementToBeAdded += '<div class="newsDate" news-key="'+news.id+'">'+news.formattedDate+'</div>';
						elementToBeAdded += '<div style="background-color: transparent;" class="news" id="news.'+news.id+'">';
						elementToBeAdded += news.information;
						elementToBeAdded += '</div></li>';
					});
					var numberOfNews = $("li",$("#newsList")).size()
					$("#newsList").append(elementToBeAdded);
					<?php 	    if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1) { ?>
					$('#newsList > li:gt('+numberOfNews+') > div.news').editInPlace(options);
					if (numberOfNews==0)
						$('#newsList > li:first > div.news').editInPlace(options);
					<?php }?>
					$('#WaitingLayer').fadeOut();
					_isNewsLoading = false;
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					$.log(XMLHttpRequest);
					$.log(textStatus);
					$.log(errorThrown);
					$('#WaitingLayer').fadeOut();
					_isNewsLoading = false;
				}
			});
		}
	}
	$(document).ready(function() {
		getNews();
	});
	</script>
</div>

<div id="resultsTitle" style="width:260px;">
<div class="container" >
	<div class="containerTitle">Dernier résultats</div>
</div>
<div class="container2 flexcroll" >
<ul>
<?php

$query= "SELECT groups.PrimaryKey GroupKey, groups.Description,UNIX_TIMESTAMP( BeginDate) unixBeginDate, UNIX_TIMESTAMP(EndDate) unixEndDate,
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
    AND forecasts.PlayerKey=".$_authorisation->getConnectedUserKey().") forecasts,
(SELECT SUM(Score) FROM playermatchresults INNER JOIN matches ON matches.PrimaryKey=playermatchresults.MatchKey WHERE playermatchresults.PlayerKey=".$_authorisation->getConnectedUserKey()." 
AND matches.GroupKey=groups.PrimaryKey)
+(SELECT SUM(Score) FROM playergroupresults WHERE playergroupresults.PlayerKey=".$_authorisation->getConnectedUserKey()."
AND playergroupresults.GroupKey=groups.PrimaryKey) Score,
(SELECT Rank FROM playergroupranking WHERE GroupKey=groups.PrimaryKey and PlayerKey=".$_authorisation->getConnectedUserKey()." ORDER BY RankDate DESC LIMIT 0,1) Rank
 FROM groups
WHERE groups.CompetitionKey=" . COMPETITION . " AND IsCompleted=1
ORDER BY groups.PrimaryKey DESC";


$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get all groups of the current competition");
if (count($rowsSet)>0) {
echo "<ul>";
foreach ($rowsSet as $rowSet)
{
  $rank = "";

	if ($rowSet["Score"]==1)
		$rank = "Rang : 1<sup>er</sup>";
	else
		$rank = "Rang : ".$rowSet["Rank"]."<sup>ème</sup>";
	
	$groupScore = $rowSet["Score"]."pts";
	$colorScore = "#B3D207";


  echo '<li group-key="'.$rowSet["GroupKey"].'" style="cursor:pointer;padding-bottom:5px;border-bottom:1px solid #ffffff">';
  echo "<div class='status'>";
  echo $rank;
  echo "</div>";

  if (strftime("%d",$rowSet['unixBeginDate']) == strftime("%d",$rowSet['unixEndDate'])){
    $groupDateFormatted = strftime("%d %B %Y",$rowSet['unixEndDate']);
    //$groupDateFormatted = " (".$groupDateFormatted.")";
  }
  else {
    if (strftime("%B",$rowSet['unixBeginDate'])==strftime("%B",$rowSet['unixEndDate'])) {
		$groupDateFormatted = strftime("%d-",$rowSet['unixBeginDate']);
	} else {
		$groupDateFormatted = strftime("%d %B-",$rowSet['unixBeginDate']);
	}
    $groupDateFormatted .= strftime("%d %B %Y",$rowSet['unixEndDate']);
    //$groupDateFormatted = " (".$groupDateFormatted.")";
  }

  if ($rowSet["unixBeginDate"]==0){
    $groupDateFormatted ="";
  }
  echo '<span style="color:#365F89;font-weight:bold;">'  . $rowSet["Description"] . '</span><br/>';
  echo '<span style="color:#365F89;font-size:9px;padding-left:20px;">' . $groupDateFormatted . '</span><br/>';
  echo '<span style="color:#365F89;padding-left:5px;">Score : </span>
  <span style="font-size:11px;color:'.$colorScore.'">'.$groupScore.'</span>';
	echo '<br/><span style="color:#365F89;padding-left:15px;font-size:10px;">' . $rowSet["players"] . __encode(" participants") . '</span>';
  echo '</li>';
}
echo "</ul>";
} else {
if ($_competitionType==3) {
  echo "<div style='font-size: 28px;font-weight:bold;text-align: center;padding-top: 60px;color:#1B3D1C;'>Aucun résultats !</div>";
} else {
  echo "<div style='font-size: 28px;font-weight:bold;text-align: center;padding-top: 60px;color:#365F89;'>Aucun résultats !</div>";
  }
}
?>

</ul>
</div>

</div>
<div id="forecastsTitle" style="width:260px;">
<div class="container" >
	<div class="containerTitle"><?php echo __encode("Pronostics à venir ...");?></div>
</div>
<div class="container2 flexcroll" >
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
ORDER BY groups.PrimaryKey";


$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get all groups of the current competition");
if (count($rowsSet)>0) {
echo "<ul>";
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


  echo '<li style="cursor:pointer;padding-bottom:5px;border-bottom:1px solid #ffffff">';
  echo "<div class='status'>";
  echo $status;
  echo "</div>";

  if (strftime("%d",$rowSet['unixBeginDate']) == strftime("%d",$rowSet['unixEndDate'])){
    $groupDateFormatted = strftime("%d %B %Y",$rowSet['unixEndDate']);
    //$groupDateFormatted = " (".$groupDateFormatted.")";
  }
  else {
    if (strftime("%B",$rowSet['unixBeginDate'])==strftime("%B",$rowSet['unixEndDate'])) {
		$groupDateFormatted = strftime("%d-",$rowSet['unixBeginDate']);
	} else {
		$groupDateFormatted = strftime("%d %B-",$rowSet['unixBeginDate']);
	}
    $groupDateFormatted .= strftime("%d %B %Y",$rowSet['unixEndDate']);
    //$groupDateFormatted = " (".$groupDateFormatted.")";
  }

  if ($rowSet["unixBeginDate"]==0){
    $groupDateFormatted ="";
  }
  echo '<span style="color:#365F89;font-weight:bold;">'  . $rowSet["Description"] . '</span><br/>';
  echo '<span style="color:#365F89;font-size:9px;padding-left:20px;">' . $groupDateFormatted . '</span><br/>';
  echo '<span style="color:#365F89;padding-left:5px;">' .  __encode("Pronostics : ") . '</span>
  <span style="font-size:11px;color:'.$colorStatus.'">'.$groupStatus.'</span>';
  if ($rowSet["Status"]>0) {
    echo '<span title="'.__encode("Match pronostiqué / Pronostics ouvert").'" style="font-size:10px;">('. $rowSet["forecasts"] . "/" . $rowSet["OpenedMatch"] . ')</span>';
  }
  if ($rowSet["forecasts"] != $rowSet["OpenedMatch"] && $rowSet["RemainingDays"]<=2 && $rowSet["unixBeginDate"]!=0) {
    echo '<span title="'. __encode("Moins de 2 jours pour donner vos pronostics!") . '" style="width:20px;height:20px;background:url(\''. ROOT_SITE . '/images/warning.small.png\') no-repeat scroll left top transparent;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
  }
  if ($rowSet["Status"]>0) {
	  echo '<br/><span style="color:#365F89;padding-left:15px;font-size:10px;">' . $rowSet["players"] . __encode(" participants") . '</span>';
  }
  echo '</li>';
}
echo "</ul>";
} else {
if ($_competitionType==3) {
  echo "<div style='font-size: 28px;font-weight:bold;text-align: center;padding-top: 60px;color:#1B3D1C;'>La competition est terminée !</div>";
} else {
  echo "<div style='font-size: 28px;font-weight:bold;text-align: center;padding-top: 60px;color:#365F89;'>La saison est terminée !</div>";
  }
}
?>

</ul>
</div>

</div>
<div >

<script>

$.requireScript('<?php echo ROOT_SITE; ?>/js/jquery.corner.js', function() {
	/*$("div[name^='surveyTitle']").corner();
	$("#newsTitle").corner();
	$("#forecastsTitle").corner();
	$("#globalRankingTitle").corner();
  */
	$("div.flexcroll").bind(
		'jsp-scroll-y',
		function(event, scrollPositionY, isAtTop, isAtBottom)
		{
			if ($(this).parent().attr('id')=='newsTitle' && isAtBottom)
				getNews();
		}
	).jScrollPane({
		showArrows: true,
		horizontalGutter: 10,
		autoReinitialise: true
	});

});

$("#forecastsTitle li").click(function() {
	window.location.replace( '<?php echo ROOT_SITE;?>/index.php?Page=1');
});

$("#resultsTitle li").click(function() {
	var groupKey = $(this).attr('group-key');
	window.location.replace( '<?php echo ROOT_SITE;?>/index.php?Page=2&GroupKey='+groupKey);
});	
	
<?php
	    if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1) {
?>
$(document).ready(function() {
	$('#AddNews').click (function () {
		$('#newsList').prepend("<li class='news' player-key='newKey'><div style='float:right;border-left:1px solid #D7E1F6;border-bottom:1px solid #D7E1F6;background: #365F89;color: #FFFFFF;font: bold 11px normal Tahoma, Verdana;'>Maintenant</div><div style='padding-top:5px;' class='news' id='news.newKey'><br/><br/></div></li>");
		$('#newsList > li:first > div.news').editInPlace(options);
	});

	$('div.news').editInPlace(options);
	$('div.newsDate').live ("mouseenter", function() {
		var newsKey = $(this).attr("news-key");
		$(this).append('<img id="DeleteNews" src="<?php echo ROOT_SITE;?>/images/delete.off.png" style="cursor:pointer;width:12px;height:12px;"/>').find('#DeleteNews').mouseenter(function() {$(this).attr('src','<?php echo ROOT_SITE;?>/images/delete.on.png');}).mouseleave(function() {$(this).attr('src','<?php echo ROOT_SITE;?>/images/delete.off.png');}).unbind('click').click(function () {
			if (confirm('Voulez vous vraiment supprimer cette news ?'))
			{
				var currentNews = $(this).parent();
				$.ajax({
					  url: "save.news.php",
					  dataType: 'json',
					  data: {NewsKey:newsKey,ToBeDeleted:true},
					  success: function (data){
						if (!data.error)
						  currentNews.slideUp();
						  },
					  error: callbackPostError
					});
			}
			});
	  }).live("mouseleave",function() {
		  $(this).find('img').remove();
	  });
	function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
	{
		$.log(XMLHttpRequest);
		$.log(textStatus);
		$.log(errorThrown);
	}

});
<?php
}
?>
$(document).ready(function() {

	$('input[name^="survey"]').click (function () {

	var selectedValue =  $(this).val();
	var surveyKey = $(this).attr('name').substring(6);
	var selectedValue =  $(this).val();
	var answer1 = selectedValue==1?1:0;
	var answer2 = selectedValue==2?1:0;
	var answer3 = selectedValue==3?1:0;
	var answer4 = selectedValue==4?1:0;


		$.ajax({
			  url: "save.survey.php",
			  dataType: 'json',
			  data: {SurveyKey:surveyKey,Answer1:answer1,Answer2:answer2,Answer3:answer3,Answer4:answer4},
			  success: function (data){
				  $.log(data);
				if (!data.error)

					$('#ResultSurvey'+surveyKey+'_1 td').each(function (){
						var idTd = $(this).attr('value').substring(-1);
						$.log("data.Score"+idTd+"Percentage");
						$.log(eval("data.Score"+idTd+"Percentage"));
						$(this).attr('width',eval("data.Score"+idTd+"Percentage")+'%');
						if (idTd==1)
							$(this).html(eval("data.Score"+idTd+"Percentage")+'%');
					});
				$('#ResultSurvey'+surveyKey+'_2 td').each(function (){
					var idTd = $(this).attr('value').substring(-1);
					$(this).attr('width',eval("data.Score"+idTd+"Percentage")+'%');
					if (idTd==2)
						$(this).html(eval("data.Score"+idTd+"Percentage")+'%');
				});
				$('#ResultSurvey'+surveyKey+'_3 td').each(function (){
					var idTd = $(this).attr('value').substring(-1);
					$(this).attr('width',eval("data.Score"+idTd+"Percentage")+'%');
					if (idTd==3)
						$(this).html(eval("data.Score"+idTd+"Percentage")+'%');
				});
				$('#ResultSurvey'+surveyKey+'_4 td').each(function (){
					var idTd = $(this).attr('value').substring(-1);
					$(this).attr('width',eval("data.Score"+idTd+"Percentage")+'%');
					if (idTd==4)
						$(this).html(eval("data.Score"+idTd+"Percentage")+'%');
				});
					$('#survey'+surveyKey+'_1').fadeOut('slow', function () {
						$('#ResultSurvey'+surveyKey+'_1').fadeIn();
						});
					$('#survey'+surveyKey+'_2').fadeOut('slow', function () {
							$('#ResultSurvey'+surveyKey+'_2').show();
						});
					$('#survey'+surveyKey+'_3').fadeOut('slow', function () {
						$('#ResultSurvey'+surveyKey+'_3').show();
					});
					$('#survey'+surveyKey+'_4').fadeOut('slow', function () {
						$('#ResultSurvey'+surveyKey+'_4').show();
					});
					$('#TotalSurvey'+surveyKey).html ('Total votes : ' + data.Participants).fadeIn();

				  },
			  error: callbackPostError
			});


	});

	function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
	{
		$.log(XMLHttpRequest);
		$.log(textStatus);
		$.log(errorThrown);
	}

	});


</script>