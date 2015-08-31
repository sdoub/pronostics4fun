<?php
//@ destroy instance
unset($_authorisation);

$dbErrors = $_databaseObject -> get ('errorLog');
if (count($dbErrors['errorLog'])>0) {
	$defaultLogger->addError(var_export($dbErrors['errorLog'], true));
}

$_databaseObject -> close ();

unset($_databaseObject);
?>