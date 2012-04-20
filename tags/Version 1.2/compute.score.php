<?php
require_once("begin.file.php");
require_once("lib/score.php");

if (isset($_GET['MatchKey']))
{
  $_matchKey = $_GET['MatchKey'];
}
else if (isset($_POST['MatchKey']))
{
  $_matchKey = $_POST['MatchKey'];
}
else
{
  //exit ("The matchKey is required!");
  $_matchKey =222;
  
}

ComputeScore();

require_once("end.file.php");
?>