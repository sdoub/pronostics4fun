<?php
require_once("begin.file.php");

$_matchKey = $_GET["MatchKey"];

$query= "SELECT
players.NickName,
UNIX_TIMESTAMP(forecasts.ForecastDate) ForecastDate
FROM forecasts
INNER JOIN playersenabled players ON forecasts.PlayerKey=players.PrimaryKey AND forecasts.MatchKey=$_matchKey
ORDER BY ForecastDate";

$resultSet = $_databaseObject->queryPerf($query,"Get players who has validated his forecast for current match");
echo "<div class='tooltipTitle' >Liste des pronostiqueurs</div>";
echo "<div style='color:#FFFFFF; font-size:11px;'>";
echo "<ul>";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $forecastFormattedDate = __encode(strftime("%A %d %B %Y à %H:%M",$rowSet['ForecastDate']));
  echo "<li style='height:16px;padding-left:5px;'><strong>". $rowSet["NickName"] ."</strong> -> validé le " . $forecastFormattedDate . "<br/></li>";
}
echo "</ul>";
echo "</div>";

require_once("end.file.php");
?>