<?php
	include('includes/includes.php');
	$ep = $_GET['ep'];
	
	$div1 = split('-',$ep);
	$showID = $div1[0];
	$div2 = split('x', $div1[1]);
	$season = $div2[0];
	$epnumber = $div2[1];
	
	$_SESSION['quicksearch_epID'] = $ep;
	
	$query = "select subID from files where is_episode=1 and showID=$showID and season=$season and season_number=$epnumber limit 1";
	$result = mysql_query($query);
	
	if (!mysql_affected_rows())
	{
		location('/index.php');
		exit();
	}
	
	$url = bd_getUrl(mysql_result($result, 0));
	location($url);
	bbdd_close();
?>