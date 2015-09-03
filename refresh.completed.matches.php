<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/lib/match.php");
require_once(dirname(__FILE__). "/lib/http.php");

class Timer
{
  private static $_start = null;

  static function start()
  {
    $mtime = microtime();
    $mtime = explode(' ', $mtime);
    $mtime = $mtime[1] + $mtime[0];
    self::$_start = $mtime;
  }

  static function end()
  {
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[1] + $mtime[0];
    $endtime = $mtime;
    return ($endtime - self::$_start);
  }
}

$dayKey="";
if (isset($_GET["DayKey"])) {

  $dayKey=$_GET["DayKey"];
  $whereClause = " WHERE groups.DayKey IN ($dayKey)";
} elseif (isset($_GET["MatchKey"])){
  $matchKey=$_GET["MatchKey"];
  $whereClause = " WHERE matches.PrimaryKey IN ($matchKey)";

}
else {
  $whereClause = " WHERE groups.Status=3 AND groups.EndDate + INTERVAL 5 DAY <NOW()";
  // for testing purpose
  //$dayKey="1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27";
  //$dayKey="28";
  //$whereClause = " WHERE groups.DayKey IN (15)";
}

$currentTime = time();

$query= "SELECT
matches.PrimaryKey MatchKey,
matches.GroupKey,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate,
matches.TeamHomeKey,
matches.TeamAwayKey,
matches.ExternalKey,
groups.Description
 FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
 " . $whereClause ;

//WHERE $currentTime >= (UNIX_TIMESTAMP(matches.ScheduleDate)) AND $currentTime <= (UNIX_TIMESTAMP(matches.ScheduleDate)+11400)";

$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get matches to be refreshed");
$_databaseObject->close();
$_queries = array();
Timer::start();
$http = Http::connect($_SERVER['SERVER_NAME'], 80);
$http->silentMode();
$arrMatches = array();
foreach ($rowsSet as $rowSet)
{
  $parameter = array();
  $parameter["TeamHomeKey"] = $rowSet["TeamHomeKey"];
  $parameter["TeamAwayKey"] = $rowSet["TeamAwayKey"];
  $parameter["ExternalKey"] = $rowSet["ExternalKey"];
  $parameter["MatchKey"] = $rowSet["MatchKey"];
  $parameter["Live"] = 0;
  print_r($parameter);
	$http->post('refresh.match.php', $parameter);
  $arrMatches[]=$rowSet["MatchKey"];
}


$results = $http ->run();
echo '<p>Pages results</p>';
print_r($results);
$info = 'Info was getting in ' . Timer::end() . ' seconds.';
echo '<p>' . $info . '</p>';
$defaultLogger->addInfo($info);

$arrMatchesRefreshed = array();
if (is_array($results)) {
	foreach ($results as $result) {
		$defaultLogger->addDebug($result);
		$queries = json_decode(trim($result));
		if (is_object($queries)){
			foreach ($queries->Queries as $query)
			{
				$_queries[] = $query;
			}

			$arrMatchesRefreshed[]=$queries->Parameters->MatchKey;
		}
	}
}
Timer::start();
if (is_array($arrMatches)) {
	foreach ($arrMatches as $value) {
		if (!array_value_exists2($arrMatchesRefreshed, $value)){
			print ("<p>Match ($value) was not refreshed!</p>");
			$defaultLogger->addInfo($info);
		}
	}
}

$_databaseObject = new mysql (SQL_HOST, SQL_LOGIN,  SQL_PWD, SQL_DB, $_dbOptions);
$updatedInfo = 0;
if (is_array($_queries)) {
	foreach ($_queries as $query) {
		print($query);
		$updatedInfo++;
		$defaultLogger->addDebug($query);
		$_databaseObject -> queryPerf ($query , "Execute query");
	}
}
if ($updatedInfo>0) {
	$query = "UPDATE groups SET groups.Status=4 " . $whereClause;
	$_databaseObject->queryPerf($query,"update group");
}

$info ='Queries ('.sizeOf($_queries).') were executed in ' . Timer::end() . ' seconds.';
echo '<p>'.$info.'</p>';
$defaultLogger->addDebug($info);


$previousGroupKey = 0;

$arr = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');

$totaltime = getElapsedTime();
echo "<p>This page loaded in $totaltime seconds.</p>";
$_errorMessage = "";
if (count($arr["errorLog"])>0) {
  if ($arr["errorLog"]!="") {
    $_error = true;
    $_errorMessage="An error occured during queries execution";
    print_r($arr["errorLog"]);
  }
}

$sqlUpdateCronJobLog =" UPDATE cronjobs SET LastStatus=2, LastExecutionInformation='$_errorMessage' WHERE JobName='RefreshCompletedMatches'";
$_databaseObject->queryPerf($sqlUpdateCronJobLog,"Update Cron job information");


require_once(dirname(__FILE__)."/end.file.php");
?>