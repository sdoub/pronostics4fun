<?php
require_once("begin.file.php");


if (isset($_GET["SurveyKey"])){
  $surveyKey = $_GET["SurveyKey"];
  $answer1 = $_GET["Answer1"];
  $answer2 = $_GET["Answer2"];
  $answer3 = $_GET["Answer3"];
  $answer4 = $_GET["Answer4"];

  $query = "UPDATE surveys SET Score1=Score1+$answer1,
  Score2=Score2+$answer2,
  Score3=Score3+$answer3,
  Score4=Score4+$answer4,
  Participants=CONCAT(IFNULL(Participants,''),',','" . $_authorisation->getConnectedUserKey() . "')
  WHERE PrimaryKey=$surveyKey";
  $arr = array();
  if ($_databaseObject -> queryPerf ($query , "Update survey")) {
    $arr["error"] = false;
    $arr["surveyKey"] = $surveyKey;

    $query = "SELECT * FROM surveys
			   WHERE PrimaryKey = $surveyKey";
    $resultSet = $_databaseObject->queryPerf($query,"Get survey");

    while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
    {
      $participants = explode(",", substr($rowSet["Participants"],1));
      $arr["Score1Percentage"] = round(100*$rowSet["Score1"]/sizeof($participants),2);
      $arr["Score2Percentage"] = round(100*$rowSet["Score2"]/sizeof($participants),2);
      $arr["Score3Percentage"] = round(100*$rowSet["Score3"]/sizeof($participants),2);
      $arr["Score4Percentage"] = round(100*$rowSet["Score4"]/sizeof($participants),2);
      $arr["Score1"] = $rowSet["Score1"];
      $arr["Score2"] = $rowSet["Score2"];
      $arr["Score3"] = $rowSet["Score3"];
      $arr["Score4"] = $rowSet["Score4"];
      $arr["Participants"] = sizeof($participants);
    }
  } else {
    $arr["error"] = true;
    $arr["surveyKey"] = $surveyKey;
  }

  writeJsonResponse($arr);
}

require_once("end.file.php");
?>