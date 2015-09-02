<?php
require_once("begin.file.php");

$rec_limit = 10;
/* Get total number of records */
$sql = "SELECT COUNT(NewsKey) NbrOfNews FROM (
		SELECT news.PrimaryKey NewsKey, news.Information NewsInfos,InfoType, 
					 '' NewsPicture,UNIX_TIMESTAMP(news.InfoDate) NewsDate
			FROM news
		 WHERE CompetitionKey=" .COMPETITION . "
		 UNION ALL
		SELECT playersenabled.PrimaryKey,
					 playersenabled.NickName NewsInfos,
					 3 InfoType,
					 playersenabled.AvatarName NewsPicture,
					 UNIX_TIMESTAMP(playersenabled.CreationDate) NewsDate
			FROM playersenabled
		 WHERE playersenabled.CreationDate > CURDATE() - INTERVAL 365 DAY
	) NewsList";
$resultSet = $_databaseObject->queryPerf($sql,"Get total news");
$rowSet = $_databaseObject -> fetch_assoc ($resultSet);
$rec_count = $rowSet["NbrOfNews"];

if( isset($_GET{'Page'} ) )
{
   $page = $_GET{'Page'} - 1;
   $offset = $rec_limit * $page ;
}
else
{
   $page = 0;
   $offset = 0;
}
$left_rec = $rec_count - ($page * $rec_limit);
$arr["TotalNews"] = $rec_count;
$arr["News"] = array();

$query = "SELECT NewsKey, NewsInfos,InfoType, NewsPicture,NewsDate FROM (
		SELECT news.PrimaryKey NewsKey, news.Information NewsInfos,InfoType, '' NewsPicture,UNIX_TIMESTAMP(news.InfoDate) NewsDate
			FROM news
		 WHERE CompetitionKey=" .COMPETITION . "
		 UNION ALL
		SELECT playersenabled.PrimaryKey,
					 playersenabled.NickName NewsInfos,
					 3 InfoType,
					 playersenabled.AvatarName NewsPicture,
					 UNIX_TIMESTAMP(playersenabled.CreationDate) NewsDate
			FROM playersenabled
		 WHERE playersenabled.CreationDate > CURDATE() - INTERVAL 365 DAY
	) NewsList
	ORDER BY NewsList.NewsDate desc
	LIMIT $offset, $rec_limit
";
$resultSet = $_databaseObject->queryPerf($query,"Get news");

$cnt = 0;
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
	$tempNews = array();
	$tempNews["id"] = $rowSet["NewsKey"];
	$newsInformation="";
	if ($rowSet["InfoType"]=="3"){
		$newsInformation .= "<div class='player'>";
		$avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
		if (!empty($rowSet["NewsPicture"])) {
			$avatarPath= ROOT_SITE. '/images/avatars/'.$rowSet["NewsPicture"];
		}
		$newsInformation .= '<img class="avat" src="' . $avatarPath . '" ></img>';
		$creationFormattedDate = strftime("%A %d %B %Y",$rowSet['NewsDate']);
		$newsInformation .= "<strong>" . __encode($rowSet['NewsInfos'] ."</strong>". __encode(" est inscrit depuis le ") . $creationFormattedDate);
		$newsInformation .= "</div>";
		$tempNews["information"] = $newsInformation; 
	} else {
		$newsFormattedDate = strftime("%A %d %B %Y à %H:%M",$rowSet['NewsDate']);
		$tempNews["formattedDate"] = "Le " . __encode($newsFormattedDate);
		if (strpos($rowSet["NewsInfos"],"<img") === false){
			switch ($rowSet["InfoType"]) {
				case "1":
					$newsInformation .= '<img  class="news" src="' . ROOT_SITE . '/images/news.png" ></img>';
					break;
				case "2":
					$newsInformation .= '<img  class="news" src="' . ROOT_SITE . '/images/stats.png" ></img>';
					break;
				case "5":
					$newsInformation .= '<img  class="news" src="' . ROOT_SITE . '/images/calendar.png" ></img>';
					break;
				case "6":
					$newsInformation .= '<img  class="news" src="' . ROOT_SITE . '/images/TropheeGold.png" ></img>';
					break;
				case "7":
					$newsInformation .= '<img  class="news" src="' . ROOT_SITE . '/images/podium.png" ></img>';
					break;
				case "8":
					$newsInformation .= '<img  class="news" src="' . ROOT_SITE . '/images/star_48.png" ></img>';
					break;
			}
		}
		$newsInformation .= __encode($rowSet['NewsInfos']);
		$tempNews["information"] = $newsInformation;
	}
	$arr["News"][] = $tempNews;
}

writeJsonResponse($arr);
require_once("end.file.php");
?>