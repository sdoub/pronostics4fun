<?php
AddScriptReference("home");
WriteScripts();

echo __encode("
<div id='mainCol'>
<div class='altBloc'>
<h3 class='homeTitle'>Bienvenue sur le site</h3>
<h3 class='homeTitle'>Pronostics4Fun</h3>
<h4 class='homeSubTitle'>Ligue 1 - Saison 2011/2012</h3>
<br/>
<br/>
<br/>
<p>
Comme chaque saison de ligue 1, nous vous proposons de venir vous afronter en pronostiquant sur les matchs qui se disputeront tout au long de ces 38 journ�es de championnat.</p>
<br/>
<p>
Vous pouvez consulter le r�glement de ce jeu-concours, en cliquant sur le lien 'r�glement' se situant dans la partie droite du menu.
</p>
<br/>
<p>
Si vous �tes convaincu, alors rejoignez-nous en vous inscrivant, en un simple clique sur Inscription (en haut � droite), et tout de suite apr�s vous pourrez pronostisquer vos premiers matchs.
</p>
<br/>
<p class='homeSignature' >
<strong>A tr�s bient�t sur Pronostics4Fun !</strong>
</p>
</div>
	<div class='mainBloc'>
<img src='" . ROOT_SITE. "/images/logo-lfp-2010.png' />
</div>
	</div>

");

?>