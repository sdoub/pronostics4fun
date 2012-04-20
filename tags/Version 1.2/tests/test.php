<?php
      // Définition du temps d'expiration des cookies
      if (isset($_COOKIE["keepConnection"])){
        $expiration = $_COOKIE["keepConnection"]=="false" ? time() + (60*45) : time() + 90 * 24 * 60 * 60;
      }
      else {
        $expiration =  time() + (60*45);
      }
      // Création des cookies
      setcookie("UserId", "19", $expiration, "/");
      setcookie("NickName", "sdoub", time() + 90 * 24 * 60 * 60, "/");
      if (isset($_COOKIE["keepConnection"])){
        setcookie("keepConnection", $_COOKIE["keepConnection"], time() + 90 * 24 * 60 * 60, "/");
      }
      else {
        setcookie("keepConnection", "false", time() + 90 * 24 * 60 * 60, "/");
      }

      print ($_COOKIE["keepConnection"]);
      print ($_COOKIE["UserId"]);

    setcookie("UserId", "", time() - 60, "/");
    if (isset($_COOKIE["UserId"])){
      unset($_COOKIE["UserId"]);
    }
    //$_SESSION['exp_user']['expires']= time()-(999*60);
    if (isset($_SESSION['exp_user'])) {
      unset($_SESSION['exp_user']);
    }

     print ($_COOKIE["keepConnection"]);
     print ($_COOKIE["UserId"]);

?>