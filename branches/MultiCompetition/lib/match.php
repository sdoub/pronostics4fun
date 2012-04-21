<?php
include_once(dirname(__FILE__). "/simple_html_dom.php");

function GetMatchInfo ($_teamHomeKey,$_teamAwayKey,$_externalKey,$matchKey,$isLive) {

  $queries = array();
  $objectReturn = array();
  $_error="";

  switch (COMPETITION){
    case 1:
      break;
    case 2:
    case 3:
      if ($isLive) {
        $urlToGetMatchInfo = "http://" .EXTERNAL_WEB_SITE . "/ligue1/feuille_match/$_externalKey?live=1";
      } else {
        $urlToGetMatchInfo = "http://" .EXTERNAL_WEB_SITE . "/ligue1/feuille_match/$_externalKey";
      }
      break;
  }
  $objectReturn["urlToGetMatchInfo"]= $urlToGetMatchInfo;
  $homeId="";
  $awayId="";
  if ($htmlMatch = file_get_html($urlToGetMatchInfo)){


    $homeId = $htmlMatch->getElementById('#dom_id_hidden')->getAttribute("value");
    $awayId = $htmlMatch->getElementById('#ext_id_hidden')->getAttribute("value");

    $liveStatus = "0";
    $homeGoals =0;
    $awayGoals =0;
    if ($isLive) {
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
        $objectReturn["CurrentDate"] = $currentDate;
        $objectReturn["MatchDateTime"]  = $matchDateTime;
        $objectReturn["PeriodMatch"]  = $periodMatch;
        //minutesJeu = minutesJeu - interruption + tempsBase[periode];
        if($periodMatch == "1C" && $matchCurrentTime > 45) {$matchCurrentTime = 45;}
        if($periodMatch == "2C" && $matchCurrentTime < 55) {$matchCurrentTime += 45;}
        if($periodMatch == "2C" && $matchCurrentTime > 90) {$matchCurrentTime = 90;}
        if($periodMatch == "3C" && $matchCurrentTime > 105) {$matchCurrentTime = 105;}
        if($periodMatch == "4C" && $matchCurrentTime > 120) {$matchCurrentTime = 120;}

        $actualTime = $matchCurrentTime;

      }
      foreach($htmlMatch->find('div.score p.periode') as $item) {
        $matchState=$item->innertext;
        $matchState= trim(substr($matchState,0,strpos($matchState,"<br")));
        if ($matchState==utf8_encode("Match termin�")) {
          $liveStatus=10;
          $actualTime=90;
        }
      }
    } else {
      $liveStatus = 10;
      $actualTime = 90;
    }

    foreach($htmlMatch->find('div.club_dom span.buts') as $item) {
      $homeGoals = $item->innertext;
    }

    foreach($htmlMatch->find('div.club_ext span.buts') as $item) {
      $awayGoals = $item->innertext;
    }

    $updateQuery = "INSERT IGNORE INTO results (MatchKey, LiveStatus, ActualTime) VALUES ($matchKey, $liveStatus, $actualTime) ON DUPLICATE KEY UPDATE LiveStatus=$liveStatus, ActualTime=$actualTime";
    $queries[]=$updateQuery;

    $updateQuery = "DELETE FROM events WHERE ResultKey=(SELECT PrimaryKey ResultKey
      			    FROM results
      			   WHERE results.MatchKey=$matchKey)";

    $resultKey="(SELECT PrimaryKey ResultKey
      			    FROM results
      			   WHERE results.MatchKey=$matchKey)";

    $queries[]=$updateQuery;

    foreach($htmlMatch->find('div.#buts ul') as $ulitems) {
      $isHome=$ulitems->getAttribute('class') == 'club_dom';
      foreach($ulitems->find('li') as $items) {

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

          $eventTime = substr($eventTime,0,strpos($eventTime,"'"));
          if ((int)$eventTime>45) {
            $half = 3;
          }
          else {
            $half = 1;
          }

          $teamPlayer = $items->find('a',0)->plaintext;

          switch ($eventType) {
            case utf8_encode("(P�n)"):
              $eventType="2";
              break;
            case "(csc)":
              $eventType="3";
              break;
            default:
              $eventType="1";
              break;
          }

          $eventTime += $eventAdditionalTime;
        }
        else { // assists
          $teamPlayer = $items->find('span',0)->plaintext;
          $teamPlayer = substr($teamPlayer,10);
          $eventType="4";

        }

        $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
        $queries[]=$updateQuery;
        $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
        if ($isHome) {
          $teamKey = $_teamHomeKey;
        } else {
          $teamKey = $_teamAwayKey;
        }
        $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey)
        VALUES ($resultKey, $teamKey, $eventType, $eventTime, $half, $teamPlayerKey)
        ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
        $queries[]=$updateQuery;

      }

      foreach($htmlMatch->find('div.#cartons ul') as $ulitems) {
        $isHome = $ulitems->getAttribute('class') == 'club_dom';
        foreach($ulitems->find('li') as $items) {

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
          $queries[]=$updateQuery;

          $eventTime += $eventAdditionalTime;
          $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
          if ($isHome) {
            $teamKey = $_teamHomeKey;
          } else {
            $teamKey = $_teamAwayKey;
          }

          $updateQuery = "INSERT IGNORE INTO events (ResultKey, TeamKey, EventType, EventTime, Half, TeamPlayerKey)
        VALUES ($resultKey, $teamKey, $eventType, $eventTime, $half, $teamPlayerKey)
        ON DUPLICATE KEY UPDATE ResultKey=$resultKey,TeamPlayerKey=$teamPlayerKey";
          $queries[]=$updateQuery;
        }
      }
    }


    $htmlMatch->clear();
    unset($htmlMatch);

  }

  $objectReturn["HomeId"] = $homeId;
  $objectReturn["AwayId"] = $awayId;
  $objectReturn["Queries"] = $queries;
  return $objectReturn;

}

