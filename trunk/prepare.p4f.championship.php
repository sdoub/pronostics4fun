<?php
include_once(dirname(__FILE__)."/begin.file.php");
include_once(dirname(__FILE__). "/classes/championship.php");
include_once(dirname(__FILE__). "/lib/p4f.championship.php");

writeJsonResponse(CreateFirstSeason());

include_once(dirname(__FILE__)."/end.file.php");
?>