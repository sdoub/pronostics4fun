#!/usr/local/bin/php
<?php
use Sunra\PhpSimple\HtmlDomParser;

require_once(dirname(__FILE__)."/begin.file.php");


$query = "SELECT PrimaryKey GroupKey,DayKey,Status
            FROM groups
           WHERE IsCompleted=0 AND CompetitionKey = ".COMPETITION."
           LIMIT 0,4";
$matches = "";
$rowsSet = $_databaseObject -> queryGetFullArray ($query, "Get all groups of the current competition");
$_databaseObject->close();

$queries = array();

foreach ($rowsSet as $rowSet)
{
  $groupKey= $rowSet["GroupKey"];
  $dayKey= $rowSet["DayKey"];

  //Get information from uefa
  $isGroupScheduled = true;

  $_error = "";


  $url = "http://fr.uefa.com/uefaeuro/season=2016/standings/round=2000448/group=$dayKey/index.html";

  print($url);
  if ($html = HtmlDomParser::file_get_html($url))
  {
    {
      //      $scheduleDates = array();
      //      foreach($html->find('h4') as $h4) {
      //        $scheduleDates[]=ConvertFrenchDateToUniversalDate(utf8_decode($h4->plaintext));
      //      }

      //      $currentTable = 0;
      foreach($html->find('div.listmatches table') as $table) {
        $numberOfTr = 0;
        foreach($table->find('tr') as $row) {

          if ($numberOfTr==0) {
            $rawDate = $row->find('div.sup-left',0)->first_child ();
            $scheduleDates=ConvertFrenchDateToUniversalDate(utf8_decode($rawDate->plaintext));
          }
          if ($numberOfTr==1) {
            $matchDetail = $row->find('td',2);
            $uefaMatchUrl = split('/',$matchDetail->find('a',0)->getAttribute("href"));
            $uefaMatchKey= str_replace("match=", "", $uefaMatchUrl[5]);
            $hour = str_replace(".",":",$matchDetail->find('a',0)->plaintext);
            $hourMatchArray = split(':',$hour);
            $dateMatchArray = split('/',$scheduleDates);
            $hours = (int)$hourMatchArray[0]==0?19:(int)$hourMatchArray[0];
            $minutes = $hourMatchArray[1];
            $scheduleDate = mktime((int)$hours, (int)$minutes, 0, (int)$dateMatchArray[1], (int)$dateMatchArray[0], (int)$dateMatchArray[2]);

            $matchTeamHome = $row->find('td',0);
            $uefaTeamHome = split('/',$matchTeamHome->find('a',0)->getAttribute("href"));
            $uefaTeamHomeKey = str_replace ("team=","",$uefaTeamHome[4]);
            $teamHomeKey = ConvertUefaKeyToP4F ($uefaTeamHomeKey);

            $matchTeamAway = $row->find('td',4);
            $uefaTeamAway = split('/',$matchTeamAway->find('a',0)->getAttribute("href"));
            $uefaTeamAwayKey = str_replace ("team=","",$uefaTeamAway[4]);
            $teamAwayKey = ConvertUefaKeyToP4F ($uefaTeamAwayKey);

            $insertQuery = "INSERT IGNORE INTO matches (GroupKey, TeamHomeKey, TeamAwayKey, ExternalKey, ScheduleDate) VALUES ";
            $insertQuery .= "($groupKey,";
            $insertQuery .= "$teamHomeKey,";
            $insertQuery .= "$teamAwayKey,";
            $insertQuery .= "$uefaMatchKey,";
            $insertQuery .= "FROM_UNIXTIME($scheduleDate)) ";

            $queries[]=$insertQuery;

            $matchStatus=0;
            $updateQuery = "UPDATE matches SET ExternalKey=$uefaMatchKey, ScheduleDate=FROM_UNIXTIME($scheduleDate), Status=$matchStatus WHERE GroupKey=";
            $updateQuery .= "$groupKey AND TeamHomeKey=$teamHomeKey AND TeamAwayKey=$teamAwayKey";
            $queries[]=$updateQuery;
          }
          $numberOfTr++;
          if ($numberOfTr==4){
            $numberOfTr=0;
          }
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
