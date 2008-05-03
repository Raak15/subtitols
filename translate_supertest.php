<?php
	$subID = $_GET['id'];
	$fversion = $_GET['fversion'];
	$lang = $_GET['lang'];
	
	include('includes/includes.php');
	include('translate_fns.php');
	tn_duplicates($subID,$fversion, $lang);
	bbdd_close();
	
?>