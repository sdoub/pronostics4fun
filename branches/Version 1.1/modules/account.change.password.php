<style>
label {
	color: #6D8AA8;
	font-weight: bold;
	display: block;
}

label.title {
	color: #FFFFFF;
	font-weight: bold;
	display: block;
	font-size:11px;
}

label.read {
	/* background: #b3a58f; */
	border: solid 1px #D7E1F6;
	padding: 3px;
	margin: 4px 0px 4px 0px;
	width: 87%;
	border-radius: 7px;
	-moz-border-radius: 7px;
	font-weight: normal;
}

.textfield {
	background: #b3a58f;
	border: solid 1px #D7E1F6;
	padding: 3px;
	margin: 4px 0px 4px 0px;
	width: 90%;
}

.textfield:hover,.textfield:focus {
	background: #365F89;
	border: solid 1px #365F89;
	color: #fff;
}

.checkboxfield {
	width: 10%;
}

.checkboxlabel {
	font-size:9px;
	font-weight:bold;
	display:inline !important;
}

.buttonfield {
	background: #365F89;
	border: solid 1px #D7E1F6;
	color: #FFFFFF;
	font: bold 11px/ normal Tahoma, Verdana;
	margin-top: 10px;
	padding: 4px;
	float: right;
}

.buttonfield:hover,.buttonfield:focus {
	background: #000;
	border: solid 1px #fff;
	color: #fff;
	cursor: pointer;
}
</style>
<?php
AddScriptReference("validate");

WriteScripts();
?>



<?php
if (isset($_GET["key"]))
{
 $_activationKey=$_GET["key"];
}
else
{
 $_activationKey ="";
}

