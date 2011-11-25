<?php
require_once("lib/simple_html_dom.php");

//@ session not active
if($_SERVER['REQUEST_METHOD']=='GET')
{
  // get next matches with max 2 days
  $matches = array();
  $match = array();
  $continue = true;
  for ($day=3;$day<=3;$day++) {
    $url = "http://". EXTERNAL_WEB_SITE . "/media/pressKit?code_evt=1&code_jr_tr=" . $day;
    //$url = "http://media.lfp.fr/pressKit.asp?code_evt=D1&code_jr_tr=J" . str_pad($day, 2, "0", STR_PAD_LEFT);
    if (@$html = file_get_html($url))
    {
      foreach($html->find('table tr') as $rows) {
        if ($rows->find('td',0)){
          $dateMatchArray = split('/',$rows->find('td',0)->plaintext);
          if ($rows->find('td',1)->plaintext!="-"){
            $hourMatchArray = split(':',$rows->find('td',1)->plaintext);

            $scheduleDate = mktime($hourMatchArray[0], $hourMatchArray[1], 0, $dateMatchArray[1], $dateMatchArray[0], $dateMatchArray[2]);
            if ($scheduleDate>time() && $scheduleDate-864000<time()) {
              $lfpTeamHome = split('/',$rows->find('td',3)->first_child ()->getAttribute("src"));
              $lfpTeamHomeKey = substr($lfpTeamHome[6], 0, -4);
              $teamHomeKey = ConvertLfpKeyToP4F ($lfpTeamHomeKey);
              $lfpTeamAway = split('/',$rows->find('td',5)->first_child ()->getAttribute("src"));
              $lfpTeamAwayKey = substr($lfpTeamAway[6], 0, -4);
              $teamAwayKey = ConvertLfpKeyToP4F ($lfpTeamAwayKey);
              $teamHomeName = $rows->find('td',2)->plaintext;
              $teamAwayName = $rows->find('td',6)->plaintext;
              $match["DayKey"] =$day;
              $match["TeamHomeKey"] =$teamHomeKey;
              $match["TeamAwayKey"] =$teamAwayKey;
              $match["TeamHomeName"] =__encode(utf8_decode($teamHomeName));
              $match["TeamAwayName"] =__encode(utf8_decode($teamAwayName));
              $match["ScheduleDate"] = $scheduleDate;
              $matches[] =$match;
            }
            else {
              if ($scheduleDate-864000>time())
              $continue=false;
            }
          }
          else {
            $continue=false;
            break;
          }
        }
      }

      $html->clear();
      unset($html);

    }
    if (!$continue) {
      break;
    }
  }


  $divHeight = 80 + (sizeof($matches)*50);
  $htmlContent = "<div style='height:" . $divHeight . "px;'> <img src='" .ROOT_SITE ."/images/warning.png' style='height:50px;width:50px;float:left;padding-right:5px;'/> <div style='color:red;font-size:10px;'>Malheureusement le site est actuellement indisponible, néanmoins, afin que vous ne perdiez pas de précieux points, vous pouvez utiliser le formulaire ci-dessous pour donner vos pronostics sur les matchs à venir!<br/>Bon pronostics et désolé pour le désagrément occasioné.</div><div style='text-align: right;color:#000;font-weight:normal;padding-top:10px;font-size:10px;'>L'administrateur de Pronostics4Fun.</div><hr/><form id='frmForecast'><style>
.day {
	font-size: 14px;
	text-align: center;
	background-color: #6D8AA8;
	color: #FFFFFF;
	font-weight: bold;
}

.match {
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}

.equipeRankUp {
	font-size: 12px;
	color: #365F89;
	font-weight: bold;
}

.equipeRankDown {
	font-size: 12px;
	color: #365F89;
	font-weight: normal;
}

.teamAway {
	text-align: left;
	width: 150px;
}

.teamFlag {
	width: 30px;
}

.teamFlag img {
	width: 30px;
	height: 30px;
}

.score {
	width: 80px;
	text-align: center;
}

.teamHome {
	width: 150px;
	text-align: right;
}

.time0 {
	width: 60px;
	padding-left: 20px;
}
.time1 {
	width: 60px;
	padding-left: 20px;
	background: url('" . ROOT_SITE . "/images/star_25.png') no-repeat scroll left center transparent;

}
</style>		<div><label>Veuillez indiquer votre adresse email utilisé sur Pronostics4Fun</label><input
			type='text' class='textfield' id='Email'
			name='EmailAddress' /></div><table>
";
  $scheduleMonth = "00";
  $scheduleDay = "00";
  while (list ($key, $value) = each ($matches)) {

    $tempScheduleMonth=strftime("%m",$value['ScheduleDate']);
    $tempScheduleDay=strftime("%d",$value['ScheduleDate']);
    if (!($scheduleMonth==$tempScheduleMonth && $scheduleDay==$tempScheduleDay))
    {
      setlocale(LC_TIME, "fr_FR");
      $scheduleFormattedDate = __encode(strftime("%A %d %B %Y",$value['ScheduleDate']));
      $htmlContent.= '<tr class="day"
      	    style="">
      	  <td colspan="6">' . __encode("Journée") . '&nbsp;' . $value["DayKey"] . '&nbsp;->&nbsp;' . $scheduleFormattedDate . '</td>';
      $htmlContent.=  '<td colspan="2">&nbsp;</td>
      	</tr>';
    }

    $htmlContent.= '<tr class="match" schedule-date="'.$value['ScheduleDate'].'" day-key="'. $value['DayKey'] .'" team-home-key="' . $value['TeamHomeKey'] . '" team-away-key="' . $value['TeamAwayKey'] . '">
      	  <td class="time0">' . strftime("%H:%M",$value['ScheduleDate']) . '</td>
      	  <td class="teamHome">' . $value['TeamHomeName'] . '</td>
      	  <td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $value['TeamHomeKey'] . '.png" width="30px" height="30px"></img></td>';
    $htmlContent.= '<td >

		<input
			type="text" class="textfield" id="TeamHomeScore' . $value['TeamHomeKey'] . '"
			name="TeamHomeScore" maxlength="1" size="3em" /></td>
		<td ><input
			type="text" class="textfield" id="TeamAwayScore' . $value['TeamHomeKey'] . '"
			name="TeamAwayScore" maxlength="1"
			size="3em" /></td>
			';

    $htmlContent.= '<td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $value['TeamAwayKey'] . '.png"></img></td>
      	  <td class="teamAway">' . $value['TeamAwayName'] . '</td>';

    $htmlContent.= '<td nowrap><div style="text-align:center;">';

    $htmlContent.= '</tr>';

    $scheduleMonth = strftime("%m",$value['ScheduleDate']);
    $scheduleDay = strftime("%d",$value['ScheduleDate']);
  }
  $htmlContent.= '</table>
  <div id="footerForecast" ><input type="submit"
	value="Envoyer" class="buttonfield" id="btn" name="btn"></div>
  </form></div>';
  $arr["status"] = false;
  $arr["message"] = __encode($htmlContent);
  $arr["matches"] = $matches;
}
else {

  $email = (!empty($_POST['EmailAddress']))?trim($_POST['EmailAddress']):false;	// retrive password var

  if ($email){
  $cMail = new cPHPezMail();

  //Don't try to add invalid e-mail address format
  $cMail->SetFrom($_POST['EmailAddress'], $_POST['EmailAddress']);

  $cMail->AddTo('contact@pronostics4fun.com', 'Pronostics4Fun Contact');

  $cMail->SetSubject('Pronostics');

  $htmlbody ='<html><body><div>
  <img src="'. ROOT_SITE.'/images/Logo.png" /><p>';
  $emailAddress = $_POST["EmailAddress"];
  $emailAddress = strtolower(__encode($emailAddress));
  $queries = array();

  $htmlContent = "<style>
.day {
	font-size: 14px;
	text-align: center;
	background-color: #6D8AA8;
	color: #FFFFFF;
	font-weight: bold;
}

.match {
	font-size: 12px;
	color: #000000;
	font-weight: bold;
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}

.equipeRankUp {
	font-size: 12px;
	color: #365F89;
	font-weight: bold;
}

.equipeRankDown {
	font-size: 12px;
	color: #365F89;
	font-weight: normal;
}

.teamAway {
	text-align: left;
	width: 150px;
}

.teamFlag {
	width: 30px;
}

.teamFlag img {
	width: 30px;
	height: 30px;
}

.score {
	width: 80px;
	text-align: center;
}

.teamHome {
	width: 150px;
	text-align: right;
}

.time0 {
	width: 60px;
	padding-left: 20px;
}

</style><table>
";
  $scheduleMonth = "00";
  $scheduleDay = "00";
  while (list ($key, $value) = each ($_POST["matches"])) {

    $query = "INSERT INTO forecasts (MatchKey,PlayerKey,TeamHomeScore,TeamAwayScore,ForecastDate)
              VALUES (
              (SELECT PrimaryKey FROM matches WHERE matches.TeamHomeKey=" .$value["MatchKey"] ."
                                                AND EXISTS (SELECT 1 FROM groups
                                                             WHERE matches.GroupKey=groups.PrimaryKey
                                                             AND groups.DayKey=". $value["DayKey"] .")),
              (SELECT PrimaryKey FROm players WHERE EmailAddress='" . $emailAddress. "'),
			  " . $value["TeamHomeScore"] . "," . $value["TeamAwayScore"] . ", FROM_UNIXTIME(" . time() . "));";
    $queries[] =$query;


    $tempScheduleMonth=strftime("%m",$value['ScheduleDate']);
    $tempScheduleDay=strftime("%d",$value['ScheduleDate']);
    if (!($scheduleMonth==$tempScheduleMonth && $scheduleDay==$tempScheduleDay))
    {
      setlocale(LC_TIME, "fr_FR");
      $scheduleFormattedDate = __encode(strftime("%A %d %B %Y",$value['ScheduleDate']));
      $htmlContent.= '<tr class="day"
      	    style="">
      	  <td colspan="6">' . __encode("Journée") . '&nbsp;' . $value["DayKey"] . '&nbsp;->&nbsp;' . $scheduleFormattedDate . '</td>';
      $htmlContent.=  '<td colspan="2">&nbsp;</td>
      	</tr>';
    }

    $htmlContent.= '<tr class="match" day-key="'. $value['DayKey'] .'" team-home-key="' . $value['TeamHomeKey'] . '">
      	  <td class="time0">' . strftime("%H:%M",$value['ScheduleDate']) . '</td>
      	  <td class="teamHome">' . __encode(utf8_decode($value['TeamHomeName'])) . '</td>
      	  <td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $value['TeamHomeKey'] . '.png" width="30px" height="30px"></img></td>';
    $htmlContent.= '<td ><label class="textfield">' . $value['TeamHomeScore'] . '</label></td>
		<td ><label class="textfield">' . $value['TeamAwayScore'] . '</label></td>
			';

    $htmlContent.= '<td class="teamFlag"><img src="' . ROOT_SITE . '/images/teamFlags/' . $value['TeamAwayKey'] . '.png"></img></td>
      	  <td class="teamAway">' . __encode(utf8_decode($value['TeamAwayName'])) . '</td>';

    $htmlContent.= '<td nowrap><div style="text-align:center;">';

    $htmlContent.= '</tr>';

    $scheduleMonth = strftime("%m",$value['ScheduleDate']);
    $scheduleDay = strftime("%d",$value['ScheduleDate']);
  }
  $htmlContent.= '</table>
  <div style="display:none;">
  ';

  foreach ($queries as $query) {
    $htmlContent.=$query;
  }
  $htmlContent.= '</div>';
  $htmlbody .= $htmlContent;
  $htmlbody .='</p>
  </div>
  </body>
  </html>';

  $cMail->SetBodyHTML(__encode($htmlbody));

  $cMail->SetCharset('windows-1252');
  $cMail->SetEncodingBit("8bit");

  //$to      = 'contact@pronostics4fun.com';
  //$subject = 'Email From web site Pronostics4Fun ...';
  //$message = "This is a multi-part message in MIME format.\r\n--==Multipart_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X\r\nContent-Type: multipart\/alternative;\r\n boundary=\"==Alternative_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X\"\r\n\r\n--==Alternative_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X\r\nContent-Type: text\/html; charset=TIS-620\r\nContent-Transfer-Encoding: 8bit\r\n\r\n<HTML><DIV>\r\n  <img src=\"http:\/\/pronostics4fun.com\/CDM2010\/images\/logo.png\" border=\"0\"><p>rrrrr<\/p>\r\n  <\/DIV>\r\n  <\/HTML>\r\n--==Alternative_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X--\r\n\r\n--==Multipart_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X--\r\n";
  //$headers = "MIME-Version: 1.0\r\nX-Mailer: cPHPezMail,1.2\r\nFrom: sdoub <sebastien.dubuc@gmail.com>\r\nContent-Type: multipart\/mixed;\r\n boundary=\"==Multipart_Boundary_Xd7c955b1fc278d89941b14d4bd8a78f9X\"";
  //$arr["emailLog3"] = mail($to, $subject, $message, $headers);
  //
  //send your e-mail
  $emailResponse = $cMail->Send();

  if($emailResponse["MailResponse"])
  {
    $arr["emailLog"] =$emailResponse;
    $arr["status"] = true;

    $arr["message"] = __encode('<form id="frmForecastValidated">
<label>Vos pronostics ont été envoyé à contact@pronostics4fun.neti.net, un email vous confirmera qu\'ils ont été mis à jour sur le site.</label>
<div id="footerForecast" ><input type="submit"
	value="Fermer" class="buttonfield" id="btnClose" name="btnClose"></div>
</form>');
  }
  else
  {
    $arr["emailLog"] =$emailResponse;
    $arr["status"] = false;
    $arr["message"] = __encode("Une erreur est survenue durant l'envoi!");

  }
  /*  $arr["status"] = false;
    $arr["message"] = __encode($htmlbody);
  */

  unset($cMail);

  $arr["queries"]=$queries;
  }
  else {
    $arr["status"] = false;
    $arr["queries"]=$queries;
    $arr["message"] = __encode("Veuillez vérifier votre adresse email, elle est incorrecte!");

  }

}

echo json_encode($arr);

?>