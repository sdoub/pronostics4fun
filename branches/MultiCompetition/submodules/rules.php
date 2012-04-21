<?php
      $arr["status"] = false;
      $arr["message"] = __encode("
<div style='overflow: auto; width: 760px; height: 450px; margin: 58px 0px 0pt 0pt;'>
<h5>Comptabilisation des points</h5>
<br>
<p>
- Bon r&eacute;sultat = <strong>5 points</strong>.<br>
- Bon r&eacute;sultat et  bonne diff&eacute;rence de buts entre les deux &eacute;quipes = <strong>8 points</strong>.<br>
- Bon r&eacute;sultat et bon score = <strong>15 points</strong>.<br>
- Bon pronostic sur le nombre de buts marqu&eacute;s par l'une des deux &eacute;quipe = <strong>1 point</strong> suppl&eacute;mentaire.<br>
</p>
<br/>
<h4>Bonus :</h4><br>
<p>
&nbsp;&nbsp;&nbsp;- 7 bons r&eacute;sultats = <strong>20 points</strong> suppl&eacute;mentaires <br>
&nbsp;&nbsp;&nbsp;- 8 bons r&eacute;sultats = <strong>40 points</strong> suppl&eacute;mentaires<br>
&nbsp;&nbsp;&nbsp;- 9 bons r&eacute;sultats = <strong>60 points</strong> suppl&eacute;mentaires<br>
&nbsp;&nbsp;&nbsp;- 10 bons r&eacute;sultats = <strong>100 points</strong> suppl&eacute;mentaires<br>
<br/>
&nbsp;&nbsp;&nbsp;- Double de point pour un match 'bonus'<br>
</p>
<br/>
<br/>
<br/>
<h5>Règlement détaillé</h5>
<br/>
<p>
Le Jeu-Concours, proposé à partir du site Internet Pronostics4Fun.com (ci-après le 'Site'), est gratuit et sans obligation d'achat, il implique l'acceptation des participants du présent règlement.</p>
<br/>
<h3>Article 1 - Règlement du jeu-concours</h3>
<p>
Pour participer au jeu de pronostics sur Pronostics4Fun, il vous suffit de vous inscrire en respectant les champs d'identifications sur le site www.pronostics4fun.com et de pronostiquer tous les match non joués.
Une fois inscrit, l'internaute peut participer à tout moment au jeu des pronostics '4fun'.
</p>
<br/>
<h3>Article 2 - Déroulement du jeu</h3>
<p>
Le fonctionnement du 'jeu des pronostics 4 fun' est le suivant :
</p>
<p>
Chaque participant inscrit a la possibilité de participer au jeu des pronostics '4fun'.
Le but du jeu pour les participants est de cumuler le plus grand nombre de points possible en pronostiquant les scores au terme des 90 minutes (soit à la fin de la 2ème période) des différents matchs de la coupe du monde 2010.
</p>
<p>
</p>
<p>
Les pronostics sont possible dès l'instant où le lieu et l'heure du match sont connu. Le participant a donc tous les jours précédant le match pour pronostiquer. Le participant ne peut pronostiquer un match qu'avant son coup d'envoi.
(par exemple, un match prévu le samedi à 17H : le participant peut pronostiquer jusqu'à 16H59 de ce même jour)
</p>
<p>
Pendant le déroulement d'un ou des matchs, une page consacré au score en direct, permette à l'internaute de consulter ses scores en direct, l'attribution des points finaux se fera dans un temps ultérieur, après validation de ceux ci.
</p>
<p>
Lors de chaque journée, un match sera qualifié de match 'Bonus' signalé par une étoile <span style='width:30px;height:30px;background: url(\"" . ROOT_SITE . "/images/star_15.png\") no-repeat scroll center top transparent;'>&nbsp;&nbsp;&nbsp;&nbsp;</span>, il sera désigné au moins 1 jour avant le début de chaque jounée de championnat
<p>
Si le participant obtient le bon résultat, il gagne 5 points.
</p>
<p>
Si le participant obtient un bon résultat et la bonne différence de buts entre les deux équipes, il gagne 8 points.
</p>
<p>
Si le participant obtient le bon résultat et le bon score, il gagne 15 points.
</p>
<p>
Si le participant fait un bon pronostic sur le nombre de buts marqués par l'une des deux équipe, il gagne 1 point bonus.
</p>
<p>
Si le participant, obtient au sein d'une journée 7 bons résultats, il gagne 20 points supplémentaires
</p>
<p>
Si le participant, obtient au sein d'une journée 8 bons résultats, il gagne 40 points supplémentaires
</p>
<p>
Si le participant, obtient au sein d'une journée 9 bons résultats, il gagne 60 points supplémentaires
</p>
<p>
Si le participant, obtient au sein d'une journée 10 bons résultats, il gagne 100 points supplémentaires
</p>
<p>
Si le participant, obtient d'un point sur le match bonus 'bonus' de la journée, les points sur ce match seront doublés
</p>
<p>
Le meilleur pronostiqueur d'une journée est le participant qui aura cumulé le plus de points sur les matchs de celle-ci.
</p>
<p>
Le meilleur pronostiqueur de la saison 2010-2011, est le participant qui aura cumulé le plus de points sur les 38 journées qui se déroulent pendant cette saison 2010/2011
</p>
<br/>
<h3>Article 3 - Acceptation du règlement</h3>
<p>
La participation à ce jeu implique l'acceptation entière et sans réserve du présent règlement.
</p>
<p>
Si un joueur ne se connecte pas au jeu pendant 120 jours consécutif alors son compte sera automatiquement désactivé, une nouvelle connexion permettra de le réactiver.
</p>

<br/>
<h3>Article 4 - Modification du réglement</h3>
<p>
Les représentants de la Société Organisatrice se réservent la possibilité de modifier le présent règlement en cas de besoin.
<br/>
Des modifications, substantielles ou non, peuvent éventuellement être apportées au présent règlement pendant le déroulement du Jeu ; elles seront alors portées à la connaissance des joueurs qui devront s'y soumettre en tant qu'annexes aux présentes.
</p>
</div>");
echo json_encode($arr);
?>