function GetMatchCompleteInfo ($_teamHomeKey,$_teamAwayKey,$_externalKey,$matchKey) {
  global $_queries;
  $_error="";

  switch (COMPETITION){
    case 1:
      break;
    case 2:
    case 3:
      $urlToGetMatchInfo = "http://" .EXTERNAL_WEB_SITE . "/ligue1/feuille_match/$_externalKey";
      break;
  }
  $objectReturn["UrlToGetMatchInfo"]= $urlToGetMatchInfo;
  $homeId="";
  $awayId="";
  if ($htmlMatch = file_get_html($urlToGetMatchInfo)){


    $homeId = $htmlMatch->getElementById('#dom_id_hidden')->getAttribute("value");
    $awayId = $htmlMatch->getElementById('#ext_id_hidden')->getAttribute("value");

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

        $eventTime = substr($eventTime,0,strpos($eventTime,"'"));
        if ((int)$eventTime>45) {
          $half = 3;
        }
        else {
          $half = 1;
        }

        $teamPlayer = $items->find('a',0)->plaintext;

        switch ($eventType) {
          case utf8_encode("(P�n)"):
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

        $eventTime = substr($eventTime,0,strpos($eventTime,"'"));
        if ((int)$eventTime>45) {
          $half = 3;
        }
        else {
          $half = 1;
        }

        $teamPlayer = $items->find('a',0)->plaintext;

        switch ($eventType) {
          case utf8_encode("(P�n)"):
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
  $objectReturn = array();
  $objectReturn["HomeId"] = $homeId;
  $objectReturn["AwayId"] = $awayId;
  return $objectReturn;
}

function GetMatchsLineupsInfo ($_teamHomeKey,$_teamAwayKey,$_externalKey,$matchKey,$isLive,$homeId,$awayId){
  $queries = array();
  $objectReturn = array();

  $_error="";

  switch (COMPETITION){
    case 1:
      //$urlToGetMatchInfo = "http://live.football365.fr/direct_sybase/3/1/0/0/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_commentmatch.xml";
      //$urlToGetMatchInfoTL = "http://live.football365.fr/direct_sybase/3/1/0/0/" . $_groupFootball365Key . "/" . $_matchFootball365Key . "_timeline.xml";
      break;
    case 2:
    case 3:
      if ($isLive) {
        $urlToGetMatchInfo = "http://" .EXTERNAL_WEB_SITE . "/ligue1/feuille_match/showInfosMatch?matchId=$_externalKey&domId=$homeId&extId=$awayId&live=1";
      } else {
        $urlToGetMatchInfo = "http://" .EXTERNAL_WEB_SITE . "/ligue1/feuille_match/showInfosMatch?matchId=$_externalKey&domId=$homeId&extId=$awayId&live=0";
      }
      break;
  }
  if ($htmlMatch = file_get_html($urlToGetMatchInfo)){

    $currentDiv=0;
    foreach($htmlMatch->find('div.domicile') as $players) {
      if ($currentDiv==0){
        foreach($players->find('ul') as $homeDivPermanent) {
          $teamPlayer="";
          foreach($homeDivPermanent->find('li') as $homePermanentPlayer) {

            $lineUpInfo = $homePermanentPlayer->innertext;
            $timeIn = trim(substr($lineUpInfo,0,strpos($lineUpInfo,"<a")));
            $timeIn = str_replace("(", "", $timeIn);
            $timeIn = str_replace(")", "", $timeIn);
            $timeIn = str_replace("'", "", $timeIn);
            $isSubstitute = 0;
            $playerDetailReplaced="";
            if ($timeIn=="") {
              $isSubstitute = 0;
              $timeIn="NULL";
            } else {
              $isSubstitute = 1;
              $playerDetailReplaced = $teamPlayer;
              if (strpos($timeIn,"+")) {
                $timeArray = explode("+",$timeIn);
                $timeIn = $timeArray[0] + $timeArray[1];
              }
            }

            $currentSpan=0;
            foreach($homePermanentPlayer->find('span') as $homeSpanPermanentPlayer) {
              if ($currentSpan==0) {
                $teamPlayer = str_replace("(c) ", "", $homeSpanPermanentPlayer->plaintext);

              }
              $currentSpan++;
            }
            $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
            $queries[]=$updateQuery;

            $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
            $teamPlayerReplacedKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($playerDetailReplaced))) . "')";
            $updateQuery = "INSERT IGNORE INTO lineups (MatchKey, TeamKey, TeamPlayerKey, IsSubstitute, TimeIn, TeamPlayerReplacedKey)
            VALUES ($matchKey, $_teamHomeKey, $teamPlayerKey,$isSubstitute,$timeIn,$teamPlayerReplacedKey)
            ON DUPLICATE KEY UPDATE TimeIn=$timeIn, TeamPlayerReplacedKey=$teamPlayerReplacedKey";
            $queries[]=$updateQuery;

          }
        }
      }
      if ($currentDiv==1){
        foreach($players->find('ul') as $homeDivSubstitute) {
          $teamPlayer="";
          foreach($homeDivSubstitute->find('li') as $homeSubstitutePlayer) {

            $isSubstitute = 1;
            $timeIn="NULL";

            foreach($homeSubstitutePlayer->find('a') as $homeSpanSubstitutePlayer) {
              $teamPlayer = $homeSpanSubstitutePlayer->innertext;
              $teamPlayer = trim(substr($teamPlayer,0,strpos($teamPlayer,"<span")));
            }
            $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
            $queries[]=$updateQuery;

            $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
            $updateQuery = "INSERT IGNORE INTO lineups (MatchKey, TeamKey, TeamPlayerKey, IsSubstitute) VALUES ($matchKey, $_teamHomeKey, $teamPlayerKey,$isSubstitute)";
            $queries[]=$updateQuery;

          }
        }
      }
      $currentDiv++;
    }

    $currentDiv=0;
    foreach($htmlMatch->find('div.exterieur') as $players) {
      if ($currentDiv==0){
        foreach($players->find('ul') as $awayDivPermanent) {
          $teamPlayer="";
          foreach($awayDivPermanent->find('li') as $awayPermanentPlayer) {

            $lineUpInfo = $awayPermanentPlayer->innertext;
            $timeIn = trim(substr($lineUpInfo,strpos($lineUpInfo,"</a>")+4));
            $timeIn = str_replace("(", "", $timeIn);
            $timeIn = str_replace(")", "", $timeIn);
            $timeIn = str_replace("'", "", $timeIn);
            $isSubstitute = 0;
            $playerDetailReplaced="";
            if ($timeIn=="") {
              $isSubstitute = 0;
              $timeIn="NULL";
            } else {
              $isSubstitute = 1;
              $playerDetailReplaced = $teamPlayer;
              if (strpos($timeIn,"+")) {
                $timeArray = explode("+",$timeIn);
                $timeIn = $timeArray[0] + $timeArray[1];
              }
            }

            $currentSpan=0;
            foreach($awayPermanentPlayer->find('span') as $awaySpanPermanentPlayer) {
              if ($currentSpan==1) {
                $teamPlayer = str_replace(" (c)", "", $awaySpanPermanentPlayer->plaintext);

              }
              $currentSpan++;
            }
            $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
            $queries[]=$updateQuery;

            $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
            $teamPlayerReplacedKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($playerDetailReplaced))) . "')";
            $updateQuery = "INSERT IGNORE INTO lineups (MatchKey, TeamKey, TeamPlayerKey, IsSubstitute, TimeIn, TeamPlayerReplacedKey)
            VALUES ($matchKey, $_teamAwayKey, $teamPlayerKey,$isSubstitute,$timeIn,$teamPlayerReplacedKey)
            ON DUPLICATE KEY UPDATE TimeIn=$timeIn, TeamPlayerReplacedKey=$teamPlayerReplacedKey";
            $queries[]=$updateQuery;

          }
        }
      }
      if ($currentDiv==1){
        foreach($players->find('ul') as $awayDivSubstitute) {
          $teamPlayer="";
          foreach($awayDivSubstitute->find('li') as $awaySubstitutePlayer) {

            $isSubstitute = 1;
            $timeIn="NULL";

            foreach($awaySubstitutePlayer->find('a') as $awaySpanSubstitutePlayer) {
              $teamPlayer = $awaySpanSubstitutePlayer->innertext;
              $teamPlayer = trim(substr($teamPlayer,strpos($teamPlayer,"</span>")+7));
            }
            $updateQuery = "INSERT IGNORE INTO teamplayers (FullName) VALUES ('". str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
            $queries[]=$updateQuery;

            $teamPlayerKey = "(SELECT teamplayers.PrimaryKey FROM teamplayers WHERE FullName='" . str_replace("'","''",__encode(utf8_decode($teamPlayer))) . "')";
            $updateQuery = "INSERT IGNORE INTO lineups (MatchKey, TeamKey, TeamPlayerKey, IsSubstitute) VALUES ($matchKey, $_teamAwayKey, $teamPlayerKey,$isSubstitute)";
            $queries[]=$updateQuery;

          }
        }
      }
      $currentDiv++;
    }


    $htmlMatch->clear();
    unset($htmlMatch);

  }
  $objectReturn["Queries"] = $queries;
  return $objectReturn;
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
  INNER JOIN results ON results.MatchKey=matches.PrimaryKey AND results.LiveStatus>0
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

    $insertQuery= "INSERT IGNORE INTO matchstates (MatchKey, StateDate, EventKey, TeamHomeScore, TeamAwayScore) VALUES (" . $rowSet["MatchKey"] . ",FROM_UNIXTIME(" . $rowSet["ScheduleDate"] . ")," .$rowSet["EventKey"] .",". $tempArray["TeamHomeScore"] . "," . $tempArray["TeamAwayScore"] . ") ON DUPLICATE KEY UPDATE EventKey=".$rowSet["EventKey"].", TeamHomeScore= ".$tempArray["TeamHomeScore"]. ", TeamAwayScore= ".$tempArray["TeamAwayScore"];

    $_databaseObject->queryPerf($insertQuery,"Insert Match State");
  }
}