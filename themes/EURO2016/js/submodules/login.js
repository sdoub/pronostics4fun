var wrapperId 	=	'#wrapper';		// main container
	var waitId		=	'#wait';		// wait message container
	var formId		=	'#frmLogin';	// submit button identifier
	var userId		=	'#u';			// user input identifier
	var passId		=	'#p';			// password input identifier
	
	var waitNote	=	'Chargement...';											// loading message
	var jsErrMsg	=	"Nom d'utilisateur ou mot de passe non valide";						// clientside error message
	
	var postFile	=	'submodule.post.php?SubModule=1';	// post handler
	
	var autoRedir	=	true;			// auto redirect on success
	

	
	$(document).ready(function(){ 
		$(waitId).hide(); $(wrapperId).hide();
	});
	
	function getLogin () { 

	
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
			$(waitId).hide().html('Redirection...').fadeIn('fast', function(){window.location.reload();});
		} else {
			$(waitId).fadeOut('slow', function(){ $(wrapperId).html(data.message).slideDown(); }).html();
		}
	} else {
		// show form
		$(wrapperId).html(data.message).slideDown('fast',function (){
			// hide  message
			$(waitId).fadeOut('fast',function(){
				
				//*/ submit handler
				jQuery('#frmlogin').validated(function(){
					// loading
					
					$(waitId).html(waitNote).fadeIn();
						
					var _u = $(userId).val();	// form user
					var _p = $(passId).val();	// form id
					var _kc =false;
					if ($('#KeepConnection:checked').is(':checked') ) {
					  _kc = true;
					}
					
					$.log(_kc);
					$.ajax({
						type: "POST",
						url: postFile,
						  dataType: 'json',
						  data: { u: _u, p: _p, kc: _kc },
						  success: callbackAuthentication,
						  error: callbackPostError
						});	
					return false;
				});
				$("label").live('click',function (){
				  if (this.id=="passwordForgotten") {
					$(waitId).html(waitNote).fadeIn();
					
					var _u = $(userId).val();	// form user

					$.ajax({
						type: "POST",
						url: postFile,
						  dataType: 'json',
						  data: { u: _u, pf : 1 },
						  success: callbackPasswordForgotten,
						  error: callbackPostError
						});
					}
				});	
				$(userId).validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Champ obligatoire!"
                });
				$(passId).validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Champ obligatoire!"
                });
				if ($(userId).val()) {
				  $(passId).focus();
				}
				else {
				  $(userId).focus();
				}
				
			}).html();
		});
		
	}
	
}


function callbackAuthentication(data) {
	
	if(data.status==true){ 
		if(autoRedir){ 
			$(waitId).html('Redirection...').fadeIn('fast', function(){
				window.location.reload();
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

function callbackPasswordForgotten(data) {

	$(waitId).html(data.message).slideDown('fast', function(){
		$(passId).focus();
	}); 

//	if(data.status==true){ 
//		if(autoRedir){ 
//			$(waitId).html('Redirection...').fadeIn('fast', function(){
//				window.location.replace(data.url);
//				//window.location=data.url;
//			});
//		} else {
//			$(waitId).fadeOut('slow', function(){ 
//				$(wrapperId).slideUp('slow',function(){
//					$(this).html(data.message).slideDown();
//				}); 
//			}).html();
//		}
//	} else {
//		$(waitId).html(data.message).slideDown('fast', function(){ 
//		}); 
//	}
}