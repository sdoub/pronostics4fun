<?php

if ($_isAuthenticated)
{
  switch ($_currentPage)
  {
    case "1":
      if (isset($_GET['Calendar'])) {
        if ($_GET['Calendar']=="1") {
          include("modules/forecasts.calendar.php");
        }
        else {
          include("modules/forecasts.agenda.php");
        }

      }
      else
      if ($_authorisation->getConnectedUserInfo("IsCalendarDefaultView") == "1") {
        include("modules/forecasts.calendar.php");
      }
      else
      {
        include("modules/forecasts.agenda.php");
      }
      break;
    case "2":
      if (isset($_GET['Mode'])){
        if (isset($_GET['GroupKey']) || $_competitionType==1) {
			if ($_GET['Mode']=="1") {
			  include("modules/result.group.php");
			} else {
			  include("modules/result.group2.php");
			}

		}
		else
		  include("modules/resultsHome.php");
      }
      else {
        if (isset($_GET['GroupKey']) || $_competitionType==1)
		  include("modules/result.group.php");
		else
		  include("modules/resultsHome.php");
      }
      break;
    case "3":
      if (isset($_GET['View'])){
        $view=$_GET['View'];
      }
      else {
        $view="Global";
      }

      switch ($view) {
        case "Global":
          include("modules/ranking.php");
          break;
        case "Group":
          include("modules/ranking.group.php");
          break;
        case "Wins":
          include("modules/ranking.wins.php");
          break;
        case "Teams":
          switch ($_competitionType) {
            case 1:
              require_once("lib/ranking.php");
              include("modules/ranking.teams.competition.php");
              break;
            case 2:
            case 3:
              include("modules/ranking.competition.php");
              break;
          }
          break;
        case "Scorer":
          include("modules/ranking.top.scorer.php");
          break;
        case "Assist":
          include("modules/ranking.top.assist.php");
          break;
      }

      break;
    case "5":
      $scheduleDate = time();
      $query= "SELECT
matches.PrimaryKey MatchKey,
matches.TeamHomeKey,
matches.TeamAwayKey,
TeamHome.Name TeamHomeName,
TeamAway.Name TeamAwayName,
matches.GroupKey,
groups.Description GroupName,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate
 FROM matches
INNER JOIN groups ON groups.PrimaryKey=matches.GroupKey AND groups.CompetitionKey=" . COMPETITION . "
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
WHERE DATE(matches.ScheduleDate)=DATE(FROM_UNIXTIME($scheduleDate))";

      $_databaseObject -> queryPerf ($query, "Get today matches");
      if ($_databaseObject -> num_rows()>0) {
        include("modules/live.php");
      }
      else {
        include("modules/live.nomatch.php");
      }
      break;
    case "4":
      if (isset($_GET['DayKey']))
      {
        include("modules/livev2.php");
      }
      else {


        $scheduleDate = time();

        $query= "SELECT
groups.PrimaryKey GroupKey,
groups.Description GroupDescription
FROM groups
WHERE EXISTS (
SELECT 1
  FROM matches
 WHERE matches.GroupKey=groups.primaryKey
   AND DATE(matches.ScheduleDate)=DATE(FROM_UNIXTIME($scheduleDate)))
 AND groups.CompetitionKey=" . COMPETITION;

        $_databaseObject -> queryPerf ($query, "Get today matches");

        if ($_databaseObject -> num_rows()>0) {
          include("modules/livev2.php");
        }
        else {
          include("modules/live.nomatch.php");
        }
      }
      break;
    case "6":

        if (isset($_GET['View'])){
          $view=$_GET['View'];
        }
        else {
          $view="";
        }

        switch ($view) {
          case "Global":
            include("modules/p4f.statistics.php");
            break;
          case "Goals":
            include("modules/ligue1.statistics.php");
            break;
          case "ScoreLigue1":
            include("modules/ligue1.statistics.php");
            break;
          default:
            include("modules/statistics.php");
            break;
        }






      break;
    case "7":
      if ($_isAuthenticated && $_authorisation->getConnectedUserInfo("IsAdministrator")==1) {
        if (isset($_GET['Mode'])){
          $mode=$_GET['Mode'];
        }
        else {
          $mode="Matches";
        }

        switch ($mode) {
          case "Reminder":
            include("modules/reminder.forecasts.php");
            break;
          case "Matches":
            include("modules/admin.matches.php");
            break;
        }

      }
      else {
        include("modules/home.connected.php");
      }
      break;
    case "8":
      include("modules/account.change.password.php");
      break;
    case "9":
      if (isset($_GET['Competition'])) {
        switch ($_GET['Competition']){
					case "OldCup":
						include("modules/p4f.cup.draw.php");
						break;
					case "Cup":
						include("modules/p4f.cup.php"); 
						break;
					default:
						include("modules/p4f.ranking.division.php");
				}
      } else {
        include("modules/p4f.ranking.division.php");
      }
      break;
    case "10":
      include("modules/winners.php");
      break;
    default:
      include("modules/new.home.connected.php");
  }
}
else
{
    switch ($_currentPage)
    {
      case "8":
			  include("modules/account.change.password.php");
        break;
      case "11":
			  include("modules/account.email.validation.php");
        break;
      default:
  			include("modules/home.php");
    }

}
?>