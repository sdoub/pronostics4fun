<?php

AddScriptReference("gracket");
AddScriptReference("cluetip");
AddScriptReference("p4f.cup.draw");
WriteScripts();
$_seasonKey=5;
if (isset($_GET["SeasonKey"]))
  $_seasonKey = $_GET["SeasonKey"];

$query = "SELECT playercupmatches.PlayerHomeKey, HomePlayer.NickName HomeNickName, 
                 playercupmatches.PlayerAwayKey, AwayPlayer.NickName AwayNickName,
                 CupRoundKey, playercupmatches.HomeScore, playercupmatches.AwayScore,
								 playercupmatches.GroupKey
            FROM playercupmatches
           LEFT JOIN players HomePlayer ON HomePlayer.PrimaryKey =playercupmatches.PlayerHomeKey
           LEFT JOIN players AwayPlayer ON AwayPlayer.PrimaryKey =playercupmatches.PlayerAwayKey
           WHERE SeasonKey=$_seasonKey
             AND CupRoundKey NOT IN (7,9)
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
  } else {
    $match[1]["name"] = $rowSet["AwayNickName"];
    $match[1]["id"] = $rowSet["PlayerAwayKey"];
    $match[1]["seed"] = $rowSet["PlayerAwayKey"];
    $match[1]["displaySeed"] = "";
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
}
$rounds[]=$matches;
$nbrOfMatches = count($matches);
while ($nbrOfMatches>1) {
  $dummyMatches =  count($matches)/2;
  $matches= array();
  for ($i = 0; $i < $dummyMatches; $i++) {
    $match = array();
    $match[0]["name"] = "-";
    $match[0]["id"] = -2;
    $match[0]["seed"] = -2;
    $match[0]["displaySeed"] = "";
    $match[1]["name"] = "-";
    $match[1]["id"] = -2;
    $match[1]["seed"] = -2;
    $match[1]["displaySeed"] = "";
    $matches[] =$match;
  }
  $rounds[]=$matches;
  $nbrOfMatches = count($matches);
}

$query = "SELECT playercupmatches.PlayerHomeKey, HomePlayer.NickName HomeNickName, playercupmatches.PlayerAwayKey, AwayPlayer.NickName AwayNickName,
          CupRoundKey, playercupmatches.HomeScore, playercupmatches.AwayScore
            FROM playercupmatches
           LEFT JOIN players HomePlayer ON HomePlayer.PrimaryKey =playercupmatches.PlayerHomeKey
           LEFT JOIN players AwayPlayer ON AwayPlayer.PrimaryKey =playercupmatches.PlayerAwayKey
           WHERE SeasonKey=$_seasonKey
             AND CupRoundKey = 8
             AND playercupmatches.HomeScore IS NOT NULL
           ORDER BY playercupmatches.PrimaryKey";
$resultSet = $_databaseObject->queryPerf($query,"Get winner");
$matches = array();
$isCupCompleted = false;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $isCupCompleted = true;
  if ($rowSet["HomeScore"]>$rowSet["AwayScore"]) {
    $match = array();
    $match[0]["name"] = $rowSet["HomeNickName"];
    $match[0]["id"] = $rowSet["PlayerHomeKey"];
    $match[0]["seed"] = $rowSet["PlayerHomeKey"];
    $match[0]["displaySeed"] = "";
  } else {
    $match = array();
    $match[0]["name"] = $rowSet["AwayNickName"];
    $match[0]["id"] = $rowSet["PlayerAwayKey"];
    $match[0]["seed"] = $rowSet["PlayerAwayKey"];
    $match[0]["displaySeed"] = "";
  }
  $matches[] =$match;
  $rounds[]=$matches;

}
if (!$isCupCompleted ) {
  // create winner game
  $matches= array();
  $match = array();
  $match[0]["name"] = "-";
  $match[0]["id"] = -2;
  $match[0]["seed"] = -2;
  $match[0]["displaySeed"] = "";
  $matches[] =$match;
  $rounds[]=$matches;
}

$query = "SELECT playercupmatches.PlayerHomeKey, HomePlayer.NickName HomeNickName, playercupmatches.PlayerAwayKey, AwayPlayer.NickName AwayNickName,
          CupRoundKey, playercupmatches.HomeScore, playercupmatches.AwayScore
            FROM playercupmatches
           LEFT JOIN players HomePlayer ON HomePlayer.PrimaryKey =playercupmatches.PlayerHomeKey
           LEFT JOIN players AwayPlayer ON AwayPlayer.PrimaryKey =playercupmatches.PlayerAwayKey
           WHERE SeasonKey=$_seasonKey
             AND CupRoundKey IN (7,9)
           ORDER BY playercupmatches.PrimaryKey";
