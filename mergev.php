<?php
	include_once('includes/includes.php');
	
	if (!bd_userIsModerador())
	{
		bbdd_close();
		exit();
	}
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	$prevv = $fversion - 1;
	
	
	

	$query = "update subs set original=0,fversion=$prevv where subID=$id and fversion=$fversion";
	mysql_query($query);
	$query = "update flangs set merged=1,original=0,fversion=$prevv where subID=$id and fversion=$fversion";
	mysql_query($query);
	
	$query = "delete from fversions where subID=$id and fversion=$fversion";
	mysql_query($query);
	$query = "delete from flangs where subID = $id and fversion=$fversion";
	mysql_query($query);
	
	$query = "select max(fversion) from fversions where subID=$id";
	$result =  mysql_query($query);
	
	if (mysql_result($result, 0)>$fversion)
	{
		$query = "update fversions set fversion=fversion -1 where subID=$id and fversion>$fversion";
		mysql_query($query);
		$query = "update flangs set fversion=fversion -1 where subID=$id and fversion>$fversion";
		mysql_query($query);
	}
	
	log_insert(LOG_merge, "Merged from version $fversion to $prevv", $_SESSION['userID'], $id, bd_userIsModerador());
	$url = bd_getUrl($id);
	location($url);
	bbdd_close();

?>