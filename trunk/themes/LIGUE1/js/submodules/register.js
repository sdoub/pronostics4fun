/*
**	@desc:	PHP ajax login form using jQuery
**	@author:	programmer@chazzuka.com
**	@url:		http://www.chazzuka.com/blog
**	@date:	15 August 2008
**	@license:	Free!, but i'll be glad if i my name listed in the credits'
*/
var wrapperId 	=	'#wrapper';		// main container
	var waitId		=	'#wait';		// wait message container
	var formId		=	'#frmRegister';	// submit button identifier
	var userId		=	'#nickName';			// user input identifier
	var passId		=	'#password';			// password input identifier
	var passbisId		=	'#pbis';			// password input identifier
	var lastNameId		=	'#lastName';			// password input identifier
	var firstNameId		=	'#firstName';			// password input identifier
	var emailId		=	'#email';			// password input identifier
	
	var waitNote	=	'Chargement...';											// loading message
	var jsErrMsg	=	"Nom d'utilisateur ou mot de passe non valide";						// clientside error message
	
	var postFile	=	'submodule.post.php?SubModule=2';	// post handler
	
	var autoRedir	=	true;			// auto redirect on success
	

	
	$(document).ready(function(){ 
		$(waitId).hide(); $(wrapperId).hide();
	});
	
	function getRegister () { 

	
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
	$.log(data.status);
	if(data.status==true) {
		// status is authorized
		if(autoRedir){ 
			$(waitId).hide().html('Redirection...').fadeIn('fast', function(){window.location=data.url;});
		} else {
			$(waitId).fadeOut('slow', function(){ $(wrapperId).html(data.message).slideDown(); }).html();
		}
	} else {
		$.log(data.message);
		// show form
		$(wrapperId).html(data.message).slideDown('fast',function (){
			// hide  message
			$(waitId).fadeOut('fast',function(){
				
				
				jQuery('#frmRegister').validated(function(){
					
					// loading
					
					$(waitId).html(waitNote).fadeIn();
						
					var _u = $(userId).val();	// form user
					var _p = $(passId).val();	// form id
					var _fn = $(firstNameId).val();	// form user
					var _ln = $(lastNameId).val();	// form id
					var _e = $(emailId).val();	// form user
					
					$.ajax({
						type: "POST",
						url: postFile,
						  dataType: 'json',
						  data: { nickName: _u, password: _p, firstName: _fn, lastName: _ln, email: _e },
						  success: callbackRegister,
						  error: callbackPostError
						});	
					return false;
				});	
				$(userId).validate({
                    expression: "if (VAL && VAL.length > 4 && VAL.length < 16 && VAL.indexOf(' ')==-1) return true; else return false;",
                    message: "Veuillez saisir entre 5 et 15 caract&egrave;res sans espace!"
                });
				$(passId).validate({
                    expression: "if (VAL.length > 5 && VAL) return true; else return false;",
                    message: "Veuillez saisir plus de 5 caract&egrave;res!"
                });
				
				$(lastNameId).validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Champ obligatoire!"
                });
				$(firstNameId).validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Champ obligatoire!"
                });
				
				$(passbisId).validate({
                    expression: "if ((VAL == jQuery('" + passId + "').val()) && VAL) return true; else return false;",
                    message: "Le mot de passe n'est pas identique!"
                });
                
                $(emailId).validate({
                    expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
                    message: "Saisissez un email correct!"
                });                
                
				//*/
				$(userId).focus();
			}).html();
		});
		
	}
	
}


function callbackRegister(data) {
	
	if(data.status==true){ 
		if(autoRedir){ 
			$(waitId).html('Redirection...').fadeIn('fast', function(){
			  $.log("redirection :"+data.url);
				window.location.replace(data.url);
				//window.location=data.url;
			});
		} else {
			$(waitId).fadeOut('slow', function(){ 
				$(wrapperId).slideUp('slow',function(){
					$(this).html(data.message).slideDown();
				}); 
			}).html();
		}
	} else {
		$(waitId).html(data.message).slideDown('fast', function(){ 
			$(userId).focus(); 
		}); 
	}
}
