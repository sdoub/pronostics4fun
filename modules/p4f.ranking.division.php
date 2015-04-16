<?php

$query = "SELECT PDR.SeasonKey, PDR.DivisionKey, seasons.Description SeasonName, divisions.Description DivisionName
            FROM playerdivisionranking PDR
           INNER JOIN divisions ON divisions.PrimaryKey=PDR.DivisionKey
           INNER JOIN seasons ON seasons.PrimaryKey=PDR.SeasonKey
           WHERE PlayerKey=".$_authorisation->getConnectedUserKey()." ORDER BY 1 DESC";

$resultSet = $_databaseObject -> queryPerf ($query, "Get group info");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);

$_seasonDescription=$rowSet["SeasonName"];
$_seasonKey= $rowSet["SeasonKey"];
$_divisionDescription=$rowSet["DivisionName"];
$_divisionKey= $rowSet["DivisionKey"];

AddScriptReference("flexigrid");
AddScriptReference("scrollpane");
AddScriptReference("dropdownchecklist");
AddScriptReference("tokeninput");
AddScriptReference("ellipsis");
AddScriptReference("cluetip");
AddScriptReference("p4f.ranking.division");
WriteScripts();

$avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';

$defaultSeason =array();
$defaultSeason["id"] = "200". $_seasonKey ;
$defaultSeason["nickname"] = $_seasonDescription ;
$defaultSeason["avatar"] = ROOT_SITE. $_themePath. '/images/season.png';

$defaultDivision=array();
$defaultDivision["id"] = "300". $_divisionKey ;
$defaultDivision["nickname"] = $_divisionDescription ;
$defaultDivision["avatar"] = ROOT_SITE. $_themePath. '/images/division.png';

$defaultPlayer =array();
$avatarName = $_authorisation->getConnectedUserInfo("AvatarName");

if (!empty($avatarName))
  $avatarPath= ROOT_SITE. '/images/avatars/'.$avatarName;

$defaultPlayer["id"] = "100". $_authorisation->getConnectedUserKey() ;
$defaultPlayer["nickname"] = $_authorisation->getConnectedUser() ;
$defaultPlayer["avatar"] = $avatarPath;

$defaultFilterItems = array();
$defaultFilterItems[] = $defaultSeason;
$defaultFilterItems[] = $defaultDivision;
$defaultFilterItems[] = $defaultPlayer;

$prepopulatedItems = json_encode($defaultFilterItems);
if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1)
{
	$currentTime = time();

	$query= "SELECT groups.PrimaryKey GroupKey, groups.Code GroupCode
					 FROM groups
					WHERE groups.CompetitionKey=" . COMPETITION . "
						AND $currentTime >= (UNIX_TIMESTAMP(groups.EndDate))
						ORDER BY groups.EndDate DESC";

	$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get last completed group");
	$groupKey = $rowsSet[0]["GroupKey"];
$calculateButton = '<div style="height:55px;"><div style="float:left;margin-left:327px;">
<input type="submit"
	value="Calculer les points de la" data-group-key="'.$rowsSet[0]["GroupKey"].'" class="buttonfield" id="btnCalculate" name="btnCalculate">
	<div class="divHeightSpace" >&nbsp;</div></div>';
	$groupList = "<div style='float:left;margin-top:12px;'>
<select id='ValueChoice' style='z-index: 999; display: none;'>";

  $sql = "SELECT groups.PrimaryKey GroupKey, groups.Code GroupName, groups.Description FROM groups WHERE CompetitionKey=" . COMPETITION . " AND (IsCompleted='1' OR EXISTS (SELECT 1 FROM matches INNER JOIN results ON matches.PrimaryKey=results.MatchKey WHERE matches.GroupKey=groups.PrimaryKey)) ORDER BY PrimaryKey";
  $resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");


  $addComma = false;
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
    $selected = "";
    if ($rowSet["GroupKey"]==$groupKey)
      $selected ="selected='selected'";


    $groupList .= "<option ".$selected." value='" . $rowSet["GroupKey"] . "'>". $rowSet["Description"].'</option>';
  }

