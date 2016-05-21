<div>
<?php
$additionalParameter = "";
if (isset($_GET["Mode"]))
  $additionalParameter="&Mode=" . $_GET["Mode"];

$firstGroupKey = 169;

?>
<style	>
	.groups > ul > li {
		float:left;
		width:11em;
		background-color: #3c8bb9;
		text-align:center;
		color:#fff;
		height:8em;
		margin:1em;
	}	
	
	div.groups {
    width: 940px;
    background-color: #1d5886;
	}
	
	.roundGroup {
		height:10em;
	}
	.round16 > ul > li > ul  {
		padding-top:0.5em;
	}	
	.round16 > ul > li {
		width:8.1em;
		height:4em;
		margin:0.8em;
	}
	.round16 {
		height:6em;
	}
	

	div.titleGroup {
    width: 940px;
		margin-top: 2em;
    background-color: #1d5886;
		height:2em;
	}
	div.titleGroup > h3 {
		padding-top:5px;
	}
	div.groups ul li div h3 {	
		padding: 2px 0px 10px 0px;
	}
</style>
<div>
	<div class="titleGroup">
		<h3>
			Phase de groupes
		</h3>
	</div>
	<div class="groups roundGroup" name="groups"	>
		<ul >
			<li>
				<div>
					<h3>
						Groupe A
					</h3>
					<ul>
						<li>Albanie</li>
						<li>France</li>
						<li>Roumanie</li>
						<li>Suisse</li>
					</ul>
				</div>
			</li>
			<li>
				<div>
					Groupe B
				</div>
			</li>
			<li>
				<div>
					Groupe C
				</div>
			</li>
			<li>
				<div>
					Groupe D
				</div>
			</li>
			<li>
				<div>
					Groupe E
				</div>
			</li>
			<li>
				<div>
					Groupe F
				</div>
			</li>
		</ul>
	</div>
	<div class="titleGroup">
		<h3>
			Huitièmes de finale
		</h3>
	</div>
	<div class="groups round16" name="FinalRound1">
	  <ul>
			<li>
				<ul>
					<li>Albanie</li>
					<li>France</li>
				</ul>
			</li>
			<li>
				<ul>
					<li>Albanie</li>
					<li>Rép. d'Irlande</li>
				</ul>
			</li>
						<li>
				<ul>
					<li>Albanie</li>
					<li>France</li>
				</ul>
			</li>
						<li>
				<ul>
					<li>Albanie</li>
					<li>Pays de Galles</li>
				</ul>
			</li>
						<li>
				<ul>
					<li>Albanie</li>
					<li>France</li>
				</ul>
			</li>
						<li>
				<ul>
					<li>Albanie</li>
					<li>France</li>
				</ul>
			</li>
						<li>
				<ul>
					<li>Albanie</li>
					<li>France</li>
				</ul>
			</li>
						<li>
				<ul>
					<li>Albanie</li>
					<li>France</li>
				</ul>
			</li>
		</ul>
	</div>
	<div class="titleGroup">
		<h3>
			Quarts de finale
		</h3>
	</div>	
	<div class="groups round8" name="FinalRound2">
	  
	</div>
	<div class="titleGroup">
		<h3>
			 Demi-finales
		</h3>
	</div>
	<div class="groups round4" name="FinalRound3">
	 
	</div>
		<div class="titleGroup">
		<h3>
			 Finale
		</h3>
	</div>
	<div class="groups round2" name="FinalRound4">
	  
	</div>
</div>
<img style="border:0px;" src="<?php echo ROOT_SITE. $_themePath; ?>/images/resultshomebg.png" USEMAP="#Map"/>
<MAP NAME="Map">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  $firstGroupKey . $additionalParameter; ?>"
		   COORDS="10,20,95,70" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +1) . $additionalParameter; ?>"
		   COORDS="125,20,210,70" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +2) . $additionalParameter; ?>"
		   COORDS="240,20,325,70" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +3) . $additionalParameter; ?>"
		   COORDS="355,20,440,70" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +4) . $additionalParameter; ?>"
		   COORDS="470,20,555,70" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +5) . $additionalParameter; ?>"
		   COORDS="585,20,670,70" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +6) . $additionalParameter; ?>"
		   COORDS="700,20,785,70" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +7) . $additionalParameter; ?>"
		   COORDS="815,20,900,70" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +8) . $additionalParameter; ?>"
		   COORDS="70,120,160,175" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +8) . $additionalParameter; ?>"
		   COORDS="70,220,160,275" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +8) . $additionalParameter; ?>"
		   COORDS="70,320,160,375" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +8) . $additionalParameter; ?>"
		   COORDS="70,410,160,465" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +8) . $additionalParameter; ?>"
		   COORDS="750,120,840,175" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +8) . $additionalParameter; ?>"
		   COORDS="750,220,840,275" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +8) . $additionalParameter; ?>"
		   COORDS="750,320,840,375" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +8) . $additionalParameter; ?>"
		   COORDS="750,410,840,465" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +9) . $additionalParameter; ?>"
		   COORDS="210,175,305,235" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +9) . $additionalParameter; ?>"
		   COORDS="620,175,715,235" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +9) . $additionalParameter; ?>"
		   COORDS="210,360,305,425" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +9) . $additionalParameter; ?>"
		   COORDS="620,360,715,425" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +10) . $additionalParameter; ?>"
		   COORDS="300,270,395,330" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +10) . $additionalParameter; ?>"
		   COORDS="530,270,625,330" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +12) . $additionalParameter; ?>"
		   COORDS="400,130,530,210" style="border: solid 1px #000; ">
	<AREA SHAPE="rect"
		   HREF="<?php echo ROOT_SITE; ?>/index.php?Page=2&GroupKey=<?php echo  ($firstGroupKey +11) . $additionalParameter; ?>"
		   COORDS="420,430,510,490" style="border: solid 1px #000; ">
<!---->
</MAP>


</div>