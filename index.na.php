<?php
require_once("begin.file.php");

$_isAuthenticated = false;

//$_browserInfo=getBrowser();
//$yourbrowser= "Your browser: " . $_browserInfo['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
define('IEBROWSER',"Internet Explorer");
?>

<html>
<head>
<meta name="robots" content="index,follow"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="gwt:property" content="locale=fr">
<title>
<?php
if ($_SERVER['SERVER_NAME']=="beta.pronostics4fun.com") {
?>
Beta -
<?php }?>

<?php
if ($_SERVER['SERVER_NAME']=="localhost") {
?>
Dev -
<?php }?>
Pronostics4fun - Ligue 1</title>

<meta name="description" content="Pronostics4Fun vous propose de vous mesurer entre passionné de football. Pour participer, il vous suffit de vous inscrire (Inscription en haut de la page), et de pronostiquer chacune des journée de la ligue 1.
Des classements et des statistiques sont éblis à la fin de chaque journée de championnat. (Classement général, Classement par journée ...).">
<meta name="keywords" content="p4f, pronostics 4 fun, pronostics4fun, pronostic, pronostics, pronostic football, pronostic foot, pronostics foot, pronostics football, ligue 1, pronostique, prono foot, pronostic ligue 1, pronostic foot france, prono, prono foot, prono ligue 1">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo ROOT_SITE; ?>/apple-touch-icon.png">
<link rel="icon" type="image/png" href="<?php echo ROOT_SITE; ?>/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo ROOT_SITE; ?>/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="<?php echo ROOT_SITE; ?>/manifest.json">
<link rel="mask-icon" href="<?php echo ROOT_SITE; ?>/safari-pinned-tab.svg" color="#5bbad5">
<meta name="apple-mobile-web-app-title" content="pronostics4fun">
<meta name="application-name" content="pronostics4fun">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-TileImage" content="/mstile-144x144.png">
<meta name="theme-color" content="#808080">
<link href="<?php echo ROOT_SITE.$_themePath ; ?>/css/default.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.jmpopups.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.log.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.text-overflow.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.cookies.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.requireScript.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery-ui-1.8.16.custom.min.js"></script>
<link rel='stylesheet' type='text/css' href='<?php echo ROOT_SITE.$_themePath;?>/css/custom-theme/jquery-ui-1.8.16.custom.css' />



<?php
if ($_SERVER['SERVER_NAME']=="beta.pronostics4fun.com") {
?>
<style>
#header h1 a span {
	background: url(<?php echo ROOT_SITE; ?>/images/logobeta.png) no-repeat scroll left top transparent;}
</style>
<?php }?>

<?php
if ($_SERVER['SERVER_NAME']=="localhost") {
?>
<style>
#header h1 a span {
	background: url(<?php echo ROOT_SITE; ?>/images/logodev.png) no-repeat scroll left top transparent;}
</style>
<?php }?>


</head>
<body>
<div id="mainwrapper">
	<div id="top"><span>&nbsp;</span></div>
	<div id="container">
    	<div id="header">
    		<h1><a id="linkHome"><span>&nbsp;</span></a></h1>

<?php
            echo '<h2><span style="font-size:12px;padding-right: 30px;"><a href="javascript:void(0);" style="font-size:12px;" id="Connection">Connexion</a> | <a style="font-size:12px;padding-right: 120px;" href="javascript:void(0);" id="Register"> Inscription</a></span></h2>';
    		?>

    		<div id="navMenu">
              <ul id="navMenu1">
              	<li class="navRight" id="MyAccount"><a href="javascript:void(0);" >Mon compte</a></li>
              </ul>
      		  <ul id="navMenu2">
							<?php 
switch ($_currentPage) {
	case "8":
 		echo '<li><a href="javascript:void(0);" ><span>Mot de passe</span></a></li>';
		break;
	case "11":
 		echo '<li><a href="javascript:void(0);" ><span>Validation</span></a></li>';
		break;
}
?>
      	      </ul>
    		</div>
    	</div>

    	<div id="content">
        <?php
          include("module.loader.php");
        ?>
    	</div>

    	<div id="footer">
    	</div>

	</div>
</div>

<div id="WaitingLayer" style="display:none;position: absolute; top: 380px; left: 500px; width: 350px; height: 40px; text-align:center;padding-top:20px;	background: #365F89;
	border: solid 1px #D7E1F6;
	color: #FFFFFF;
	font: bold 11px/ normal Tahoma, Verdana;z-Index: 99;
	"><img style="padding-right:10px;" src="<?php echo ROOT_SITE;?>/images/wait.gif"></img>Veuillez patienter pendant le chargement...</div>
</body>
<script>
$(function() {
	$.setupJMPopups({
		screenLockerBackground: "#D7E1F6",
		screenLockerOpacity: "0.7"
	});

	$("#top").click(function() {
			$.openPopupLayer({
				name: "ManualForecatsPopup",
				width: "450",
				height: "500",
				url: "submodule.loader.php?SubModule=10"
			});
	});

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
			alert('Les inscriptions sont momentanement ferméé.');
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

//	$.openPopupLayer({
//		name: "accountPopup",
//		width: "507",
//		height: "490",
//		url: "submodule.loader.php?SubModule=7"
//	});

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



$.requireScript('<?php echo ROOT_SITE; ?>/js/jquery.center.js', function() {
	$("#mainwrapper").center();
});

var waitingLayerWidth = $("#WaitingLayer").width();
var waitingLayerHeight = $("#WaitingLayer").height();
var topPosition = (clientHeight/2) - (waitingLayerHeight/2);
var leftPosition = (clientWidth/2) - (waitingLayerWidth/2);

$('#WaitingLayer').css("top",topPosition );
$('#WaitingLayer').css("left",leftPosition );

<?php
//if (!$_isAuthenticated)
//{
//  print ('$("#MyAccount").css("display","none");');
//  print ('$("#Forecasts").css("display","none");');
//  print ('$("#Results").css("display","none");');
//  print ('$("#Ranking").css("display","none");');
//  print ('$("#Live").css("display","none");');
//  print ('$("#Statistics").css("display","none");');
//}
//
//switch ($_currentPage)
//  {
//    case "1":
//      print ('$("#Forecasts").addClass("activate");');
//      break;
//    case "2":
//      print ('$("#Results").addClass("activate");');
//      break;
//    case "3":
//      print ('$("#Ranking").addClass("activate");');
//      break;
//    case "4":
//      print ('$("#Live").addClass("activate");');
//      break;
//    case "6":
//      print ('$("#Statistics").addClass("activate");');
//      break;
//    case "7":
//      print ('$("#AdminConsole").addClass("activate");');
//      break;
//    default:
//      print ('$("#Home").addClass("activate");');
//  }
print ('$("#MyAccount").addClass("activate");');
?>
});
</script>
</html>
<?php
//writeGoogleAnalytics();
//writeTrackingJS();
//writePerfInfo();
//$ua=getBrowser();

//$yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
//print_r($yourbrowser);
require_once("end.file.php");
?>