$groupList .= '</select></div></div>';

	echo $calculateButton;
	echo $groupList;
}
$seasonList = "<div id='ContainerGroup' >
<label style='font-weight: bold;color: #FFFFFF;'>Saison : </label><select id='SeasonChoice' style='z-index: 999; display: none;'>";

  $sql = "SELECT seasons.PrimaryKey SeasonKey, seasons.Code SeasonName, seasons.Description FROM seasons WHERE seasons.CompetitionKey=" . COMPETITION . "
  AND (EXISTS (SELECT 1 FROM playerdivisionranking WHERE playerdivisionranking.SeasonKey=seasons.PrimaryKey)) ORDER BY PrimaryKey";
  $resultSet = $_databaseObject->queryPerf($sql,"Get Seasons");
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
    $selected = "";
    if ($rowSet["SeasonKey"]==$_seasonKey)
      $selected ="selected='selected'";


    $seasonList .= "<option ".$selected." value='" . $rowSet["SeasonKey"] . "'>". $rowSet["Description"].'</option>';
  }

$seasonList .= '</select>';

$seasonList .= "<label style='padding-left:25px;font-weight: bold;color: #FFFFFF;'>Division : </label><select id='DivisionChoice' style='z-index: 999; display: none;'>";

  $sql = "SELECT divisions.PrimaryKey DivisionKey, divisions.Code DivisionName, divisions.Description FROM divisions ORDER BY divisions.Order";
  $resultSet = $_databaseObject->queryPerf($sql,"Get Divisions");
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
    $selected = "";
    if ($rowSet["DivisionKey"]==$_divisionKey)
      $selected ="selected='selected'";


    $seasonList .= "<option ".$selected." value='" . $rowSet["DivisionKey"] . "'>". $rowSet["Description"].'</option>';
  }

$seasonList .= '</select>';

$seasonList .= '</div>';
echo $seasonList;
if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1)
	$matchesTop = 63;
else
	$matchesTop = 63;
echo '<div id="matches" style="float:left;position:absolute;right:42px;top:'. $matchesTop.'px;color:#FFFFFF;font-weight:bold;">';

echo '<input type="text" id="filter-matches" name="filter-matches" />';

$sql = "SELECT PDM.PrimaryKey, PDM.PlayerHomeKey, HomePlayer.NickName HomeNickName, PDM.PlayerAwayKey, AwayPlayer.NickName AwayNickName
  FROM playerdivisionmatches PDM
  INNER JOIN players HomePlayer ON HomePlayer.PrimaryKey=PDM.PlayerHomeKey
  INNER JOIN players AwayPlayer ON AwayPlayer.PrimaryKey=PDM.PlayerAwayKey
  WHERE PDM.DivisionKey=$_divisionKey AND PDM.SeasonKey=$_seasonKey
  AND  (PDM.PlayerHomeKey=".$_authorisation->getConnectedUserKey()." OR PDM.PlayerAwayKey=".$_authorisation->getConnectedUserKey().")
  ORDER BY PDM.ScheduleDate";
  $resultSet = $_databaseObject->queryPerf($sql,"Get Division matches");
  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
  {
//    echo '<span>' . $rowSet["HomeNickName"] . '</span> Vs. <span>'.$rowSet["AwayNickName"] . '</span><br/>';
  }

echo '<table id="flexGridMatches" style="display:none"></table>';

echo '</div>';
echo '<table id="flexGridRanking" style="display:none"></table>';

