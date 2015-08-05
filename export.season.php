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

$groups = GroupsQuery::create()
  ->filterByCompetitionkey($competitionKey)
  ->find();

$groupList = '';
foreach ($groups as $group){
	if (!empty($groupList))
		$groupList.=',';	
	$groupList.=$group->getGroupPK();
}

$matches = MatchesQuery::create()
	->useGroupsQuery()
  ->filterByCompetitionkey($competitionKey)
  ->endUse()
  ->find();
$matchList = '';
foreach ($matches as $match){
	if (!empty($matchList))
		$matchList.=',';	
	$matchList.=$match->getMatchPK();
}

$seasons = SeasonsQuery::create()
  ->filterByCompetitionkey($competitionKey)
  ->find();
$seasonList = '';
foreach ($seasons as $season){
	if (!empty($seasonList))
		$seasonList.=',';	
	$seasonList.=$season->getSeasonPK();
}
$tables = array("competitions"=>"", 
								"cuprounds"=>"",
							  "divisions"=>"",
							  "events"=>"MatchKey IN ($matchList)",
								"forecasts"=>"MatchKey IN ($matchList)",
								"groups"=>"CompetitionKey=$competitionKey",
								"lineups"=>"MatchKey IN ($matchList)",
								"matches"=>"GroupKey IN ($groupList)",
								"matchstates"=>"MatchKey IN ($matchList)",
								"news"=>"CompetitionKey=$competitionKey",
								"playercupmatches"=>"GroupKey IN ($groupList)",
								"playerdivisionmatches"=>"GroupKey IN ($groupList)",
								"playerdivisionranking"=>"SeasonKey IN ($seasonList)",
								"playergroupranking"=>"GroupKey IN ($groupList)",
								"playergroupresults"=>"GroupKey IN ($groupList)",
								"playergroupstates"=>"GroupKey IN ($groupList)",
								"playermatchresults"=>"MatchKey IN ($matchList)",
								"playermatchstates"=>"MatchKey IN ($matchList)",
								"playerranking"=>"CompetitionKey=$competitionKey",
								"players"=>"",
								"results"=>"MatchKey IN ($matchList)",
								"seasons"=>"CompetitionKey=$competitionKey",
								"surveys"=>"",
								"teamplayers"=>"",
								"teams"=>"",
								"votes"=>"MatchKey IN ($matchList)"
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