$resultSet = $_databaseObject->queryPerf($query,"Get round");
$rounds3rdPlace = array();
$matches = array();
$roundKey = 0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  if ($roundKey==0)
    $roundKey = $rowSet["CupRoundKey"];

  if ($roundKey!=$rowSet["CupRoundKey"]) {
    $rounds3rdPlace[]=$matches;
    $matches = array();
    $roundKey = $rowSet["CupRoundKey"];
  }
  $match = array();
  $match[0]["name"] = $rowSet["HomeNickName"];
  $match[0]["id"] = $rowSet["PlayerHomeKey"];
  $match[0]["seed"] = $rowSet["PlayerHomeKey"];
  $match[0]["displaySeed"] = "";
  if ($rowSet["HomeScore"])
  {
    $match[0]["score"] = $rowSet["HomeScore"];
    if ($rowSet["HomeScore"]>$rowSet["AwayScore"])
      $match[0]["displaySeed"] = "<img src='images/trophy.gold.png' style='width:10px;height:10px;'/>";
  }

  if ($rowSet["PlayerAwayKey"] == -1) {
    $match[0]["displaySeed"] = "<img src='images/trophy.gold.png' style='width:10px;height:10px;'/>";
    if ($rowSet["HomeScore"]==0)
      $match[0]["displaySeed"] = "<span title='disqualifié pour non participation'>*</span>";
    $match[1]["name"] = "-";
    $match[1]["id"] = $rowSet["PlayerAwayKey"];
    $match[1]["seed"] = $rowSet["PlayerAwayKey"];
    $match[1]["displaySeed"] = "";
  } else {
    $match[1]["name"] = $rowSet["AwayNickName"];
    $match[1]["id"] = $rowSet["PlayerAwayKey"];
    $match[1]["seed"] = $rowSet["PlayerAwayKey"];
    $match[1]["displaySeed"] = "";
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
}
$rounds3rdPlace[]=$matches;
$nbrOfMatches = count($matches);
while ($nbrOfMatches>1) {
  $dummyMatches =  count($matches)/2;
  $matches= array();
  for ($i = 0; $i < $dummyMatches; $i++) {
    $match = array();
    $match[0]["name"] = "-";
    $match[0]["id"] = -2;
    $match[0]["seed"] = -2;
    $match[0]["displaySeed"] = "";
    $match[1]["name"] = "-";
    $match[1]["id"] = -2;
    $match[1]["seed"] = -2;
    $match[1]["displaySeed"] = "";
    $matches[] =$match;
  }
  $rounds[]=$matches;
  $nbrOfMatches = count($matches);
}


$query = "SELECT playercupmatches.PlayerHomeKey, HomePlayer.NickName HomeNickName, playercupmatches.PlayerAwayKey, AwayPlayer.NickName AwayNickName,
          CupRoundKey, playercupmatches.HomeScore, playercupmatches.AwayScore
            FROM playercupmatches
           LEFT JOIN players HomePlayer ON HomePlayer.PrimaryKey =playercupmatches.PlayerHomeKey
           LEFT JOIN players AwayPlayer ON AwayPlayer.PrimaryKey =playercupmatches.PlayerAwayKey
           WHERE SeasonKey=$_seasonKey
             AND CupRoundKey = 7
             AND playercupmatches.HomeScore IS NOT NULL
           ORDER BY playercupmatches.PrimaryKey";
$resultSet = $_databaseObject->queryPerf($query,"Get 3rd place");
$matches= array();
$match = array();
$isCupCompleted = false;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $isCupCompleted = true;
  if ($rowSet["HomeScore"]>$rowSet["AwayScore"]) {
    $match = array();
    $match[0]["name"] = $rowSet["HomeNickName"];
    $match[0]["id"] = $rowSet["PlayerHomeKey"];
    $match[0]["seed"] = $rowSet["PlayerHomeKey"];
    $match[0]["displaySeed"] = "";
  } else {
    $match = array();
    $match[0]["name"] = $rowSet["AwayNickName"];
    $match[0]["id"] = $rowSet["PlayerAwayKey"];
    $match[0]["seed"] = $rowSet["PlayerAwayKey"];
    $match[0]["displaySeed"] = "";
  }
  $matches[] =$match;
  $rounds3rdPlace[]=$matches;

}
if (!$isCupCompleted) {
// create 3rdPlace game
  $matches= array();
  $match = array();
  $match[0]["name"] = "-";
  $match[0]["id"] = -2;
  $match[0]["seed"] = -2;
  $match[0]["displaySeed"] = "";
  $matches[] =$match;
  $rounds3rdPlace[]=$matches;
}

