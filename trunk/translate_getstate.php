<?php
	include('includes/includes.php');
	
	$id = $_GET['id'];
	$langto = $_GET['langto'];
	$fversion = $_GET['fversion'];
	
	$state = bd_getLangState($id, $langto, $fversion);
	echo $state;
	bbdd_close();
?>