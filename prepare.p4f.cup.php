<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/classes/cup.php");
include_once(dirname(__FILE__). "/lib/p4f.cup.php");

use Propel\Runtime\ActiveQuery\Criteria;
if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1)
{
	$seasons = SeasonsQuery::create()->orderByPrimaryKey(Criteria::DESC)->find();
	$seasonMatches = PlayercupmatchesQuery::create()->filterBySeasonkey($seasons[0]->getPrimarykey())->count();
	$arr = array();
	$arr["NbrOfMatches"]=$seasonMatches;
	if ($seasonMatches==0) {
		$arr[] = CreateCup();
	} else {
		$arr["Season"]="Season is already initialised";
	}
} else {
  $arr["Error"] = "Not authorized";
}

writeJsonResponse($arr);

include_once(dirname(__FILE__)."/end.file.php");
?>