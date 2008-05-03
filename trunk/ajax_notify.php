<?php
	include ('includes/includes.php');
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	$lang = $_GET['lang'];
	
	$query = "select count(*) from moderations where subID=$id and fversion=$fversion and lang=$lang and active=1";
	$result = mysql_query($query);
	
	if (mysql_result($result, 0)>0)
	{
		$query = "update moderations set counter=counter+1 where subID=$id and fversion=$fversion and lang=$lang and active=1";
		mysql_query($query);
	}
	else 
	{
		$query = "insert into moderations(subID,fversion,lang) values($id,$fversion,$lang)";
		mysql_query($query);
	}
	
	bbdd_close();
	
	echo "done.";
	
?>