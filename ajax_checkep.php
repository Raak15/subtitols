<?php
	include('includes/includes.php');
	
	$show = $_GET ['show'];
	$epnumber = $_GET['epnumber'];
	$season = $_GET['season'];
	
	$query = "select subID from files where is_episode=1 and showID=$show and season=$season and season_number=$epnumber";
	$result = mysql_query($query);
	
	if (mysql_affected_rows()>0)
	{
		$sID = mysql_result($result, 0);
		$showName = bd_getShowTitle($show);
		$title = bd_getTitle($sID);
		
		$url = bd_getUrl($sID);
		echo '<a href="'.$url.'">'.$title.'</a> ';
		echo "$wikilang_ep_already_exists";
	}
	else 
		echo ''.$wikilang_ep_not_exists.'';
	
	bbdd_close();
?>