<?php
AddScriptReference("validate");
AddScriptReference("account.email.validation");
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

	$q = new PlayersQuery();
  $player = $q->findOneByActivationkey($_activationKey);
  if(!$player) 
		$_activationKey='';
	else {
		$player->setIsEmailValid(1);
		$player->save();
	}
if ($_activationKey) {
?>
<div id="containerTitle">
<div class="titleContainer" >
<div class="title" >Validation de l'adresse email ...</div>
</div>

<div id="containerSuccess">
<div class="titleContainer" >
		<div class="title" >
			<img src="<?php echo ROOT_SITE;?>/images/ok.2.png"/>
		</div>
</div>

<div class="content">
	Votre adresse email est validée, vous pouvez maintenant vous rendre sur la page d'accueil en cliquant <a href='/'>ici</a>
</div>

</div>

<div id="containerDataInput">
<div class="titleContainer" >
		<div class="title" >
		</div>
</div>
</div>
</div>
<?php }
else {
?>

<div id="containerTitle">
<div class="titleContainer">
<div class="title" >Validation de l'adresse email ...</div>
</div>

<div id="containerError">
<div class="titleContainer">
		<div class="title">
<img src="<?php echo ROOT_SITE;?>/images/error.png" id="avatar"/>
		</div>
</div>

<div class="content">
Ce lien n'est pas valide, veuillez contacter l'administrateur du site directement par email <a href='mailto:admin@pronostics4fun.com'>admin@pronostics4fun.com</a>.
</div>
</div>

</div>
<?php }?>
