<?php
//@ destroy instance
unset($_authorisation);

$dbErrors = $_databaseObject -> get ('errorLog');

if (is_array($dbErrors['errorLog'])) {
	foreach ($dbErrors['errorLog'] as $error)
		$defaultLogger->addError($error);
}

$_databaseObject -> close ();

unset($_databaseObject);
?>