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
	
	var postFile	=	'submodule.post.php?SubModule=14';	// post handler
	
	var autoRedir	=	false;			// auto redirect on success
	
	var _matchKey = "";

	
	$(document).ready(function(){ 
		$(waitId).hide(); $(wrapperId).hide();
	});
	
	function getVote () { 

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
			$(waitId).fadeOut('slow', function(){ 
				$(wrapperId).html(data.message).slideDown();
				
				$('div[name^="stars-wrapper"]').stars();
				
			}).html();
		}
	} else {
		// show form
		$(wrapperId).html(data.message).slideDown('fast',function (){
			// hide message
			$(waitId).fadeOut('fast',function(){
				   $("#wrapper span.votePlayers[rel]").cluetip(
							{positionBy:"fixed",
								showTitle:false,
								width:350,
								ajaxCache:false,
								cluetipClass:"p4f",
								arrows:false,
								sticky:false,
								topOffset: 30,
								leftOffset: -200,
								cluezIndex: 999999
					});
				
				   $("#wrapper span.bonusMatch[rel]").cluetip(
							{positionBy:"fixed",
								showTitle:false,
								width:500,
								ajaxCache:false,
								cluetipClass:"p4f",
								arrows:false,
								sticky:false,
								topOffset: 30,
								leftOffset: -200,
								cluezIndex: 999999
					});

				   
				   var values = 10;
				$('div[name^="stars-wrapper"]').each(function (index){
					if (eval($(this).attr("vote-value"))) {
						values -= eval($(this).attr("vote-value"));
					}
					
				});
				var isExceeded = eval($('#frmVote tr[class="day"]').attr("is-exceeded"));
				$.log('isExceeded'+isExceeded);
				var isDisabled = false;
				var isVoted =false;
				if (values==0) 
					isVoted =true;
				if (isVoted || isExceeded)
					isDisabled =true;
				$('div[name^="stars-wrapper"]').each(function(index) {
					var matchDisabled = isDisabled;
					$.log('eval : '+ eval($(this).attr("is-disabled")));
					if (eval($(this).attr("is-disabled"))) {
						matchDisabled = true;
					}
					$.log('matchDisabled : '+ matchDisabled);
					
					$(this).stars({
						callback: function(ui, type, value){
					
					var values = 10;
					$('div[name^="stars-wrapper"]').each(function (index){
						values -= $(this).data("stars").options.value;
					});
					if (values<0) {
						values +=eval(value);
						ui.select(0);
					} 
						$('#voteRemaining').stars("select",values);
						$('#remainingStars').html(values);

					},
					disabled:matchDisabled 
					});
				});
				
				if (isDisabled){
					if (isVoted) 
						$("#Voted").show();
					else
						$("#TooLateVoted").show();
					$("#ToBeVoted").hide();
					$('span[name="GlobalNote"]').show();
				}
				
				$('div[name^="stars-wrapper"]').each(function (index){
					if (eval($(this).attr("vote-value"))) {
						$(this).stars("select",$(this).attr("vote-value"));
					}
				});


				$('#voteRemaining').stars({
				    disabled: true
				});
				
				$('#voteRemaining').stars("select",values);				
				if (isDisabled){
					$("#btnValidateVote").attr("value","Fermer");
					$("#frmVote").submit( function() { 
            		   $.closePopupLayer();
						return false;
   				});			
               }
               else
               {
				// */ submit handler
				$("#frmVote").submit( function() { 
					
					var stars = $('#voteRemaining').data("stars").options.value;	// form user
 
					if(stars != 0) 
					{
						$(waitId).html("Avant de valider votre vote, veuillez attribuer les 10 &eacute;toiles").fadeIn('fast',function(){ 
//							$(teamHomeScoreId).focus();
						});
					} 
					else
					{
					// loading
					$(waitId).html(waitNote).fadeIn();
						
					var arrMatches = new Array();
					$('div[name^="stars-wrapper"]').each(function (index){
						var arrMatch = new Array();
						arrMatch.push( $(this).attr("match-key"));
						arrMatch.push( $(this).data("stars").options.value);
						arrMatches.push(arrMatch);
					});

					
								$.ajax({
									type: "POST",
									url: postFile,
									  dataType: 'json',
									  data: { matches: arrMatches},
									  success: callbackVote,
									  error: callbackPostError
									});	
								// $.post(postFile, { u: _u, p: _p
								// },callbackAuthentication,'json');
					}
					return false;
				});				
				// */
               }
			}).html();
		
		});
		
	}
	
}


function callbackVote(data) {
	
	if(data.status==true){ 
		if(autoRedir){ 
			$(waitId).html('Redirection...').fadeIn('fast', function(){
			  $.log("redirection :"+data.url);
				// window.location.replace(data.url);
				// window.location=data.url;
			});
		} else {
			$.ajax({
			  url: postFile,
			  dataType: 'json',
			  data: "",
			  success: callbackPost,
			  error: callbackPostError
						});	
		}
	} else {
		$(waitId).html(data.message).slideDown('fast', function(){ 
			$(teamHomeScoreId).focus(); 
		}); 
	}
}
