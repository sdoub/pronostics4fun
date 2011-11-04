<?php
include('refresh.matches.php');

$_databaseObject = new mysql (SQL_HOST, SQL_LOGIN,  SQL_PWD, SQL_DB, $_dbOptions);

if (isset($_GET["MatchKey"])) {
  $query= "SELECT
matches.PrimaryKey MatchKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate
 FROM matches
WHERE matches.PrimaryKey IN (". $_GET["MatchKey"] . ")";
}
else {
  $currentTime = time();

  $query= "SELECT
 matches.PrimaryKey MatchKey,
 matches.GroupKey,
 UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate
 FROM matches
 INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
 WHERE $currentTime >= (UNIX_TIMESTAMP(matches.ScheduleDate)) AND $currentTime <= (UNIX_TIMESTAMP(matches.ScheduleDate)+10200)";

}
echo $query;
$resultSet = $_databaseObject->queryPerf($query,"Get matches to be refreshed");
$queryResults = array();
$queryEvents = array();
$queryTeamPlayers = array();
$matches = array();
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  $matches[]=$rowSet["MatchKey"];
  // Results
  /* INSERT IGNORE INTO `results` (`PrimaryKey`, `MatchKey`, `LiveStatus`, `ActualTime`, `ResultDate`) VALUES
   (35, 157, 10, 93, '2010-06-11 09:00:04');
   */
  $sqlResult = "SELECT PrimaryKey, MatchKey, LiveStatus, ActualTime, ResultDate FROM results WHERE MatchKey=" . $rowSet["MatchKey"];
  $resultSetResult = $_databaseObject->queryPerf($sqlResult,"Get matches to be refreshed");

  while ($rowSetResult = $_databaseObject -> fetch_assoc ($resultSetResult))
  {
    $resultKey = $rowSetResult["PrimaryKey"];
    $matchKey = $rowSetResult["MatchKey"];
    $liveStatus = $rowSetResult["LiveStatus"];
    $actualTime = $rowSetResult["ActualTime"];

    $queryResults[] = "INSERT IGNORE INTO results (PrimaryKey, MatchKey, LiveStatus, ActualTime) VALUES ($resultKey, $matchKey, $liveStatus, $actualTime) ON DUPLICATE KEY UPDATE LiveStatus=$liveStatus, ActualTime=$actualTime";

    $sqlEvents = "SELECT TeamKey, EventType, EventTime, Half, TeamPlayerKey FROM events WHERE ResultKey=$resultKey";
    $resultSetEvents = $_databaseObject->queryPerf($sqlEvents,"Get matches to be refreshed");

    while ($rowSetEvent = $_databaseObject -> fetch_assoc ($resultSetEvents))
    {
      $teamKey = $rowSetEvent["TeamKey"];
      $eventType= $rowSetEvent["EventType"];
      $eventTime= $rowSetEvent["EventTime"];
      $half= $rowSetEvent["Half"];
      $teamPlayerKey=$rowSetEvent["TeamPlayerKey"];
      $queryEvents[] = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey)
      VALUES ($resultKey, $teamKey, $eventType, $eventTime, $half, $teamPlayerKey) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";

      $sqlTeamPlayer = "SELECT FullName FROM teamplayers WHERE PrimaryKey=$teamPlayerKey";
      $resultSetTeamPlayer = $_databaseObject->queryPerf($sqlTeamPlayer,"Get matches to be refreshed");
      $rowSetTeamPlayer = $_databaseObject -> fetch_assoc ($resultSetTeamPlayer);
      $queryTeamPlayers[] = "INSERT IGNORE INTO teamplayers (PrimaryKey, FullName) VALUES ($teamPlayerKey, '" . str_replace("'","''",__encode($rowSetTeamPlayer["FullName"])) . "')";
    }

  }
}
$date = date("d-m-Y");
$heure = date("H:i");
Print("<br/>Nous sommes le $date et il est $heure");
$queries = "";
echo "<br/><textarea id='TextAreaQueries' rows='20' cols='100'>";
while (list ($key, $value) = each ($queryResults)) {
  echo __encode($value) . ";";
  $queries .= __encode($value) . ";";
}
while (list ($key, $value) = each ($queryEvents)) {
  echo __encode($value). ";";
  $queries .= __encode($value) . ";";
}
while (list ($key, $value) = each ($queryTeamPlayers)) {
  echo __encode($value). ";";
  $queries .= __encode($value) . ";";
}

echo "</textarea>";
echo "<br/><input id='btnExecute' type='submit' value='Executer'/>";

if (!empty($queries)) {
  //echo $queries;
  //  open connection
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "http://pronostics4fun.com/execute.sql.query.php");

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, 1);

  //prepare the field values being posted to the service
  $data = array();
  $data["SQL"] = $queries;

  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

  //execute post
  $result = curl_exec($ch);

  //close connection
  curl_close($ch);

  //echo $result;

  while (list ($key, $value) = each ($matches)) {
    $ch = curl_init();
    echo "<br/>MatchKey: " .$value;
    curl_setopt($ch, CURLOPT_URL, "http://pronostics4fun.com/manual.refresh.match.php?MatchKey=". $value);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_GET, 1);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);
    echo $result;
  }

}

$_databaseObject->close();
unset($_databaseObject);
?>
<script
	type="text/javascript"
	src="<?php echo ROOT_SITE; ?>/js/jquery-1.4.2.min.js"></script>
<script
	type="text/javascript"
	src="<?php echo ROOT_SITE; ?>/js/jquery.log.min.js"></script>
<script
	type="text/javascript"
	src="<?php echo ROOT_SITE; ?>/js/jquery.xdomainajax.js"></script>
<script>
$(document).ready(function($) {
//	var queries = $("#TextAreaQueries").val();
//$.log(queries);
//if (queries){
//	$.ajax({
//		type: "POST",
//		url: "proxy.php",
//		  dataType: 'json',
//		  data: {SQL: queries},
//		  success: callbackQueries,
//		  error: callbackPostError
//		});
//}
//else
	setTimeout( "refresh()", 300*1000 );
$("#btnExecute").click (function () {
	var queries = $("#TextAreaQueries").val();


//	$.post("proxy.php?url=" + escape('http://crum.bs/api.php?function=shorten'), {
//	    "url": $("#crumbs_url").val(),
//	    "desc": $("#crumbs_desc").val(),
//	    "custom": $("#crumbs_custom").val(),
//	    "password": $("#crumbs_password").val(),
//	    "limit": $("#crumbs_limit").val(),
//	    "terms": 1,
//
//	}, function (data) {
//
//	    console.log(data);
//	    if (data.error) {
//	        alert('Error: ' + data.error);
//	    } else {
//
//	        $(".crumbs_result").html('Result: <a href="' + data.short + '">Link</a>').slideDown();
//	    }
//	}, "json");
//
	$.ajax({
			type: "POST",
			url: "proxy.php",
			  dataType: 'json',
			  data: {SQL: queries},
			  success: callbackQueries,
			  error: callbackPostError
			});
});
});

function callbackQueries(data) {
	$.log(data);
	setTimeout( "refresh()", 300*1000 );
}

function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
{
	$.log(XMLHttpRequest);
	$.log(textStatus);
	$.log(errorThrown);
}
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
