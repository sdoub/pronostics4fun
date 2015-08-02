<?php
require_once("begin.file.php");
//require_once(dirname(__FILE__)."/lib/p4fmailer.php");

$host=SQL_HOST;
$user=SQL_LOGIN;
$password=SQL_PWD;
$db=SQL_DB;

$competitionKey = 9;
if (isset($_GET["Competition"])) {
  $competitionKey = $_GET["Competition"];
}

$tables = array("competitions"=>"", 
								"cuprounds"=>"",
							  "divisions"=>"",
							  "events"=>"MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.MatchKey=matches.PrimaryKey AND groups.CompetitionKey=$competitionKey)",
								"forecasts"=>"MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.MatchKey=matches.PrimaryKey AND groups.CompetitionKey=$competitionKey)",
								"groups"=>"CompetitionKey=$competitionKey",
								"lineups"=>"MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.MatchKey=matches.PrimaryKey AND groups.CompetitionKey=$competitionKey)",
								"matches"=>"GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=$competitionKey)",
								"matchstates"=>"MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.MatchKey=matches.PrimaryKey AND groups.CompetitionKey=$competitionKey)",
								"news"=>"CompetitionKey=$competitionKey",
								"playercupmatches"=>"GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=$competitionKey)",
								"playerdivisionmatches"=>"GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=$competitionKey)",
								"playerdivisionranking"=>"SeasonKey IN (SELECT seasons.PrimaryKey FROM seasons WHERE seasons.CompetitionKey=$competitionKey)",
								"playergroupranking"=>"GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=$competitionKey)",
								"playergroupresults"=>"GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=$competitionKey)",
								"playergroupstates"=>"GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=$competitionKey)",
								"playermatchresults"=>"MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.MatchKey=matches.PrimaryKey AND groups.CompetitionKey=$competitionKey)",
								"playermatchstates"=>"MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.MatchKey=matches.PrimaryKey AND groups.CompetitionKey=$competitionKey)",
								"playerranking"=>"CompetitionKey=$competitionKey",
								"players"=>"",
								"results"=>"MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.MatchKey=matches.PrimaryKey AND groups.CompetitionKey=$competitionKey)",
								"seasons"=>"CompetitionKey=$competitionKey",
								"surveys"=>"",
								"teamplayers"=>"",
								"teams"=>"",
								"votes"=>"MatchKey IN (SELECT matches.PrimaryKey FROM matches INNER JOIN groups ON groups.MatchKey=matches.PrimaryKey AND groups.CompetitionKey=$competitionKey)"
							 );
//system("mysqldump -t -u $user -p $pssword --replace $db $table -wPrimaryKey=8");
       
$fileList = '';
foreach($tables as $table=>$filter){
	$dumpCommand="mysqldump -t --host=$host --user=$user --password=$password --replace $db $table";
	//  --password=$password
	if(!empty($filter)){
		$dumpCommand.=" --where '$filter' ";
	}
	$dumpCommand.=" > data/$table.sql";
	$fileList.= "data/$table.sql ";
	//echo $dumpCommand;
	//echo "<br/>";
	system($dumpCommand);
}

system("cat $fileList | gzip > data/dump.season.$competitionKey.gz");

foreach($tables as $table=>$filter){
	if (file_exists("data/$table.sql")) {
  	if (@unlink("data/$table.sql") === true) {
    	//echo "<br/>";
    	//echo "the file data/$table.sql was successfully deleted!";
  	}
	}
}

if (file_exists("data/dump.season.$competitionKey.gz")) {
	echo '<br/>';
	echo '<a href="/data/dump.season.'.$competitionKey.'.gz">Dump</a>';
}

require_once("end.file.php");
