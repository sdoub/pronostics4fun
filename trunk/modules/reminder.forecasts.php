<?php
AddScriptReference("dropdownchecklist");
AddScriptReference("ckeditor");
WriteScripts();
?>
<style>
.ui-dropdownchecklist{
z-index:999;
text-align:left;
margin-top:10px;
}
.mainBloc {
width:0px;
}
.altBloc {
width:800px;
}
</style>

<div id="mainCol">
<div class="altBloc">
<div style="width:100%;">
<span
	style="width:30px;margin-left: 15px; color: FFF; font-weight: bold; vertical-align: top; _vertical-align: middle; padding-top: 10px; padding-right: 3px;">A : </span>
<?php
$sql = "SELECT PrimaryKey PlayerKey, NickName, players.ReceiveAlert, EmailAddress,
(SELECT COUNT(1) FROM  forecasts INNER JOIN matches
                ON matches.PrimaryKey=forecasts.MatchKey
               AND DATE(matches.ScheduleDate)=(CURDATE()+ INTERVAL 1 DAY)
			 WHERE forecasts.PlayerKey=players.PrimaryKey) NbrOfForecasts,
(SELECT COUNT(1)
            FROM matches
            WHERE DATE(matches.ScheduleDate)=(CURDATE()+ INTERVAL 1 DAY)) NbrOfMatches
FROM players
WHERE IsReminderEmailSent=0
ORDER BY NickName";
$resultSet = $_databaseObject->queryPerf($sql,"Get matches linked to selected group");
$content = "<select id='PlayerChoice'  multiple='multiple' style='z-index:999;display:none;'>";
$emailAddress = "";
while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
{
  if ($rowSet["NbrOfForecasts"]!=$rowSet["NbrOfMatches"] && $rowSet["ReceiveAlert"]==1) {
    $content .= "<option selected='selected' value='".$rowSet["PlayerKey"]."'>".$rowSet["NickName"]."</option>";
    $emailAddress.=",".$rowSet["EmailAddress"];
  }
  else {
    $content .= "<option value='".$rowSet["PlayerKey"]."'>".$rowSet["NickName"]."</option>";
  }
}
$content .= "</select>";
echo __encode($content);
echo "<input type='text' style='width:700px;' value='" . $emailAddress . "'/>";
?>
</div>
<div>
<span
	style="width:30px;margin-left: 15px; color: FFF; font-weight: bold; vertical-align: top; _vertical-align: middle; padding-top: 10px; padding-right: 3px;">Sujet : </span>
<input type="text" id="Subject" style="width:700px;" value="<?php

$tomorrowDate = strftime("%A %d %B %Y",time()+86400);
echo __encode("Pronostics4Fun - Alerte pronostics pour les matchs du ".$tomorrowDate); ?>" />
</div>
<textarea class="jquery_ckeditor" cols="80" id="editor1" name="editor1" rows="10" style="width:870px;">
<?php
  $emailBody = "<body style='margin: 10px;'>";
  $emailBody = '<div ><img src="images/Logo.png" ></div><br>';
  $emailBody .= "<p>" . __encode('Bonjour <b>' . $rowSet["NickName"] . '</b>,') . "</p>";
  $emailBody .= "</br>";
  $emailBody .= "<p>" . __encode('Vous recevez cet email, car le/les match(s) suivant se d�rouleront demain, et vous n\'avez pas valid� vos pronostics:') . "<br/>";
  $emailBody .= "<ul>";
  $query2= "SELECT TeamHome.Name TeamHomeName,
TeamAway.Name TeamAwayName,
UNIX_TIMESTAMP(matches.ScheduleDate) ScheduleDate
FROM matches
INNER JOIN teams TeamHome ON TeamHome.PrimaryKey=matches.TeamHomeKey
INNER JOIN teams TeamAway ON TeamAway.PrimaryKey=matches.TeamAwayKey
WHERE DATE(matches.ScheduleDate)=(CURDATE()+ INTERVAL 1 DAY)
 ORDER BY  matches.ScheduleDate";

  $resultSetMissingForecasts = $_databaseObject->queryPerf($query2,"Get missing forecasts");
  $tomorrowDate ="";
  while ($rowSetMF = $_databaseObject -> fetch_assoc ($resultSetMissingForecasts)) {

    $scheduleFormattedDate = strftime("%A %d %B %Y, %H:%M",$rowSetMF['ScheduleDate']);
    $tomorrowDate = strftime("%A %d %B %Y",$rowSetMF['ScheduleDate']);

    $emailBody .= "<li>" . __encode($rowSetMF["TeamHomeName"] . "-" . $rowSetMF["TeamAwayName"] . " -> " . $scheduleFormattedDate . "</li>");
  }
  $emailBody .= "</ul></p>";
  $emailBody .= __encode("<p>D�p�chez-vous de vous rendre sur <a href='" . ROOT_SITE . "/'>" . ROOT_SITE . "</a> pour saisir vos pronostics!");
  $emailBody .= "<p>" . __encode("L'administrateur de Pronostics4Fun.") . "</p>";
  $emailBody .= "</body>";

  $emailBody= eregi_replace("[\]",'',$emailBody);
  echo $emailBody;

?>


</textarea>
<input type="button" name="Send" id="Send"
	value="Envoyer" />
</div>
<div class="mainBloc">
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#PlayerChoice").dropdownchecklist({
        icon: {},
        width: 750,
        maxDropHeight: 150
    });
    $( '#editor1' ).ckeditor();

    $('#Send').click(function () {

		var emailBody = $('#editor1').val();
		var emailSubject = $('#Subject').val();
		var selectedPlayers = $('#PlayerChoice').val();
        $.log(emailBody);
        $.log(emailSubject);
        var playersKeys = "";
        for (var currentIndex=0;currentIndex<selectedPlayers.length; currentIndex++) {
        	if (playersKeys.length>0) {
        		playersKeys += ",";
        	}
        	playersKeys += selectedPlayers[currentIndex];
        }
$.log(playersKeys);
    	$.ajax({
			type: "POST",
			url: "send.reminder.php",
			  dataType: 'json',
			  data: { PlayerKeys:playersKeys , EmailBody:emailBody , Subject: emailSubject},
			  success: callbackPost,
			  error: callbackPostError
			});


        });
});

function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
{
	$.log(XMLHttpRequest);
	$.log(textStatus);
	$.log(errorThrown);
}

function callbackPost (data){
	$.log(data);
}
</script>
</div>