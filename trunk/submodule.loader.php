<?php
require_once("begin.file.php");
$delay = "1000";
switch ($_GET['SubModule'])
{
  case "1":
    $submoduleName = "login";
    $additionalJSorCssScript  = '<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.validate.js"></script>
    							<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.validation.functions.js"></script>
            					<link rel="stylesheet" type="text/css" href="' . ROOT_SITE .$_themePath . '/css/jquery.validate.css?ver=1.0" />';

    $additionalScripts = '<script>
	$(document).ready(function() {
		window.setTimeout("getLogin()",'.$delay.');
	});
</script>
';
    break;
  case "2":
    $submoduleName = "register";
    $additionalJSorCssScript = '<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.validate.js"></script>
    							<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.validation.functions.js"></script>
            					<link rel="stylesheet" type="text/css" href="' . ROOT_SITE .$_themePath . '/css/jquery.validate.css?ver=1.0" />';

    $additionalScripts = '<script>
	$(function() {
		window.setTimeout("getRegister()",'.$delay.');
	});
</script>
';
    break;
  case "3":
    $submoduleName = "forecast";
    $additionalJSorCssScript = '<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.numberinput.js"></script>';

    $additionalScripts = '<script>

    function localGetForecast () {
    	getForecast("' . $_GET['matchKey'] . '");
    }
    $(function() {
		window.setTimeout("localGetForecast()",'.$delay.');
	});
</script>
';
    break;
  case "4":
    $submoduleName = "rules";
    $additionalJSorCssScript = '';
    $additionalScripts = '<script>
	$(function() {
		window.setTimeout("getRules()",'.$delay.');
	});
</script>';
    break;
  case "5":
    $submoduleName = "contact";
    $additionalJSorCssScript = '<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.validate.js"></script>
    							<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.validation.functions.js"></script>
            					<link rel="stylesheet" type="text/css" href="' . ROOT_SITE .$_themePath . '/css/jquery.validate.css" />';
    $additionalScripts = '<script>
	$(function() {
		window.setTimeout("getContact()",500);
	});
</script>';
    break;
  case "6":
    $submoduleName = "match";
    $additionalJSorCssScript = '';
    $additionalScripts = '<script>
	function localGetMatchDetail () {
		getMatchDetail("' . $_GET['matchKey'] . '");
	}
    $(function() {
		window.setTimeout("localGetMatchDetail()",'.$delay.');
	});
</script>';
    break;
  case "7":
    $submoduleName = "account";
    $additionalJSorCssScript = '<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.validate.js"></script>
    							<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.validation.functions.js"></script>
            					<link rel="stylesheet" type="text/css" href="' . ROOT_SITE .$_themePath . '/css/jquery.validate.css" />
            					<link href="' . ROOT_SITE .$_themePath . '/css/fileuploader.css" rel="stylesheet" type="text/css">
    							<script src="' . ROOT_SITE . '/js/fileuploader.js" type="text/javascript"></script>
            					<script src="' . ROOT_SITE . '/js/jquery.Jcrop.js"></script>
								<link rel="stylesheet" href="' . ROOT_SITE .$_themePath . '/css/jquery.Jcrop.css" type="text/css" />
								<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.ibutton.js"></script>
								<link type="text/css" href="' . ROOT_SITE .$_themePath . '/css/jquery.ibutton.p4f.css" rel="stylesheet" media="all" />
								';

    $additionalScripts = '<script>
$(function() {
		window.setTimeout("getAccount()",'.$delay.');
});
</script>
';
    break;
  case "8":
    $submoduleName = "player";
    $additionalJSorCssScript = '';

    $additionalScripts = '<script>

    function localGetPlayerDetail() {
    	getPlayerDetail("' . $_GET['playerKey'] . '");
    }
    $(function() {
		window.setTimeout("localGetPlayerDetail()",'.$delay.');
	});
</script>
';
    break;
  case "9":
    $submoduleName = "player.group";
    $additionalJSorCssScript = '';

    $additionalScripts = '<script>
	function localGetPlayerGroupDetail () {
		getPlayerGroupDetail("' . $_GET['playerKey'] . '","' . $_GET['groupKey'] . '");
	}
    $(function() {
		window.setTimeout("localGetPlayerGroupDetail()",'.$delay.');
	});
</script>
';
    break;
  case "10":
    $submoduleName = "manual.forecast";
    $additionalJSorCssScript = '<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.validate.js"></script>
    							<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.validation.functions.js"></script>
            					<link rel="stylesheet" type="text/css" href="' . ROOT_SITE .$_themePath . '/css/jquery.validate.css" />
							    <script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.numberinput.js"></script>';


    $additionalScripts = '<script>
	$(function() {
		window.setTimeout("getManualForecast()",'.$delay.');
	});
</script>
';
    break;
  case "11":
    $submoduleName = "scorer";
    $additionalJSorCssScript = '';

    $additionalScripts = '<script>
	function localGetScorerDetail () {
		getScorerDetail(' . $_GET['teamPlayerKey'] . ',' . $_GET['teamKey'] . ');
	}
    $(function() {
		window.setTimeout("localGetScorerDetail()",'.$delay.');
	});
</script>
';
    break;
  case "12":
    $submoduleName = "score";
    $additionalJSorCssScript = '';

    $additionalScripts = '<script>
	function localGetScoreDetail () {
		getScoreDetail("' . $_GET['score'] . '","' . $_GET['teamKeys'] . '","' . $_GET['groupKeys'] . '");
	}
    $(function() {
		window.setTimeout("localGetScoreDetail()",'.$delay.');
	});
</script>
';
    break;
  case "13":
    $submoduleName = "assist";
    $additionalJSorCssScript = '';

    $additionalScripts = '<script>
	function localGetAssistDetail () {
		getAssistDetail(' . $_GET['teamPlayerKey'] . ',' . $_GET['teamKey'] . ');
	}
    $(function() {
		window.setTimeout("localGetAssistDetail()",'.$delay.');
	});
</script>
';
    break;

  case "14":
//    $_browserInfo=getBrowser();
//    define('IEBROWSER',"Internet Explorer");
//    if ($_browserInfo['name']==IEBROWSER) {
//      $submoduleName = "vote";
//      $additionalJSorCssScript = '<script type="text/javascript" src="' . ROOT_SITE . '/js/jquery.ui.stars.js"></script>
//    <link rel="stylesheet" type="text/css" href="' . ROOT_SITE .$_themePath . '/css/jquery.ui.stars.css" />';
//
//      $additionalScripts = '<script>
//    $(function() {
//		window.setTimeout("getVote()",'.$delay.');
//	});
//	</script>
//	';
//    } else {
      $submoduleName = "vote";
      $additionalJSorCssScript = '<link rel="stylesheet" type="text/css" href="' . ROOT_SITE .$_themePath . '/css/jquery.ui.stars.css" />
      ';

      $additionalScripts = '<script>
    $(function() {
		$.requireScript("'.ROOT_SITE.'/js/jquery.ui.stars.js", function() {
  			window.setTimeout("getVote()",'.$delay.');
		});
	});
</script>
';
//    }
    break;
}
?>
<img
	id="close" src="<?php echo ROOT_SITE; ?>/images/close.png"
	style="position: absolute; cursor: pointer;right:0;"></img>
<div id="wait"></div>
<div id="wrapper"></div>

<script
	type="text/javascript"
	src="<?php echo ROOT_SITE.$_themePath ; ?>/js/submodules/<?php echo $submoduleName; ?>.js?ver=1.6"></script>
<link
	href="<?php echo ROOT_SITE.$_themePath ; ?>/css/submodules/<?php echo $submoduleName; ?>.css?ver=1.4"
	type="text/css" rel="stylesheet" />

<?php print($additionalJSorCssScript); ?>

<?php print($additionalScripts); ?>

<script>
$(document).ready(function(){
	$('#close').click(function () {
		$.closePopupLayer();
	});
});
</script>
<?php
require_once("end.file.php");
?>
