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
<h5>R�glement d�taill�</h5>
<br/>
<p>
Le Jeu-Concours, propos� � partir du site Internet Pronostics4Fun.com (ci-apr�s le 'Site'), est gratuit et sans obligation d'achat, il implique l'acceptation des participants du pr�sent r�glement.</p>
<br/>
<h3>Article 1 - R�glement du jeu-concours</h3>
<p>
Pour participer au jeu de pronostics sur Pronostics4Fun, il vous suffit de vous inscrire en respectant les champs d'identifications sur le site www.pronostics4fun.com et de pronostiquer tous les match non jou�s.
Une fois inscrit, l'internaute peut participer � tout moment au jeu des pronostics '4fun'.
</p>
<br/>
<h3>Article 2 - D�roulement du jeu</h3>
<p>
Le fonctionnement du 'jeu des pronostics 4 fun' est le suivant :
</p>
<p>
Chaque participant inscrit a la possibilit� de participer au jeu des pronostics '4fun'.
Le but du jeu pour les participants est de cumuler le plus grand nombre de points possible en pronostiquant les scores au terme des 90 minutes (soit � la fin de la 2�me p�riode) des diff�rents matchs de la coupe du monde 2010.
</p>
<p>
</p>
<p>
Les pronostics sont possible d�s l'instant o� le lieu et l'heure du match sont connu. Le participant a donc tous les jours pr�c�dant le match pour pronostiquer. Le participant ne peut pronostiquer un match qu'avant son coup d'envoi.
(par exemple, un match pr�vu le samedi � 17H : le participant peut pronostiquer jusqu'� 16H59 de ce m�me jour)
</p>
<p>
Pendant le d�roulement d'un ou des matchs, une page consacr� au score en direct, permette � l'internaute de consulter ses scores en direct, l'attribution des points finaux se fera dans un temps ult�rieur, apr�s validation de ceux ci.
</p>
<p>
Lors de chaque journ�e, un match sera qualifi� de match 'Bonus' signal� par une �toile <span style='width:30px;height:30px;background: url(\"" . ROOT_SITE . "/images/star_15.png\") no-repeat scroll center top transparent;'>&nbsp;&nbsp;&nbsp;&nbsp;</span>, il sera d�sign� au moins 1 jour avant le d�but de chaque joun�e de championnat
<p>
Si le participant obtient le bon r�sultat, il gagne 5 points.
</p>
<p>
Si le participant obtient un bon r�sultat et la bonne diff�rence de buts entre les deux �quipes, il gagne 8 points.
</p>
<p>
Si le participant obtient le bon r�sultat et le bon score, il gagne 15 points.
</p>
<p>
Si le participant fait un bon pronostic sur le nombre de buts marqu�s par l'une des deux �quipe, il gagne 1 point bonus.
</p>
<p>
Si le participant, obtient au sein d'une journ�e 7 bons r�sultats, il gagne 20 points suppl�mentaires
</p>
<p>
Si le participant, obtient au sein d'une journ�e 8 bons r�sultats, il gagne 40 points suppl�mentaires
</p>
<p>
Si le participant, obtient au sein d'une journ�e 9 bons r�sultats, il gagne 60 points suppl�mentaires
</p>
<p>
Si le participant, obtient au sein d'une journ�e 10 bons r�sultats, il gagne 100 points suppl�mentaires
</p>
<p>
Si le participant, obtient d'un point sur le match bonus 'bonus' de la journ�e, les points sur ce match seront doubl�s
</p>
<p>
Le meilleur pronostiqueur d'une journ�e est le participant qui aura cumul� le plus de points sur les matchs de celle-ci.
</p>
<p>
Le meilleur pronostiqueur de la saison 2010-2011, est le participant qui aura cumul� le plus de points sur les 38 journ�es qui se d�roulent pendant cette saison 2010/2011
</p>
<br/>
<h3>Article 3 - Acceptation du r�glement</h3>
<p>
La participation � ce jeu implique l'acceptation enti�re et sans r�serve du pr�sent r�glement.
</p>
<p>
Si un joueur ne se connecte pas au jeu pendant 120 jours cons�cutif alors son compte sera automatiquement d�sactiv�, une nouvelle connexion permettra de le r�activer.
</p>

<br/>
<h3>Article 4 - Modification du r�glement</h3>
<p>
Les repr�sentants de la Soci�t� Organisatrice se r�servent la possibilit� de modifier le pr�sent r�glement en cas de besoin.
<br/>
Des modifications, substantielles ou non, peuvent �ventuellement �tre apport�es au pr�sent r�glement pendant le d�roulement du Jeu ; elles seront alors port�es � la connaissance des joueurs qui devront s'y soumettre en tant qu'annexes aux pr�sentes.
</p>
</div>");
echo json_encode($arr);
?>