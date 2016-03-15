/*
**	@desc:	PHP ajax login form using jQuery
**	@author:	programmer@chazzuka.com
**	@url:		http://www.chazzuka.com/blog
**	@date:	15 August 2008
**	@license:	Free!, but i'll be glad if i my name listed in the credits'
*/
var wrapperId 	=	'#wrapper';		// main container
	var waitId		=	'#wait';		// wait message container
	var formId		=	'#frmForecast';	// submit button identifier
	var teamHomeScoreId		=	'#TeamHomeScore';			// user input
	var teamAwayScoreId		=	'#TeamAwayScore';			// password input
	
	var waitNote	=	'Chargement...';											// loading
																					// message
	var jsErrMsg	=	"Nom d'utilisateur ou mot de passe non valide";						// clientside
																							// error
																							// message
	
	var postFile	=	'submodule.post.php?SubModule=3';	// post handler
	
	var autoRedir	=	false;			// auto redirect on success
	
	var _matchKey = "";

	
	$(document).ready(function(){ 
		$(waitId).hide(); $(wrapperId).hide();
	});
	
	function getForecast (matchKey) { 

		_matchKey = matchKey;
	// hide first
	$(waitId).hide(); $(wrapperId).hide();
	
	// FirstLoad
	$(waitId).html(waitNote).fadeIn('slow',function(){
		// get request to load form

		$.ajax({
			  url: postFile + "&matchKey="+matchKey,
			  dataType: 'json',
			  data: "",
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
			$.log(data.message);
			$(waitId).fadeOut('slow', function(){ 
				$(wrapperId).html(data.message).slideDown();
			}).html();
		}
	} else {
		// show form
		$(wrapperId).html(data.message).slideDown('fast',function (){
			// hide message
			$(waitId).fadeOut('fast',function(){
				
               if (data.timeExpired){
            	   $("#frmForecastTooLate").submit( function() { 
            		   $.closePopupLayer();
						return false;
   				});			
               }
               else
               {
				// */ submit handler
				$("#frmForecast").submit( function() { 
					
					var ths = $(teamHomeScoreId).val();	// form user
					var tas = $(teamAwayScoreId).val();	// form id
 
					if(ths == null || ths.length<1 || tas == null || tas.lelength<1) 
					{
						$(waitId).html("Veuillez remplir les 2 scores pour valider votre pronostic").fadeIn('fast',function(){ 
							$(teamHomeScoreId).focus();
						});
					} 
					else
					{
					// loading
					$(waitId).html(waitNote).fadeIn();
						
					
								$.ajax({
									type: "POST",
									url: postFile,
									  dataType: 'json',
									  data: { matchKey: _matchKey, teamHomeScore: ths, teamAwayScore: tas},
									  success: callbackForecast,
									  error: callbackPostError
									});	
								// $.post(postFile, { u: _u, p: _p
								// },callbackAuthentication,'json');
					}
					return false;
				});				
				// */
				$(teamHomeScoreId).focus();
				$(teamHomeScoreId).numberInput();
				$(teamAwayScoreId).numberInput();
               }
			}).html();
		
		});
		
	}
	
}


function callbackForecast(data) {
	
	if(data.status==true){ 
		if(autoRedir){ 
			$(waitId).html('Redirection...').fadeIn('fast', function(){
			  $.log("redirection :"+data.url);
				// window.location.replace(data.url);
				// window.location=data.url;
			});
		} else {
			$(waitId).fadeOut('slow', function(){ 
				$(wrapperId).slideUp('slow',function(){
					$(this).html(data.message).slideDown('fast',function(){
						$.log('attach submit');
						// */ submit handler
						$("#frmForecastValidated").submit( function() { 
							
							
							$.closePopupLayer();
							try {
							$('div[match-key='+_matchKey+']').removeClass("ToBeDone" + data.isBonus);
							$('div[match-key='+_matchKey+']').addClass("AlreadyDone" + data.isBonus);
							}
							catch (exception)
							{
								$.log(exception);
							}
							try {
								$('a[match-key='+_matchKey+']').html(data.teamHomeScore + " - " + data.teamAwayScore);
							}
							catch (exception)
							{
								$.log(exception);
							}
							return false;
						});	
						
						$("#btnClose").focus();
						// */
					});
				}); 
			}).html();
		}
	} else {
		$(waitId).html(data.message).slideDown('fast', function(){ 
			$(teamHomeScoreId).focus(); 
		}); 
	}
}
