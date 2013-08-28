<?php
require_once("begin.file.php");

$query = sprintf("SELECT 100 ItemKey, PrimaryKey, NickName, AvatarName from players WHERE NickName LIKE '%%%s%%'
ORDER BY 2 DESC LIMIT 10", mysql_real_escape_string($_GET["q"]));

$resultSet = $_databaseObject->queryPerf($query,"Get division players");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  unset($tempArray);
  $tempArray["id"]=$rowSet["ItemKey"].$rowSet["PrimaryKey"];
  $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
  if (!empty($rowSet["AvatarName"])) {
    $avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["AvatarName"];
  }

  $tempArray["nickname"] = $rowSet["NickName"];
  $tempArray["avatar"] = $avatarPath;

  $arr[]=$tempArray;
}

$query = sprintf("SELECT 200 ItemKey, PrimaryKey, Description, Code FROM seasons WHERE Description LIKE '%%%s%%' AND CompetitionKey =".COMPETITION."
 ORDER BY 2 DESC LIMIT 10", mysql_real_escape_string($_GET["q"]));

$resultSet = $_databaseObject->queryPerf($query,"Get division players");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  unset($tempArray);
  $tempArray["id"]=$rowSet["ItemKey"].$rowSet["PrimaryKey"];
  $tempArray["nickname"] = $rowSet["Description"];
  $tempArray["avatar"] = ROOT_SITE. $_themePath. '/images/season.png';

  $arr[]=$tempArray;
}

$query = sprintf("SELECT 300 ItemKey, PrimaryKey, Description, Code FROM divisions WHERE Description LIKE '%%%s%%'
 ORDER BY 2 DESC LIMIT 10", mysql_real_escape_string($_GET["q"]));

$resultSet = $_databaseObject->queryPerf($query,"Get division players");

while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  unset($tempArray);
  $tempArray["id"]=$rowSet["ItemKey"].$rowSet["PrimaryKey"];
  $tempArray["nickname"] = $rowSet["Description"];
  $tempArray["avatar"] = ROOT_SITE. $_themePath. '/images/division.png';

  $arr[]=$tempArray;
}


# JSON-encode the response
$json_response = json_encode($arr);

# Optionally: Wrap the response in a callback function for JSONP cross-domain support
if($_GET["callback"]) {
    $json_response = $_GET["callback"] . "(" . $json_response . ")";
}
echo $json_response;

require_once("end.file.php");
?>