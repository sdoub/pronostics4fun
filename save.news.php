<?php
require_once("begin.file.php");

if (isset($_GET["ToBeDeleted"])){
  $newsKey = $_GET["NewsKey"];
	$news = NewsQuery::create()->findPK($newsKey);
	$news->delete();
  $arr = array();
	$arr["error"] = !$news->isDeleted();
  writeJsonResponse($arr);
} else {
  $newsId = explode('.',$_POST["element_id"]);
  $newsKey = $newsId[1];

  $newsInfo = $_POST["update_value"];
  if ($newsKey=="newKey") {
		$news = new News();
		$news->setCompetitionkey(COMPETITION);
		$news->setInformation($newsInfo);
		$news->setInfotype(4);
		$news->save();
  }
  else {
		$news = NewsQuery::create()->findPK($newsKey);
		$news->setInformation($newsInfo);
		$news->save();
  }


  if ($newsKey=="newKey") {
    $newsKey = $news->getPrimarykey();
  }

  writeJsonResponse($news->toArray());
}
require_once("end.file.php");

?>