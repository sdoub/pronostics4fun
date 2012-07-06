<?php
AddScriptReference("home");
WriteScripts();

echo __encode("
<div id='mainCol'>
<div class='altBloc' style='width:380px;'>
<h3 class='homeTitle'>Bienvenue sur </h3>
<h3 class='homeTitle'>Pronostics4Fun</h3>
<h4 class='homeSubTitle'>Spécial - EURO 2012</h3>
<br/>
<br/>
<br/>
<p>
A l'instar de la coupe du monde 2010, Pronostics ouvre ses portes, pour vous offrir une compétition sur l'EURO 2012, alors venez vous affronter sur cette compétition qui comptera 31 matchs et qui se déroulera du 8 juin au 1er juillet.
</p>
<br/>
<p>
Vous pouvez consulter le règlement de ce jeu-concours, en cliquant sur le lien 'règlement' se situant dans la partie droite du menu.
</p>
<br/>
<p>
Si vous êtes convaincu, alors rejoignez-nous en vous inscrivant, en un simple clique sur Inscription (en haut à droite), et tout de suite après vous pourrez pronostisquer vos premiers matchs.
</p>
<br/>
<p class='homeSignature' >
<strong>A très bientôt sur Pronostics4Fun !</strong>
</p>
</div>
	<div class='mainBloc' style='width:520px;'>
<img src='" . ROOT_SITE . $_themePath . "/images/logo-home.png' />
</div>
	</div>

");

?>