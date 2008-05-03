<?php
	include('includes/includes.php');
	$id = $_GET['showID'];
	$_SESSION['quicksearch'] = true;
	$_SESSION['quicksearch_show'] = $id;
	
	echo '<a href="/show/'.$_SESSION['quicksearch_show'].'"><b>'.bd_getShowTitle($_SESSION['quicksearch_show']).'</b></a>';
	
	echo ' 
		<select name="qsiSeason" id="qsiSeason" onchange="seasonChange('.$id.',-1);">';
	if (!isset($_SESSION['quicksearch_season']))
		echo '<option value="0">'.$wikilang_season.'</option>';
	
	$query = "select distinct(season) from files where showID=$id order by season";
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_assoc($result))
	{	
		if ((!isset($_SESSION['quicksearch'])) || ((isset($_SESSION['quicksearch'])) && ($_SESSION['quicksearch_season']!=$row['season'])))
			echo '<option>'.$row['season'].'</option>';
			else 
			echo '<option selected>'.$row['season'].'</option>';
	}
	echo '</select>';
	
	bbdd_close();

?>