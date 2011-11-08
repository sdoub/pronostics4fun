<?php
require_once("begin.file.php");

if (isset($_GET["Page"]))
{
$_currentPage=$_GET["Page"];
}
else
{
$_currentPage="0";
}

$_browserInfo=getBrowser();
//$yourbrowser= "Your browser: " . $_browserInfo['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
define('IEBROWSER',"Internet Explorer");
?>

<html>
<head>
<meta name="robots" content="index,follow"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Pronostics4fun - Ligue 1</title>
<meta name="description" content="Pronostics4Fun vous propose de vous mesurer entre passionnés de football. Pour participer, il vous suffit de vous inscrire (Inscription en haut de la page), et de pronostiquer chacune des journées de la ligue 1.
Des classements et des statistiques sont établis à la fin de chaque journée de championnat. (Classement général, Classement par journée, ...).">
<meta name="keywords" content="p4f, pronostics 4 fun, pronostics4fun, pronostic, pronostics, pronostic football, pronostic foot, pronostics foot, pronostics football, ligue 1, pronostique, prono foot, pronostic ligue 1, pronostic foot france, prono, prono foot, prono ligue 1">
<link rel="icon" href="<?php echo ROOT_SITE; ?>/favico.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo ROOT_SITE; ?>/favico.ico" type="image/x-icon" />
<link href="<?php echo ROOT_SITE; ?>/css/default.css" type="text/css" rel="stylesheet"/>
<link type="text/css" href="<?php echo ROOT_SITE; ?>/css/jixedbar.themes/rave/jx.stylesheet.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.jmpopups.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.log.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.text-overflow.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.cookies.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.requireScript.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery-ui-1.8.16.custom.min.js"></script>
<link rel='stylesheet' type='text/css' href='<?php echo ROOT_SITE;?>/css/custom-theme/jquery-ui-1.8.16.custom.css' />


<?php
if ($_browserInfo['name']!=IEBROWSER) {
?>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.jixedbar.min.js"></script>
<?php }?>

</head>
<body>
<div id="mainwrapper">
	<div id="top"><span>&nbsp;</span></div>
	<div id="container">
    	<div id="header">
    		<h1><a id="linkHome"><span>&nbsp;</span></a></h1>

<?php

if ($_isAuthenticated)
{
  echo '<h2><span style="font-size:14px;padding-right: 30px;">Bienvenue  ' . $_authorisation->getConnectedUser() . ' | <a style="font-size:12px;padding-right: 120px;" href="index.php?logoff=1"> ' . __encode("Déconnexion") . '</a></span></h2>';
}
else
{
  echo '<h2><span style="font-size:12px;padding-right: 30px;"><a href="javascript:void(0);" style="font-size:12px;" id="Connection">Connexion</a> | <a style="font-size:12px;padding-right: 120px;" href="javascript:void(0);" id="Register"> Inscription</a></span></h2>';
}

    		?>

    		<div id="navMenu">
              <ul id="navMenu1">
              	<li id="Home"><a href="index.php" id="nav_home" ><span>Accueil</span></a></li>
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
              	<li id="Results"><a href="index.php?Page=2" ><?php echo __encode("Résultats"); ?></a></li>
              	<li id="Ranking"><a href="index.php?Page=3" >Classements</a></li>
              	<li id="Statistics"><a href="index.php?Page=6" >Statistiques</a></li>

<?php
  } else {
?>
              	<li id="Results" class="disabled" title="Non disponible!"><a href="javascript:void()" ><?php echo __encode("Résultats"); ?></a></li>
              	<li id="Ranking" class="disabled" title="Non disponible!"> <a href="javascript:void()" >Classements</a></li>
              	<li id="Statistics" class="disabled" title="Non disponible!"><a href="javascript:void()" >Statistiques</a></li>

<?php
  }
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

    		    <?php
    		    if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1) {
              	  echo '<li class="navRight" id="AdminConsole"><a href="index.php?Page=7" >Administration</a></li>';
              	}
              	?>
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
 <?php    	if ($_isAuthenticated)
{
  ?>
  <?php
if ($_browserInfo['name']!=IEBROWSER) {
?>

<div id="demo-bar">
        <ul>
            <li title="Contact" id="ContactLink"><a href="javascript:void(0);"><img src="<?php echo ROOT_SITE; ?>/images/email.png" style="width:20px;height: 20px;" alt="" /></a></li>
        </ul>
        <span class="jx-separator-left"></span>

        <ul class="jx-bar-button-right">
			<li title=""><a href="#"><img style="width:20px;height: 20px;" src="<?php echo ROOT_SITE; ?>/images/DefaultAvatar.jpg" alt="<?php echo $_nbrConnectedUser  . utf8_encode(" membre(s) connect&eacute;(s)");?>" /></a>
                <ul>
					<?php


					$resultSet = $_databaseObject -> queryPerf ("SELECT NickName, AvatarName FROM playersenabled players
					INNER JOIN connectedusers ON connectedusers.PlayerKey=players.PrimaryKey ORDER BY NickName");
                    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
                      $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
                      if (!empty($rowSet["AvatarName"])) {
                        $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
                      }

                      echo '<li><a href="javascript:void(0);"><img style="width:30px;height: 30px;" src="' .$avatarPath.'" title="'.$rowSet["NickName"].'" />&nbsp;&nbsp;&nbsp;'.$rowSet["NickName"].'</a></li>';
                    }
					?>
                </ul>
			</li>
        </ul>
        <span class="jx-separator-right"></span>
</div>
<?php
}}?>

<div id="WaitingLayer" style="display:none;position: absolute; top: 380px; left: 500px; width: 350px; height: 40px; text-align:center;padding-top:20px;	background: #365F89;
	border: solid 1px #D7E1F6;
	color: #FFFFFF;
	font: bold 11px/ normal Tahoma, Verdana;z-Index: 99;
	"><img style="padding-right:10px;" src="<?php echo ROOT_SITE;?>/images/wait.gif"></img>Veuillez patienter pendant le chargement...</div>
</body>
<script>
<?php    	if ($_isAuthenticated)
{
  ?>
  <?php
		  if ($_browserInfo['name']!=IEBROWSER) {
		  ?>

$(document).ready(function() {
	$("#demo-bar").jixedbar();
});
<?php
}}?>
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
			alert('Les inscriptions sont momentanement fermées.');
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
if (!$_isAuthenticated)
{
  print ('$("#MyAccount").css("display","none");');
  print ('$("#Forecasts").css("display","none");');
  print ('$("#Results").css("display","none");');
  print ('$("#Ranking").css("display","none");');
  print ('$("#Live").css("display","none");');
  print ('$("#Statistics").css("display","none");');
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
    case "7":
      print ('$("#AdminConsole").addClass("activate");');
      break;
    default:
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