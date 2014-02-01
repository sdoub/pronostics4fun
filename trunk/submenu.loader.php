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
      $submenu = '<ul id="navMenu2">';

      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Mode=1" ><span>Vue 1</span></a></li>';

      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Mode=2" ><span>Vue 2</span></a></li>';
      $submenu .= '</ul>';
      echo $submenu;
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
      $submenu .= '<li><span class="menuGroup" style="padding: 2px 5px 2px 5px;">p4f</span></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Global" ><span>Général</span></a></li>';
      if ($_competitionType==1) {
        $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Group" ><span>Journée</span></a></li>';
      } else {
        $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Group" ><span>Phase</span></a></li>';
      }
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Wins" ><span>Podium</span></a></li>';
      $submenu .= '<li><span class="menuGroup" style="margin-left:100px;padding: 2px 5px 2px 5px;">' . $_competitionName .' :</span></li>';
      $competitionTitle = "Championnat";
      if ($_competitionType==3) {
        $competitionTitle = "Tournoi";
      }
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Teams" ><span>' . $competitionTitle . '</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Scorer" ><span>Buteur</span></a></li>';
      if ($_competitionType==1) {
        $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Assist" ><span>Passeur</span></a></li>';
      }
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
        $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&DayKey='. $rowSet["DayKey"] . '" id="nav_home"><span>' . $rowSet["GroupDescription"] . '</span></a></li>';
      }
      unset($rowSet,$resultSet,$sql);

      $submenu .= '</ul>';
      echo $submenu;


      }
      break;
    case "6":
      $submenu = '<ul id="navMenu2">';
      $submenu .= '<li><span class="menuGroup" style="padding: 2px 5px 2px 5px;">p4f</span></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Ranking" ><span>Position</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Score" ><span>Point</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Global" ><span>Globale</span></a></li>';
      $submenu .= '<li><span class="menuGroup" style="margin-left:100px;padding: 2px 5px 2px 5px;">' . $_competitionName .' :</span></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=Goals" ><span>But</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&View=ScoreLigue1" ><span>Score</span></a></li>';
      $submenu .= '</ul>';
      echo $submenu;

      break;
    case "7":
      $submenu = '<ul id="navMenu2">';

      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Mode=Reminder" ><span>Rappel</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Mode=Matches" ><span>Matchs</span></a></li>';
      $submenu .= '</ul>';
      echo $submenu;

      break;
    case "9":
      $submenu = '<ul id="navMenu2">';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Competition=Championship" ><span>Championnat</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Competition=Cup" ><span>Coupe S1</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Competition=Cup&SeasonKey=2" ><span>Coupe S2</span></a></li>';
      $submenu .= '<li><a href="index.php?Page=' . $_currentPage . '&Competition=Cup&SeasonKey=3" ><span>Coupe S3</span></a></li>';
      $submenu .= '</ul>';
      echo $submenu;

      break;
  }
}
?>