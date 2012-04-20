<?php
require_once("begin.file.php");

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>' . __encode("Pronostics4Fun - Statistiques de la base de données") . '</title>
<link rel="icon" href="http://pronostics4fun.com/favico.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://pronostics4fun.com/favico.ico" type="image/x-icon" />
</head>
<body>
';


echo "<p align='center'>Si ce message ne s'affiche pas correctement, visualisez-le <a href='".ROOT_SITE."/database.stats.php'>ici</a></p><hr/>";

echo "<div ><a style='border:0;' href='".ROOT_SITE."'><img style='border:0;' src='".ROOT_SITE."/images/Logo.png' ></a></div><br>";

echo '<p>Bonjour,</p>';

echo '<table style="width:500px;font-size:14px;border-spacing:0px;border-collapse:collapse">
<tr style="background-color:#6d8aa8;color:#FFFFFF;font-weight:bold;">
<td style="vertical-align: middle;font-size:14px;font-variant: small-caps ;" colspan="3"><img src="' . ROOT_SITE . '/images/stats.gif" style="height:20px;width:20px;padding-right:15px;"/>Statistiques</td>
</tr>';


/**
 * Retourne la taille plus l'unité arrondie
 *
 * @param mixed $bytes taille en octets
 * @param string $format formatage (http://www.php.net/manual/fr/function.sprintf.php)
 * @param string $lang indique la langue des unités de taille
 * @return string chaine de caractères formatées
 */
function formatSize($bytes,$format = '%.2f',$lang = 'en')
{
	static $units = array(
	'fr' => array(
	'o',
	'Ko',
	'Mo',
	'Go',
	'To'
	),
	'en' => array(
	'B',
	'KB',
	'MB',
	'GB',
	'TB'
	));
	$translatedUnits = &$units[$lang];
	if(isset($translatedUnits)  === false)
	{
		$translatedUnits = &$units['en'];
	}
	$b = (double)$bytes;
	/*On gére le cas des tailles de fichier négatives*/
	if($b > 0)
	{
		$e = (int)(log($b,1024));
		/**Si on a pas l'unité on retourne en To*/
		if(isset($translatedUnits[$e]) === false)
		{
			$e = 4;
		}
		$b = $b/pow(1024,$e);
	}
	else
	{
		$b = 0;
		$e = 0;
	}
	return sprintf($format.' %s',$b,$translatedUnits[$e]);
}
$sql = "SHOW TABLE STATUS";

$resultSet = $_databaseObject->queryPerf($sql,"Get table status");
$totalSize = 0;
$totalFreeSize = 0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{

  if ($rowSet["Rows"]){
    echo '
<tr style="border-bottom:1px solid #CCCCCC;">';

    $totalFreeSize+=$rowSet["Data_free"];
    $totalSize+=$rowSet["Data_length"]+$rowSet["Index_length"];
    $size=formatSize($rowSet["Data_length"]+$rowSet["Index_length"],'%.2f','fr');
    $freeSize=formatSize($rowSet["Data_free"],'%.0f','fr');
    if ($rowSet["Data_free"]>0) {
      $size.=" (<span style='color:red;'>".$freeSize."</span>)";
    }
    echo '<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:120px;text-align:left;">'.$rowSet["Name"].'</td>
<td style="border-bottom:1px solid #CCCCCC;vertical-align:bottom;width:80px;text-align:right;">'.$rowSet["Rows"].' rows</td>
<td style="border-bottom:1px solid #CCCCCC;vertical-align:top;width:80px;text-align:right;">'.$size.'</td></tr>';
  }
}

$formattedTotalFreeSize = formatSize($totalSize,'%.2f','fr');
if ($totalFreeSize) {
  $formattedTotalFreeSize.=" (<span style='color:red;'>".formatSize($totalFreeSize,'%.0f','fr')."</span>)";
}

echo '
<tr>
<td colspan="1">&nbsp;</td>
<td >Total</td>
<td style="text-align:right;padding-right:5px;font-weight:bold;font-style:italic;">'.$formattedTotalFreeSize.'</td>
</tr>';

echo "</table>";
if ($totalFreeSize) {
  echo __encode("<p style='font-size:12px;color:red;'><sup>*</sup> Le chiffre en rouge signifie une perte d'espace, la table devrait être optimisée</p>");
}
echo "<p>L'administrateur de Pronostics4Fun.</p>
</body>
</html>";

//writePerfInfo();

require_once("end.file.php");
?>