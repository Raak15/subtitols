<?php
	include('includes/includes.php');
	$id = $_GET['showID'];
	$season = $_GET['season'];
	$_SESSION['quicksearch_season'] = $season;
	
	echo ' '.$wikilang_episode.' 
		<select name="qsiEp" id="qsiEp" onchange="changeEp();">';
		
	if (!isset($_SESSION['quicksearch_epID']))
		echo '<option value="0">'.$wikilang_select_episode.'</option>';
	
	$query = "select title,season_number from files where showID=$id and season=$season order by season_number";
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_assoc($result))
	{
		$valor = $id.'-'.$season.'x'.$row['season_number'];
		$singletitle = stripslashes($row['title']);
		$singletitle = split('-', $singletitle);
		$singletitle = $row['season_number'].'. '.$singletitle[count($singletitle) -1];		
		if ((!isset($_SESSION['quicksearch'])) || ((isset($_SESSION['quicksearch'])) && ($_SESSION['quicksearch_epID']!=$valor)))
			echo '<option value="'.$valor.'">'.$singletitle.'</option>';
			else 
			echo '<option value="'.$valor.'" selected>'.$singletitle.'</option>';		
	}
	echo '</select>';	
	bbdd_close();

?>