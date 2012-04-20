<?php
require_once("begin.file.php");


if (isset($_GET["ToBeDeleted"])){
  $newsKey = $_GET["NewsKey"];

  $query = "DELETE FROM news WHERE PrimaryKey=$newsKey";
  $arr = array();
  if ($_databaseObject -> queryPerf ($query , "Delete News"))
    $arr["error"] = false;
  else
    $arr["error"] = true;

  writeJsonResponse($arr);
} else {
  $newsId = explode('.',$_POST["element_id"]);
  $newsKey = $newsId[1];

  //$newsInfo = mysql_real_escape_string(__encode(utf8_decode($_POST["update_value"])));
  $newsInfo = utf8_decode($_POST["update_value"]);
  if ($newsKey=="newKey") {
    $query = "INSERT INTO news (CompetitionKey, Information, InfoType) VALUE (".COMPETITION.",'".$newsInfo."', 4)";
  }
  else {
    $query = "UPDATE news SET Information='".$newsInfo."' WHERE PrimaryKey=$newsKey";
  }

  $_databaseObject -> queryPerf ($query , "Update News");

  if ($newsKey=="newKey") {
    $newsKey = $_databaseObject -> insert_id();
  }

  print(stripcslashes (utf8_encode($newsInfo)));
}
require_once("end.file.php");

?>