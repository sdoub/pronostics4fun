<?php
require_once("begin.file.php");
//require_once('lib/envolve_api_client.php');

if (isset($_GET["Page"]))
{
$_currentPage=$_GET["Page"];
}
else
{
$_currentPage="0";
}

//$_browserInfo=getBrowser();
//$yourbrowser= "Your browser: " . $_browserInfo['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
define('IEBROWSER',"Internet Explorer");
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="robots" content="index,follow"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="gwt:property" content="locale=fr">
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--meta name="viewport" content="width=device-width,user-scalable=no"-->
<title>
<?php
if ($_SERVER['SERVER_NAME']=="beta.pronostics4fun.com") {
?>
Beta -
<?php }?>

<?php
if ($_SERVER['SERVER_NAME']=="preview.lcydfkcwzq3bx1orp5bkx9czikzc9pb9ldxe5deii3r9hpvi.box.codeanywhere.com") {
?>
Dev -
<?php }?>
Pronostics4fun</title>

<meta name="description" content="Pronostics4Fun vous propose de vous mesurer entre passionnés de football. Pour participer, il vous suffit de vous inscrire (Inscription en haut de la page), et de pronostiquer chacune des journ�es de la ligue 1.
Des classements et des statistiques sont établis à la fin de chaque journée de championnat. (Classement général, Classement par journée, ...).">
<meta name="keywords" content="p4f, pronostics 4 fun, pronostics4fun, pronostic, pronostics, pronostic football, pronostic foot, pronostics foot, pronostics football, ligue 1, pronostique, prono foot, pronostic ligue 1, pronostic foot france, prono, prono foot, prono ligue 1">
<link rel="icon" href="<?php echo ROOT_SITE; ?>/favico.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo ROOT_SITE; ?>/favico.ico" type="image/x-icon" />
<link href="<?php echo ROOT_SITE.$_themePath; ?>/css/default.css?ver=1.3" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery-1.7.min.js"></script>
<!--<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery-1.8.3.min.js"></script>-->
<!--  <script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery-migrate-1.2.1.min.js"></script> -->
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.jmpopups.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.log.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.text-overflow.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.cookies.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.requireScript.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.json-2.4.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE.$_themePath ; ?>/js/jquery-ui.custom.min.js"></script>
<link rel='stylesheet' type='text/css' href='<?php echo ROOT_SITE.$_themePath ;?>/css/custom-theme/jquery-ui.custom.css' />
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.youtubepopup.min.js"></script>

<?php
if ($_SERVER['SERVER_NAME']=="beta.pronostics4fun.com") {
?>
<style>
#header h1 a span {
	background: url(<?php echo ROOT_SITE; ?>/images/logobeta.png) no-repeat scroll left top transparent;}
</style>
<?php }?>

<?php
if ($_SERVER['SERVER_NAME']=="preview.lcydfkcwzq3bx1orp5bkx9czikzc9pb9ldxe5deii3r9hpvi.box.codeanywhere.com") {
?>
<style>
#header h1 a span {
	background: url(<?php echo ROOT_SITE; ?>/images/logodev.png) no-repeat scroll left top transparent;}
</style>
<?php }?>


</head>
<body>
<?php if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1)
{
?>
	<div style="position:absolute;right:10px;top:5px;z-index:999">
<?php
	$phpMyAdminUrl = "https://phpmyadmin.ovh.net/";
	$phpMyAdminTitle = "mysql51-39.perso / pronostilxp4f";
 if ($_SERVER['SERVER_NAME']=="preview.lcydfkcwzq3bx1orp5bkx9czikzc9pb9ldxe5deii3r9hpvi.box.codeanywhere.com") {
	 $phpMyAdminUrl= "http://preview.lcydfkcwzq3bx1orp5bkx9czikzc9pb9ldxe5deii3r9hpvi.box.codeanywhere.com/phpmyadmin";
	 $phpMyAdminTitle = "root";
 }
?>
		<a href="/index.php?Page=7" title=""><img style="width:128px;height:32px;" src="/images/p4f.admin.png"/></a>	
		<a href="<?php echo $phpMyAdminUrl; ?>" title="<?php echo $phpMyAdminTitle; ?>" target="_blank"><img style="width:100px;height:20px;" src="/images/PhpMyAdmin_logo.png"/></a>	


	</div>
	<?php }	?>
	<div id="mainwrapper">
	<div id="top">
