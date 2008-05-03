<?php
	include('includes/includes.php');
	
	if (!logged()) exit();
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	$lang = $_GET['lang'];
	$seq = $_GET['seq'];
	
	$query = "select locked from subs where subID=$id and fversion=$fversion and lang_id=$lang and edited_seq=$seq and last=1";
	$result = mysql_query($query);
	$locked = mysql_result($result, 0);
	
	$query = "update subs set locked = NOT(locked) where subID=$id and fversion=$fversion and lang_id=$lang and edited_seq=$seq and last=1";
	mysql_query($query);
	
	if ($locked)
		echo '<a href="javascript:lock(false, \''.$seq.'\');" ><img src='.$SCRIPT_PATH.'images/lock_open.png border="0" /></a>';
		else 
		echo '<a href="javascript:lock(true, \''.$seq.'\');" ><img src='.$SCRIPT_PATH.'images/lock.png border="0" /></a>';
?>