<?php
require_once("begin.file.php");

if (isset($_GET["logout"])) {
  $_authorisation-> signout();
}
print ($_COOKIE["keepConnection"]);
print ($_COOKIE["UserId"]);

print("<a href='test.php?logout=true'>Logout</a>");
print("<a href='test.php'>Login</a>");

require_once("end.file.php");
?>
