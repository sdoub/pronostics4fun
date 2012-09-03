#!/usr/local/bin/php
<?php
require_once(dirname(__FILE__)."/begin.file.php");
require_once(BASE_PATH . "/lib/simple_html_dom.php");

//$_databaseObject->close();
$writeScript=true;

if (isset($_GET["Season"])){
$writeScript=false;
  try {
    /*** connect to SQLite database ***/
    $ligue1db  = new PDO("sqlite:data/ligue1.db");
    $ligue1db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //var_dump($ligue1db);

//    $arrSeasons = array(
//     1 => "69",
//     2 => "70",
//     3 => "71",
//     4 => "72",
//     5 => "73",
//     6 => "74",
//     7 => "75",
//     8 => "76",
//     9 => "77",
//     10 => "78",
//     11 => "79");

    $arrSeasons = array(
     12 => "80");

    $arrGroups = array(
    1 => "1ère journée",
    2 => "2ème journée",
    3 => "3ème journée",
    4 => "4ème journée",
    5 => "5ème journée",
    6 => "6ème journée",
    7 => "7ème journée",
    8 => "8ème journée",
    9 => "9ème journée",
    10 => "10ème journée",
    11 => "11ème journée",
    12 => "12ème journée",
    13 => "13ème journée",
    14 => "14ème journée",
    15 => "15ème journée",
    16 => "16ème journée",
    17 => "17ème journée",
    18 => "18ème journée",
    19 => "19ème journée",
    20 => "20ème journée",
    21 => "21ème journée",
    22 => "22ème journée",
    23 => "23ème journée",
    24 => "24ème journée",
    25 => "25ème journée",
    26 => "26ème journée",
    27 => "27ème journée",
    28 => "28ème journée",
    29 => "29ème journée",
    30 => "30ème journée",
    31 => "31ème journée",
    32 => "32ème journée",
    33 => "33ème journée",
    34 => "34ème journée",
    35 => "35ème journée",
    36 => "36ème journée",
    37 => "37ème journée",
    38 => "38ème journée");


    while (list ($Competitionkey, $lfpKey) = each ($arrSeasons)) {

      print($Competitionkey);
      print("<br/>");

      print($lfpKey);
      print("<br/>");
      $numberOfDays=0;
      $query = "SELECT COUNT(*) NumberOfDays
            FROM groups
            WHERE CompetitionKey=".$Competitionkey;

      $statement = $ligue1db->prepare($query);

      $statement->execute();
      $resultSet = $statement->fetchAll();

      foreach($resultSet as $rowSet)
      {
        $numberOfDays =$rowSet["NumberOfDays"];
      }
   print ($numberOfDays);
      print ('<br/>');
      if ($numberOfDays==0) {
        print("Creation des groups");
        while (list ($Daykey, $GroupDescription) = each ($arrGroups)) {
          $query="INSERT INTO groups (PrimaryKey,Description,Code,CompetitionKey,IsCompleted,DayKey)
        VALUES (NULL,'$GroupDescription','J$Daykey',$Competitionkey,0,$Daykey)";
          $statement = $ligue1db->prepare($query);
          $statement->execute();
        }
      }


      $query = "SELECT PrimaryKey GroupKey, DayKey, CompetitionKey
                FROM groups
               WHERE CompetitionKey=$Competitionkey AND IsCompleted=0";

      $statement = $ligue1db->prepare($query);

      $statement->execute();
      $rowsSet = $statement->fetchAll();

      $queries = array();
      $matches= "";
      foreach ($rowsSet as $rowSet)
      {
        $groupKey= $rowSet["GroupKey"];
        $dayKey= $rowSet["DayKey"];

        //Get information from lfp
        //ligue1/competitionPluginCalendrierResultat/changeCalendrierJournee?sai=79&jour=2
        $url = "http://". EXTERNAL_WEB_SITE . "/ligue1/competitionPluginCalendrierResultat/changeCalendrierJournee?sai=$lfpKey&jour=$dayKey";
        print($url);
        if ($html = file_get_html($url))
        {
          $scheduleDates = array();
          foreach($html->find('h4') as $h4) {
            $scheduleDates[]=ConvertFrenchDateToUniversalDate($h4->plaintext);
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
                if ($teamHomeKey==0) {
                  print ("The team " .$rows->find('td',1)->plaintext . " is not created<br/>");
                  $team = str_replace( "'", "''", trim(str_replace("\n", "", $rows->find('td',1)->plaintext)));
                  $insertQuery = "INSERT OR IGNORE INTO teams (PrimaryKey, Name, Code) VALUES (NULL, '$team','$lfpTeamHomeKey')";
                  $queries[]=$insertQuery;
                  $teamHomeKey = "(SELECT teams.PrimaryKey FROM teams WHERE Code='$lfpTeamHomeKey')";
                }

                if ($rows->find('td',4)->first_child()->hasAttribute("src")) {
                  $lfpTeamAway = split('/',$rows->find('td',4)->first_child()->getAttribute("src"));
                } else {
                  $lfpTeamAway = split('/',$rows->find('td',4)->first_child()->first_child()->getAttribute("src"));
                }
                $lfpTeamAwayKey = substr($lfpTeamAway[6], 0, -4);

                $teamAwayKey = ConvertLfpKeyToP4F ($lfpTeamAwayKey);
                if ($teamAwayKey==0) {
                  print ("The team " .$rows->find('td',5)->plaintext . " is not created<br/>");
                  $team = str_replace( "'", "''", trim(str_replace("\n", "", $rows->find('td',5)->plaintext)));
                  $insertQuery = "INSERT OR IGNORE INTO teams (PrimaryKey, Name, Code) VALUES (NULL, '$team','$lfpTeamAwayKey')";
                  $queries[]=$insertQuery;
                  $teamAwayKey = "(SELECT teams.PrimaryKey FROM teams WHERE Code='$lfpTeamAwayKey')";
                }


                $insertQuery = "INSERT OR IGNORE INTO matches (GroupKey, TeamHomeKey, TeamAwayKey, ExternalKey, ScheduleDate) VALUES ";
                $insertQuery .= "($groupKey,";
                $insertQuery .= "$teamHomeKey,";
                $insertQuery .= "$teamAwayKey,";
                $insertQuery .= "$lfpMatchKey,";
                $insertQuery .= "datetime($scheduleDate, 'unixepoch')) ";

                $queries[]=$insertQuery;

                $updateQuery = "UPDATE matches SET ExternalKey=$lfpMatchKey, ScheduleDate=datetime($scheduleDate, 'unixepoch') WHERE GroupKey=";
                $updateQuery .= "$groupKey AND TeamHomeKey=$teamHomeKey AND TeamAwayKey=$teamAwayKey";
                $queries[]=$updateQuery;

                //$_databaseObject -> queryPerf ($insertQuery , "Get matches according the current group");
                $matches .= "<match teamHomeId='$teamHomeKey' teamAwayId='$teamAwayKey'   date='$scheduleDate' />";

              }
            }
            $currentTable++;
          }
          $updateQuery = "UPDATE groups SET IsCompleted=1 WHERE PrimaryKey=$groupKey";
          $queries[]=$updateQuery;
        }
        $html->clear();
        unset($html);


      }

      foreach ($queries as $query) {
        print($query);
        print("<br/>");
        $statement = $ligue1db->prepare($query);
        $statement->execute();
      }

    }
    $ligue1db = null;
    unset($ligue1db);
  }
  catch(PDOException $e)
  {
    echo $e->getMessage();
  }
} else {

  try {
    /*** connect to SQLite database ***/
    $ligue1db  = new PDO("sqlite:data/ligue1.db");
    $ligue1db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query= "SELECT
              matches.PrimaryKey MatchKey,
              matches.GroupKey,
              matches.TeamHomeKey,
              matches.TeamAwayKey,
              matches.ExternalKey
         FROM matches
         WHERE matches.Status=0
         LIMIT 0,1";

    $statement = $ligue1db->prepare($query);

    $statement->execute();
    $rowsSet = $statement->fetchAll();

    $_queries = array();
    foreach ($rowsSet as $rowSet)
    {
      $_teamHomeKey = $rowSet["TeamHomeKey"];
      $_teamAwayKey = $rowSet["TeamAwayKey"];
      $_externalKey = $rowSet["ExternalKey"];
      $matchKey = $rowSet["MatchKey"];
      $_error="";

      $urlToGetMatchInfo = "http://" .EXTERNAL_WEB_SITE . "/ligue1/feuille_match/$_externalKey";
      echo "<br/>" . $urlToGetMatchInfo;
      if ($htmlMatch = file_get_html($urlToGetMatchInfo)){

        $liveStatus = 10;
        $actualTime = "90";
        $homeGoals =0;
        $awayGoals =0;


        foreach($htmlMatch->find('div.club_dom span.buts') as $item) {
          $homeGoals = $item->innertext;
        }

        foreach($htmlMatch->find('div.club_ext span.buts') as $item) {
          $awayGoals = $item->innertext;
        }

        if ($homeGoals=="") {
          echo "Match reporté</br>";
          $writeScript = false;
        }
        $updateQuery = "INSERT OR IGNORE INTO results (MatchKey, LiveStatus, ActualTime, ResultDate) VALUES ($matchKey, $liveStatus, $actualTime, date('now'))";
        $_queries[]=$updateQuery;
        $updateQuery = "UPDATE results SET LiveStatus=$liveStatus, ActualTime=$actualTime, ResultDate=date('now') WHERE MatchKey=$matchKey";
        $_queries[]=$updateQuery;

        $updateQuery = "DELETE FROM events WHERE ResultKey=(SELECT PrimaryKey ResultKey
      			    FROM results
      			   WHERE results.MatchKey=$matchKey)";
        $resultKey="(SELECT PrimaryKey ResultKey
      			    FROM results
      			   WHERE results.MatchKey=$matchKey)";
        $_queries[]=$updateQuery;

        foreach($htmlMatch->find('div.#buts ul.club_dom li') as $items) {

          $liItem = $items->find('span.icon') ;
          if ($liItem) {
            $eventTime = $items->innertext;
            $eventType = trim(substr($eventTime,strpos($eventTime,"</a>")+4));
            $eventTime = trim(substr($eventTime,strpos($eventTime,"</span>")+7,strpos($eventTime,"<a ")-strpos($eventTime,"</span>")-7));
            $eventAdditionalTime = substr($eventTime,0,strpos($eventTime,":"));
            if (strpos($eventAdditionalTime,"+")>0) {
              $eventAdditionalTime = trim(substr($eventTime,strpos($eventTime,"+"),strpos($eventTime,":")));
            }
            else {
              $eventAdditionalTime = 0;
            }
            echo '$eventAdditionalTime :' . $eventAdditionalTime;

            $eventTime = substr($eventTime,0,strpos($eventTime,"'"));
            if ((int)$eventTime>45) {
              $half = 3;
            }
            else {
              $half = 1;
            }

            $teamPlayer = $items->find('a',0)->plaintext;

            switch ($eventType) {
              case "(Pén)":
                $eventType="2";
                break;
              case "(csc)":
                $eventType="3";
                break;
              default:
                $eventType="1";
                break;
            }

            $updateQuery = "INSERT OR IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
            $_queries[]=$updateQuery;
            $eventTime += $eventAdditionalTime;
            $updateQuery = "INSERT OR IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamHomeKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "'))";
            $_queries[]=$updateQuery;
            $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
            $updateQuery = "UPDATE events SET ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey WHERE ResultKey=$resultKey AND TeamKey=$_teamHomeKey AND EventType=$eventType AND EventTime=$eventTime";
            $_queries[]=$updateQuery;

          }
          else { // assists
            $teamPlayer = $items->find('span',0)->plaintext;
            $teamPlayer = substr($teamPlayer,10);
            $eventType="4";

            $updateQuery = "INSERT OR IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
            $_queries[]=$updateQuery;
            $updateQuery = "INSERT OR IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamHomeKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "'))";
            $_queries[]=$updateQuery;
            $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
            $updateQuery = "UPDATE events SET ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey WHERE ResultKey=$resultKey AND TeamKey=$_teamHomeKey AND EventType=$eventType AND EventTime=$eventTime";
            $_queries[]=$updateQuery;
          }
        }


        foreach($htmlMatch->find('div.#buts ul.club_ext li') as $items) {

          $liItem = $items->find('span.icon') ;
          if ($liItem) {

            $eventTime = $items->innertext;
            $eventType = trim(substr($eventTime,strpos($eventTime,"</a>")+4));
            $eventTime = trim(substr($eventTime,strpos($eventTime,"</span>")+7,strpos($eventTime,"<a ")-strpos($eventTime,"</span>")-7));
            $eventAdditionalTime = substr($eventTime,0,strpos($eventTime,":"));
            if (strpos($eventAdditionalTime,"+")>0) {
              $eventAdditionalTime = trim(substr($eventTime,strpos($eventTime,"+"),strpos($eventTime,":")));
            }
            else {
              $eventAdditionalTime = 0;
            }
            echo '$eventAdditionalTime :' . $eventAdditionalTime;

            $eventTime = substr($eventTime,0,strpos($eventTime,"'"));
            if ((int)$eventTime>45) {
              $half = 3;
            }
            else {
              $half = 1;
            }

            $teamPlayer = $items->find('a',0)->plaintext;

            switch ($eventType) {
              case "(Pén)":
                $eventType="2";
                break;
              case "(csc)":
                $eventType="3";
                break;
              default:
                $eventType="1";
                break;
            }

            $updateQuery = "INSERT OR IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
            $_queries[]=$updateQuery;
            $eventTime += $eventAdditionalTime;
            $updateQuery = "INSERT OR IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamAwayKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "'))";
            $_queries[]=$updateQuery;
            $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
            $updateQuery = "UPDATE events SET ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey WHERE ResultKey=$resultKey AND TeamKey=$_teamAwayKey AND EventType=$eventType AND EventTime=$eventTime";
            $_queries[]=$updateQuery;
          }
          else { // assists
            $teamPlayer = $items->find('span',0)->plaintext;
            $teamPlayer = substr($teamPlayer,10);
            $eventType="4";

            $updateQuery = "INSERT OR IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
            $_queries[]=$updateQuery;
            $updateQuery = "INSERT OR IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamAwayKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "'))";
            $_queries[]=$updateQuery;
            $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
            $updateQuery = "UPDATE events SET ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey WHERE ResultKey=$resultKey AND TeamKey=$_teamAwayKey AND EventType=$eventType AND EventTime=$eventTime";
            $_queries[]=$updateQuery;
          }
        }



        foreach($htmlMatch->find('div.#cartons ul.club_dom li') as $items) {

          $eventTime = $items->innertext;
          $eventType = trim(substr($eventTime,strpos($eventTime,"</a>")+4));
          $eventTime = trim(substr($eventTime,strpos($eventTime,"</span>")+7,strpos($eventTime,"<a ")-strpos($eventTime,"</span>")-7));
          $eventAdditionalTime = substr($eventTime,0,strpos($eventTime,":"));
          if (strpos($eventAdditionalTime,"+")>0) {
            $eventAdditionalTime = trim(substr($eventTime,strpos($eventTime,"+"),strpos($eventTime,":")));
          }
          else {
            $eventAdditionalTime = 0;
          }
          echo '$eventAdditionalTime :' . $eventAdditionalTime;

          $eventTime = substr($eventTime,0,strpos($eventTime,"'"));
          if ((int)$eventTime>45) {
            $half = 3;
          }
          else {
            $half = 1;
          }

          $teamPlayer = $items->find('a',0)->plaintext;

          $eventType = $items->find('span',0)->plaintext;

          switch ($eventType) {
            case "Carton rouge":
              $eventType="6";
              break;
            default: //"Carton jaune":
              $eventType="5";
              break;
          }

          $updateQuery = "INSERT OR IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
          $_queries[]=$updateQuery;
          $eventTime += $eventAdditionalTime;
          $updateQuery = "INSERT OR IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamHomeKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "'))";
          $_queries[]=$updateQuery;
          $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
          $updateQuery = "UPDATE events SET ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey WHERE ResultKey=$resultKey AND TeamKey=$_teamHomeKey AND EventType=$eventType AND EventTime=$eventTime";
          $_queries[]=$updateQuery;

        }


        foreach($htmlMatch->find('div.#cartons ul.club_ext li') as $items) {

          $liItem = $items->find('span.icon') ;
          if ($liItem) {

            $eventTime = $items->innertext;
            $eventType = trim(substr($eventTime,strpos($eventTime,"</a>")+4));
            $eventTime = trim(substr($eventTime,strpos($eventTime,"</span>")+7,strpos($eventTime,"<a ")-strpos($eventTime,"</span>")-7));
            $eventAdditionalTime = substr($eventTime,0,strpos($eventTime,":"));
            if (strpos($eventAdditionalTime,"+")>0) {
              $eventAdditionalTime = trim(substr($eventTime,strpos($eventTime,"+"),strpos($eventTime,":")));
            }
            else {
              $eventAdditionalTime = 0;
            }
            echo '$eventAdditionalTime :' . $eventAdditionalTime;

            $eventTime = substr($eventTime,0,strpos($eventTime,"'"));
            if ((int)$eventTime>45) {
              $half = 3;
            }
            else {
              $half = 1;
            }

            $teamPlayer = $items->find('a',0)->plaintext;

            $eventType = $items->find('span',0)->plaintext;

            switch ($eventType) {
              case "Carton rouge":
                $eventType="6";
                break;
              default: //"Carton jaune":
                $eventType="5";
                break;
            }

            $updateQuery = "INSERT OR IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
            $_queries[]=$updateQuery;
            $eventTime += $eventAdditionalTime;
            $updateQuery = "INSERT OR IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamAwayKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "'))";
            $_queries[]=$updateQuery;
            $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string($teamPlayer)) . "')";
            $updateQuery = "UPDATE events SET ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey WHERE ResultKey=$resultKey AND TeamKey=$_teamAwayKey AND EventType=$eventType AND EventTime=$eventTime";
            $_queries[]=$updateQuery;

          }
        }

        $updateQuery = "UPDATE matches SET Status=10 WHERE PrimaryKey=$matchKey";
        $_queries[]=$updateQuery;

        $htmlMatch->clear();
        unset($htmlMatch);


      }


    }
    foreach ($_queries as $query) {
      print($query);
      print("<br/>");
      $statement = $ligue1db->prepare($query);
      $statement->execute();
    }

    $ligue1db = null;
    unset($ligue1db);

  }
  catch(PDOException $e)
  {
    echo $e->getMessage();
  }

}
//Open a new connection to execute all queries
//$_databaseObject = new mysql (SQL_HOST, SQL_LOGIN,  SQL_PWD, SQL_DB, $_dbOptions);
//foreach ($queries as $query) {
//  //  print($query);
//  //  print("<br/>");
//  $_databaseObject -> queryPerf ($query , "Execute query");
//}
//$log = "<matches>";
//$log .= $matches;
//$log .= '<errors>';
//$arrDatabaseInfo = $_databaseObject -> get ('xmlQueryPerf', 'xmlErrorLog', '_totalTime','errorLog');

//$xmlErrorLog = $arrDatabaseInfo['xmlErrorLog'];
//if (sizeOf($arrDatabaseInfo["errorLog"])>0) {
//  if ($arrDatabaseInfo["errorLog"]!="") {
//    $_error = true;
//    $_errorMessage="An error occured during queries execution";
//    print_r($arrDatabaseInfo["errorLog"]);
//  }
//}
//$queriesErrors = $xmlErrorLog->getElementsByTagName('queries-errors')->item(0);
//$log .= $xmlErrorLog->saveXML($queriesErrors);
//$log .= '</errors>';
//$log .= "</matches>";
//print (__encode($log));
if ($writeScript) {
echo '<script>
setTimeout( "refresh()", 30*1000 );
var sURL = unescape(window.location.pathname);
function refresh()
{
    window.location.replace( sURL );
}
</script>';
}
require_once(dirname(__FILE__)."/end.file.php");
?>
