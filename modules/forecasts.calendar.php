<link rel='stylesheet' type='text/css' href='<?php echo ROOT_SITE;?>/css/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='<?php echo ROOT_SITE;?>/css/jquery-ui.custom.css' />
<script type='text/javascript' src='<?php echo ROOT_SITE;?>/js/fullcalendar.js'></script>
<link rel='stylesheet' type='text/css' href='<?php echo ROOT_SITE;?>/css/jquery.cluetip.css' />
<script type='text/javascript' src='<?php echo ROOT_SITE;?>/js/jquery.cluetip.min.js'></script>
<link rel='stylesheet' type='text/css' href='<?php echo ROOT_SITE;?>/css/modules/forecasts.calendar.css' />

<center>
<input type="submit"
	value="Voter pour le match bonus" class="buttonfield" id="btnVote" name="btnVote">
</center>
<div id='loading' style='display:none'><label style="text-align: :center;vertical-align: middle;">chargement...</label></div>
<div id='calendarLegend' style="float:right; ">
  <div style="width:80px;height: 16px;float:right; text-align:center;margin-top:20px;margin-right:130px;font-size:10px;margin-left:5px;" class="fc-event fc-event-hori fc-corner-left fc-corner-right ToBeDone0">
    <a><span class="fc-event-title"><?php echo __encode("Non Validé");?></span></a>
  </div>
  <div style="width:80px;height: 16px;float:right; margin-top:20px;text-align:center;font-size:10px;" class="fc-event fc-event-hori fc-corner-left fc-corner-right AlreadyDone0">
    <a><span class="fc-event-title"><?php echo __encode("Validé");?></span></a>
  </div>
</div>
<div id='calendar'></div>

<script type='text/javascript'>

	$(document).ready(function() {

		$("#btnVote").click(function() {

			$.openPopupLayer({
				name: "VotePopup",
				width: "500",
				height: "500",
				url: "submodule.loader.php?SubModule=14"
			});
		});


		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek'
			},
			theme:true,
			editable: false,
			events: "matches.scheduled.php",
			firstDay:1,
			defaultView: "<?php if (isset($_COOKIE["CalendarView"])) { echo $_COOKIE["CalendarView"]; } else { echo 'month';}?>",
			viewDisplay: function(view) {
				var exipryCookieDate = new Date();
				exipryCookieDate.setDate(exipryCookieDate.getDate() + 90);
				$.cookies.set("CalendarView", view.name, { expiresAt: exipryCookieDate});
		    },
			loading: function(bool) {
				if (bool) $('#loading').fadeIn('slow');
				else {
					$('#loading').fadeOut('fast');

				$('div[match-key]').cluetip({
					   hoverClass: 'highlight',
					   sticky: true,
					   closePosition: 'bottom',
					   closeText: '<img src="cross.png" alt="close" />',
					  truncate: 60,
					   ajaxSettings: {
					     type: 'POST'
					   }
				});

				}
			},
			contentHeight : 450


		});

		$('div[match-key]').live('click',function(){
			$.openPopupLayer({
				name: "forecatstPopup",
				width: 402,
				height: 282,
				url: "submodule.loader.php?SubModule=3&matchKey="+$(this).attr("match-key")
			});

	});

		$("#loading").css({
			position: "absolute",
			left: "200",
			top: "15",
			display: "none"
		});

	});

</script>