<?php

if ($_isAuthenticated )
{
  $queryRanking = "SELECT Rank FROM playerranking WHERE PlayerKey=" . $_authorisation->getConnectedUserKey() . " AND CompetitionKey=". COMPETITION . " ORDER BY RankDate DESC LIMIT 0,1";
  $playerRanking = $_databaseObject -> queryGetFullArray($queryRanking, "Get Ranking");

  $queryScore = "SELECT SUM(playermatchresults.score) Score
                   FROM playermatchresults
                  INNER JOIN matches ON matches.PrimaryKey=playermatchresults.MatchKey AND playermatchresults.PlayerKey=" . $_authorisation->getConnectedUserKey() . "
                  INNER JOIN results ON results.MatchKey=matches.PrimaryKey AND LiveStatus=10
                  INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=". COMPETITION;
  $playerScore = $_databaseObject -> queryGetFullArray($queryScore, "Get Player Score");

  $queryBonusScore = "SELECT SUM(playergroupresults.score) BonusScore
                        FROM playergroupresults
                       INNER JOIN groups ON groups.PrimaryKey=playergroupresults.GroupKey
                         AND groups.CompetitionKey=". COMPETITION ."
                         WHERE playergroupresults.PlayerKey=" . $_authorisation->getConnectedUserKey();
  $playerBonusScore = $_databaseObject -> queryGetFullArray($queryBonusScore, "Get Player Bonus Score");

  $queryDivisionRanking = "SELECT DivisionKey, Rank 
	                           FROM playerdivisionranking
	                          INNER JOIN seasons ON seasons.PrimaryKey=playerdivisionranking.SeasonKey 
														       AND seasons.CompetitionKey=".COMPETITION." 
														WHERE PlayerKey=" . $_authorisation->getConnectedUserKey() . "
													  ORDER BY RankDate DESC LIMIT 0,1";
  $playerDivisionRanking = $_databaseObject -> queryGetFullArray($queryDivisionRanking, "Get DivisionRanking");

  if ($playerRanking)
		$playerRank = $playerRanking[0]["Rank"];
	else
		$playerRank='';
  if ($playerRank=="")
  	$playerRank.="<sup>-</sup>";
  elseif ($playerRank==1)
    $playerRank.="<sup>er</sup>";
  else
    $playerRank.="<sup>ème</sup>";

  $playerGlobalScore = (int)$playerScore[0]["Score"] + (int)$playerBonusScore[0]["BonusScore"];
  if ($_competitionType==2)
    $playerRank.=" ($playerGlobalScore pts)";
	if ($_competitionType==1) {
		$playerDivisionRank = $playerDivisionRanking[0]["Rank"];
		if ($playerDivisionRank==0)
			$playerDivisionRank="-";
		elseif ($playerDivisionRank==1)
			$playerDivisionRank.="<sup>er</sup>";
		else
			$playerDivisionRank.="<sup>ème</sup>";

		$queryCupRounds = "SELECT cuprounds.Description 
		FROM playercupmatches 
		INNER JOIN cuprounds ON cuprounds.PrimaryKey=playercupmatches.CupRoundKey 
		INNER JOIN seasons ON seasons.PrimaryKey=playercupmatches.SeasonKey AND seasons.CompetitionKey=".COMPETITION." 
		WHERE (playercupmatches.PlayerHomeKey=" . $_authorisation->getConnectedUserKey() . " OR playercupmatches.PlayerAwayKey=" . $_authorisation->getConnectedUserKey() . ")
		ORDER BY playercupmatches.PrimaryKey DESC LIMIT 0,1";
		$playerCupRounds = $_databaseObject -> queryGetFullArray($queryCupRounds, "Get DivisionRanking");
		if ($playerCupRounds)
			$playerCupRound = $playerCupRounds[0]["Description"];
		else
			$playerCupRound='';


		$queryCurrentCupRounds = "SELECT cuprounds.Description,playercupmatches.SeasonKey, seasons.Order FROM playercupmatches INNER JOIN cuprounds ON cuprounds.PrimaryKey=playercupmatches.CupRoundKey INNER JOIN seasons ON seasons.PrimaryKey=playercupmatches.SeasonKey ORDER BY playercupmatches.PrimaryKey DESC LIMIT 0,1";
		$playerCurrentCupRounds = $_databaseObject -> queryGetFullArray($queryCurrentCupRounds, "Get DivisionRanking");
		$playerCurrentCupRound = $playerCurrentCupRounds[0]["Description"];
		$playerCurrentSeason = $playerCurrentCupRounds[0]["Order"];

		$cupStatus = $playerCupRound;
		if ($playerCupRound!=$playerCurrentCupRound)
			$cupStatus = "éliminé";
		if (!$playerCupRound) {
			$cupStatus = "-";
			$playerCurrentSeason = 0;
		}
	}
  echo '<span style="float: right;padding-right: 177px;height: 21px;"> ';
  echo '<img src="'.ROOT_SITE. '/images/podium.png" style="width:25px;height:25px;" title="Classement général"/>';
  echo '<span style="color:#ffffff; font-size:12px;padding-left:5px;padding-right:15px;">'.$playerRank.'</span>';
  if ($_competitionType==1) {
    if (count($queryDivisionRanking)>0){
      echo '<img src="'.ROOT_SITE. $_themePath .'/images/division'.$playerDivisionRanking[0]["DivisionKey"].'.png" title="Division '.$playerDivisionRanking[0]["DivisionKey"].'"/>';
      echo '<span style="color:#ffffff; font-size:12px;padding-left:5px;padding-right:15px;">'.$playerDivisionRank.'</span>';
    }
    echo '<img src="'.ROOT_SITE. $_themePath .'/images/cup.s'.$playerCurrentSeason.'.png" style="" title="Coupe Saison '.$playerCurrentSeason.'"/>';
    echo '<span style="color:#ffffff; font-size:10px;padding-left:5px;">'.$cupStatus.'</span>';
    echo '</span>';
  }
}
else
{
  echo '  <span>&nbsp;</span>';
}

        ?>

