<?php
require_once("begin.file.php");
require_once("lib/ranking.php");

if (isset($_GET['GroupKey']))
{
  $_groupKey = $_GET['GroupKey'];
}
else if (isset($_POST['GroupKey']))
{
  $_groupKey = $_POST['GroupKey'];
}
else
{
  //exit ("The matchKey is required!");
  $_groupKey =11;
  
}

CalculateGroupRanking ($_groupKey);

require_once("end.file.php");
?>