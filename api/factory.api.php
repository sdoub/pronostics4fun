<?php
require_once 'p4f.api.php';
require_once 'players.api.php';
require_once 'login.api.php';

class APIFactory {
   public static function create ($className, $request, $var, $origin){
//       if (($driverEndPos = strpos ($connectionString, ':')) === false){
//          throw new Exception ('Mauvaise chaine de connexion');
//       }
 
      switch ($className){
         case 'players':
            $api = new playersapi ($request, $var, $origin);
            break;
         case 'login':
            $api = new loginapi ($request, $var, $origin);
            break;
         default:
            throw new Exception ('Type de base inconnu');
      }
      return $api;
   }
}