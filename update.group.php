<?php
require_once("begin.file.php");
require_once(dirname(__FILE__)."/lib/p4fmailer.php");

error_reporting(E_ALL);
ini_set('display_errors', 'on');

if (isset($_GET["GroupKey"]) && $_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1) {
  $_groupKey = $_GET["GroupKey"];
} else {
	if (($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1) || isset($_GET["token"])) {
		$groupToUpdate = GroupsQuery::Create()
			->filterByCompetitionkey(COMPETITION)   
			->filterByStatus(1)
			->where('TIMEDIFF(groups.BeginDate,(NOW()+ INTERVAL 1 HOUR))<?', 0)
			->where('DATE(groups.BeginDate)=(CURDATE() - INTERVAL ? DAY)', 0, PDO::PARAM_INT)
			->orderByBegindate()
			->findOne();
		if ($groupToUpdate)
			$_groupKey = $groupToUpdate->getGroupPK();
		else
			exit ("No group starts today");
	} else {
		exit("Group key is required, and only administrator can execute this process ! ");
	}
}

$groupInfo = GroupsQuery::Create()->findPK($_groupKey);

$matchBonus = MatchesQuery::Create()
	->filterByGroupkey($_groupKey)
	->filterByIsbonusmatch('true')
	->count();
if ($matchBonus>0) {
	exit("Match bonus already setup for this group (".$groupInfo->getDescription()." - $_groupKey) ! ");
} 
$updateQuery = "UPDATE matches SET IsBonusMatch=1
	WHERE PrimaryKey IN
				(SELECT TMP2.MatchKey FROM (SELECT TMP.MatchKey,TMP.GlobalVoteValue  FROM
																	(SELECT matches.PrimaryKey MatchKey,
																					groups.PrimaryKey GroupKey,
																					(SELECT SUM(GlobalVotes.value) FROM votes GlobalVotes WHERE GlobalVotes.MatchKey=matches.PrimaryKey) GlobalVoteValue,
																					(SELECT COUNT(GlobalVotes.value) FROM votes GlobalVotes WHERE GlobalVotes.MatchKey=matches.PrimaryKey) GlobalVoteCount
																		FROM matches
																	 INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey
																	 WHERE matches.GroupKey = $_groupKey
																	) TMP
																	WHERE TMP.GlobalVoteCount > 0
																		ORDER BY TMP.GlobalVoteValue desc
															LIMIT 0,1) TMP2)";

print($updateQuery);
print("<br/>");
$_databaseObject -> queryPerf ($updateQuery , "Execute query");

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
                                 WHERE groups.PrimaryKey = $_groupKey AND groups.CompetitionKey=".COMPETITION."
                                ) TMP
               ORDER BY TMP.GlobalVoteValue desc";

$resultSet = $_databaseObject->queryPerf($queryVotes,"Get matches to be played by current day");

$infonews = '<span><img class="news" src="images/star_48.png"></span><div>Résultat du vote pour le match bonus de la '.$groupInfo->getDescription().':</div>'; //'.$rowSetAfter["Description"].'

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
	$mail->Subject    = 'Résultat du vote pour le match bonus de la '.$groupInfo->getDescription();
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
$defaultLogger->addNotice("Vote is completed for the group: $_groupKey");



require_once("end.file.php");
?>