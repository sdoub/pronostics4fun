/*
**	@desc:	PHP ajax login form using jQuery
**	@author:	programmer@chazzuka.com
**	@url:		http://www.chazzuka.com/blog
**	@date:	15 August 2008
**	@license:	Free!, but i'll be glad if i my name listed in the credits'
*/
var wrapperId 	=	'#wrapper';		// main container
	var waitId		=	'#wait';		// wait message container
	var formId		=	'#frmContact';	// submit button identifier
	var bodyId		=	'#body';			// user input
	
	var waitNote	=	'Chargement...';											// loading
																					// message
	var jsErrMsg	=	"Nom d'utilisateur ou mot de passe non valide";						// clientside
																							// error
																							// message
	
	var postFile	=	'submodule.post.php?SubModule=5';	// post handler
	
	var autoRedir	=	false;			// auto redirect on success
	
	$(document).ready(function(){ 
		$(waitId).hide(); $(wrapperId).hide();
	});
	
	function getContact () { 

	// hide first
	$(waitId).hide(); $(wrapperId).hide();
	
	// FirstLoad
	$(waitId).html(waitNote).fadeIn('slow',function(){
		// get request to load form

		$.ajax({
			  url: postFile,
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
				
				// */ submit handler
				$(formId).submit( function() { 
					
					var bodyContent = $(bodyId).val();	
 
					// loading
					$(waitId).html(waitNote).fadeIn();
					
					$.ajax({
						type: "POST",
						url: postFile,
						  dataType: 'json',
						  data: { body: bodyContent},
						  success: callbackForecast,
						  error: callbackPostError
						});	
								// $.post(postFile, { u: _u, p: _p
								// },callbackAuthentication,'json');
					return false;
				});
				// */
				$(bodyId).focus();
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
						$("#frmContactValidated").submit( function() { 
							$.closePopupLayer();
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
			$(bodyId).focus(); 
		}); 
	}
}
