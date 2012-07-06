<?php

/******************************************************************************/
/*                                                                            */
/*                       __        ____                                       */
/*                 ___  / /  ___  / __/__  __ _____________ ___               */
/*                / _ \/ _ \/ _ \_\ \/ _ \/ // / __/ __/ -_|_-<               */
/*               / .__/_//_/ .__/___/\___/\_,_/_/  \__/\__/___/               */
/*              /_/       /_/                                                 */
/*                                                                            */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/* Titre          : Classe d'abstraction PHP4 de base de données, mysql et... */
/*                                                                            */
/* URL            : http://www.phpsources.org/scripts158-PHP.htm              */
/* Auteur         : Johan Barbier                                             */
/* Date édition   : 14 Fév 2006                                               */
/*                                                                            */
/******************************************************************************/
//@ validate inclusion
if(!defined('VALID_ACCESS_DATABASE_')) exit(basename(__FILE__) . ' -> direct access is not allowed.');

include_once (BASE_PATH . "/lib/safeIO.php");

class database {

  /*********************************************************************
   * Définition des Propriétés
   *********************************************************************/
  var $config = array ();
  var $errorLog = '';
  var $xmlErrorLog;
  var $isConnected = false;
  var $options = array (
        'ERROR_DISPLAY' => true
  );
  var $sql = '';
  var $qryRes;
  var $link;
  var $sQueryPerf = array ();
  var $xmlQueryPerf;
  var $_totalTime = 0;

  /*********************************************************************
   * Constructeur
   * On peut ou non passer le nom de la base de données; si on le passe,
   la connexion à la base se fait d'elle même
   * Sinon, il faudra passer par la méthode select_base ()
   * @Param string $host => serveur de bdd
   * @Param string $user => login
   * @Param string $pwd => password
   * @Param string $db => base de donnée
   * @Param array $options => options (voir la propriété $config)
   *********************************************************************/
  function database ($host, $user, $pwd, $db = '', $options = array ()) {
    $this -> config['HOST'] = $host;
    $this -> config['USER'] = $user;
    $this -> config['PWD'] = $pwd;
    $this -> xmlQueryPerf = new DOMDocument();
    $this -> xmlQueryPerf -> loadXML('<root><queries-perfs></queries-perfs></root>');
    $this -> xmlErrorLog = new DOMDocument();
    $this -> xmlErrorLog -> loadXML('<root><queries-errors></queries-errors></root>');
    if (!empty ($options)) {
      foreach ($options as $clef => $opt) {
        if (array_key_exists ($clef, $this -> options)
        && is_bool ($opt)) {
          $this -> options[$clef] = $opt;
        }
      }
    }
    if (!empty ($db)) {
      $this -> connect ();
      $this -> select_base ($db);

    }
  }

