<?php
require_once("begin.file.php");

$dir    = __DIR__ . '/images/avatars';
$files = array_diff(scandir($dir), array('..', '.'));


echo count($files);
$players = PlayersQuery::create()->find();
foreach($players as $player) {
  echo "<br/>";
	$path_parts = pathinfo(__DIR__ . '/images/avatars/'.$player->getAvatarname());
	if (array_key_exists('extension', $path_parts)) {
		echo $path_parts['extension'];
		echo $player->getAvatarname();
		$files = array_diff($files, array($player->getPrimaryKey().'original.'.$path_parts['extension'], $player->getAvatarname()));
	} else {
		echo $player->getAvatarname();
		$files = array_diff($files, array($player->getPrimaryKey().'original', $player->getAvatarname()));
	}
	echo "<br/>";
}

foreach ($files as $file){
    unlink(__DIR__ . '/images/avatars/'.$file);
}
echo count($files);
require_once("end.file.php");
?>