<?php
	include_once('includes/includes.php');

	if (!isset($_SESSION['userID']) && (!bd_userIsModerador())) 
	{
		echo "Forbidden";
		bbdd_close();
		exit();
	}
	
	
	$id = $_POST['id'];
	$fversion = $_POST['fversion'];
	
	if (isset($fversion))
		$modifications = bd_countSubNoOriginal($id,$fversion);
		else
		$modifications = bd_countSubNoOriginal($id,0);
	
	$query = "select title,author from files where subID=$id";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$author = $row['author'];
	if (($author!=$_SESSION['userID']) && (!bd_userIsModerador()))
	{
		echo "Forbidden";
		bbdd_close();
		exit();
	}
	
	if (isset($fversion))
		$query = "delete from subs where subID=$id and fversion=$fversion";
		else
		$query = "delete from subs where subID=$id";
	mysql_query($query);
	
	if (!isset($fversion))
	{
		$query = "delete from files where subID=$id";
		mysql_query($query);
		$query = "delete from comments where subID=$id";
		mysql_query($query);
		$query = "delete from translating where subID=$id";
		mysql_query($query);
		$query = "delete from fversions where subID=$id";
		mysql_query($query);
		$query = "delete from flangs where subID=$id";
		mysql_query($query);
		header("Location: /index.php");
	}
	else 
	{
		$query = "delete from translating where subID=$id and fversion=$fversion";
		mysql_query($query);
		$query = "delete from fversions where subID=$id and fversion=$fversion";
		mysql_query($query);
		$query = "delete from flangs where subID=$id and fversion=$fversion";
		mysql_query($query);
		$query = "update subs  set fversion=fversion-1 where subID=$id and fversion>$fversion";
		mysql_query($query);
		$query = "update flangs  set fversion=fversion-1 where subID=$id and fversion>$fversion";
		mysql_query($query);
		$query = "update fversions  set fversion=fversion-1 where subID=$id and fversion>$fversion";
		mysql_query($query);
		$query = "update translating  set fversion=fversion-1 where subID=$id and fversion>$fversion";
		mysql_query($query);
		$query = "update comments  set fversion=fversion-1 where subID=$id and fversion>$fversion";
		mysql_query($query);
		
		$url = bd_getUrl($id);
		location($url);
		
	}
	

	bbdd_close();
	
	
?>