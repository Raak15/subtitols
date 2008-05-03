<?php

	include('includes/includes.php');
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	$seq = $_GET['seq'];
	$langto = $_GET['langto'];
	
	$query = "update translating set tokened=0 where subID=$id and fversion=$fversion and lang_id=$langto and sequence=$seq";
	mysql_query($query);
	
	bbdd_close();
	echo $wikilang_released;
?>