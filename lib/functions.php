<?php

function saveStartTime ()
{
  // Get time when the page starts to build it
  $startBuildingTime = microtime();
  $startarray = explode(" ", $startBuildingTime);
  $startBuildingTime = $startarray[1] + $startarray[0];
  $_SESSION["StartBuildingTime"]=$startBuildingTime;
}

function getElapsedTime ()
{
  $endtime = microtime();
  $endarray = explode(" ", $endtime);
  $endtime = $endarray[1] + $endarray[0];
  $starttime = $_SESSION["StartBuildingTime"];
  $totaltime = $endtime - $starttime;
  $totaltime = round($totaltime,5);

  return $totaltime;
}

/**
 * Check if given time is during Europen Summer Time
 *
 * @link http://en.wikipedia.org/wiki/European_Summer_Time
 * @param int $time UTC timestamp (GMT)
 * @return boolean true if it is EST else false
 */
function is_est($time)
{
  // get year
  $Y = gmdate("Y", $time);

  // calc start / end dates and time for that year
  $begin_date = (31 - (5*$Y/4 + 4) % 7);
  $end_date = (31 - (5*$Y/4 + 1) % 7);
  $begin_time = gmmktime(0,0,0,  3,$begin_date,$Y);
  $end_time = gmmktime(0,0,0,  10,$end_date,$Y);

  // if it's in that period
  $is_dst = $time >= $begin_time && $time < $end_time;
  return $is_dst;
}

$html_entities = array (
			"À" =>  "&Agrave;",	#capital a, grave accent
			"Á" =>  "&Aacute;", 	#capital a, acute accent
			"Â" =>  "&Acirc;", 	#capital a, circumflex accent
			"Ã" =>  "&Atilde;", 	#capital a, tilde
			"Ä" => "&Auml;",	#capital a, umlaut mark
			"Å" => "&Aring;", 	#capital a, ring
			"Æ" => "&AElig;", 	#capital ae
			"Ç" => "&Ccedil;", 	#capital c, cedilla
			"È" => "&Egrave;", 	#capital e, grave accent
			"É" => "&Eacute;", 	#capital e, acute accent
			"Ê" => "&Ecirc;", 	#capital e, circumflex accent
			"Ë" => "&Euml;", 	#capital e, umlaut mark
			"Ì" => "&Igrave;", 	#capital i, grave accent
			"Í" => "&Iacute;", 	#capital i, acute accent
			"Î" => "&Icirc;", 	#capital i, circumflex accent
			"Ï" => "&Iuml;", 	#capital i, umlaut mark
			"Ð" => "&ETH;",		#capital eth, Icelandic
			"Ñ" => "&Ntilde;", 	#capital n, tilde
			"Ò" => "&Ograve;", 	#capital o, grave accent
			"Ó" => "&Oacute;", 	#capital o, acute accent
			"Ô" => "&Ocirc;", 	#capital o, circumflex accent
			"Õ" => "&Otilde;", 	#capital o, tilde
			"Ö" => "&Ouml;", 	#capital o, umlaut mark
			"Ø" => "&Oslash;", 	#capital o, slash
			"Ù" => "&Ugrave;", 	#capital u, grave accent
			"Ú" => "&Uacute;", 	#capital u, acute accent
			"Û" => "&Ucirc;", 	#capital u, circumflex accent
			"Ü" => "&Uuml;", 	#capital u, umlaut mark
			"Ý" => "&Yacute;", 	#capital y, acute accent
			"Þ" => "&THORN;", 	#capital THORN, Icelandic
			"ß" => "&szlig;", 	#small sharp s, German
			"à" => "&agrave;", 	#small a, grave accent
			"á" => "&aacute;", 	#small a, acute accent
			"â" => "&acirc;", 	#small a, circumflex accent
			"ã" => "&atilde;", 	#small a, tilde
			"ä" => "&auml;", 	#small a, umlaut mark
			"å" => "&aring;", 	#small a, ring
			"æ" => "&aelig;", 	#small ae
			"ç" => "&ccedil;", 	#small c, cedilla
			"è" => "&egrave;", 	#small e, grave accent
			"é" => "&eacute;", 	#small e, acute accent
			"ê" => "&ecirc;", 	#small e, circumflex accent
			"ë" => "&euml;", 	#small e, umlaut mark
			"ì" => "&igrave;", 	#small i, grave accent
			"í" => "&iacute;", 	#small i, acute accent
			"î" => "&icirc;", 	#small i, circumflex accent
			"ï" => "&iuml;", 	#small i, umlaut mark
			"ð" => "&eth;",		#small eth, Icelandic
			"ñ" => "&ntilde;", 	#small n, tilde
			"ò" => "&ograve;", 	#small o, grave accent
			"ó" => "&oacute;", 	#small o, acute accent
			"ô" => "&ocirc;", 	#small o, circumflex accent
			"õ" => "&otilde;", 	#small o, tilde
			"ö" => "&ouml;", 	#small o, umlaut mark
			"ø" => "&oslash;", 	#small o, slash
			"ù" => "&ugrave;", 	#small u, grave accent
			"ú" => "&uacute;", 	#small u, acute accent
			"û" => "&ucirc;", 	#small u, circumflex accent
			"ü" => "&uuml;", 	#small u, umlaut mark
			"ý" => "&yacute;", 	#small y, acute accent
			"þ" => "&thorn;", 	#small thorn, Icelandic
			"ÿ" => "&yuml;"		#small y, umlaut mark
);

