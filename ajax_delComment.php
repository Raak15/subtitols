<?php
	include('includes/includes.php');

	$cid = $_GET['cid'];
	
	$query = "select * from comments where commentid=$cid";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	
	if (bd_userIsModerador() || ($row['userID']==$_SESSION['userID']))
	{
		$query = "update comments set deleted=1 where commentid=$cid";
		mysql_query($query);
	}
	
	bbdd_close();
	echo "OK";
?>