#!/usr/local/bin/php
<?php
use Sunra\PhpSimple\HtmlDomParser;

require_once(dirname(__FILE__)."/begin.file.php");
require_once(dirname(__FILE__)."/lib/p4fmailer.php");

error_reporting(E_ALL);
ini_set('display_errors', 'on');

$query = "SELECT PrimaryKey GroupKey, DayKey, IF (TIMEDIFF(BeginDate,(NOW()+ INTERVAL 1 HOUR))<0,1,0) isVoteClosed,
(SELECT COUNT(1) FROM matches WHERE matches.IsBonusMatch=1 AND matches.GroupKey=groups.PrimaryKey) IsBonusMatchValidated,
Status
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
	$groupStatus = $rowSet["Status"];

  //Get information from lfp
  $isGroupScheduled = true;

  $_error = "";
  switch (COMPETITION)
  {
    case 1:
      //$url = "http://live.football365.fr/direct_sybase/competitions/CDM_2010/CDM_2010_" . $rowSet["Football365Key"] . ".xml";
      break;
    default:
      $url = "http://" . EXTERNAL_WEB_SITE . "/competitionPluginCalendrierResultat/changeCalendrierHomeJournee?c=ligue1&js=" . $dayKey . "&id=0";
      break;
  }

	$defaultLogger->addInfo($url);
  if ($html = HtmlDomParser::file_get_html($url))
  {
    {
      $scheduleDates = array();
      foreach($html->find('h4') as $h4) {
        $scheduleDates[]=ConvertFrenchDateToUniversalDate($h4->plaintext);
      }

      $currentTable = 0;
      foreach($html->find('table') as $table) {
        foreach($table->find('tbody tr') as $rows) {

          if ($rows->find('td',0)){
            $isMatchReported = false;
            // Get "Feuille de match Id
            $lfpUrl = explode ('/',$rows->find('td',0)->first_child ()->getAttribute("href"));
            $lfpMatchKey = $lfpUrl[3];

            $dateMatchArray = explode('/',$scheduleDates[$currentTable]);
            if (strpos($rows->find('td',0)->plaintext,":")!==false ) {
              $hourMatchArray = explode(':',$rows->find('td',0)->plaintext);
            } else {
              echo "<br/>". $rows->find('td',0)->plaintext;
              if (strpos($rows->find('td',0)->plaintext,"Reporté")!==false ) {
                $isMatchReported = true;
              } else {
                $hourMatchArray = array();
                $hourMatchArray[0] = 0;
                $hourMatchArray[1] = 0;
                $isGroupScheduled =false;
              }
            }
            $hours = (int)$hourMatchArray[0]==0?19:(int)$hourMatchArray[0];
            $minutes = $hourMatchArray[1];
            $scheduleDate = mktime((int)$hours, (int)$minutes, 0, (int)$dateMatchArray[1], (int)$dateMatchArray[0], (int)$dateMatchArray[2]);

            if ($rows->find('td',2)->first_child()->hasAttribute("src")) {
              $lfpTeamHome = explode('/',$rows->find('td',2)->first_child()->getAttribute("src"));
            } else {
              $lfpTeamHome = explode('/',$rows->find('td',2)->first_child()->first_child()->getAttribute("src"));
            }
            $lfpTeamHomeKey = substr($lfpTeamHome[6], 0, -4);
            $teamHomeKey = ConvertLfpKeyToP4F ($lfpTeamHomeKey);

            if ($rows->find('td',4)->first_child()->hasAttribute("src")) {
              $lfpTeamAway = explode('/',$rows->find('td',4)->first_child()->getAttribute("src"));
            } else {
              $lfpTeamAway = explode('/',$rows->find('td',4)->first_child()->first_child()->getAttribute("src"));
            }
            $lfpTeamAwayKey = substr($lfpTeamAway[6], 0, -4);

            $teamAwayKey = ConvertLfpKeyToP4F ($lfpTeamAwayKey);

            if ($teamAwayKey != 0 && $teamHomeKey!=0) {
              $insertQuery = "INSERT IGNORE INTO matches (GroupKey, TeamHomeKey, TeamAwayKey, ExternalKey, ScheduleDate) VALUES ";
              $insertQuery .= "($groupKey,";
              $insertQuery .= "$teamHomeKey,";
              $insertQuery .= "$teamAwayKey,";
              $insertQuery .= "$lfpMatchKey,";
              $insertQuery .= "FROM_UNIXTIME($scheduleDate)) ";

              $queries[]=$insertQuery;

              $matchStatus=0;
              if ($isMatchReported) {
                $matchStatus=1;
              }
              $updateQuery = "UPDATE matches SET ExternalKey=$lfpMatchKey, ScheduleDate=FROM_UNIXTIME($scheduleDate), Status=$matchStatus WHERE GroupKey=";
              $updateQuery .= "$groupKey AND TeamHomeKey=$teamHomeKey AND TeamAwayKey=$teamAwayKey";
              $queries[]=$updateQuery;
            } else {
							$defaultLogger->addError("Impossible d'insérer le match car une des équipes n'a pas de correspondance sur p4fey");
							$defaultLogger->addError("lfpTeamHomeKey -> $lfpTeamHomeKey");
							$defaultLogger->addError("lfpTeamAwayKey -> $lfpTeamAwayKey");
            }
          }
        }
        $currentTable++;
      }
    }

    $html->clear();
    unset($html);
  }
  else {
    $defaultLogger->addError("Erreur lors de l'analyse du document HTML");
		$_error = $_error . "<error>Erreur lors de l'analyse du document HTML</error>";
  }

  if ($isGroupScheduled) {
    $status="1";
  } else {
    // Specific test for Lille - evian moved due to Davis cup
		// 246 - Specific test for Bastia - Nantes moved due to bad condition in Corse
    if ($groupKey==195 || $groupKey==246) {
      $status="1";
    } else {
			$status=$groupStatus;
    }
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
                                    WHERE TMP.GlobalVoteCount > 0
                                      ORDER BY TMP.GlobalVoteValue desc
                                LIMIT 0,1) TMP2)";
    $queries[]=$updateQuery;
  }
}
//Open a new connection to execute all queries
$_databaseObject = new mysql (SQL_HOST, SQL_LOGIN,  SQL_PWD, SQL_DB, $_dbOptions);

