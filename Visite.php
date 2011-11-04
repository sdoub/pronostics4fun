<?php

$ip_simple = $_SERVER['REMOTE_ADDR'];
$sessionId = session_id();

$uniqueVisitoID = "$ip_simple|$sessionId";

$_databaseObject -> queryPerf ("DELETE FROM connectedusers WHERE PlayerKey=" . $_authorisation->getConnectedUserKey() );
$_databaseObject -> queryPerf ("INSERT INTO connectedusers (UserUniqueID, VisiteDate, PlayerKey) VALUES('" . $uniqueVisitoID . "', FROM_UNIXTIME(" . time() . ")," . $_authorisation->getConnectedUserKey() . ")");

//Suppression du visiteur si le timestamp date de 3 minutes
// On enregistre le temps écoulé par le visiteur
$timestamp_3min = time() - (60 * 3); // 60 * 3 = Nbr secondes dans 3 minutes (la fonction time() est en secondes)
$_databaseObject -> queryPerf ("DELETE FROM connectedusers WHERE VisiteDate < FROM_UNIXTIME(" . $timestamp_3min . ")");

// Nombre de visiteurs connectées
// Comptage du nombre d'ip
$resultSet = $_databaseObject -> queryPerf ("SELECT COUNT(*) NbrConnectedUser FROM connectedusers");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$_nbrConnectedUser = $rowSet["NbrConnectedUser"];

?>