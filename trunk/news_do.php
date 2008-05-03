<?php
	include('includes/includes.php');
	
	$text = $_POST['texto'];
	$text = addslashes(trim($text));
	
	if (!bd_userIsModerador()) exit();
	
	$query = "insert into news (date, text) values(NOW(), '$text')";
	mysql_query($query);
	
	location("/index.php");
	
	bbdd_close();
	
?>