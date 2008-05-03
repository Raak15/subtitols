<?php
	include_once('includes/includes.php');
	
	if ((!isset($_SESSION['userID'])) && (!bd_userIsModerador()))
	{
		bbdd_close();
		echo "Forbidden";
		exit();
	}
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	$title = bd_getTitle($id);
	
	if (isset($fversion))
	{
		$query = "delete from fversions where subID=$id and fversion=$fversion";
		mysql_query($query);
		$query = "delete from flangs where subID=$id and fversion=$fversion";
		mysql_query($query);
		$query = "delete from subs where subID=$id and fversion=$fversion";
		mysql_query($query);
		$query = "delete from translating where subID=$id and fversion=$fversion";
		mysql_query($query);

		$query = "select max(fversion) from fversions where subID=$id";
		$result = mysql_query($query);
		
		if (mysql_result($result,0)>$fversion)
		{
			$query = "update fversions set fversion = fversion -1 where subID=$id and fversion>$fversion";
			mysql_query($query);
			$query = "update flangs set fversion = fversion -1 where subID=$id and fversion>$fversion";
			mysql_query($query);
			$query = "update subs set fversion = fversion -1 where subID=$id and fversion>$fversion";
			mysql_query($query);
		}
		
		$url = bd_getUrl($id);
		location("$url");
		
		
		log_insert(LOG_deleteVersion, "version $fversion", $_SESSION['userID'], $id, bd_userIsModerador());
	}
	else 
	{
		$query = "delete from fversions where subID=$id";
		mysql_query($query);
		$query = "delete from flangs where subID=$id";
		mysql_query($query);
		$query = "delete from subs where subID=$id";
		mysql_query($query);
		$query = "delete from translating where subID=$id";
		mysql_query($query);
		$query = "delete from files where subID=$id";
		mysql_query($query);
		$query = "delete from lasttranslated where subID=$id";
		mysql_query($query);
		location("/index.php");
		
		log_insert(LOG_deleteFile, $title, $_SESSION['userID'], $id, bd_userIsModerador());
	}
	
	bbdd_close();
?>