echo '<div id="" style="color: #fff;font-size: xx-small;padding-bottom: 10px;">* : disqualifié au premier tour pour non participation</div>';
echo '<div id="" style="width:940px;overflow:hidden;"><div id="cupDraw" ></div><div id="cupDraw3rd" ></div></div>';
?>

  <style type="text/css">
    .g_gracket { width: 9999px; background-color: transparent; padding: 35px 15px 5px; line-height: 100%; position: relative; overflow: hidden;}
    .g_round { float: left; margin-right: 10px; }
    .g_game { position: relative; margin-bottom: 5px; }
    .g_gracket h3 {margin:0;padding: 5px 4px 4px;font-size:11px;font-weight:normal;color:#fff}
    .g_gracket h3 small{ background:#D7E1F6;float:right;position:absolute;right:3px;padding-left:2px;padding-right:2px;color:#365F89}
    .g_team { width:110px; background: #6D8AA8; }
    .g_team:last-child {  background: #6091c3; }
    .g_round:last-child { margin-right: 20px; }
    .g_winner { background: #FFF; }
    .g_winner .g_team { background: #000; }
    .g_current { cursor: pointer; background: #365F89!important; }
    .g_round_label { top: 0px; font-weight: normal; color: #FFFFFF; text-align: center; font-size: 12px; padding-left:28px; }
    #cupDraw3rd {bottom: 1400px;left: 751px;position: absolute;width: 350px;}
		/* #cupDraw3rd {bottom: 579px;left: 631px;position: absolute;width: 350px;} */
  </style>
<script type="text/javascript">
(function(win, doc, $){

      win.TestData = <?php echo json_encode($rounds);?>;
      // initializer
      $("#cupDraw").gracket(      {
        src : win.TestData,
        canvasLineWidth : 1,      // adjusts the thickness of a line
        canvasLineGap : 5,        // adjusts the gap between element and line
        cornerRadius : 3,         // adjusts edges of line
        canvasLineCap : "round",  // or "square"
        canvasLineColor : "white",
<?php if($_seasonKey==6) {?>
        roundLabels :["1er tour<br/><small> Journée 13</small>","2ème tour<br/><small> Journée 14</small>","1/16 finale<br/><small> Journée 15</small>","1/8 finale<br/><small> Journée 16</small>", "1/4 finale<br/><small> Journée 17</small>", "1/2 finale<br/><small> Journée 18</small>", "Finale<br/><small> Journée 19</small>", "Vainqueur"]
<?php } else if ($_seasonKey==7) {?>
roundLabels :["1er tour<br/><small> Journée 22</small>","2ème tour<br/><small> Journée 23</small>","1/16 finale<br/><small> Journée 24</small>","1/8 finale<br/><small> Journée 25</small>", "1/4 finale<br/><small> Journée 26</small>", "1/2 finale<br/><small> Journée 27</small>", "Finale<br/><small> Journée 28</small>", "Vainqueur"]
<?php } else if ($_seasonKey==8) {?>
roundLabels :["1er tour<br/><small> Journée 30</small>","2ème tour<br/><small> Journée 31</small>","1/16 finale<br/><small> Journée 32</small>","1/8 finale<br/><small> Journée 33</small>", "1/4 finale<br/><small> Journée 34</small>", "1/2 finale<br/><small> Journée 35</small>", "Finale<br/><small> Journée 36</small>", "Vainqueur"]
<?php } else {?>
roundLabels :["1er tour<br/><small> Journée 3</small>","2ème tour<br/><small> Journée 4</small>","1/16 finale<br/><small> Journée 5</small>","1/8 finale<br/><small> Journée 6</small>", "1/4 finale<br/><small> Journée 7</small>", "1/2 finale<br/><small> Journée 8</small>", "Finale<br/><small> Journée 9</small>", "Vainqueur"]
<?php }?>
});
	var _groupKey = <?php echo $_groupKey; ?>;		
	
	$("div.g_round").each(function( roundIndex ) {
		var currentGroupKey = _groupKey + roundIndex;
		$("div.g_game", $(this)).each(function( gameIndex ) {
			var divGame = this;
			var players = "";
			var playersScore = 0;
			$("div.g_team", $(divGame)).each(function (matchIndex){
				if (players!="")
					players += ",";	
				players += $(this).attr('class').split(' ')[1];
				$("small", $(this)).each(function (matchIndex){
					playersScore += $(this).text();
				});
			});
			if (players!="-2,-2" && playersScore>0)
				$(divGame).attr('rel','get.player.group.detail.php?Mode=P4F&GroupKey='+currentGroupKey+'&PlayerKeys='+players);
		});
	});
	$("div.g_game").cluetip(
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

	var thirdPlaceData = <?php echo json_encode($rounds3rdPlace);?>;
      // initializer
      $("#cupDraw3rd").gracket(      {
        src : thirdPlaceData,
        canvasLineWidth : 1,      // adjusts the thickness of a line
        canvasLineGap : 5,        // adjusts the gap between element and line
        cornerRadius : 3,         // adjusts edges of line
        canvasLineCap : "round",  // or "square"
        canvasLineColor : "white",
        <?php if($_seasonKey==6) {?>
        roundLabels :["3ème place<br/><small> Journée 18</small>", "3ème"]
<?php } else if ($_seasonKey==7) {?>
roundLabels :["3ème place<br/><small> Journée 27</small>", "3ème"]
<?php } else if ($_seasonKey==8) {?>
roundLabels :["3ème place<br/><small> Journée 36</small>", "3ème"]
<?php } else {?>
roundLabels :["3ème place<br/><small> Journée 9</small>", "3ème"]
<?php }?>

    });

})(window, document, jQuery);

</script>