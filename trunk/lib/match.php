<?php
include_once(dirname(__FILE__). "/simple_html_dom.php");

function GetMatchInfo ($_teamHomeKey,$_teamAwayKey,$_externalKey,$matchKey) {
  global $_queries;
  $_error="";

  switch (COMPETITION){
    case 1:
      //$urlToGetMatchInfo = "http://live.football365.fr/direct_sybase/3/1/0/0/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_commentmatch.xml";
      //$urlToGetMatchInfoTL = "http://live.football365.fr/direct_sybase/3/1/0/0/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_timeline.xml";
      break;
    case 2:
    case 3:
      //$urlToGetMatchInfo = "http://93.188.131.134/direct_sybase/3/9/9/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_commentmatch.xml";
      //$urlToGetMatchInfoTL = "http://93.188.131.134/direct_sybase/3/9/9/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_timeline.xml";
      //$urlToGetMatchInfo = "http://live.football365.fr/direct_sybase/3/9/9/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_commentmatch.xml";
      //$urlToGetMatchInfoTL = "http://live.football365.fr/direct_sybase/3/9/9/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_timeline.xml";
      $urlToGetMatchInfo = "http://" .EXTERNAL_WEB_SITE . "/ligue1/feuille_match/$_externalKey?live=1";
      break;
  }
  echo "<br/>" . $urlToGetMatchInfo;
  if ($htmlMatch = file_get_html($urlToGetMatchInfo)){

    $liveStatus = "0";
    foreach($htmlMatch->find('div.#periodeMatch') as $item) {
      $liveStatus= $item->innertext;

      switch ($liveStatus) {
        case "1C":
          $liveStatus=1;
          break;
        case "1T":
          $liveStatus=2;
          break;
        case "2C":
          $liveStatus=3;
          break;
        default:
          $liveStatus=0;
          break;

      }
    }
    $actualTime = "0";
    $homeGoals =0;
    $awayGoals =0;
    if ($liveStatus!=0) {
      foreach($htmlMatch->find('div.#minuteDebut') as $item) {
        $beginDate = $item->innertext;
      }
      foreach($htmlMatch->find('div.#periodeMatch') as $item) {
        $periodMatch = $item->innertext;
      }
      foreach($htmlMatch->find('div.#minuteInterruption') as $item) {
        $additionalTime = $item->innertext;
      }
      $matchDateTime = explode(' ',$beginDate);
      $matchDate = explode('-',$matchDateTime[0]);
      $matchTime = explode(':',$matchDateTime[1]);
      date_default_timezone_set('Europe/Paris');
      $currentDate = time();
      $matchDateTime = mktime($matchTime[0],$matchTime[1],$matchTime[2],$matchDate[1],$matchDate[2],$matchDate[0],is_est($currentDate)?1:0);
      $matchCurrentTime = floor(($currentDate - $matchDateTime)/60);
      echo '</br>$currentDate :'.$currentDate;
      echo '</br>$matchDateTime :'.$matchDateTime;
      echo '</br>$periodMatch:'.$periodMatch;
      //minutesJeu = minutesJeu - interruption + tempsBase[periode];
      if($periodMatch == "1C" && $matchCurrentTime > 45) {$matchCurrentTime = 45;}
      if($periodMatch == "2C" && $matchCurrentTime < 55) {$matchCurrentTime += 45;}
      if($periodMatch == "2C" && $matchCurrentTime > 90) {$matchCurrentTime = 90;}
      if($periodMatch == "3C" && $matchCurrentTime > 105) {$matchCurrentTime = 105;}
      if($periodMatch == "4C" && $matchCurrentTime > 120) {$matchCurrentTime = 120;}

      $actualTime = $matchCurrentTime;

    }

    foreach($htmlMatch->find('div.club_dom span.buts') as $item) {
      $homeGoals = $item->innertext;
    }
    foreach($htmlMatch->find('div.club_ext span.buts') as $item) {
      $awayGoals = $item->innertext;
    }

    foreach($htmlMatch->find('div.score p.periode') as $item) {
      $matchState=$item->innertext;
      $matchState= trim(substr($matchState,0,strpos($matchState,"<br")));
      if ($matchState==utf8_encode("Match terminé")) {
        $liveStatus=10;
        $actualTime=90;
      }
    }

    $updateQuery = "INSERT IGNORE INTO results (MatchKey, LiveStatus, ActualTime) VALUES ($matchKey, $liveStatus, $actualTime) ON DUPLICATE KEY UPDATE LiveStatus=$liveStatus, ActualTime=$actualTime";
    $_queries[]=$updateQuery;
    //$_databaseObject -> queryPerf ($updateQuery , "Update match result");
    //    $query = "SELECT PrimaryKey ResultKey
    //      			    FROM results
    //      			   WHERE results.MatchKey=$matchKey";
    //
    //    $resultSet = $_databaseObject -> queryPerf ($query, "Get Result key");
    //    $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
    //
    //    $resultKey= $rowSet["ResultKey"];


    $updateQuery = "DELETE FROM events WHERE ResultKey=(SELECT PrimaryKey ResultKey
      			    FROM results
      			   WHERE results.MatchKey=$matchKey)";

    $resultKey="(SELECT PrimaryKey ResultKey
      			    FROM results
      			   WHERE results.MatchKey=$matchKey)";
    $_queries[]=$updateQuery;

    //$_databaseObject -> queryPerf ($updateQuery , "Delete Events");

    foreach($htmlMatch->find('div.#buts ul.club_dom li') as $items) {

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
        case utf8_encode("(Pén)"):
          $eventType="2";
          break;
        case "(csc)":
          $eventType="3";
          break;
        default:
          $eventType="1";
          break;
      }

      $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
      $_queries[]=$updateQuery;
      //$_databaseObject -> queryPerf ($updateQuery , "Update team player");
      //      $query = "SELECT PrimaryKey TeamPlayerKey
      //    			        FROM teamplayers
      //    			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "'";
      //
      //      $resultSet = $_databaseObject -> queryPerf ($query, "Get team player key");
      //      $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
      //      $teamPlayerKey= $rowSet["TeamPlayerKey"];

      $eventTime += $eventAdditionalTime;
      $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
      $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamHomeKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
      $_queries[]=$updateQuery;
      //$_databaseObject -> queryPerf ($updateQuery , "Update match events");

    }


    foreach($htmlMatch->find('div.#buts ul.club_ext li') as $items) {

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
        case utf8_encode("(Pén)"):
          $eventType="2";
          break;
        case "(csc)":
          $eventType="3";
          break;
        default:
          $eventType="1";
          break;
      }

      $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
      $_queries[]=$updateQuery;
      //      $_databaseObject -> queryPerf ($updateQuery , "Update team player");
      //      $query = "SELECT PrimaryKey TeamPlayerKey
      //      			        FROM teamplayers
      //      			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "'";
      //
      //      $resultSet = $_databaseObject -> queryPerf ($query, "Get team player key");
      //      $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
      //      $teamPlayerKey= $rowSet["TeamPlayerKey"];

      $eventTime += $eventAdditionalTime;
      $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
      $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamAwayKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
      			        FROM teamplayers
      			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
      //$_databaseObject -> queryPerf ($updateQuery , "Update match events");
      $_queries[]=$updateQuery;
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

      $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
      $_queries[]=$updateQuery;
      //      $_databaseObject -> queryPerf ($updateQuery , "Update team player");
      //      $query = "SELECT PrimaryKey TeamPlayerKey
      //    			        FROM teamplayers
      //    			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "'";
      //
      //      $resultSet = $_databaseObject -> queryPerf ($query, "Get team player key");
      //      $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
      //      $teamPlayerKey= $rowSet["TeamPlayerKey"];

      $eventTime += $eventAdditionalTime;
      $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
      $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamHomeKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
      //$_databaseObject -> queryPerf ($updateQuery , "Update match events");
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

        $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
        $_queries[]=$updateQuery;
        //        $_databaseObject -> queryPerf ($updateQuery , "Update team player");
        //        $query = "SELECT PrimaryKey TeamPlayerKey
        //      			        FROM teamplayers
        //      			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "'";
        //
        //        $resultSet = $_databaseObject -> queryPerf ($query, "Get team player key");
        //        $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
        //        $teamPlayerKey= $rowSet["TeamPlayerKey"];

        $eventTime += $eventAdditionalTime;
        $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
        $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamAwayKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
      			        FROM teamplayers
      			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
        //$_databaseObject -> queryPerf ($updateQuery , "Update match events");
        $_queries[]=$updateQuery;
      }
    }


    $htmlMatch->clear();
    unset($htmlMatch);

  }

}
// http://193.201.77.27/ligue1/feuille_match/53716
function GetMatchCompleteInfo ($_teamHomeKey,$_teamAwayKey,$_externalKey,$matchKey) {
  global $_queries;
  $_error="";

  switch (COMPETITION){
    case 1:
      //$urlToGetMatchInfo = "http://live.football365.fr/direct_sybase/3/1/0/0/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_commentmatch.xml";
      //$urlToGetMatchInfoTL = "http://live.football365.fr/direct_sybase/3/1/0/0/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_timeline.xml";
      break;
    case 2:
    case 3:
      //$urlToGetMatchInfo = "http://93.188.131.134/direct_sybase/3/9/9/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_commentmatch.xml";
      //$urlToGetMatchInfoTL = "http://93.188.131.134/direct_sybase/3/9/9/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_timeline.xml";
      //$urlToGetMatchInfo = "http://live.football365.fr/direct_sybase/3/9/9/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_commentmatch.xml";
      //$urlToGetMatchInfoTL = "http://live.football365.fr/direct_sybase/3/9/9/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_timeline.xml";
      $urlToGetMatchInfo = "http://" .EXTERNAL_WEB_SITE . "/ligue1/feuille_match/$_externalKey";
      break;
  }
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

    $updateQuery = "INSERT IGNORE INTO results (MatchKey, LiveStatus, ActualTime) VALUES ($matchKey, $liveStatus, $actualTime) ON DUPLICATE KEY UPDATE LiveStatus=$liveStatus, ActualTime=$actualTime";
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
          case utf8_encode("(Pén)"):
            $eventType="2";
            break;
          case "(csc)":
            $eventType="3";
            break;
          default:
            $eventType="1";
            break;
        }

        $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
        $_queries[]=$updateQuery;
        $eventTime += $eventAdditionalTime;
        $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string(__encode(utf8_decode($teamPlayer)))) . "')";
        $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamHomeKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
        $_queries[]=$updateQuery;


      }
      else { // assists
        $teamPlayer = $items->find('span',0)->plaintext;
        $teamPlayer = substr($teamPlayer,10);
        $eventType="4";

        $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
        $_queries[]=$updateQuery;
        $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string(__encode(utf8_decode($teamPlayer)))) . "')";
        $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamHomeKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
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
          case utf8_encode("(Pén)"):
            $eventType="2";
            break;
          case "(csc)":
            $eventType="3";
            break;
          default:
            $eventType="1";
            break;
        }

        $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
        $_queries[]=$updateQuery;
        $eventTime += $eventAdditionalTime;
        $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string(__encode(utf8_decode($teamPlayer)))) . "')";
        $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamAwayKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
        $_queries[]=$updateQuery;
      }
      else { // assists
        $teamPlayer = $items->find('span',0)->plaintext;
        $teamPlayer = substr($teamPlayer,10);
        $eventType="4";

        $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
        $_queries[]=$updateQuery;
        $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string(__encode(utf8_decode($teamPlayer)))) . "')";
        $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamAwayKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
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

      $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
      $_queries[]=$updateQuery;
      $eventTime += $eventAdditionalTime;
      $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string(__encode(utf8_decode($teamPlayer)))) . "')";
      $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamHomeKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
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

        $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
        $_queries[]=$updateQuery;
        $eventTime += $eventAdditionalTime;
        $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",mysql_real_escape_string(__encode(utf8_decode($teamPlayer)))) . "')";
        $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey) VALUES ($resultKey, $_teamAwayKey, $eventType, $eventTime, $half, (SELECT PrimaryKey TeamPlayerKey
    			        FROM teamplayers
    			       WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')) ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
        $_queries[]=$updateQuery;
      }
    }

    $htmlMatch->clear();
    unset($htmlMatch);

  }

}