function __encode($var){
  global $html_entities;

  foreach ($html_entities as $key => $value) {
    $var = str_replace($key, $value, $var);
  }
  return $var;
}

function __decode($var){
  global $html_entities;

  foreach ($html_entities as $key => $value) {
    $var = str_replace($value, $key, $var);
  }
  return $var;
}

function generatePassword($length=9, $strength=0) {
  $vowels = 'aeuy';
  $consonants = 'bdghjmnpqrstvz';
  if ($strength & 1) {
    $consonants .= 'BDGHJLMNPQRSTVWXZ';
  }
  if ($strength & 2) {
    $vowels .= "AEUY";
  }
  if ($strength & 4) {
    $consonants .= '23456789';
  }
  if ($strength & 8) {
    $consonants .= '@#$%';
  }

  $password = '';
  $alt = time() % 2;
  for ($i = 0; $i < $length; $i++) {
    if ($alt == 1) {
      $password .= $consonants[(rand() % strlen($consonants))];
      $alt = 0;
    } else {
      $password .= $vowels[(rand() % strlen($vowels))];
      $alt = 1;
    }
  }
  return $password;
}


function convertStatus ($status) {

  $arrStatus = array("PERIODE_1"=>1, "MITEMPS_1"=>2,"PERIODE_2"=>3,
                     "MITEMPS_2"=>4,"PROLONG_1"=>5,"MITEMPS_3"=>6,
                     "PROLONG_2"=>7,"MITEMPS_4"=>8,"TAB"=>9,
                     "APRESMATCH"=>10);



  if (array_key_exists($status,$arrStatus)) {
    return $arrStatus[$status];
  }
  else {
    return 0;
  }
}

function getStatus ($statusKey) {

  $arrStatus = array(0=>"A venir",
  1=>"1ère mi-temps",
  2=>"Mi-temps",
  3=>"2ème période",
  4=>"Mi-temps avant prolongation",
  5=>"1ère prolongation",
  6=>"Mi-temps prolongation",
  7=>"2ème prolongation",
  8=>"Mi-temps avant tir au but",
  9=>"Tir au but",
  10=>"Terminé");


  if (array_key_exists($statusKey,$arrStatus))
  return $arrStatus[$statusKey];
  else
  return "";
}

