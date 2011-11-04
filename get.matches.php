#!/usr/local/bin/php
<?php
require_once(dirname(__FILE__)."/begin.file.php");
require_once(BASE_PATH . "/lib/simple_html_dom.php");


$query = "SELECT PrimaryKey GroupKey, DayKey, IF (TIMEDIFF(BeginDate,(NOW()+ INTERVAL 1 HOUR))<0,1,0) isVoteClosed
            FROM groups
           WHERE IsCompleted=0 AND CompetitionKey = ".COMPETITION."
           LIMIT 0,5";
$matches = "";
$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get all groups of the current competition");
$_databaseObject->close();

$queries = array();

foreach ($rowsSet as $rowSet)
{
  $groupKey= $rowSet["GroupKey"];
  $dayKey= $rowSet["DayKey"];

  //Get information from lfp
  $isGroupScheduled = true;

  $_error = "";
  switch (COMPETITION)
  {
    case 1:
      //$url = "http://live.football365.fr/direct_sybase/competitions/CDM_2010/CDM_2010_" . $rowSet["Football365Key"] . ".xml";
      break;
    case 2:
    case 3:
      $url = "http://" . EXTERNAL_WEB_SITE . "/competitionPluginCalendrierResultat/changeCalendrierHomeJournee?c=ligue1&js=" . $dayKey . "&id=0";
      break;
  }

print($url);
  if ($html = file_get_html($url))
  {
    {
      $scheduleDates = array();
      foreach($html->find('h4') as $h4) {
        $scheduleDates[]=ConvertFrenchDateToUniversalDate(utf8_decode($h4->plaintext));
      }

      $currentTable = 0;
      foreach($html->find('table') as $table) {
        foreach($table->find('tbody tr') as $rows) {

          if ($rows->find('td',0)){

            // Get "Feuille de match Id
            $lfpUrl = split('/',$rows->find('td',0)->first_child ()->getAttribute("href"));
            $lfpMatchKey = $lfpUrl[3];

            $dateMatchArray = split('/',$scheduleDates[$currentTable]);
            if (strpos($rows->find('td',0)->plaintext,":")!==false ) {
              $hourMatchArray = split(':',$rows->find('td',0)->plaintext);
            } else {
              $hourMatchArray = array();
              $hourMatchArray[0] = 0;
              $hourMatchArray[1] = 0;
              $isGroupScheduled =false;
            }
            $hours = (int)$hourMatchArray[0]==0?19:(int)$hourMatchArray[0];
            $minutes = $hourMatchArray[1];
            $scheduleDate = mktime((int)$hours, (int)$minutes, 0, (int)$dateMatchArray[1], (int)$dateMatchArray[0], (int)$dateMatchArray[2]);

            if ($rows->find('td',2)->first_child()->hasAttribute("src")) {
              $lfpTeamHome = split('/',$rows->find('td',2)->first_child()->getAttribute("src"));
            } else {
              $lfpTeamHome = split('/',$rows->find('td',2)->first_child()->first_child()->getAttribute("src"));
            }
            $lfpTeamHomeKey = substr($lfpTeamHome[6], 0, -4);
            $teamHomeKey = ConvertLfpKeyToP4F ($lfpTeamHomeKey);

            if ($rows->find('td',4)->first_child()->hasAttribute("src")) {
              $lfpTeamAway = split('/',$rows->find('td',4)->first_child()->getAttribute("src"));
            } else {
              $lfpTeamAway = split('/',$rows->find('td',4)->first_child()->first_child()->getAttribute("src"));
            }
            $lfpTeamAwayKey = substr($lfpTeamAway[6], 0, -4);

            $teamAwayKey = ConvertLfpKeyToP4F ($lfpTeamAwayKey);


            $insertQuery = "INSERT IGNORE INTO matches (GroupKey, TeamHomeKey, TeamAwayKey, ExternalKey, ScheduleDate) VALUES ";
            $insertQuery .= "($groupKey,";
            $insertQuery .= "$teamHomeKey,";
            $insertQuery .= "$teamAwayKey,";
            $insertQuery .= "$lfpMatchKey,";
            $insertQuery .= "FROM_UNIXTIME($scheduleDate)) ";

            $queries[]=$insertQuery;

            $updateQuery = "UPDATE matches SET ExternalKey=$lfpMatchKey, ScheduleDate=FROM_UNIXTIME($scheduleDate) WHERE GroupKey=";
            $updateQuery .= "$groupKey AND TeamHomeKey=$teamHomeKey AND TeamAwayKey=$teamAwayKey";
            $queries[]=$updateQuery;


          }
        }
        $currentTable++;
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

  if ($rowSet["isVoteClosed"]=="1") {
    $updateQuery = "UPDATE matches SET IsBonusMatch=1 
 WHERE PrimaryKey IN 
          (SELECT TMP2.MatchKey FROM (SELECT TMP.MatchKey,TMP.GlobalVoteValue  FROM 
                                    (SELECT matches.PrimaryKey MatchKey,
                                            groups.PrimaryKey GroupKey,
                                            (SELECT SUM(GlobalVotes.value) FROM votes GlobalVotes WHERE GlobalVotes.MatchKey=matches.PrimaryKey) GlobalVoteValue,
                                            (SELECT COUNT(GlobalVotes.value) FROM votes GlobalVotes WHERE GlobalVotes.MatchKey=matches.PrimaryKey) GlobalVoteCount
                                      FROM matches
                                     INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
                                     WHERE matches.GroupKey = $groupKey
                                    ) TMP 
                                      ORDER BY TMP.GlobalVoteValue desc
                                LIMIT 0,1) TMP2)";
    $queries[]=$updateQuery;
  }
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
	