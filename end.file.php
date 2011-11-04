<?php
//@ destroy instance
unset($_authorisation);

$_databaseObject -> close ();

unset($_databaseObject);
?>