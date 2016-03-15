var wrapperId 	=	'#wrapper';		// main container
	var waitId		=	'#wait';		// wait message container
	var formId		=	'#frmForecast';	// submit button identifier
	var emailId		=	'#Email';			// password input identifier
	
	var waitNote	=	'Chargement...';											// loading message
	var jsErrMsg	=	"Nom d'utilisateur ou mot de passe non valide";						// clientside error message
	
	var postFile	=	'submodule.post.php?SubModule=10';	// post handler
	
	var autoRedir	=	false;			// auto redirect on success
	

	
	$(document).ready(function(){ 
		$(waitId).hide(); $(wrapperId).hide();
	});
	
	function getManualForecast () { 

	
	// hide first
	$(waitId).hide(); $(wrapperId).hide();
	
	// FirstLoad
	$(waitId).html(waitNote).fadeIn('slow',function(){
		// get request to load form

		$.ajax({
			  url: postFile,
			  dataType: 'json',
			  data: "{tes:test}",
			  success: callbackPost,
			  error: callbackPostError
			});

	
	});
	
}

function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
{
	$.log(XMLHttpRequest);
	$.log(textStatus);
	$.log(errorThrown);
}

function callbackPost (data){
	if(data.status==true) {
		// status is authorized
		if(autoRedir){ 
			$(waitId).hide().html('Redirection...').fadeIn('fast', function(){window.location=data.url;});
		} else {
			$(waitId).fadeOut('slow', function(){ $(wrapperId).html(data.message).slideDown(); }).html();
		}
	} else {
		// show form
		$(wrapperId).html(data.message).slideDown('fast',function (){
			// hide  message
			$(waitId).fadeOut('fast',function(){
				
				//*/ submit handler
				jQuery('#frmForecast').validated(function(){
					// loading
					
					$(waitId).html(waitNote).fadeIn();
					
					var jsonData = "{";
					
					$.log($(emailId));
					
					jsonData += '"EmailAddress":"' +  $(emailId).val() + '", "matches":[' ;
					var addComma=false;
					$("tr").each(function (index) {
						try {
						if ($(this).attr('team-home-key')) {
							var teamHomeKey = $(this).attr('team-home-key');
							var teamAwayKey = $(this).attr('team-away-key');
							var scheduleDate = $(this).attr('schedule-date');
							var dayKey = $(this).attr('day-key');
							
							var teamHomeScore ="";
							$(this).find("input[name=TeamHomeScore]").each (function (index) {
								teamHomeScore= $(this).val();
							});
							var teamHomeName ="";
							$(this).find("td[class=teamHome]").each (function (index) {
								teamHomeName= $(this).html();
							});
							var teamAwayScore ="";
							$(this).find("input[name=TeamAwayScore]").each (function (index) {
								teamAwayScore= $(this).val();
							});
							var teamAwayName ="";
							$(this).find("td[class=teamAway]").each (function (index) {
								teamAwayName= $(this).html();
							});							
							if (teamAwayScore && teamHomeScore){
								if (addComma)
									jsonData += ",";
								jsonData += '{"DayKey": "' + dayKey + '","ScheduleDate": "' + scheduleDate + '","MatchKey": "' + teamHomeKey + '","TeamHomeKey": "' + teamHomeKey + '","TeamHomeName": "' + teamHomeName + '","TeamAwayKey": "' + teamAwayKey + '","TeamAwayName": "' + teamAwayName + '", "TeamHomeScore": "' + teamHomeScore+ '", "TeamAwayScore": "' + teamAwayScore + '"}';
								if (!addComma)
									addComma=true;
							}
						}
						}
						catch(ex) {$.log(ex);}
					});
					jsonData += "]}";
					$.log(jsonData);
					try {
					jsonObj = eval("(" + jsonData + ")");
					
					var myObj = 
					$.ajax({
						type: "POST",
						url: postFile,
						  dataType: 'json',
						  data: jsonObj ,
						  success: callbackForecast,
						  error: callbackPostError
						});
					}
					catch (ex) {$.log(ex);}
					return false;
				});				
                $(emailId).validate({
                    expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
                    message: "Saisissez un email correct!"
                });
                
				$("input[name=TeamHomeScore]").numberInput();
				$("input[name=TeamAwayScore]").numberInput();
				
			}).html();
		});
		
	}
	
}


function callbackForecast(data) {
	
	if(data.status==true){ 
		$(waitId).fadeOut('slow', function(){ 
			$(wrapperId).slideUp('slow',function(){
				$(this).html(data.message).slideDown('fast',function(){
					// */ submit handler
					$("#frmForecastValidated").submit( function() { 
						$.closePopupLayer();
						return false;
					});	
					
					$("#btnClose").focus();
					// */
				});
			}); 
		}).html();
	} else {
		$(waitId).html(data.message).slideDown('fast', function(){ 
			// 
		}); 
	}
}