if ($_activationKey) {
  $sql = "SELECT * FROM players WHERE ";
  $sql .= " ActivationKey = '".mysql_real_escape_string($_activationKey)."'";
  //echo $sql;
  $resultSet = $_databaseObject->queryPerf($sql,"Recuperation des infos du user");

  if(!$resultSet) return exit (__encode("La clé est non valide!"));

  $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
  $rootSite = ROOT_SITE;
  $avatarPath = $rootSite . '/images/DefaultAvatar.jpg';
  $avatarName= $rowSet["AvatarName"];
  $_activationKey= $rowSet["ActivationKey"];
  if (!empty($avatarName)) {
    $avatarPath= $rootSite . '/images/avatars/'.$avatarName;
  }
}
if ($_activationKey) {
?>
<div id="containerTitle" style="background:url('<?php echo ROOT_SITE; ?>/images/account.password.png') no-repeat scroll left top
		#D7E1F6;height:360px;margin-bottom:30px;_width:920px;">
<div style="text-align:left;color:#365F89;top-margin:5px;left-margin:10px;">
<div style="position:absolute;top:20px;left:120px;font-size:16px;text-transform: uppercase;font-weight:bold;"><?php echo __encode("Changement de mot de passe ...");?></div>
</div>

<div id="containerError" style="display:none;position:absolute;left:170px;top:45px;background-color:#6D8AA8;height:275px;width:670px;">
<div style="position:absolute;width:100px;top:5px;left:10px;">
		<div style="width:110px;height:110px;overflow:hidden;margin:20px;">
<img src="<?php echo ROOT_SITE;?>/images/error.png" id="avatar" style="width:55px;height:55px;"/>
		</div>
</div>

<div style="position:absolute;top:20px;left:120px;color:#FFFFFF;margin-right: 45px;">
<?php echo __encode("Pour une raison inconnue, votre mot de passe n'a pas pu être changé, par conséquent, veuillez contacter l'administrateur du site directement par email <a href='mailto:admin@pronostics4fun.com'>admin@pronostics4fun.com</a>, pour qu'il règle ce problème.")?>
</div>
</div>
<div id="containerSuccess" style="display:none;position:absolute;left:170px;top:45px;background-color:#6D8AA8;height:275px;width:670px;">
<div style="position:absolute;width:100px;top:5px;left:10px;">
		<div style="width:110px;height:110px;overflow:hidden;margin:20px;">
<img src="<?php echo ROOT_SITE;?>/images/ok.2.png" id="avatar" style="width:55px;height:55px;"/>
		</div>
</div>

<div style="position:absolute;top:20px;left:120px;color:#FFFFFF;margin-right: 45px;">
<?php echo __encode("Votre mot de passe a été changé, vous pouvez maintenant vous rendre sur la page d'accueil en cliquant <a href='".ROOT_SITE."'>ici</a>")?>
</div>

</div>

<div id="containerDataInput" style="position:absolute;left:170px;top:45px;background-color:#6D8AA8;height:275px;width:670px;">
<div style="position:absolute;width:100px;top:5px;left:10px;">
		<div style="width:110px;height:110px;overflow:hidden;margin:20px;">
<img src="<?php echo $avatarPath;?>" id="avatar" style="width:110px;height:110px;"/>
		</div>
</div>
<div style="position:absolute;top:20px;left:200px;">
<form id="frmAccount">
<div style="width:360px;padding-left:20px;padding-top:0px;" id="accountDiv">
<div><label class="title">Pseudo : </label>
<label class="read title"><?php echo $rowSet["NickName"]; ?></label>
</div>
<div>
<label class="title">Email: </label>
<label class="read title"><?php echo $rowSet['EmailAddress']; ?></label>
</div>
<label class="title">Mot de passe : </label>
<input name="password" id="password" class="textfield"	type="password">
<label class="title">Confirmer votre mot de passe : </label>
<input name="pbis" id="pbis" class="textfield" type="password">
<input name="btn" id="btn" class="buttonfield" value="Changer" type="submit"/>
</div>
</form>
</div>
</div>
</div>
<script>

var _activationKey = '<?php echo $_activationKey;?>';
var _email = '<?php echo $rowSet['EmailAddress']; ?>';
var _nickName = '<?php echo $rowSet["NickName"]; ?>';

$.requireScript('<?php echo ROOT_SITE; ?>/js/jquery.corner.js', function() {
	$("#containerTitle").corner();
});

jQuery('#frmAccount').validated(function(){

	var _p = $("#password").val();	// form id

	$.ajax({
		type: "POST",
		url: 'submodule.post.php?SubModule=7',
		  dataType: 'json',
		  data: {  password: _p , key:_activationKey, email:_email, nickName:_nickName},
		  success: callbackChangePassword,
		  error: callbackPostError
		});
	return false;
});

$("#password").validate({
    expression: "if (VAL.length==0 || VAL.length > 5) return true; else return false;",
    message: "Veuillez saisir plus de 5 caract&egrave;res!"
});

$("#pbis").validate({
    expression: "if ((VAL == jQuery('#password').val())) return true; else return false;",
    message: "Le mot de passe n'est pas identique!"
});

$("#password").validate({
    expression: "if (VAL) return true; else return false;",
    message: "Champ obligatoire!"
});

$("#pbis").validate({
    expression: "if (VAL) return true; else return false;",
    message: "Champ obligatoire!"
});

function callbackPostError (XMLHttpRequest, textStatus, errorThrown)
{
	$.log(XMLHttpRequest);
	$.log(textStatus);
	$.log(errorThrown);
}

function callbackChangePassword(data) {

	if(data.status==true){
		$("#containerDataInput").fadeOut('slow', function(){
			$("#containerSuccess").fadeIn();
		}).html();
	} else {
		$("#containerDataInput").fadeOut('slow', function(){
			$("#containerError").fadeIn();
		}).html();
	}
}
</script>
<?php }
else {
?>

<div id="containerTitle" style="background:url('<?php echo ROOT_SITE; ?>/images/account.password.png') no-repeat scroll left top
		#D7E1F6;height:360px;margin-bottom:30px;_width:920px;">
<div style="text-align:left;color:#365F89;top-margin:5px;left-margin:10px;">
<div style="position:absolute;top:20px;left:120px;font-size:16px;text-transform: uppercase;font-weight:bold;"><?php echo __encode("Changement de mot de passe ...");?></div>
</div>

<div id="containerError" style="position:absolute;left:170px;top:45px;background-color:#6D8AA8;height:275px;width:670px;">
<div style="position:absolute;width:100px;top:5px;left:10px;">
		<div style="width:110px;height:110px;overflow:hidden;margin:20px;">
<img src="<?php echo ROOT_SITE;?>/images/error.png" id="avatar" style="width:55px;height:55px;"/>
		</div>
</div>

<div style="position:absolute;top:20px;left:120px;color:#FFFFFF;margin-right: 45px;">
<?php echo __encode("Ce lien n'est plus valide, veuillez recommencer la procédure depuis le début en cliquant sur le lien <u>'Mot de passe oublié?'</u>, de la fenêtre de connexion, ou bien, veuillez contacter l'administrateur du site directement par email <a href='mailto:admin@pronostics4fun.com'>admin@pronostics4fun.com</a>.")?>
</div>
</div>

</div>
<script>

$.requireScript('<?php echo ROOT_SITE; ?>/js/jquery.corner.js', function() {
	$("#containerTitle").corner();
});

</script>

<?php }?>


