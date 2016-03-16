<?php
AddScriptReference("home");
WriteScripts();

switch ($_competitionType) {
case 2:
echo __encode("
<div id='mainCol'>
<div class='altBloc' style='width:380px;'>
<h3 class='homeTitle'>Bienvenue sur </h3>
<h3 class='homeTitle'>Pronostics4Fun</h3>
<h4 class='homeSubTitle'>Spécial - Coupe du monde 2014</h3>
<br/>
<br/>
<br/>
<p>
A chaque grand événement footballistique, Pronostics4Fun ouvre ses portes pour une compétition dédié à cet événement. C'est donc à l'occasion de la coupe du monde 2014, que vous allez vous affronter, cette compétition compte 64 matchs et qui se déroulera du 12 juin au 13 juillet.
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
<img style='padding-left: 130;' src='" . ROOT_SITE . $_themePath . "/images/logo-home.png' />
</div>
	</div>

");
break;
	case 3:
echo __encode("
<div id='mainCol'>
<div class='altBloc' style='width:380px;'>
<h3 class='homeTitle'>Bienvenue sur </h3>
<h3 class='homeTitle'>Pronostics4Fun</h3>
<h4 class='homeSubTitle'>Spécial - EURO 2016</h3>
<br/>
<br/>
<br/>
<p>
A l'instar de la coupe du monde 2014, Pronostics ouvre ses portes, pour vous offrir une compétition sur l'EURO 2016, alors venez vous affronter sur cette compétition qui comptera 51 matchs et qui se déroulera du 10 juin au 10 juillet.
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
	<div class='mainBloc' style='width:410px;'>
<img src='" . ROOT_SITE . $_themePath . "/images/logo-home.png' />
</div>
	</div>

");
break;
default:
echo __encode("
<div id='mainCol'>
<style>
p {
  color:#000000;
  font-size:12px;
  font-weight:normal;
}
</style>
<div class='altBloc'>
<h3 style='font-size:30px;font-family: Georgia;color:#365F89'>Bienvenue sur le site</h3>
<h3 style='font-size:30px;font-family: Georgia;color:#365F89'>Pronostics4Fun</h3>
<h4 style='font-size:20px;font-family: Georgia;color:#FFFFFF;text-align:center;'>Ligue 1 - Saison 2015/2016</h3>
<br/>
<br/>
<br/>
<p>
Comme chaque saison de ligue 1, nous vous proposons de venir vous afronter en pronostiquant sur les matchs qui se disputeront tout au long de ces 38 journées de championnat.</p>
<br/>
<p>
Vous pouvez consulter le règlement de ce jeu-concours, en cliquant sur le lien 'règlement' se situant dans la partie droite du menu.
</p>
<br/>
<p>
Si vous êtes convaincu, alors rejoignez-nous en vous inscrivant, en un simple clique sur Inscription (en haut à droite), et tout de suite après vous pourrez pronostisquer vos premiers matchs.
</p>
<br/>
<p style='text-align:right;'>
<strong>A très bientôt sur Pronostics4Fun !</strong>
</p>
</div>
	<div class='mainBloc'>
<img src='" . ROOT_SITE . $_themePath . "/images/logo-home.png' />
</div>
	</div>

");
}


?>