</div>
	<div id="container">
    	<div id="header">
    		<h1><a id="linkHome" href="index.php"><span>&nbsp;</span></a></h1>

<?php

if ($_isAuthenticated)
{
  $q = new PlayersQuery();
	$firstPlayer = $q->findPK($_authorisation->getConnectedUserKey());

//$defaultLogger->addWarning($firstPlayer->getIsemailvalid());

	if ($firstPlayer->getIsemailvalid())
		echo '<h2><span style="font-size:14px;padding-right: 30px;">Bienvenue  ' . $_authorisation->getConnectedUser() . ' | <a style="font-size:12px;padding-right: 120px;" href="index.php?logoff=1"> Déconnexion</a></span></h2>';
	else
		echo '<h2><span style="font-size:14px;padding-right: 30px;"><img style="width:20px;height:20px;padding-right:5px;" title="Adresse email non vérifiée ! A l\'issue de votre inscription ou bien de votre changement d\'adresse email, vous avez reçu un email pour valider celle-ci. Une adresse email validée vous permet de recevoir des notifications/informations (Alertes, Résultats, ...) de Pronostics4Fun. En cas de problème, contactez l\'administrateur de P4F (admin@pronostics4fun.com)" src="/images/warning.png"/>Bienvenue  ' . $_authorisation->getConnectedUser() . ' | <a style="font-size:12px;padding-right: 120px;" href="index.php?logoff=1"> Déconnexion</a></span></h2>';
}
else
{
  echo '<h2><span style="font-size:12px;padding-right: 30px;"><a href="javascript:void(0);" style="font-size:12px;" id="Connection">Connexion</a> | <a style="font-size:12px;padding-right: 120px;" href="javascript:void(0);" id="Register"> Inscription</a></span></h2>';
}

    		?>

    		<div id="navMenu">
              <ul id="navMenu1">
              	<li id="Home"><a href="index.php" id="nav_home">Accueil</a></li>
              	<li id="Forecasts"><a href="index.php?Page=1" >Pronostics</a></li>