function GenerateMatchStates ($_groupKey) {
  global $_databaseObject;
  $query = "SELECT matches.PrimaryKey MatchKey, matches.GroupKey, (UNIX_TIMESTAMP(matches.ScheduleDate)+ (events.EventTime *60)) ScheduleDate, events.PrimaryKey EventKey,
   IF (events.TeamKey=matches.TeamHomeKey,1,0) isHomeGoal, 1 'isGoalEvent' FROM events
  INNER JOIN results ON results.PrimaryKey = events.ResultKey
  INNER JOIN matches ON matches.PrimaryKey = results.MatchKey
  INNER JOIN groups ON groups.PrimaryKey = matches.GroupKey AND groups.PrimaryKey=$_groupKey
  WHERE events.EventType IN (1,2,3)
UNION ALL
SELECT matches.PrimaryKey, matches.GroupKey, UNIX_TIMESTAMP(matches.ScheduleDate),1, 1,0
  FROM matches
  INNER JOIN groups ON groups.PrimaryKey = matches.GroupKey AND groups.PrimaryKey=$_groupKey
UNION ALL
SELECT matches.PrimaryKey, matches.GroupKey, UNIX_TIMESTAMP(matches.ScheduleDate)+ (100 *60),2, 1,0
  FROM matches
  INNER JOIN groups ON groups.PrimaryKey = matches.GroupKey AND groups.PrimaryKey=$_groupKey
  INNER JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus=10
ORDER BY 3,4";

  $resultSet = $_databaseObject->queryPerf($query,"Get Group State");

  $arrMatches = array();

  while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {
    if (array_key_exists($rowSet["MatchKey"],$arrMatches)) {

      $tempArray = $arrMatches[$rowSet["MatchKey"]];
      $tempArray["GroupKey"]=$rowSet["GroupKey"];

      $tempArray["ScheduleDate"]=$rowSet["ScheduleDate"];

      if ($rowSet["isGoalEvent"]==1) {
        if ($rowSet["isHomeGoal"] == 1) {
          $tempArray["TeamHomeScore"]+=1;
        } else {
          $tempArray["TeamAwayScore"]+=1;
        }
      }

      $arrMatches[$rowSet["MatchKey"]]=$tempArray;
    }
    else {

      $tempArray = array();
      $tempArray["GroupKey"]=$rowSet["GroupKey"];

      $tempArray["ScheduleDate"]=$rowSet["ScheduleDate"];

      if ($rowSet["isGoalEvent"]==1) {
        if ($rowSet["isHomeGoal"] == 1) {
          $tempArray["TeamHomeScore"]=1;
          $tempArray["TeamAwayScore"]=0;
        } else {
          $tempArray["TeamHomeScore"]=0;
          $tempArray["TeamAwayScore"]=1;
        }
      } else {
        $tempArray["TeamHomeScore"]=0;
        $tempArray["TeamAwayScore"]=0;
      }

      $arrMatches[$rowSet["MatchKey"]]=$tempArray;

    }

    $insertQuery= "INSERT IGNORE INTO matchstates (MatchKey, StateDate, EventKey, TeamHomeScore, TeamAwayScore) VALUES (" . $rowSet["MatchKey"] . ",FROM_UNIXTIME(" . $rowSet["ScheduleDate"] . ")," .$rowSet["EventKey"] .",". $tempArray["TeamHomeScore"] . "," . $tempArray["TeamAwayScore"] . ") ON DUPLICATE KEY UPDATE TeamHomeScore= ".$tempArray["TeamHomeScore"]. ", TeamAwayScore= ".$tempArray["TeamAwayScore"];

    $_databaseObject->queryPerf($insertQuery,"Insert Match State");
  }
}