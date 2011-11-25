<?php
require_once("begin.file.php");

if (isset($_GET["SQL"])) {
  $sql = $_GET["SQL"];
} else if (isset($_POST["SQL"])) {
  $sql = $_POST["SQL"];
} else {
  exit('There is no SQL query defined.');
}

$queries = explode (";",$sql);

while (list ($key, $value) = each ($queries)) {
  if (!empty($value)) {
    $query = str_replace('\\','',$value);
    if(!$_databaseObject->queryPerf(__encode($query),"execute sqlQueries"))
    {
      $status= false;
      //echo "An error occured during the sql execution";
    }
    else
    {
      $status= true;
      //echo "The sql query has been successfully executed";
    }
  }
}
$arr["Status"] = $status;
$arr["perfAndError"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
writeJsonResponse($arr);

require_once("end.file.php");
?>