<?php

  $query= "SELECT
      groups.PrimaryKey GroupKey,
      groups.Description GroupDescription
      FROM groups
      WHERE EXISTS (
      SELECT 1
        FROM matches
        INNER JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus=10
       WHERE matches.GroupKey=groups.primaryKey
         AND groups.CompetitionKey=" . COMPETITION . ")";

  $_databaseObject -> queryPerf ($query, "Get today matches");

  if ($_databaseObject -> num_rows()>0) {

?>
              	<li id="Results"><a href="index.php?Page=2" >Résultats</a></li>
              	<li id="Ranking"><a href="index.php?Page=3" >Classements</a></li>
              	<li id="Statistics"><a href="index.php?Page=6" >Statistiques</a></li>
								<li id="P4FWinners"><a href="index.php?Page=10" >Palmarès</a></li>
<?php if ($_competitionType==1) {?>
                <li id="P4FCompetitions"><a href="index.php?Page=9" >P4F - Compétitions</a></li>

<?php
  }} else {
?>
              	<li id="Results" class="disabled" title="Non disponible!"><a href="javascript:void()" >Résultats</a></li>
              	<li id="Ranking" class="disabled" title="Non disponible!"> <a href="javascript:void()" >Classements</a></li>
              	<li id="Statistics" class="disabled" title="Non disponible!"><a href="javascript:void()" >Statistiques</a></li>
								<li id="P4FWinners"><a href="index.php?Page=10" >Palmarès</a></li>
<?php if ($_competitionType==1) {?>
                <li id="P4FCompetitions" class="disabled" title="Non disponible!"><a href="javascript:void()" >P4F - Compétitions</a></li>
<?php
  }}
    $scheduleDate = time();

        $query= "SELECT
            groups.PrimaryKey GroupKey,
            groups.Description GroupDescription
            FROM groups
            WHERE EXISTS (
            SELECT 1
              FROM matches
             WHERE matches.GroupKey=groups.primaryKey
               AND DATE(matches.ScheduleDate)=DATE(FROM_UNIXTIME($scheduleDate)))
             AND groups.CompetitionKey=" . COMPETITION;

        $_databaseObject -> queryPerf ($query, "Get today matches");

        if ($_databaseObject -> num_rows()>0) {

          echo '<li id="Live"><a href="index.php?Page=4" >Les matchs du jour</a>';
          $query= "SELECT 1
          FROM groups
          INNER JOIN matches ON matches.GroupKey=groups.primaryKey
                 AND DATE(matches.ScheduleDate)=DATE(FROM_UNIXTIME($scheduleDate))
          INNER JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus BETWEEN 0 AND 9
          WHERE groups.CompetitionKey=" . COMPETITION;

          $_databaseObject -> queryPerf ($query, "Check if there is at least one match in progress");
          if ($_databaseObject -> num_rows()>0) {
            echo '<img src="' . ROOT_SITE . '/images/socballSmCLR.gif" style="height:20px;width:10px;" title="'. __encode("Un ou plusieurs matchs sont en cours") . '"/>';
          }
          echo '</li>';
        }
?>
              	<li class="navRight" id="Rules"><a href="javascript:void(0);" ><?php echo __encode("Règlements"); ?></a></li>
              	<li class="navRight" id="MyAccount"><a href="javascript:void(0);" >Mon compte</a></li>

              </ul>
              <?php
              include("submenu.loader.php");
              ?>
    		</div>
    	</div>

    	<div id="content">
        <?php

          include("module.loader.php");

        ?>
    	</div>
    	<center>
<?php
//<script type="text/javascript"><!--
//google_ad_client = "ca-pub-8033306597244488";
///* Football */
//google_ad_slot = "6782961799";
//google_ad_width = 728;
//google_ad_height = 15;
////-->
//</script>
//<script type="text/javascript"
//src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
//</script>
?>
</center>
    	<div id="footer">

    	<!-- START: FOOTERLINKS
    	<ul id="footerLinks">
    	<li id="footerLinkFirst">-->
 <?php    	if ($_isAuthenticated)
{
  //echo '<a href="javascript:void(0);" ><img style="border:0;" src="'. ROOT_SITE .'/images/email.png" /> Contact</a></li>';
}
  ?>
    	<!--</ul>
    	 END: FOOTERLINKS -->

    	</div>

	</div>
</div>

