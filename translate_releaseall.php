<?php
	include('includes/includes.php');

	$lang = $_GET['lang'];
	$fversion = $_GET['fversion'];
	$id = $_GET['id'];
	
	$query = "update translating set tokened=0 where subID=$id and fversion=$fversion and lang_id=$lang";
	mysql_query($query);
	echo $wikilang_released;
	
	bbdd_close();
?>