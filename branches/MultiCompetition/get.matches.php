#!/usr/local/bin/php
<?php
require_once(dirname(__FILE__)."/begin.file.php");
switch ($_competitionType) {
  case 3:
    header("location:get.matches.euro.php");
    break;
  default:
    header("location:get.matches.ligue1.php");
    break;
}

require_once(dirname(__FILE__)."/end.file.php");
?>
