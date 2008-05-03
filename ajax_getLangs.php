<?php
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	$lang = $_GET['lang'];
	
	include('includes/includes.php');
	
	$query = "select lang_id from flangs where subID=$id and fversion=$fversion";
	$result = mysql_query($query);
	$orquery = "";
	while ($row=mysql_fetch_assoc($result))
	{
		$orquery .= "and langID!=".$row[lang_id]." ";
	}
	
	$orquery = substr($orquery, 4);
	$orquery = trim($orquery);
	
	
	$query = "select * from languages where ".$orquery;
	$result = mysql_query($query);
	
	echo "<select name=\"langto\" class=\"inputCool\">";
	while ($row=mysql_fetch_assoc($result))
	{
		$lid = $row['langID'];
		$lname = $row['lang_name'];
		echo "<option value=\"$lid\">$lname</option>";
	}
	echo "</select>";
	
	bbdd_close();
	
?>
	