<div id="WaitingLayer" style="	"><img style="padding-right:10px;" src="<?php echo ROOT_SITE;?>/images/wait.gif"></img>Veuillez patienter pendant le chargement...</div>
</body>
<script>
$(function() {
	$.setupJMPopups({
		screenLockerBackground: "#CCCCCC",
		screenLockerOpacity: "0.7"
	});

//	$("#top").click(function() {
//			$.openPopupLayer({
//				name: "ManualForecatsPopup",
//				width: "450",
//				height: "500",
//				url: "submodule.loader.php?SubModule=10"
//			});
//	});

	// initialize scrollable
	$("#Connection").click(function() {
		<?php if ($_databaseObject -> isConnected == true) {?>
		$.openPopupLayer({
			name: "loginPopup",
			width: "302",
			height: "272",
			url: "submodule.loader.php?SubModule=1"
		});
		<?php } else { ?>
			$.openPopupLayer({
				name: "ManualForecatsPopup",
				width: "450",
				height: "500",
				url: "submodule.loader.php?SubModule=10"
			});
		 <?php }?>

	});

	$("#Register").click(function() {

		<?php if ($_databaseObject -> isConnected == true) {?>
		$.openPopupLayer({
			name: "registerPopup",
			width: "457",
			height: "382",
			url: "submodule.loader.php?SubModule=2"
		});
		<?php } else { ?>
			alert('Les inscriptions sont momentanement ferm�es.');
		 <?php }?>

	});

$("#Rules").click(function() {

		$.openPopupLayer({
			name: "rulesPopup",
			width: "825",
			height: "550",
			url: "submodule.loader.php?SubModule=4"
		});

	});
$("#MyAccount").click(function() {

	$.openPopupLayer({
		name: "accountPopup",
		width: "507",
		height: "490",
		url: "submodule.loader.php?SubModule=7"
	});

});
$("#ContactLink").click(function() {

	$.openPopupLayer({
		name: "ContactPopup",
		width: "405",
		height: "500",
		url: "submodule.loader.php?SubModule=5"
	});
});

$(".ellipsis").ellipsis();

var clientWidth = document.body.clientWidth;
var clientHeight = document.body.clientHeight;
var mainDivWidth = 1003;//$("#mainwrapper").width();
var margin = (clientWidth - mainDivWidth) / 2;

if ($.browser.msie)
{
	$("#container").css("margin","0px " + margin + "px 0px "+ margin + "px");
	$("#top").css("margin","0px " + margin + "px 0px "+ margin + "px");
	//$("body").css("margin","0px " + margin + "px 0px "+ margin + "px");
	$(window).resize(
			function () {
				$("#container").css("margin","0px " + margin + "px 0px "+ margin + "px");
				$("#top").css("margin","0px " + margin + "px 0px "+ margin + "px");

			});
}



//$.requireScript('<?php echo ROOT_SITE; ?>/js/jquery.center.js', function() {
	//$("#mainwrapper").center();
	//$("a.youtube").YouTubePopup({ idAttribute: 'youtube' });
//});

var waitingLayerWidth = $("#WaitingLayer").width();
var waitingLayerHeight = $("#WaitingLayer").height();
var topPosition = (clientHeight/2) - (waitingLayerHeight/2);
var leftPosition = (clientWidth/2) - (waitingLayerWidth/2);

$('#WaitingLayer').css("top",topPosition );
$('#WaitingLayer').css("left",leftPosition );

<?php
if (!$_isAuthenticated)
{
  print ('$("#Home").css("display","none");');
  print ('$("#MyAccount").css("display","none");');
  print ('$("#Forecasts").css("display","none");');
  print ('$("#Results").css("display","none");');
  print ('$("#Ranking").css("display","none");');
  print ('$("#Live").css("display","none");');
  print ('$("#Statistics").css("display","none");');
  print ('$("#P4FCompetitions").css("display","none");');
  print ('$("#P4FWinners").css("display","none");');
}

switch ($_currentPage)
  {
    case "1":
      print ('$("#Forecasts").addClass("activate");');
      break;
    case "2":
      print ('$("#Results").addClass("activate");');
      break;
    case "3":
      print ('$("#Ranking").addClass("activate");');
      break;
    case "4":
      print ('$("#Live").addClass("activate");');
      break;
    case "6":
      print ('$("#Statistics").addClass("activate");');
      break;
    case "9":
      print ('$("#P4FCompetitions").addClass("activate");');
      break;
    case "10":
      print ('$("#P4FWinners").addClass("activate");');
      break;
    default:
			if ($_isAuthenticated)
      	print ('$("#Home").addClass("activate");');
  }
?>
});
</script>
</html>
<?php
writeGoogleAnalytics();
//writeTrackingJS();
writePerfInfo();
$ua=getBrowser();

$yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
//print_r($yourbrowser);
require_once("end.file.php");
?>