echo '<script type="text/javascript">

  var columnSize = 202;
  if($.browser.msie)
    columnSize = 182;

  $(document).ready(function() {
  $("#flexGridRanking").flexigrid
    (
      {
      url: "get.ranking.division.php?SeasonKey=' . $_seasonKey . '&DivisionKey='.$_divisionKey.'",
      dataType: "json",
      colGroupModel : [
        {display: "&nbsp;", name : "Rank", width : 60, align: "left"},
        {display: "&nbsp;", name : "NickName", width : 200, align: "left"},
        {display: "Score", name : "Score", width : columnSize, align: "center"}
        ],
      colModel : [
        {display: "Rang", name : "Rank", width : 60, sortable : true, align: "left"},
        {display: "Joueur", name : "NickName", width : 200, sortable : true, align: "left"},
        {display: "Points", name : "Score", width : 38, sortable : true, align: "right"},
        {display: "V.", name : "Bonus", width : 30, sortable : true, align: "right"},
        {display: "N.", name : "Bonus", width : 30, sortable : true, align: "right"},
        {display: "D.", name : "MatchPlayed", width : 30, sortable : true, align: "right"},
        {display: "Diff", name : "MatchGood", width : 30, sortable : true, align: "right"}
        ],
      searchitems : [
        {display: "Joueur", name : "NickName"}
        ],
      sortname: "Rank",
      sortorder: "asc",
      usepager: false,
      title: " ",
      useRp: true,
      rp: 40,
      striped:false,
      resizable: false,
      showTableToggleBtn: false,
      width: 500,
      height: 415,
      onSuccess:flexGridRankingOnSuccess,
      pagestat: "Affichage de {from} à {to} sur {total} joueurs",
      singleSelect:true,
      pagetext: "Page",
      outof: "sur",
       findtext: "Rechercher",
       procmsg: "Traitement, patientez ...",
       nomsg: "Pas de données"
      }
    );
  $("#flexGridMatches").flexigrid
    (
      {
      url: "get.p4f.matches.php?SpecialFilter=100'.$_authorisation->getConnectedUserKey().',300'.$_divisionKey.',200'.$_seasonKey.'",
      dataType: "json",
      colGroupModel : [
        {display: "&nbsp;", name : "Context", width : 82, align: "left"},
        {display: "Domicile", name : "Home", width : 131, align: "center"},
        {display: "Extérieur", name : "Away", width : 131, align: "center"}
        ],
      colModel : [
        {display: "S.", name : "SeasonCode", width : 20, sortable : true, align: "center"},
        {display: "D.", name : "DivisionCode", width : 20, sortable : true, align: "center"},
        {display: "J.", name : "DayKey", width : 20, sortable : true, align: "center"},
        {display: "Joueur", name : "HomeNickName", width : 100, sortable : true, align: "right"},
        {display: "Pts", name : "HomeScore", width : 20, sortable : true, align: "left"},
        {display: "Pts", name : "AwayScore", width : 20, sortable : true, align: "right"},
        {display: "Joueur", name : "AwayNickName", width : 100, sortable : true, align: "left"}
        ],
      searchitems : [
        {display: "Joueur", name : "DayKey"}
        ],
      sortname: "DayKey",
      sortorder: "asc",
      usepager: false,
      title: " ",
      useRp: true,
      rp: 300,
      striped:false,
      resizable: false,
      showTableToggleBtn: false,
      width: 400,
      height: 415,
      onSuccess:flexGridMatchesOnSuccess,
      pagestat: "Affichage de {from} à {to} sur {total} joueurs",
      singleSelect:true,
       pagetext: "Page",
       outof: "sur",
       findtext: "Rechercher",
       procmsg: "Traitement, patientez ...",
       nomsg: "Pas de données"
      }
    );

    });
</script>';

?>

<script type="text/javascript">
function flexGridRankingOnSuccess () {
  var handler = function() {
    _manualRefresh = true;
    var playerKey = this.id.substring(3);
    var nickName = $(this).find('td:eq(1)').text();
    var filterPlayer = new Object();
    filterPlayer.id = 100+playerKey;
    filterPlayer.nickname = nickName;
    var filterDivision = new Object();
    filterDivision.id = 300+_divisionKey;
    filterDivision.nickname = _divisionDescription;
    var filterSeason = new Object();
    filterSeason.id = 200+_seasonKey;
    filterSeason.nickname = _seasonDescription;
    $("#filter-matches").tokenInput("clear");
    $("#filter-matches").tokenInput("add",filterSeason);
    $("#filter-matches").tokenInput("add",filterDivision);
    $("#filter-matches").tokenInput("add",filterPlayer);
    $("#flexGridMatches").flexOptions({url : 'get.p4f.matches.php?SpecialFilter=' + 100+playerKey + ',' +300+_divisionKey+','+ 200+_seasonKey});
    $("#flexGridMatches").flexReload();
    _manualRefresh = false;
  };

  $('tbody tr',$("#flexGridRanking")).unbind ('click',handler);
  $('tbody tr',$("#flexGridRanking")).bind ('click', handler);
  refreshScrollBar();
}

