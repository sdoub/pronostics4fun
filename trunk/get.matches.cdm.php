#!/usr/local/bin/php
<?php
require_once(dirname(__FILE__)."/begin.file.php");
require_once(BASE_PATH . "/lib/simple_html_dom.php");


$query = "SELECT PrimaryKey GroupKey, Description, DayKey, Status
            FROM groups
           WHERE IsCompleted=0 AND CompetitionKey = ".COMPETITION."
           ORDER BY PrimaryKey
		   LIMIT 0,8
		   ";
$matches = "";
$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get all groups of the current competition");
$_databaseObject->close();

$queries = array();
print($query);
foreach ($rowsSet as $rowSet)
{

  $groupKey= $rowSet["GroupKey"];
  $groupDescription= $rowSet["Description"];
  $dayKey= $rowSet["DayKey"];

  //Get information from uefa
  $isGroupScheduled = true;

  $_error = "";


  $url = "http://fr.fifa.com/worldcup/matches/index.html";

  
  if ($html = file_get_html($url))
  {
    {
      foreach($html->find('div.matches div.fixture') as $match) {
        $sameGroup = false;
        foreach($match->find('div.mu-i-group') as $matchGroup) {
          //<div class="mu-i-group">Groupe A</div>
          if ($matchGroup->plaintext==$groupDescription) {
            $sameGroup = true;
          }
        }
        
        if ($sameGroup) {
          // Get match information
          foreach($match->find('div.s-date-HHmm') as $matchDate) {
            //<div class="s-score s-date-HHmm" data-timeutc="16:00" data-daymonthutc="1306"><span class="s-scoreText">13:00</span>   </div></div></div>
            $matchDay = $matchDate->getAttribute("data-daymonthutc");
            $matchUtcTime = $matchDate->getAttribute("data-timeutc");
            $matchUtcTimeArray = split(':',$matchUtcTime);
            
            $scheduleDate = mktime((int)$matchUtcTimeArray[0], (int)$matchUtcTimeArray[1], 0, (int)substr($matchDay,-2), (int)substr($matchDay,0,2), 2014);
			
          }
          foreach($match->find('div.home') as $matchHome) {
            $teamHome = $matchHome->find('span.t-nTri',0)->plaintext;
            $teamHomeKey = ConvertFifaCodeToP4F ($teamHome);
          }
          foreach($match->find('div.away') as $matchAway) {
            $teamAway = $matchAway->find('span.t-nTri',0)->plaintext;
            $teamAwayKey = ConvertFifaCodeToP4F ($teamAway);
          }
		  
		  if ($teamHomeKey==0 || $teamAwayKey==0) {
		    echo "ERROR : home - " . $teamHome . " vs away - " . $teamAway;
		  }
          $fifaMatchKey=$match->getAttribute("data-id");
          $insertQuery = "INSERT IGNORE INTO matches (GroupKey, TeamHomeKey, TeamAwayKey, ExternalKey, ScheduleDate) VALUES ";
          $insertQuery .= "($groupKey,";
          $insertQuery .= "$teamHomeKey,";
          $insertQuery .= "$teamAwayKey,";
          $insertQuery .= "$fifaMatchKey,";
          $insertQuery .= "FROM_UNIXTIME($scheduleDate+7200)) ";

          $queries[]=$insertQuery;

          $matchStatus=0;
          $updateQuery = "UPDATE matches SET ExternalKey=$fifaMatchKey, ScheduleDate=FROM_UNIXTIME($scheduleDate+7200), Status=$matchStatus WHERE GroupKey=";
          $updateQuery .= "$groupKey AND TeamHomeKey=$teamHomeKey AND TeamAwayKey=$teamAwayKey";
          $queries[]=$updateQuery;
 
        }       
      }
    }

    $html->clear();
    unset($html);
  }
  else {
    $_error = $_error . "<error>Erreur lors de l'analyse du document HTML</error>";
  }

  if ($isGroupScheduled) {
    $status="1";
  } else {
    $status="0";
  }


  $updateQuery = "
    UPDATE groups SET
    BeginDate =(SELECT MIN(ScheduleDate) FROM matches WHERE matches.GroupKey = groups.PrimaryKey),
    EndDate = (SELECT MAX(ScheduleDate) FROM matches WHERE matches.GroupKey = groups.PrimaryKey),";
  $updateQuery .= "Status=" . $status . "
    WHERE groups.PrimaryKey=$groupKey";
  $queries[]=$updateQuery;

}
//Open a new connection to execute all queries
$_databaseObject = new mysql (SQL_HOST, SQL_LOGIN,  SQL_PWD, SQL_DB, $_dbOptions);

foreach ($queries as $query) {
  print($query);
  print("<br/>");
  $_databaseObject -> queryPerf ($query , "Execute query");
}


$arrDatabaseInfo = $_databaseObject -> get ('xmlQueryPerf', 'xmlErrorLog', '_totalTime','errorLog');
if (sizeOf($arrDatabaseInfo["errorLog"])>0) {
  if ($arrDatabaseInfo["errorLog"]!="") {
    $_error = true;
    $_errorMessage="An error occured during queries execution";
    print_r($arrDatabaseInfo["errorLog"]);
  }
}

require_once(dirname(__FILE__)."/end.file.php");
?>
