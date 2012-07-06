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
	
	var postFile	=	'submodule.post.php?SubModule=12';	// post handler
	
	var autoRedir	=	false;			// auto redirect on success
	
	var _score = "";
	var _teamKeys = "";
	var _groupKeys = "";

	
	$(document).ready(function(){ 
		$(waitId).hide(); $(wrapperId).hide();
	});
	
	function getScoreDetail (score,teamKeys,groupKeys) { 

		_score = score;
		_teamKeys = teamKeys;
		_groupKeys = groupKeys;
	// hide first
	$(waitId).hide(); $(wrapperId).hide();
	
	// FirstLoad
	$(waitId).html(waitNote).fadeIn('slow',function(){
		// get request to load form

		$.ajax({
			  url: postFile + "&score="+score+ "&teamKeys="+teamKeys+ "&groupKeys="+groupKeys,
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
			$(waitId).fadeOut('fast').html();
		
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
							$('div[match-key='+_matchKey+']').removeClass("ToBeDone");
							$('div[match-key='+_matchKey+']').addClass("AlreadyDone");
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
