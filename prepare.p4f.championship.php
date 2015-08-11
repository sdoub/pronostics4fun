<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/classes/championship.php");
include_once(dirname(__FILE__). "/lib/p4f.championship.php");

use Propel\Runtime\ActiveQuery\Criteria;
if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1)
{
	$seasons = SeasonsQuery::create()->orderByPrimaryKey(Criteria::DESC)->find();
	$seasonMatches = PlayerdivisionmatchesQuery::create()->filterBySeasonkey($seasons[0]->getPrimarykey())->count();
	$arr = array();
	$arr["NbrOfMatches"]=$seasonMatches;
	if ($seasonMatches==0) {
		$arr[] = CreateNextSeason($seasons[1]->getPrimarykey(), $seasons[0]->getPrimarykey());
		$arr[] = CalculateP4FDivisionsRanking($seasons[0]->getPrimarykey());
	} else {
		$arr["Season"]="Season is already initialised";
	}
} else {
  $arr["Error"] = "Not authorized";
}

writeJsonResponse($arr);

include_once(dirname(__FILE__)."/end.file.php");
?>