function ConvertP4FKeyTolfp($p4fKey) {
  /*
   Saison 2010/2011 -> 11 -> 79
   Saison 2009/2010 -> 10 -> 78
   Saison 2008/2009 -> 9 -> 77
   Saison 2007/2008 -> 8 -> 76
   Saison 2006/2007 -> 7 -> 75
   Saison 2005/2006 -> 6 -> 74
   Saison 2004/2005 -> 5 -> 73
   Saison 2003/2004 -> 4 -> 72
   Saison 2002/2003 -> 3 -> 71
   Saison 2001/2002 -> 2 -> 70
   Saison 2000/2001 -> 1 -> 69
   */

  $arrp4fKeys = array(1 => 69,
  2 => 70,
  3 => 71,
  4 => 72,
  5 => 73,
  6 => 74,
  7 => 75,
  8 => 76,
  9 => 77,
  10 => 78,
  11 => 79);

  if (array_key_exists($p4fKey,$arrp4fKeys)) {
    return $arrp4fKeys[$p4fKey];
  }
  else {
    return 0;
  }
}


function ConvertLfpKeyToP4F($lfpKey) {
  /*
   *      AJ Auxerre -> 500220 -> 37
   FC Lorient -> 501913 -> 51
   RC Lens ->500369 -> 52
   AS Nancy Lorraine -> 500302 -> 45
   Olympique Lyonnais -> 500080 -> 33
   AS Monaco FC -> 500091 -> 38
   Olympique de Marseille ->500083 -> 34
   SM Caen -> 500075 -> 54
   OGC Nice -> 500208 -> 49
   Valenciennes FC -> 500250 -> 36
   Paris Saint-Germain -> 500247 -> 48
   AS Saint-Etienne -> 500225 -> 41
   Stade Rennais FC -> 500015 -> 42
   LOSC Lille Métropole -> 500054 -> 43
   FC Sochaux-Montbéliard -> 500303 -> 46
   AC Arles Avignon -> 500116 -> 53
   Toulouse FC -> 524391 -> 47
   Stade Brestois 29 -> 500024 -> 55
   Montpellier Hérault SC -> 500099 -> 40
   Girondins de Bordeaux -> 500211 -> 44
   AC Ajaccio -> 500765 -> 56
   Dijon FCO -> 547450 -> 57
   Evian TG FC -> 553251 -> 58
   CS Sedan      -> 500266 -> 59
   FC Nantes  -> 501904 -> 69
   SC Bastia -> 508009 -> 61
   EA Guingamp -> 500126 -> 70
   FC Metz -> 500154 -> 63
   RC Strasbourg -> 500191 -> 64
   ESTAC -> 500073 -> 65
   Havre AC -> 500052 -> 66
   Le Mans FC -> 537103 -> 67
   FC Istres -> 501523 -> 68
   Grenoble GF38 -> 546946 -> 69
   US Boulogne CO -> 500077 -> 70,
   Stade de reims -> 542397 -> 67
   */

  $arrlfpKeys = array(500220 => 37,
  501913 => 51,
  500369 => 52,
  500302 => 45,
  500080 => 33,
  500091 => 38,
  500083 => 34,
  500075 => 54,
  500208 => 49,
  500250 => 36,
  500247 => 48,
  500225 => 41,
  500015 => 42,
  500054 => 43,
  500303 => 46,
  500116 => 53,
  524391 => 47,
  500024 => 55,
  500099 => 40,
  500211 => 44,
  500765 => 56,
  547450 => 57,
  553251 => 58,
  500266 => 59,
  501904 => 69,
  508009 => 66,
  500126 => 70,
  500154 => 77,
  500191 => 64,
  500073 => 68,
  500052 => 66,
  537103 => 67,
  501523 => 68,
  546946 => 69,
  500077 => 70,
  "logo_lille_12_13" =>43,
  "ajaccio_oeil" => 56,
  "553251_new" => 58,
  542397 => 67,
  "Reims_Pantone" => 67,
  "estac_2012" => 68,
  "PSG_logo" => 48,
  "Monaco_AS"=>38,
  "Stade_reims"=>67,
  "logo_nice_100" => 49,
	501931 => 78,
	546836 => 79	
  );

  if (array_key_exists($lfpKey,$arrlfpKeys)) {
    return $arrlfpKeys[$lfpKey];
  }
  else {
    return 0;
  }
}

