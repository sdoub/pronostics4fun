<?php
error_reporting(0);
require_once('refresh.matches.php');

$date = date("d-m-Y");
$heure = date("H:i");
Print("<br/>Nous sommes le $date et il est $heure");
?>

<script
	type="text/javascript"
	src="<?php echo ROOT_SITE; ?>/js/jquery-1.4.2.min.js"></script>
<script
	type="text/javascript"
	src="<?php echo ROOT_SITE; ?>/js/jquery.log.min.js"></script>

<script>
$(document).ready(function($) {
	setTimeout( "refresh()", 300*1000 );
});

var sURL = unescape(window.location.pathname);
function refresh()
{
    //  This version does NOT cause an entry in the browser's
    //  page view history.  Most browsers will always retrieve
    //  the document from the web-server whether it is already
    //  in the browsers page-cache or not.
    //
    window.location.replace( sURL );
}

</script>