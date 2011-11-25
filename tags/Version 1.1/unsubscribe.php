<?php
require_once("begin.file.php");

if (!isset($_GET["key"]) && !isset($_GET["type"])) {
  exit('Information required are missing!');
}

if (!isset($_GET["action"])) {
$sql= "SELECT * from players WHERE ActivationKey='" . $_GET["key"] . "'";

$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");

$rowSet = $_databaseObject -> fetch_assoc ($resultSet);

?>

<html>
<head>
<meta name="robots" content="index,follow"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Pronostics4fun - Ligue 1 - Saison 2010/2011</title>
<meta name="description" content="Pronostics4Fun vous propose de vous mesurer entre passionn�s de football. Pour participer, il vous suffit de vous inscrire (Inscription en haut de la page), et de pronostiquer chacune des journ�es de la ligue 1.
Des classements et des statistiques sont �tablis � la fin de chaque journ�e de championnat. (Classement g�n�ral, Classement par journ�e, ...).">
<meta name="keywords" content="pronostics4fun, pronostic, pronostics, pronostic football, pronostic foot, pronostics foot, pronostics football, ligue 1, pronostique, prono foot, pronostic ligue 1, pronostic foot france, prono, prono foot, prono ligue 1">
<link rel="icon" href="<?php echo ROOT_SITE; ?>/favico.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo ROOT_SITE; ?>/favico.ico" type="image/x-icon" />
<link href="<?php echo ROOT_SITE; ?>/css/default.css" type="text/css" rel="stylesheet"/>
<link type="text/css" href="<?php echo ROOT_SITE; ?>/css/jixedbar.themes/rave/jx.stylesheet.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.jmpopups.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.log.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.text-overflow.js"></script>
<script type="text/javascript" src="<?php echo ROOT_SITE; ?>/js/jquery.cookies.js"></script>
<style>
.buttonfield {
	background: #365F89;
	border: solid 1px #D7E1F6;
	color: #FFFFFF;
	font: bold 11px/ normal Tahoma, Verdana;
	margin-top: 10px;
	margin-left: 15px;
	width:50px;
	padding: 4px;
	float:right;

}
.buttonfield:hover, .buttonfield:focus {
	background: #000;
	border: solid 1px #fff;
	color: #fff;
	cursor: pointer;
}
</style>

</head>
<body>
<div id="mainwrapper">
	<div id="top"><span>&nbsp;</span></div>
	<div id="container">
    	<div id="header">
    		<h1><a id="linkHome"><span>&nbsp;</span></a></h1>
			<h2><span style="font-size:12px;padding-right: 30px;">&nbsp;</span></h2>
			    		<div id="navMenu">
              <ul id="navMenu1">
              	<li id="Home"><a href="index.php" id="nav_home"><span>Accueil</span></a></li>
			</ul>
    		</div>
    	</div>

    	<div id="content">
    	<div id="divConfirm" style="height:120px;margin-top:50px;margin-right:150px;margin-left:150px;">
        	<div style="height:120px;border: solid 1px #365F89;background: #d6e1f5;">
            	<p style="color:#365F89;font-size:14px;margin-top:10px;margin-bottom:20px;">Bonjour <strong><?php echo $rowSet["NickName"];?></strong>,</p>
            	<?php if ($_GET["type"]==2) {?>
            	<p style="color:#365F89;font-size:12px;"><?php echo __encode("Etes vous sur de ne plus vouloir recevoir les r�sultats par email ?")?></p>
            	<?php } else if ($_GET["type"]==1) {?>
            	<p style="color:#365F89;font-size:12px;"><?php echo __encode("Etes vous sur de ne plus vouloir recevoir les alertes pour les matchs non pronostiqu�s ?")?></p>
            	<?php } ?>
        		<p style="color:#365F89;font-size:10px; margin-top:30px;"><?php echo __encode("p.s. : Si vous vous d�sabonnez, vous pourrez toujours r�-activer cette option � partir de l'�cran d�tail de votre compte.")?></p>
        	</div>
			<input name="btn" id="btnYes" class="buttonfield" value="Oui" type="submit"/>
			<input name="btn" id="btnNo" class="buttonfield" value="Non" type="submit"/>
    	</div>
    	<div id="divResult" style="height:120px;margin-top:50px;margin-right:150px;margin-left:150px;display:none;" >
        	<div style="height:120px;border: solid 1px #365F89;background: #d6e1f5;">
            	<p style="color:#365F89;font-size:14px;margin-top:10px;margin-bottom:20px;"><strong><?php echo $rowSet["NickName"];?></strong>,</p>
            	<?php if ($_GET["type"]==2) {?>
            	<p style="color:#365F89;font-size:12px;"><?php echo __encode("A partir de maintenant vous ne recevrez plus l'email vous informant des r�sultats d'une journ�e de championnat.")?></p>
            	<?php } else if ($_GET["type"]==1) {?>
            	<p style="color:#365F89;font-size:12px;"><?php echo __encode("A partir de maintenant, vous ne recevrez plus l'email de rappel, 1 jour, avant un match non pronostiqu�.")?></p>
            	<?php } ?>

        		<p style="color:#365F89;font-size:10px; margin-top:25px;"><?php echo __encode("p.s. : Si vous changez d'avis, vous pourrez toujours r�-activer cette option � partir de l'�cran d�tail de votre compte.")?></p>
        	</div>
			<input name="btn" id="btnOk" class="buttonfield" value="Ok" type="submit"/>
    	</div>

    	</div>
	</div>
</div>
<script>
$(document).ready(function() {
	$("#btnYes").click(function() {
		$.ajax({
			  url: 'unsubscribe.php?key=<?php echo $_GET["key"];?>&type=<?php echo $_GET["type"];?>&action=1',
			  dataType: 'json',
			  data: "",
			  success: callbackPost,
			  error: callbackPostError
			});
	});
	$("#btnNo").click(function() {
		window.location.replace("<?php echo ROOT_SITE;?>/index.php");
	});
	$("#btnOk").click(function() {
		window.location.replace("<?php echo ROOT_SITE;?>/index.php");
	});

});

function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
{
	$.log(XMLHttpRequest);
	$.log(textStatus);
	$.log(errorThrown);
}
function callbackPost (data){
	if (data.status) {
		$("#divConfirm").fadeOut('fast', function () {
			$("#divResult").fadeIn('fast');
		});
	}
}
</script>
<?php
} else {
  if ($_GET["action"]==1){
    if ($_GET["type"]==1) {
      $sql="UPDATE players SET ReceiveAlert=0 WHERE ActivationKey='" . $_GET["key"] . "'";

    } else if ($_GET["type"]==2) {
      $sql="UPDATE players SET ReceiveResult=0 WHERE ActivationKey='" . $_GET["key"] . "'";
    }

    if(!$_databaseObject->queryPerf($sql,"Account update")) {
      //@ failed
      $arr["status"] = false;
    } else {
      //@ success
      $arr["status"] = true;
    }
    $arr["perfAndError"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
    echo json_encode($arr);

  }
}

require_once("end.file.php");
?>