function ConvertUefaKeyToP4F($uefaKey) {
  /*
   *      Pologne -> 109 -> 59
   Grèce -> 49 -> 8
   Russie -> 57451 -> 60
   Répiblique Tchèque -> 58837 -> 61
   Pays-Bas -> 95 -> 17
   Danemark -> 35 -> 18
   Allemagne -> 47-> 13
   Portugal -> 110 -> 28
   Espagne -> 122 -> 29
   Italie -> 66 -> 21
   République d'irlande -> 64 -> 62
   Croatie -> 56370 -> 63
   Ukraine -> 57166 -> 64
   Suède -> 127 -> 65
   France -> 43 -> 1
   Angleterre -> 39 -> 9
   109 => 59,
  49 => 8,
  57451 => 60,
  58837 => 61,
  95 => 17,
  35 => 18,
  47 => 13,
  110 => 28,
  122 => 29,
  66 => 21,
  64 => 62,
  56370 => 63,
  57166 => 64,
  127 => 65,
  43 => 1,
  39 => 9
	 */

  $arrUefaKeys = 
		array(
			2 => 80, //Albanie
			43 => 1, //France
			113 => 81, //Roumanie
			128 => 30, //Suisse
			39 => 9, //Angleterre
			57451 => 60, //Russie
			58836 => 24, //Slovaquie
			144 => 82, //Pays de Galles
			47 => 13, //Allemagne
			63 => 83, //Irlande du nord
			109 => 59, //Pologne
			57166 => 64, //Ukraine
			56370 => 63, //Croatie
			58837 => 61, // République tchèque
			122 => 29, // Espagne
			135 => 84, //Turquie
			13 => 76, //Belgique
			66 => 21, //Italie
			64 => 62, //République d'irlande
			127 => 65, //Suède
			8 => 85, //Autriche
			57 => 86, //Hongrie
			58 => 87, //Islande
			110 => 28 //Portugal
	);

  if (array_key_exists($uefaKey,$arrUefaKeys)) {
    return $arrUefaKeys[$uefaKey];
  }
  else {
    return 0;
  }
}

function ConvertFifaCodeToP4F($fifaCode) {
  /*
   *      Brésil -> BRA->25
   Croatie -> CRO -> 63
   Mexique -> MEX -> 3
   Cameroun -> CMR -> 20
   Pays-Bas -> NED -> 17
   Espagne -> ESP -> 29
   Australie -> AUS -> 14
   Chilie -> CHI -> 32
   Grèce -> GRE -> 8
   Colombie -> COL -> 71
   Uruguay -> URU -> 4
   Costa Rica -> CRC -> 72
   Angleterre -> ENG -> 9
   Italie -> ITA -> 21
   Japon -> JPN -> 19
   Code d'ivoire -> CIV -> 27
   Suisse -> SUI -> 30
   Equateur -> ECU -> 73
   France -> FRA -> 1
   Honduras -> HON -> 31
   Argentine -> ARG -> 5
   Bosnie et herzegovine -> BIH -> 74
   Allemagne -> GER -> 13
   Portugal -> POR0 -> 28
   Iran -> IRN -> 75
   Nigeria -> NGA -> 6
   Etat-unis -> USA -> 10
   Ghana -> GHA -> 16
   Algérie -> ALG -> 11
   Belgique -> BEL -> 76
   Russie -> RUS -> 60
   République de corée -> KOR -> 7
   */

  $arrFifaCodes = array("BRA" => 25,
  "CRO" => 63,
  "MEX" => 3,
  "CMR" => 20,
  "NED" => 17,
  "ESP" => 29,
  "AUS" => 14,
  "CHI" => 32,
  "GRE" => 8,
  "COL" => 71,
  "URU" => 4,
  "CRC" => 72,
  "ENG" => 9,
  "ITA" => 21,
  "JPN" => 19,
  "CIV" => 27,
  "SUI" => 30,
  "ECU" => 73,
  "ARG" => 5,
  "BIH" => 74,
  "GER" => 13,
  "POR" => 28,
  "IRN" => 75,
  "NGA" => 6,
  "USA" => 10,
  "GHA" => 16,
  "ALG" => 11,
  "BEL" => 76,
  "RUS" => 60,
  "KOR" => 7,
  "FRA" => 1,
  "HON" => 31);

  if (array_key_exists($fifaCode,$arrFifaCodes)) {
    return $arrFifaCodes[$fifaCode];
  }
  else {
    return 0;
  }
}