  /*********************************************************************
   * Méthode de connexion
   * méthode publique
   * Se fait automatiquement, ou peut être explicitement appelée
   *********************************************************************/
  function connect () {
    $url = $_SERVER['SERVER_NAME'];
    $page = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
    $stringData = "http://".$url.$page ."\n";
    $datetime= strftime("%A %d %B %Y %H:%M:%S",time());

    if (false ===  $this -> private_connect ()) {
      $this -> isConnected=false;
      $this -> error (get_class ($this).' :: connect()',
      $this -> private_errno().' : '.$this -> private_error(),
            'Connexion avec Host : '.$this -> config['HOST'].'
             User : '. $this -> config['USER'].' Pwd : ********');

      $stringData .= "   Error: " . $this -> private_errno().' : '.$this -> private_error()."\n";
    }
    else {
      $this -> isConnected = true;
      $stringData .= "Ouverture du thread " . $this->private_thread_id() . " a $datetime\n";
    }

    $filename= strftime("Connection %d%m%Y",time()) . ".log";
    $myFile = dirname(__FILE__)."/log/" . $filename;
    //$fh = fopen($myFile, 'a') or die("can't open file -> ". $myFile);
    //chmodr($myFile,"0666");
//    $sio = new safeIO;
//    $e=$sio->open($myFile,APPEND);
//    if ($e) {
//      $sio->write( $stringData,strlen($stringData));
//      $sio->close();
//    }
    //fwrite($fh, $stringData);
    //fclose($fh);

  }

  /*********************************************************************
   * Méthode de log des erreurs
   * méthode privée
   * @Param string $func => méthode appelant l'erreur
   * @Param string $err => message d'erreur interne au moteur de la bdd
   * @Param string $qry => requête ayant provquée l'erreur,
   ou message interne à la classe
   *********************************************************************/
  function error ($func, $err,  $qry) {
    $this -> errorLog[] = $func.' : '.$err.' => '.$qry;
    $errors = $this -> xmlErrorLog-> getElementsByTagName('queries-errors')->item(0);
    $newElement = $this-> xmlErrorLog->createElement('error', $qry);
    $newElement -> setAttribute('function',utf8_encode($func));
    $newElement -> setAttribute('description',utf8_encode($err));
    $errors->appendChild($newElement);

    if ($this -> options['ERROR_DISPLAY'] === true) {
      echo 'ERREUR! : ', $this -> errorLog[key ($this -> errorLog)],
                 '<br />';
    }
  }

  /*********************************************************************
   * Méthode de sélection d'une base de données
   * méthode publique
   * @Param string $name => nom de la base.
   *********************************************************************/
  function select_base($name) {
    if (false === is_scalar ($name)) {
      $this -> error (get_class ($this).' :: select_base()',
      $this -> private_errno().' : '.$this -> private_error(),
            'Nom incorrect passé à la méthode : '.$name);
    } else {
      $this -> config['BD'] = $name;
      if (false === $this -> private_select_base()) {
        $this -> error (get_class ($this).' :: select_base()',
        $this -> private_errno().' : '.$this -> private_error(),
                 'La méthode n\'a pu se connecter à la base : '.$name);
      }
    }
  }

  /*********************************************************************
   * Méthode de fermeture de la connexion
   * méthode publique
   *********************************************************************/
  function close() {

    if (isset($this-> link) ) {
      $datetime= strftime("%A %d %B %Y %H:%M:%S",time());
      $filename= strftime("Connection %d%m%Y",time()) . ".log";
      $myFile = dirname(__FILE__)."/log/" . $filename;
      $stringData = "Fermeture du thread " . $this->private_thread_id() . " a $datetime\t\n";

//      $sio = new safeIO;
//      $e=$sio->open($myFile,APPEND);
//      if ($e) {
//        $sio->write( $stringData,strlen($stringData));
//        $sio->close();
//      }
      $this -> private_close();
      unset ($this-> link);
    }
  }

  /*********************************************************************
   * Méthode de "requêtage"
   * méthode publique
   * @Param string $qry => requête
   *********************************************************************/
  function query ($qry) {
    $this -> sql = $qry;
    if (false === $this -> qryRes = $this -> private_query ()) {
      $this -> error (get_class ($this).' :: query ()',
      $this -> private_errno ().' : '.$this -> private_error (),
      $this -> sql);
    } else {
      return $this -> qryRes;
    }
  }

  /*********************************************************************
   * Méthode pour compter le nombre de lignes renvoyées
   * méthode publique
   * @Param mixed $qry => ressource d'une requête ou identifiant de
   ressource pour mssql
   * On peut la passer explicitement, ou prendre la propriété
   *********************************************************************/
  function num_rows ($qry = null) {
    if ((get_class ($this) === 'mysql'
    && is_resource ($qry))
    || (get_class ($this) === 'mssql'
    && is_int ($qry))) {
      $num =  $this -> private_num_rows ($qry);
      if (false === $num) {
        $this -> error (get_class ($this).' :: num_rows ()',
        $this -> private_errno ().' : '.$this -> private_error (),
        $this -> sql);
        return false;
      } else {
        return $num;
      }
    }elseif (isset ($this -> qryRes)
    && (get_class ($this) === 'mysql'
    && is_resource ($this -> qryRes))
    || (get_class ($this) === 'mssql'
    && is_int ($this -> qryRes))) {
      $num =  $this -> private_num_rows ($this -> qryRes);
      if (false === $num) {
        $this -> error (get_class ($this).' :: num_rows ()',
        $this -> private_errno ().' : '.$this -> private_error (),
        $this -> sql);
        return false;
      } else {
        return $num;
      }
    } else {
      $this -> error (get_class ($this).' :: num_rows ()',
      $this -> private_errno ().' : '.$this -> private_error (),
                    'Pas de ressource valide');
    }
  }

  /*********************************************************************
   * Méthode pour parcourir les lignes renvoyée par ujne requête sous forme
   de tableau associatif
   * méthode publique
   * @Param mixed $qry => ressource d'une requête ou identifiant de
   ressource pour mssql
   * On peut la passer explicitement, ou prendre la propriété
   *********************************************************************/
  function fetch_assoc ($qry = null) {
    if ((get_class ($this) === 'mysql'
    && is_resource ($qry))
    || (get_class ($this) === 'mssql'
    && is_int ($qry))) {
      return  $this -> private_fetch_assoc ($qry);
    }elseif (isset ($this -> qryRes)
    && (get_class ($this) === 'mysql'
    && is_resource ($this -> qryRes))
    || (get_class ($this) === 'mssql'
    && is_int ($this -> qryRes))) {
      return  $this -> private_fetch_assoc ($this -> qryRes);
    } else {
      $this -> error (get_class ($this).' :: fetch_assoc ()',
      $this -> private_errno ().' : '.$this -> private_error (),
                    'Pas de ressource valide');
    }
  }

  /*********************************************************************
   * Méthode pour parcourir les lignes renvoyée par ujne requête
   sous forme de tableau associatif ou numérique
   * méthode publique
   * @Param mixed $qry => ressource d'une requête ou identifiant de
   ressource pour mssql
   * On peut la passer explicitement, ou prendre la propriété
   *********************************************************************/
  function fetch_array ($qry = null) {
    if ((get_class ($this) === 'mysql'
    && is_resource ($qry))
    || (get_class ($this) === 'mssql'
    && is_int ($qry))) {
      return  $this -> private_fetch_array ($qry);
    }elseif (isset ($this -> qryRes)
    && (get_class ($this) === 'mysql'
    && is_resource ($this -> qryRes))
    || (get_class ($this) === 'mssql'
    && is_int ($this -> qryRes))) {
      return  $this -> private_fetch_array ($this -> qryRes);
    } else {
      $this -> error (get_class ($this).' :: fetch_array ()',
      $this -> private_errno ().' : '.$this -> private_error (),
                    'Pas de ressource valide');
    }
  }

  function fetch_full_array($qry = null)
  {

  if ((get_class ($this) === 'mysql'
    && is_resource ($qry))
    || (get_class ($this) === 'mssql'
    && is_int ($qry))) {
      $table_result=array();
      $r=0;
      while($row = $this -> private_fetch_assoc ($qry)){
        $c=0;
        while ($c < $this -> private_num_fields($qry)) {
          $col = $this -> private_fetch_field($qry, $c);
          $arr_row[$col -> name] = $row[$col -> name];
          $c++;
        }
        $table_result[$r] = $arr_row;
        $r++;
      }
      return  $table_result;
    }elseif (isset ($this -> qryRes)
    && (get_class ($this) === 'mysql'
    && is_resource ($this -> qryRes))
    || (get_class ($this) === 'mssql'
    && is_int ($this -> qryRes))) {
      $table_result=array();
      $r=0;
      while($row = $this -> private_fetch_assoc ($this -> qryRes)){
        $c=0;
        while ($c < $this -> private_num_fields($this -> qryRes)) {
          $col = $this -> private_fetch_field($this -> qryRes, $c);
          $arr_row[$col -> name] = $row[$col -> name];
          $c++;
        }
        $table_result[$r] = $arr_row;
        $r++;
      }
      return  $table_result;
    } else {
      $this -> error (get_class ($this).' :: fetch_assoc ()',
      $this -> private_errno ().' : '.$this -> private_error (),
                    'Pas de ressource valide');
    }

  }
  /*********************************************************************
   * Méthode pour parcourir les lignes renvoyée par ujne requête sous forme
   de tableau numérique
   * méthode publique
   * @Param mixed $qry => ressource d'une requête ou identifiant
   de ressource pour mssql
   * On peut la passer explicitement, ou prendre la propriété
   *********************************************************************/
  function fetch_row ($qry = null) {
    if ((get_class ($this) === 'mysql'
    && is_resource ($qry))
    || (get_class ($this) === 'mssql'
    && is_int ($qry))) {
      return  $this -> private_fetch_row ($qry);
    }elseif (isset ($this -> qryRes)
    && (get_class ($this) === 'mysql'
    && is_resource ($this -> qryRes))
    || (get_class ($this) === 'mssql'
    && is_int ($this -> qryRes))) {
      return  $this -> private_fetch_row ($this -> qryRes);
    } else {
      $this -> error (get_class ($this).' :: fetch_row ()',
      $this -> private_errno ().' : '.$this -> private_error (),
                    'Pas de ressource valide');
    }
  }

  /*********************************************************************
   * Méthode renvoyant le dernier ID inséré
   * méthode publique
   *********************************************************************/
  function insert_id () {
    if (isset ($this -> link)) {
      $id = $this -> private_insert_id ();
    } else {
      $this -> error (get_class ($this).' :: insert_id ()',
      $this -> private_errno ().' : '.$this -> private_error (),
                    'Pas de lien valide');
      return false;
    }
    if (false === $id) {
      $this -> error (get_class ($this).' :: insert_id ()',
      $this -> private_errno ().' : '.$this -> private_error (),
            'Echec de récupération
             du dernier id inséré');
      return false;
    } else {
      return $id;
    }
  }

  /*********************************************************************
   * Méthode pour récupérer la valeur d'une ou plusieurs propriété(s)
   de la classe
   * méthode publique
   * On peut passer n'importe quel nombre de paramètres, sous la forme
   de chaînes ayant pour valeur le nom d'une
   * propriété EXISTANTE de la classe
   *********************************************************************/
  function get () {
    $aArgs = func_get_args();
    foreach ($aArgs as $clef => $arg) {
      if (isset ($this -> $arg)) {
        $aRetour[$arg] = $this -> $arg;
      }
    }
    if (isset ($aRetour) && is_array ($aRetour)) {
      return $aRetour;
    } else {
      return false;
    }
  }

  /*********************************************************************
   * Méthode de "requêtage" renvoyant en plus les performances
   de la requête (bench)
   * méthode publique
   * @Param string $qry => requête
   *********************************************************************/
  function queryGetFullArray ($qry,$title='') {
    $this -> sql = $qry;
    $start = microtime ();
    $this -> qryRes = $this -> private_query ();
    $stop = microtime ();
    if (false === $this -> qryRes) {
      $this -> error (get_class ($this).' :: query ()',
      $this -> private_errno ().' : '.$this -> private_error (),
      $this -> sql);
      return false;
    } else {
      $startarray = explode(" ", $start);
      $start = $startarray[1] + $startarray[0];
      $endarray = explode(" ", $stop);
      $stop = $endarray[1] + $endarray[0];
      $totaltime = $stop - $start;
      $this -> _totalTime += $totaltime;
      $totaltime = round($totaltime,5);
      $elapsed = $totaltime . ' seconds.';
      $perfs = $this -> xmlQueryPerf-> getElementsByTagName('queries-perfs')->item(0);
      $newElement = $this-> xmlQueryPerf->createElement('perf', $this -> sql);
      $newElement -> setAttribute('title',utf8_encode($title));
      $newElement -> setAttribute('time',$elapsed);
      $perfs->appendChild($newElement);
      $clef = count ($this -> sQueryPerf);
      $this -> sQueryPerf[$clef]['title'] = $title;
      $this -> sQueryPerf[$clef]['query'] = $this -> sql;
      $this -> sQueryPerf[$clef]['time'] = $elapsed;
      return $this->fetch_full_array();
    }
  }

  /*********************************************************************
   * Méthode de "requêtage" renvoyant en plus les performances
   de la requête (bench)
   * méthode publique
   * @Param string $qry => requête
   *********************************************************************/
  function queryPerf ($qry,$title='') {
    $this -> sql = $qry;
    $start = microtime ();
    $this -> qryRes = $this -> private_query ();
    $stop = microtime ();
    if (false === $this -> qryRes) {
      $this -> error (get_class ($this).' :: query ()',
      $this -> private_errno ().' : '.$this -> private_error (),
      $this -> sql);
      return false;
    } else {
      $startarray = explode(" ", $start);
      $start = $startarray[1] + $startarray[0];
      $endarray = explode(" ", $stop);
      $stop = $endarray[1] + $endarray[0];
      $totaltime = $stop - $start;
      $this -> _totalTime += $totaltime;
      $totaltime = round($totaltime,5);
      $elapsed = $totaltime . ' seconds.';
      $perfs = $this -> xmlQueryPerf-> getElementsByTagName('queries-perfs')->item(0);
      $newElement = $this-> xmlQueryPerf->createElement('perf', $this -> sql);
      $newElement -> setAttribute('title',utf8_encode($title));
      $newElement -> setAttribute('time',$elapsed);
      $perfs->appendChild($newElement);
      $clef = count ($this -> sQueryPerf);
      $this -> sQueryPerf[$clef]['title'] = $title;
      $this -> sQueryPerf[$clef]['query'] = $this -> sql;
      $this -> sQueryPerf[$clef]['time'] = $elapsed;
      return $this -> qryRes;
    }
  }
}

class mssql extends database {

  function private_connect () {
    if (false === $this -> link = @mssql_connect ($this -> config['HOST'],
    $this -> config['USER'], $this -> config['PWD'])) {
      return false;
    } else {
      return true;

    }
  }


  function private_select_base () {
    if (false === @mssql_select_db($this->config['BD'], $this->link)) {
      return false;
    } else {
      return true;
    }
  }

  function private_close() {
    mssql_close($this-> link);
  }


  function private_query () {
    return @mssql_query ($this -> sql, $this -> link);
  }

  function private_num_rows ($qry) {
    return @mssql_num_rows ($qry);
  }

  function private_fetch_row ($qry) {
    return @mssql_fetch_row ($qry);
  }

  function private_fetch_array ($qry) {
    return @mssql_fetch_array ($qry);
  }

  function private_fetch_assoc ($qry) {
    return @mssql_fetch_array ($qry);
  }

  function private_fetch_field ($qry,$fld) {
    return @mssql_fetch_field ($qry,$fld);
  }

  function private_num_fields ($qry) {
    return @mssql_num_fields ($qry);
  }



  function private_insert_id () {
    $sQuery = 'SELECT @@IDENTITY';
    $requete = $this -> query ($sQuery);
    $res = $this -> fetch_row ($requete);
    return $res[0];
  }

  function private_errno () {
    return false;
  }

  function private_error () {
    return @mssql_get_last_message ();
  }

  function private_thread_id () {
    return '';
  }
}

class mysql extends database {

  function private_connect () {
    if (false === $this -> link = @mysql_connect ($this -> config['HOST'],
    $this -> config['USER'],
    $this -> config['PWD'])) {
      return false;
    } else {
      @mysql_query ("SET NAMES utf8", $this -> link);
      return true;


    }
  }


  function private_select_base () {
    if (false === @mysql_select_db($this->config['BD'], $this->link)) {
      return false;
    } else {
      return true;
      kill_sleep();
    }
  }

  function kill_sleep() {
    define ( 'MAX_SLEEP_TIME', 2 );
    $result = @mysql_query ( "SHOW PROCESSLIST", $this->link);
    $nbrOfSession = 0;
    while ( $proc = mysql_fetch_assoc ( $result ) ) {
      //if ($proc ["Command"] == "Sleep" && $proc ["Time"] > MAX_SLEEP_TIME) {
      if ($proc ["Time"] > MAX_SLEEP_TIME) {
        @mysql_query ( "KILL " . $proc ["Id"], $this->link );
        //$display = "KILL " . $proc ["Id"] . "<br />";
      }
    }
  }

  function private_close() {
    @mysql_close($this-> link);
  }


  function private_query () {
    return @mysql_query ($this -> sql, $this -> link);
  }

  function private_num_rows ($qry) {
    return @mysql_num_rows ($qry);
  }

  function private_fetch_assoc ($qry) {
    return @mysql_fetch_assoc ($qry);
  }

  function private_fetch_array ($qry) {
    return @mysql_fetch_array ($qry);
  }

  function private_fetch_row ($qry) {
    return @mysql_fetch_row ($qry);
  }

  function private_fetch_field ($qry,$fld) {
    return @mysql_fetch_field ($qry,$fld);
  }
  function private_num_fields ($qry) {
    return @mysql_num_fields ($qry);
  }


  function private_insert_id () {
    return @mysql_insert_id ($this -> link);
  }

  function private_errno () {
    if ($this -> link)
    return @mysql_errno ($this -> link);
    else
    return @mysql_errno ();
  }

  function private_error () {
    if ($this -> link)
    return @mysql_error ($this -> link);
    else
    return @mysql_error ();
  }

  function private_thread_id () {
    return @mysql_thread_id ($this -> link);
  }

}

?>