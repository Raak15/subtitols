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
	$lang = $_GET['lang'];
	
	$query = "delete from flangs where subID=$id and fversion=$fversion and lang_id=$lang";
	mysql_query($query);
	$query = "delete from subs where subID=$id and fversion=$fversion and lang_id=$lang";
	mysql_query($query);
	
	$query = "select count(*) from flangs where subID=$id and fversion=$fversion";
	$result = mysql_query($query);
	if (mysql_result($result,0)<1)
	{
		$query = "delete from fversions where subID=$id and fversion=$fversion";
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
		
	}
	log_insert(LOG_deleteLanguage, bd_getLangName($lang), $_SESSION['userID'], $id, bd_userIsModerador());
	
	
	
	$url = bd_getUrl($id);
	location($url);
	bbdd_close();
?>