foreach ($queries as $query) {
	$defaultLogger->addDebug($query);
  $_databaseObject -> queryPerf ($query , "Execute query");
}

$query = "SELECT PrimaryKey GroupKey, DayKey, Description, IF (TIMEDIFF(BeginDate,(NOW()+ INTERVAL 1 HOUR))<0,1,0) isVoteClosed,
(SELECT COUNT(1) FROM matches WHERE matches.IsBonusMatch=1 AND matches.GroupKey=groups.PrimaryKey) IsBonusMatchValidated,
Status,
(SELECT SUM(GlobalVotes.value) FROM votes GlobalVotes WHERE GlobalVotes.MatchKey IN (SELECT matches.PrimaryKey FROM matches WHERE matches.GroupKey=groups.PrimaryKey)) NumberOfVotes
            FROM groups
           WHERE IsCompleted=0 AND CompetitionKey = ".COMPETITION."
           LIMIT 0,5";
$rowsSetAfter = $_databaseObject -> queryGetFullArray ($query, "Get all groups of the current competition");

foreach ($rowsSet as $rowSet)
{
  foreach ($rowsSetAfter as $rowSetAfter){
    // If the vote have just been closed
    if ($rowSetAfter["GroupKey"]==$rowSet["GroupKey"] && $rowSetAfter["IsBonusMatchValidated"]==1 && $rowSet["IsBonusMatchValidated"]==0 && $rowSetAfter["NumberOfVotes"]>0) {

      $queryVotes = "SELECT TMP.MatchKey, TeamHomeName, TeamAwayName,TMP.GlobalVoteValue stars,TMP.GlobalVoteCount NbrOfPlayers,TMP.GlobalVoteValue/TMP.GlobalVoteCount average FROM
                                (SELECT matches.PrimaryKey MatchKey,
TeamHome.Name TeamHomeName, TeamAway.Name TeamAwayName,
                                        groups.PrimaryKey GroupKey,
                                        (SELECT SUM(GlobalVotes.value) FROM votes GlobalVotes WHERE GlobalVotes.MatchKey=matches.PrimaryKey) GlobalVoteValue,
                                        (SELECT COUNT(GlobalVotes.value) FROM votes GlobalVotes WHERE GlobalVotes.MatchKey=matches.PrimaryKey) GlobalVoteCount
                                  FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
                                 INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
                                 WHERE groups.PrimaryKey = ".$rowSetAfter["GroupKey"]." AND groups.CompetitionKey=".COMPETITION."
                                ) TMP
                                  ORDER BY TMP.GlobalVoteValue desc";

      $resultSet = $_databaseObject->queryPerf($queryVotes,"Get matches to be played by current day");

      $infonews = '<span><img class="news" src="images/star_48.png"></span><div>Résultat du vote pour le match bonus de la '.$rowSetAfter["Description"].':</div>';

      $rank = 0;
      $realRank = 0;
      $oldRank = -1;
      while ($rowSetVotes = $_databaseObject -> fetch_assoc ($resultSet)) {
        $rank++;
        $infonews .= '<div>'.$rank.'- <u>'.$rowSetVotes["TeamHomeName"].' - '.$rowSetVotes["TeamAwayName"].'</u> : '.$rowSetVotes["stars"].' étoile(s)</div>';
      }
			$mail             = new P4FMailer();
      try {
        $mail->CharSet = 'UTF-8';
        $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
        $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
        $mail->Subject    = 'Résultat du vote pour le match bonus de la '.$rowSetAfter["Description"];
        $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test
        $mail->MsgHTML($infonews);
        $mail->AddAddress("admin@pronostics4fun.com", "P4F Admin");
				$mail->Send();
      } catch (phpmailerException $e) {
        $defaultLogger->addError($e);
				echo $e->errorMessage(); //Pretty error messages from PHPMailer
      } catch (Exception $e) {
        $defaultLogger->addError($e->getMessage());
				echo $e->getMessage(); //Boring error messages from anything else!
      }
      unset($mail);
      $_databaseObject->queryPerf("INSERT INTO news (CompetitionKey, Information, InfoType) VALUES (".COMPETITION.",'".__encode($infonews)."',4)","insert news for ending vote");
			$defaultLogger->addNotice("Vote is completed for the group: ". $rowSetAfter["Description"]. " (".$rowSetAfter["GroupKey"].")");
    }

    // If a group is opened for giving pronostics
    if ($rowSetAfter["GroupKey"]==$rowSet["GroupKey"] && $rowSetAfter["Status"]==1 && $rowSet["Status"]==0) {

      $infonews = '<img class="news" src="images/calendar.png">La programmation des matchs de la '.$rowSetAfter["Description"].' est définitive, par conséquent les pronostics sont ouverts !';

      $_databaseObject->queryPerf("INSERT INTO news (CompetitionKey, Information, InfoType) VALUES (".COMPETITION.",'".__encode($infonews)."',4)","insert news for ending vote");
			$defaultLogger->addNotice("Group: ". $rowSetAfter["Description"]. " (".$rowSetAfter["GroupKey"].") is now opened");
    }

  }
}

$totaltime = getElapsedTime();
$defaultLogger->addInfo('totaltime:'.$totaltime);
$_errorMessage= '';

$arrDatabaseInfo = $_databaseObject -> get ('xmlQueryPerf', 'xmlErrorLog', '_totalTime','errorLog');
if (sizeOf($arrDatabaseInfo["errorLog"])>0) {
  if ($arrDatabaseInfo["errorLog"]!="") {
    $_error = true;
    $_errorMessage="An error occured during queries execution";
		$defaultLogger->addError(var_export($arrDatabaseInfo["errorLog"], true));
  }
}

$sqlUpdateCronJobLog =" UPDATE cronjobs SET LastStatus=2, LastExecutionInformation='$_errorMessage' WHERE JobName='GetMatches'";
$_databaseObject->queryPerf($sqlUpdateCronJobLog,"Update Cron job information");


require_once(dirname(__FILE__)."/end.file.php");
?>
