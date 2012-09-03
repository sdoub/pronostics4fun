<?php
require_once(dirname(__FILE__)."/../begin.file.php");

$competitionKey = 0;
if (isset($_GET["Competition"])) {
  $competitionKey = $_GET["Competition"];
} else {
  echo 'Please enter Competition!';
  exit;
}





require_once(dirname(__FILE__)."/../end.file.php");
?>