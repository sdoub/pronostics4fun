<?php

$dbname='history.db';
$base=new SQLiteDatabase($dbname, 0666, $err);
if ($err)
{
  echo "SQLite NOT supported.<br>\n";
  exit($err);
}
else
{
  echo "SQLite supported.<br>\n";
}

$url = $_SERVER['SERVER_NAME'];
$page = $_SERVER['PHP_SELF'];
echo "http://".$url.$page."<br>";
echo $_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']."<br>";
?>