function flexGridMatchesOnSuccess () {
	var handler = function () {
	hs.htmlExpand(null, {
		pageOrigin: {
			x: this.pageX,
			y: this.pageY
		},
		headingText: "test",

		objectType: 'ajax',
		src: "get.group.day.php?RankDate="+this.x+"&NickName="+this.series.name+"&FullDay="+fullDay,
		captionText: this.series.name,
		width: 400
	});
	};
	$('tbody tr',$("#flexGridMatches")).unbind ('click',handler);
	$('tbody tr',$("#flexGridMatches")).bind ('click', handler);
	$('tbody tr',$("#flexGridMatches")).cluetip(
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

	refreshScrollBar();
}
function refreshScrollBar() {
  $("div.bDiv").jScrollPane({
    showArrows: true,
    horizontalGutter: 10
  });
  $("span.ellipsis").ellipsis();

}

var _seasonKey = '<?php echo $_seasonKey; ?>';
var _seasonDescription = '<?php echo $_seasonDescription; ?>';
var _divisionKey = '<?php echo $_divisionKey; ?>';
var _divisionDescription = '<?php echo $_divisionDescription; ?>';
var _manualRefresh = false;

$(document).ready(function() {
<?php if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1)
{
?>	
	$("#btnCalculate").click(function() {
			var groupKey = $(this).attr('data-group-key');
			$.ajax({
				type: "POST",
				url: "calculate.p4f.championship.php?GroupKey="+groupKey,
				data: { },
				dataType: 'json',
				success: function (data){
					window.location.reload();
					},
				error: callbackPostError
			});
	});
	$("#ValueChoice").dropdownchecklist({icon: {}, width: 180,maxDropHeight: 250, closeRadioOnClick:true,
		onComplete: function(selector){
			var groupName = "";
			var groupKey = "";
			for( i=0; i < selector.options.length; i++ ) {
				if (selector.options[i].selected && (selector.options[i].value != "")) {
					groupKey = selector.options[i].value;
					groupName = $(selector.options[i]).html();
				}
      }
		   $("#btnCalculate").attr('data-group-key', groupKey );
		} 
	});
<?php } ?>
  $("#SeasonChoice").dropdownchecklist({zIndex:100,icon: {}, width: 180,maxDropHeight: 250, closeRadioOnClick:true,
    onComplete: function(selector){
    var seasonName = "";
    var seasonKey = "";
    for( i=0; i < selector.options.length; i++ ) {
            if (selector.options[i].selected && (selector.options[i].value != "")) {
              seasonKey = selector.options[i].value;
              seasonName = $(selector.options[i]).html();
              //groupName = selector.options[i].text();
            }
        }
    _seasonKey = seasonKey;
    _seasonDescription = seasonName;
    $("#flexGridRanking").flexOptions({url : 'get.ranking.division.php?SeasonKey='+seasonKey+'&DivisionKey=' + _divisionKey });
    $("#flexGridRanking").flexReload();

   }
  });

  $("#DivisionChoice").dropdownchecklist({zIndex:100,icon: {}, width: 180,maxDropHeight: 250, closeRadioOnClick:true,
    onComplete: function(selector){
    var divisionName = "";
    var divisionKey = "";
    for( i=0; i < selector.options.length; i++ ) {
            if (selector.options[i].selected && (selector.options[i].value != "")) {
              divisionKey = selector.options[i].value;
              divisionName = $(selector.options[i]).html();
            }
        }
    _divisionKey = divisionKey;
    _divisionDescription = divisionName;
    $("#flexGridRanking").flexOptions({url : 'get.ranking.division.php?SeasonKey='+_seasonKey+'&DivisionKey=' + divisionKey });
    $("#flexGridRanking").flexReload();
    }
   });
    $("#filter-matches").tokenInput("get.p4f.championship.filters.php", {
      theme: "p4f",
      resultsFormatter: function(item){ return "<li>" + "<img src='" + item.avatar + "' title='" + item.nickname + "' height='25px' width='25px' />" + "<div style='display: inline-block; padding-left: 10px;'><div class='nickname'>" + item.nickname + "</div></div></li>" },
      //tokenFormatter: function(item) { return "<li>" + "<img src='" + item.avatar + "' title='" + item.nickname + "' height='15px' width='15px' />" + "<p>" + item.nickname + "</p></li>" },
      propertyToSearch: "nickname",
      prePopulate: <?php echo $prepopulatedItems;?>,
      preventDuplicates: true,
      hintText: "Choisissez les joueurs ou/et Divisions ou/et Saisons",
      noResultsText: "Pas de résultat",
      searchingText: "patientez...",
      onAdd: function (item) {
        //$("#filter-matches").tokenInput("remove", {nickname: item.nickname});
        RefreshMatches();
      },
      onDelete: function (item) {
        RefreshMatches();
      }
    });


});

function RefreshMatches () {
  if (!_manualRefresh){
    var selectedKeys = $("#filter-matches").tokenInput("get")
    var keys = [];
    $.each(selectedKeys, function(key, value) { keys.push(value.id) });
    $("#flexGridMatches").flexOptions({url : 'get.p4f.matches.php?SpecialFilter=' + keys.join(",") });
    $("#flexGridMatches").flexReload();
		
  }
}
	function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
	{
		$.log(XMLHttpRequest);
		$.log(textStatus);
		$.log(errorThrown);
	}
</script>
