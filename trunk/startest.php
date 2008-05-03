<?php
	include ('includes/includes.php');
	include ('translate_fns.php');
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	$langto = $_GET['lang'];
	$langfrom = bd_getOriginalLang($id, $fversion);
	
	if ($langfrom!=$langto)
		tn_check($id, $fversion, $langfrom, $langto);
	
	
	$url = bd_getUrl($id);
	location($url);
	bbdd_close();
	
	
?>