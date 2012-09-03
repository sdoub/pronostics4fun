<?php
require_once("begin.file.php");

$_groupKey = $_GET["GroupKey"];

$query= "SELECT
players.NickName,
MAX(UNIX_TIMESTAMP(votes.VoteDate)) VoteDate
FROM votes
INNER JOIN playersenabled players ON votes.PlayerKey=players.PrimaryKey
  AND votes.MatchKey IN (SELECT matches.PrimaryKey FROm matches WHERE matches.GroupKey=$_groupKey)
GROUP BY players.NickName
ORDER BY VoteDate";

$resultSet = $_databaseObject->queryPerf($query,"Get players who has validated his vote for current group");
echo "<div style='text-align:center;background-color:#365F89;font-weight:bold;width:350px;color:#FFFFFF;margin-bottom:10px;'>Liste des votants</div>";
echo "<div style='color:#FFFFFF; font-size:11px;'>";
echo "<ul>";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  $voteFormattedDate = strftime("%A %d %B %Y à %H:%M",$rowSet['VoteDate']);
  echo "<li style='height:16px;padding-left:5px;'><strong>". $rowSet["NickName"] ."</strong>". " -> validé le " . $voteFormattedDate . "<br/></li>";
}
echo "</ul>";
echo "</div>";

require_once("end.file.php");
?>