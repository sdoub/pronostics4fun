/*
**	@desc:	PHP ajax login form using jQuery
**	@author:	programmer@chazzuka.com
**	@url:		http://www.chazzuka.com/blog
**	@date:	15 August 2008
**	@license:	Free!, but i'll be glad if i my name listed in the credits'
*/
var wrapperId 	=	'#wrapper';		// main container
	var waitId		=	'#wait';		// wait message container
	var formId		=	'#frmAccount';	// submit button identifier
	var userId		=	'#nickName';			// user input identifier
	var passId		=	'#password';			// password input identifier
	var passbisId		=	'#pbis';			// password input identifier
	var lastNameId		=	'#lastName';			// password input identifier
	var firstNameId		=	'#firstName';			// password input identifier
	var emailId		=	'#email';			// password input identifier
	
	var waitNote	=	'Chargement...';											// loading message
	var jsErrMsg	=	"Nom d'utilisateur ou mot de passe non valide";						// clientside error message
	
	var postFile	=	'submodule.post.php?SubModule=7';	// post handler
	
	var autoRedir	=	true;			// auto redirect on success
	var _receiveAlert = 0;
	var _receiveResult = 0;
	var _defaultView = 0;
	

	
	$(document).ready(function(){ 
		$(waitId).hide(); $(wrapperId).hide();
	});
	
	function getAccount () { 

	
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
var jcrop_api;
var _fileExt = "";
var _orginalW = 0;
var _orginalH = 0;
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
				
				
				jQuery('#frmAccount').validated(function(){
					
					// loading
					
					$(waitId).html(waitNote).fadeIn();
						
					var _p = $(passId).val();	// form id
					var _fn = $(firstNameId).val();	// form user
					var _ln = $(lastNameId).val();	// form id
					var _e = $(emailId).val();	// form user
					var _avatar = $('#avatarName').val();
					
					$.ajax({
						type: "POST",
						url: postFile,
						  dataType: 'json',
						  data: {  password: _p, firstName: _fn, lastName: _ln, email: _e , defaultview:_defaultView, avatarName:_avatar, receiveAlert:_receiveAlert, receiveResult:_receiveResult},
						  success: callbackRegister,
						  error: callbackPostError
						});	
					return false;
				});	
				$(userId).validate({
                    expression: "if (VAL.length > 4 && VAL) return true; else return false;",
                    message: "Veuillez saisir plus de 4 caract&egrave;res!"
                });
				$(passId).validate({
                    expression: "if (VAL.length==0 || VAL.length > 5) return true; else return false;",
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
                    expression: "if ((VAL == jQuery('" + passId + "').val())) return true; else return false;",
                    message: "Le mot de passe n'est pas identique!"
                });
                
                $(emailId).validate({
                    expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
                    message: "Saisissez un email correct!"
                });                
                
				//*/
				$(lastNameId).focus();
				
				var uploader = new qq.FileUploader({
				    // pass the dom node (ex. $(selector)[0] for jQuery users)
				    element: $('#file-uploader')[0],
				    // path to server-side upload script
				    action: 'upload.avatar.php',
				    onComplete: function(id, fileName, responseJSON){
					jcrop_api.release();
					jcrop_api.destroy();
					$('#OriginalAvatar').fadeOut("fast").html();
					$('#HiddenAvatar').attr("src",responseJSON.filePath).load('slow',function () {
						_orginalW = $(this).width();    // Current image width
						_orginalH = $(this).height();  // Current image height
					});
					$('#OriginalAvatar').attr("src",responseJSON.filePath).fadeIn('slow',function () {
						_fileExt = responseJSON.fileExt;
						$.log(_fileExt);
						$('#file-uploader').find("ul li:not(:has(.qq-upload-fail))").fadeOut();
						$('#avatar').attr("src",responseJSON.filePath).fadeIn();
						initJcrop();
						
					});
					
					/*$('#OriginalAvatar').fadeOut("fast").html();
					$('#OriginalAvatar').attr("src","http://localhost/Ligue12010/images/avatars/" + fileName).fadeIn('slow');
					$('#avatar').attr("src","http://localhost/Ligue12010/images/avatars/" + fileName).fadeIn('slow');*/
				}
				});
				
				$('#AvatarLink').click(function (){
					if (!$(this).hasClass("apply")){
						$('#accountDiv').fadeOut('slow', function (){
							$('#accountAvatarDiv img').attr('src',$('#avatar').attr('src'));
							$('#accountAvatarDiv').fadeIn('slow',function (){
								initJcrop();
								
							}).html();
						}).html();
						$(this).html("Valider").css("padding-left","21px").css("padding-right","21px").toggleClass("apply");

					}
					else {
						$('#accountAvatarDiv').fadeOut('slow', function (){
							if($("ul.qq-upload-list > li").length>0) {
							$.log(jcrop_api.tellScaled());
							$.log(jcrop_api.tellSelect());
							var rect = jcrop_api.tellScaled();
							
							$.log('_orginalW:'+_orginalW);
							$.log('_orginalH:'+_orginalH);
							
							var scaleW = _orginalW / 300;
					        var scaleH = _orginalH / 300;
							
					        $.log(rect.x);
					        $.log(rect.x*scaleW);
					        
							$.log(_fileExt);
							$.ajax({
								type: "POST",
								url: 'save.avatar.php',
								  dataType: 'json',
								  data: { x1: rect.x*scaleW, y1: rect.y*scaleH, w: rect.w*scaleW, h: rect.h*scaleH, fileExt: _fileExt},
								  success: function (data) { 
									  $.log(data.filePath);
									  $('#avatar').attr("src",data.filePath).fadeIn();
									  
									  $.log(data.fileName);
									  $.log($('#avatarName'));
									  $('#avatarName').val(data.fileName);
									  
									  jQuery('#avatar').css({
											width: '82px',
											height: '82px',
											marginLeft: '0px',
											marginTop: '0px'
										});
									  return true;},
								  error: callbackPostError
								});	
							}
							$('#accountDiv').fadeIn().html();
						}).html();
						$(this).html("Modifier").css("padding-left","19px").css("padding-right","19px").toggleClass("apply");
					}
				});
				
			   	$("#ReceiveResult")
			   	.iButton({
			   	    labelOn: "Oui"
			   	     , labelOff: "Non"
			   	     , change : function ($input) {
			   			_receiveAlert = $input.is(":checked") ? 1 : 0;
			   		}
			   	   }).trigger("change");
			    
			    
			   	$("#ReceiveAlert").iButton({
			   	    labelOn: "Oui"
			   	     , labelOff: "Non"
				   	 , change : function ($input) {
		   			   _receiveResult = $input.is(":checked") ? 1 : 0;
		   		}
		   	   }).trigger("change");
				
			   	$("#DefaultForecastView").iButton({
			   	    labelOn: "Calendrier"
			   	     , labelOff: "Liste"
				   	 , change : function ($input) {
		   			  _defaultView = $input.is(":checked") ? 1 : 0;
		   		}
		   	   }).trigger("change");
				  /* $('#OriginalAvatar').live('change',function() {
				        var maxWidth = 300; // Max width for the image
				        var maxHeight = 300;    // Max height for the image
				        var ratio = 0;  // Used for aspect ratio
				        var width = $(this).width();    // Current image width
				        var height = $(this).height();  // Current image height
				        _orginalW = width;
				        _orginalH = height;

				        // Check if the current width is larger than the max
				        if(width > maxWidth){
				            ratio = maxWidth / width;   // get ratio for scaling image
				            $(this).css("width", maxWidth); // Set new width
				            $(this).css("height", height * ratio);  // Scale height based on ratio
				            height = height * ratio;    // Reset height to match scaled image
				            width = width * ratio;    // Reset width to match scaled image
				        }
				 
				        // Check if current height is larger than max
				        if(height > maxHeight){
				            ratio = maxHeight / height; // get ratio for scaling image
				            $(this).css("height", maxHeight);   // Set new height
				            $(this).css("width", width * ratio);    // Scale width based on ratio
				            width = width * ratio;    // Reset width to match scaled image
				        }
				        
				    });*/
				   

					
			}).html();
		});
		
	}
	
}
function initJcrop () {
	if (jcrop_api){
		jcrop_api.destroy();
	}

	jcrop_api = $.Jcrop('#OriginalAvatar');
	jcrop_api.setOptions( {
		onChange: showPreview,
		onSelect: showPreview,
		aspectRatio: 1,
		minSize: [ 10, 10 ],
		allowSelect:false,
		allowMove:true,
		allowResize:true,
		boxWidth:300,
		boxHeight:300
	});
	if($("ul.qq-upload-list > li").length>0) {
		jcrop_api.setSelect([0,0,300,300]);
		jcrop_api.focus();
	
	}
	else {
		jcrop_api.release();
	}
}


function showPreview(coords)
{
	if (parseInt(coords.w) > 0)
	{
		var rx = 82 / coords.w;
		var ry = 82 / coords.h;

		jQuery('#avatar').css({
			width: Math.round(rx * 300) + 'px',
			height: Math.round(ry * 300) + 'px',
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
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
