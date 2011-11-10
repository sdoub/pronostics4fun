<?php
require_once("begin.file.php");

//print_r($_POST);
$newsId = explode('.',$_POST["element_id"]);
$newsKey = $newsId[1];

$newsInfo = mysql_real_escape_string(__encode(utf8_decode($_POST["update_value"])));
if ($newsKey=="newKey") {
  $query = "INSERT INTO news (CompetitionKey, Information, InfoType) VALUE (".COMPETITION.",'".$newsInfo."', 4)";
}
else {
  $query = "UPDATE news SET Information='".$newsInfo."' WHERE PrimaryKey=$newsKey";
}

$_databaseObject -> queryPerf ($query , "Update News");

$newsKey = $_databaseObject -> insert_id();

print($_POST["update_value"]);

//writePerfInfo();


require_once("end.file.php");

?>