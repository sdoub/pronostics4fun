<?php
require_once("begin.file.php");

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>' . __encode("Pronostics4Fun - Réinitialisation du mot de passe ") .'</title>
<link rel="icon" href="http://pronostics4fun.com/favico.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://pronostics4fun.com/favico.ico" type="image/x-icon" />
</head>
<body>
';

echo "<div ><a style='border:0;' href='".ROOT_SITE."'><img style='border:0;' src='".ROOT_SITE."/images/Logo.png' ></a></div><br>";

echo '<p>Bonjour <strong>' . $_GET["NickName"] . '</strong>,</p>';

echo '<p>Vous recevez ce message suite à votre perte de mot de passe sur Pronostics4Fun, veuillez cliquer <a href="'. ROOT_SITE . '/change.password.php?key=' . $_GET["Key"] . '">ici</a> pour réinitialiser votre mot de passe.</p>

<p>Merci et à très bientôt sur <a href="'. ROOT_SITE . '">Pronostics4Fun</a> </p>';

require_once("end.file.php");
?>