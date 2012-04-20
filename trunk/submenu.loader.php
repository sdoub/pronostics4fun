<?php
if ($_isAuthenticated)
{
  switch ($_currentPage) {
    case "1":
      $submenu = '<ul id="navMenu2">';

      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Calendar=1" ><span>Calendrier</span></a></li>';

      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Calendar=0" ><span>Liste</span></a></li>';
      $submenu .= '</ul>';
      echo $submenu;

      break;

    case "2":
//      $submenu = '<ul id="navMenu2">';
//
//      $sql = "SELECT PrimaryKey GroupKey, Description, Code FROM groups WHERE CompetitionKey=" . COMPETITION;
//      $resultSet = $_databaseObject->queryPerf($sql,"Get groups");
//
//
//      while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
//      {
//        $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&GroupKey='. $rowSet["GroupKey"] . '" id="nav_home"><span>' . $rowSet["Code"] . '</span></a></li>';
//
//      }
//      unset($rowSet,$resultSet,$sql);
//
//      $submenu .= '</ul>';
//      echo $submenu;

      break;
    case "3":
      $submenu = '<ul id="navMenu2">';
      $submenu .= '<li><span style="background-color: #365F89;color: white;padding: 2px 5px 2px 5px;border: solid 1px white;">' . __encode("p4f") . '</span></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Global" ><span>' . __encode("Général") . '</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Group" ><span>' . __encode("Journée") . '</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Wins" ><span>' . __encode("Podium") . '</span></a></li>';
      $submenu .= '<li><span style="margin-left:100px;background-color: #365F89;color: white;padding: 2px 5px 2px 5px;border: solid 1px white;">' . __encode("Ligue 1 :") . '</span></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Teams" ><span>' . __encode("Championnat") . '</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Scorer" ><span>' . __encode("Buteur") . '</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Assist" ><span>' . __encode("Passeur") . '</span></a></li>';
      $submenu .= '</ul>';
      echo $submenu;

      break;
    case "5":
      $scheduleDate = time();

      $query= "SELECT
	groups.PrimaryKey GroupKey,
	groups.Description GroupDescription,
	groups.DayKey
	FROM groups
	WHERE EXISTS (
	SELECT 1
	  FROM matches
	 WHERE matches.GroupKey=groups.primaryKey
	   AND DATE(matches.ScheduleDate)=DATE(FROM_UNIXTIME($scheduleDate)))
	 AND groups.CompetitionKey=" . COMPETITION;

      $resultSet = $_databaseObject -> queryPerf ($query, "Get today matches");
      if ($_databaseObject -> num_rows()>1) {
      $submenu = '<ul id="navMenu2">';

      while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
      {
        $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&DayKey='. $rowSet["DayKey"] . '" id="nav_home"><span>' . __encode($rowSet["GroupDescription"]) . '</span></a></li>';
      }
      unset($rowSet,$resultSet,$sql);

      $submenu .= '</ul>';
      echo $submenu;


      }
      break;
    case "6":
      $submenu = '<ul id="navMenu2">';
      $submenu .= '<li><span style="background-color: #365F89;color: white;padding: 2px 5px 2px 5px;border: solid 1px white;">' . __encode("p4f") . '</span></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Ranking" ><span>' . __encode("Position") . '</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Score" ><span>' . __encode("Point") . '</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Global" ><span>' . __encode("Globale") . '</span></a></li>';
      $submenu .= '<li><span style="margin-left:100px;background-color: #365F89;color: white;padding: 2px 5px 2px 5px;border: solid 1px white;">' . __encode("Ligue 1 :") . '</span></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Goals" ><span>' . __encode("But") . '</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=ScoreLigue1" ><span>' . __encode("Score") . '</span></a></li>';
      $submenu .= '</ul>';
      echo $submenu;

      break;
    case "7":
      $submenu = '<ul id="navMenu2">';

      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Mode=Reminder" ><span>' . __encode("Rappel") . '</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Mode=Matches" ><span>' . __encode("Matchs") . '</span></a></li>';
      $submenu .= '</ul>';
      echo $submenu;

      break;
  }
}
?>