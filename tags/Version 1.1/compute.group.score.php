<?php
require_once("begin.file.php");
require_once("lib/score.php");

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

ComputeGroupScore ($_groupKey);

require_once("end.file.php");
?>