<?php
//AddScriptReference("ranking.teams.competition");
//WriteScripts();

?>
<div id="mainCol" >
<!-- <img src="<?php echo ROOT_SITE.$_themePath; ?>/images/resultshomebg.svg" />-->
  <?php
$homepage = file_get_contents(ROOT_SITE.$_themePath.'/images/resultshomebg.svg');
echo $homepage;
?>
</div>