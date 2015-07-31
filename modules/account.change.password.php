<?php
AddScriptReference("validate");
AddScriptReference("account.change.password");
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

  if(!$resultSet) return exit ("La clé est non valide!");

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
<div id="containerTitle">
<div class="titleContainer" >
<div class="title" ><?php echo "Changement de mot de passe ...";?></div>
</div>

<div id="containerError">
<div class="titleContainer">
		<div class="title">
<img src="<?php echo ROOT_SITE;?>/images/error.png" id="avatar" />
		</div>
</div>

<div class="content">
<?php echo "Pour une raison inconnue, votre mot de passe n'a pas pu être changé, par conséquent, veuillez contacter l'administrateur du site directement par email <a href='mailto:admin@pronostics4fun.com'>admin@pronostics4fun.com</a>, pour qu'il règle ce problème."?>
</div>
</div>
<div id="containerSuccess">
<div class="titleContainer" >
		<div class="title" >
<img src="<?php echo ROOT_SITE;?>/images/ok.2.png" id="avatar"/>
		</div>
</div>

<div class="content">
<?php echo "Votre mot de passe a été changé, vous pouvez maintenant vous rendre sur la page d'accueil en cliquant <a href='".ROOT_SITE."'>ici</a>"?>
</div>

</div>

<div id="containerDataInput">
<div class="titleContainer" >
		<div class="title" >
<img src="<?php echo $avatarPath;?>" id="avatar"/>
		</div>
</div>
<div class="content">
<form id="frmAccount">
<div id="accountDiv">
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

<div id="containerTitle">
<div class="titleContainer">
<div class="title" ><?php echo "Changement de mot de passe ...";?></div>
</div>

<div id="containerError">
<div class="titleContainer">
		<div class="title">
<img src="<?php echo ROOT_SITE;?>/images/error.png" id="avatar"/>
		</div>
</div>

<div class="content">
<?php echo "Ce lien n'est plus valide, veuillez recommencer la procédure depuis le début en cliquant sur le lien <u>'Mot de passe oublié?'</u>, de la fen�tre de connexion, ou bien, veuillez contacter l'administrateur du site directement par email <a href='mailto:admin@pronostics4fun.com'>admin@pronostics4fun.com</a>."?>
</div>
</div>

</div>
<script>

$.requireScript('<?php echo ROOT_SITE; ?>/js/jquery.corner.js', function() {
	$("#containerTitle").corner();
});

</script>

<?php }?>
