<?php
	include('includes/includes.php');
	$linkID = $_GET['linkid'];
	$subID = bd_link_getSubID($linkID);
	
	if (!bd_userIsModerador())
	{
		bbdd_close();
		location(bd_getUrl($subID));
		exit();
	}
	
	$query = "delete from links_data where linkID=$linkID";
	mysql_query($query);
	$query = "delete from links where linkID=$linkID";
	mysql_query($query);
	
	log_insert(LOG_deleteLink, bd_link_getFversion($linkID), $_SESSION['userID'], $subID, bd_userIsModerador());
	
	
	location(bd_getUrl($subID));
	bbdd_close();

?>