function ConvertFrenchDateToUniversalDate ($strDate) {

  $findValues = array('Lundi ',
                      'Mardi ',
                      'Mercredi ',
                      'Jeudi ',
                      'Vendredi ',
                      'Samedi ',
                      'Dimanche ',
                      ' janvier ',
                      ' février ',
                      ' mars ',
                      ' avril ',
                      ' mai ',
                      ' juin ',
                      ' juillet ',
                      ' août ',
                      ' septembre ',
                      ' octobre ',
                      ' novembre ',
                      ' décembre ');
  $replaceValues = array('','','','','','','',
                         '/1/','/2/','/3/','/4/','/5/','/6/','/7/','/8/','/9/','/10/','/11/','/12/');

  $strDate = str_replace($findValues, $replaceValues, $strDate);
  return $strDate;
}

function array_value_exists ($array, $key, $value){
  if (is_array($array) && $key && $value) {
    if (seekKey ($array, $key, $value)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }

}


function seekKey($array, $key, $value)
{
  $ret = array();
  for ($i=0;$i<count($array);$i++)
  {
    if ($array[$i][$key]==$value)
    $ret[] = $array[$i];
  }
  return $ret;
}

function array_value_exists2 ($array, $value){
  if (is_array($array)  && $value) {
    if (seekKey2 ($array, $value)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }

}


function seekKey2($array, $value)
{
  $ret = array();
  for ($i=0;$i<count($array);$i++)
  {
    if ($array[$i]==$value)
    $ret[] = $array[$i];
  }
  return $ret;
}

function writeJsonResponse ($arr){
  global $_databaseObject;

  header("Expires: Mon, 11 Jul 1998 05:00:00 GMT" );
  header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
  header("Cache-Control: no-cache, must-revalidate" );
  header("Pragma: no-cache" );
  header("Content-type: application/json; charset=utf-8");
  if (WITH_PERF_AND_ERROR) {
    $arr["perfAndError"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
  }
  echo json_encode($arr);
}

function writeGoogleAnalytics () {
  $content = "<script type='text/javascript'>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-10328675-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
  })();

</script>";
  echo $content;
}

function write000WebHostStats () {
  $content = '<div style="display:none;"><!-- START OF HIT COUNTER CODE -->
<br><script language="JavaScript" src="http://www.counter160.com/js.js?img=15"></script><br><a href="http://www.000webhost.com"><img src="http://www.counter160.com/images/15/left.png" alt="free web hosting" border="0" align="texttop"></a><a href="http://www.hosting24.com"><img alt="Hosting24.com web hosting" src="http://www.counter160.com/images/15/right.png" border="0" align="texttop"></a>
<!-- END OF HIT COUNTER CODE --></div>';
  echo $content;
}

function writePerfInfo () {
  global $_databaseObject;

  if (WITH_PERF_AND_ERROR || 1==1) {
    $arr = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');

    $totaltime = getElapsedTime();
    echo "<pre style='display:none'> This page loaded in $totaltime seconds.</pre>";
    echo '<pre style="display:none">', print_r ($arr), '</pre>';
    echo '<pre style="display:none">';

    //    $script_tz = date_default_timezone_get();
    //
    //    if (strcmp($script_tz, ini_get('date.timezone'))){
    //      echo 'Script timezone differs from ini-set timezone.';
    //      echo $script_tz;
    //    } else {
    //      echo 'Script timezone and ini-set timezone match.';
    //      echo $script_tz;
    //    }
  }
  echo '</pre>';
}

$_arrScripts = array();
function AddScriptReference ($name) {
  global $_arrScripts;
  global $_themePath;
  $jsScriptTemplate = '<script type="text/javascript" src="' . ROOT_SITE . '/js/%s.js"></script>';
  $jsScriptTemplateWithTheme = '<script type="text/javascript" src="' . ROOT_SITE .$_themePath . '/js/%s.js"></script>';
  $cssScriptTemplate = '<link rel="stylesheet" type="text/css" href="' . ROOT_SITE .$_themePath . '/css/%s.css" media="all"/>';

  if (!array_key_exists($name,$_arrScripts)) {
    switch ($name) {
      // modules
      case "live":
        $tempScript=sprintf($cssScriptTemplate,"modules/live");
        $_arrScripts[$name]=$tempScript;
        break;
      case "result.group":
        $tempScript=sprintf($cssScriptTemplate,"modules/result.group");
        $_arrScripts[$name]=$tempScript;
        break;
      case "result.group2":
        $tempScript=sprintf($cssScriptTemplate,"modules/result.group2");
        //$tempScript.=sprintf($cssScriptTemplate,"ranking");
        $_arrScripts[$name]=$tempScript;
        break;
      case "statistics":
        $tempScript=sprintf($jsScriptTemplateWithTheme,"modules/statistics");
        $tempScript.=sprintf($cssScriptTemplate,"modules/statistics");
        $_arrScripts[$name]=$tempScript;
        break;
      case "ligue1.statistics":
        $tempScript=sprintf($jsScriptTemplateWithTheme,"modules/ligue1.statistics");
        $tempScript.=sprintf($cssScriptTemplate,"modules/statistics");
        $_arrScripts[$name]=$tempScript;
        break;
      case "p4f.statistics":
        $tempScript=sprintf($jsScriptTemplateWithTheme,"modules/p4f.statistics");
        $tempScript.=sprintf($cssScriptTemplate,"modules/statistics");
        $_arrScripts[$name]=$tempScript;
        break;
      case "account.change.password":
        $tempScript=sprintf($cssScriptTemplate,"modules/account.change.password");
        $_arrScripts[$name]=$tempScript;
        break;
      case "account.email.validation":
        $tempScript=sprintf($cssScriptTemplate,"modules/account.email.validation");
        $_arrScripts[$name]=$tempScript;
        break;
      case "home":
        $tempScript=sprintf($cssScriptTemplate,"modules/home");
        $_arrScripts[$name]=$tempScript;
        break;
      case "forecasts.agenda":
        $tempScript=sprintf($cssScriptTemplate,"modules/forecasts.agenda");
        $_arrScripts[$name]=$tempScript;
        break;
      case "admin.matches":
        $tempScript=sprintf($cssScriptTemplate,"modules/admin.matches");
        $_arrScripts[$name]=$tempScript;
        break;
      case "home.connected":
        $tempScript=sprintf($cssScriptTemplate,"modules/home.connected");
        $tempScript.=sprintf($cssScriptTemplate,"ranking");
        $_arrScripts[$name]=$tempScript;
        break;
      case "new.home.connected":
        $tempScript=sprintf($cssScriptTemplate,"modules/home.connected");
        $tempScript.=sprintf($cssScriptTemplate,"ranking");
        $_arrScripts[$name]=$tempScript;
        break;
      case "ranking.group":
        $tempScript=sprintf($cssScriptTemplate,"modules/ranking.group");
        $_arrScripts[$name]=$tempScript;
        break;
      case "ranking.teams.competition":
        $tempScript=sprintf($cssScriptTemplate,"modules/ranking.teams.competition");
        $_arrScripts[$name]=$tempScript;
        break;
      case "match.stats.detail":
        $tempScript=sprintf($cssScriptTemplate,"match.stats.detail");
        $_arrScripts[$name]=$tempScript;
        break;
      case "p4f.ranking.division":
        $tempScript=sprintf($cssScriptTemplate,"modules/p4f.ranking.division");
        $_arrScripts[$name]=$tempScript;
        break;
      case "p4f.cup.draw":
        $tempScript=sprintf($cssScriptTemplate,"modules/p4f.cup.draw");
        $_arrScripts[$name]=$tempScript;
        break;
      case "winners":
        $tempScript=sprintf($cssScriptTemplate,"modules/winners");
        $_arrScripts[$name]=$tempScript;
        break;
        //Components
      case "flexigrid":
        $tempScript=sprintf($jsScriptTemplate,"flexigrid");
        $tempScript.=sprintf($cssScriptTemplate,"flexigrid");
        $_arrScripts[$name]=$tempScript;
        break;
      case "smartTab":
        $tempScript=sprintf($jsScriptTemplate,"jquery.smartTab");
        $tempScript.=sprintf($cssScriptTemplate,"smart_tab");
        $_arrScripts[$name]=$tempScript;
        break;
      case "countdown":
        $tempScript=sprintf($jsScriptTemplate,"jquery.countdown");
        $tempScript.=sprintf($jsScriptTemplate,"jquery.countdown-fr");
        $tempScript.=sprintf($cssScriptTemplate,"jquery.countdown");
        $_arrScripts[$name]=$tempScript;
        break;
      case "scrollpane":
        $tempScript=sprintf($cssScriptTemplate,"jquery.jscrollpane");
        $tempScript.=sprintf($cssScriptTemplate,"jquery.jscrollpane.p4f");
        $tempScript.=sprintf($jsScriptTemplate,"jquery.jscrollpane");
        $tempScript.=sprintf($jsScriptTemplate,"jquery.mousewheel");
        $_arrScripts[$name]=$tempScript;
        break;
      case "highcharts":
        $tempScript=sprintf($jsScriptTemplate,"highcharts");
        $_arrScripts[$name]=$tempScript;
        break;
      case "bsmselect":
        $tempScript=sprintf($jsScriptTemplate,"jquery.bsmselect");
        $tempScript.=sprintf($cssScriptTemplate,"jquery.bsmselect");
        $_arrScripts[$name]=$tempScript;
        break;
      case "cluetip":
        $tempScript=sprintf($jsScriptTemplate,"jquery.cluetip");
        $tempScript.=sprintf($cssScriptTemplate,"jquery.cluetip");
        $_arrScripts[$name]=$tempScript;
        break;
      case "progressbar":
        $tempScript=sprintf($jsScriptTemplate,"jquery.progressbar");
        $_arrScripts[$name]=$tempScript;
        break;
      case "spotlight":
        $tempScript=sprintf($jsScriptTemplate,"jquery.spotlight");
        $_arrScripts[$name]=$tempScript;
        break;
      case "dropdownchecklist":
        $tempScript=sprintf($jsScriptTemplate,"ui.dropdownchecklist");
        $tempScript.=sprintf($cssScriptTemplate,"ui.dropdownchecklist.themeroller");
        $_arrScripts[$name]=$tempScript;
        break;
      case "validate":
        $tempScript=sprintf($jsScriptTemplate,"jquery.validate");
        $tempScript.=sprintf($jsScriptTemplate,"jquery.validation.functions");
        $tempScript.=sprintf($cssScriptTemplate,"jquery.validate");
        $_arrScripts[$name]=$tempScript;
        break;
      case "numberinput":
        $tempScript=sprintf($jsScriptTemplate,"jquery.numberinput");
        $_arrScripts[$name]=$tempScript;
        break;
      case "spin":
        $tempScript=sprintf($jsScriptTemplate,"jquery.spin");
        $_arrScripts[$name]=$tempScript;
        break;
      case "allipsis":
        $tempScript=sprintf($jsScriptTemplate,"jquery.ellipsis");
        $_arrScripts[$name]=$tempScript;
        break;
      case "overflow":
        $tempScript=sprintf($jsScriptTemplate,"jquery.text-overflow");
        $_arrScripts[$name]=$tempScript;
        break;
      case "threedots":
        $tempScript=sprintf($jsScriptTemplate,"jquery.ThreeDots.min");
        $_arrScripts[$name]=$tempScript;
        break;
      case "ckeditor":
        $tempScript = '<script type="text/javascript" src="' . ROOT_SITE . '/ckeditor/ckeditor.js"></script>';
        $tempScript .= '<script type="text/javascript" src="' . ROOT_SITE . '/ckeditor/adapters/jquery.js"></script>';
        $_arrScripts[$name]=$tempScript;
        break;
      case "color":
        $tempScript=sprintf($jsScriptTemplate,"jquery.colorBlend.pack");
        $_arrScripts[$name]=$tempScript;
        break;
      case "ibutton":
        $tempScript=sprintf($jsScriptTemplate,"jquery.ibutton");
        $tempScript.=sprintf($cssScriptTemplate,"jquery.ibutton.p4f");
        $_arrScripts[$name]=$tempScript;
        break;
      case "tokeninput":
        $tempScript=sprintf($jsScriptTemplate,"jquery.tokeninput");
        $tempScript.=sprintf($cssScriptTemplate,"token-input");
        $tempScript.=sprintf($cssScriptTemplate,"token-input-p4f");
        $_arrScripts[$name]=$tempScript;
        break;
      case "gracket":
        $tempScript=sprintf($jsScriptTemplate,"jquery.gracket");
        $_arrScripts[$name]=$tempScript;
        break;
			case "json2html":
        $tempScript=sprintf($jsScriptTemplate,"jquery.json2html");
        $tempScript.=sprintf($jsScriptTemplate,"json2html");
        $_arrScripts[$name]=$tempScript;
        break;
      case "dropdown":
        $tempScript=sprintf($jsScriptTemplate,"jquery.dropdown.min");
        $tempScript.=sprintf($cssScriptTemplate,"jquery.dropdown.min");
        $_arrScripts[$name]=$tempScript;
        break;
    }
  }

}

function WriteScripts () {
  global $_arrScripts;
  while (list ($key, $value) = each ($_arrScripts)) {
    echo $value;
  }
}

function getBrowser()
{
  $u_agent = $_SERVER['HTTP_USER_AGENT'];
  $bname = 'Unknown';
  $platform = 'Unknown';
  $version= "";

  //First get the platform?
  if (preg_match('/linux/i', $u_agent)) {
    $platform = 'linux';
  }
  elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
    $platform = 'mac';
  }
  elseif (preg_match('/windows|win32/i', $u_agent)) {
    $platform = 'windows';
  }

  // Next get the name of the useragent yes seperately and for good reason
  if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
  {
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  }
  elseif(preg_match('/Firefox/i',$u_agent))
  {
    $bname = 'Mozilla Firefox';
    $ub = "Firefox";
  }
  elseif(preg_match('/Chrome/i',$u_agent))
  {
    $bname = 'Google Chrome';
    $ub = "Chrome";
  }
  elseif(preg_match('/Safari/i',$u_agent))
  {
    $bname = 'Apple Safari';
    $ub = "Safari";
  }
  elseif(preg_match('/Opera/i',$u_agent))
  {
    $bname = 'Opera';
    $ub = "Opera";
  }
  elseif(preg_match('/Netscape/i',$u_agent))
  {
    $bname = 'Netscape';
    $ub = "Netscape";
  }

  // finally get the correct version number
  $known = array('Version', $ub, 'other');
  $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
  if (!preg_match_all($pattern, $u_agent, $matches)) {
    // we have no matching number just continue
  }

  // see how many we have
  $i = count($matches['browser']);
  if ($i != 1) {
    //we will have two since we are not using 'other' argument yet
    //see if version is before or after the name
    if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
      $version= $matches['version'][0];
    }
    else {
      $version= $matches['version'][1];
    }
  }
  else {
    $version= $matches['version'][0];
  }

  // check if we have a number
  if ($version==null || $version=="") {$version="?";}

  return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
  );
}

// now try it
//$ua=getBrowser();
//$yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
//print_r($yourbrowser);

function startswith($haystack, $needle) {
  return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function endswith($hay, $needle) {
  return substr($hay, -strlen($needle)) === $needle;
}

function mysql_escape_mimic($inp) {
    if(is_array($inp))
        return array_map(__METHOD__, $inp);

    if(!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
    }

    return $inp;
}
?>
