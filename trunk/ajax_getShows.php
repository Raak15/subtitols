<?php
	include('includes/includes.php');
	
	unset($_SESSION['quicksearch']);
	unset($_SESSION['quicksearch_show']);
	unset($_SESSION['quicksearch_season']);
	unset($_SESSION['quicksearch_epID']);
	
	echo '<b>'.$wikilang_quick_search.'</b> 
		<select name="qsShow" id="qsShow" onchange="showChange(0);">
		<option value="0">'.$wikilang_select_a_show.'</option>';
	
	$query = "select * from shows order by title";
	$result = mysql_query($query);
	while ($row = mysql_fetch_assoc($result))
	{
		echo '<option value="'.$row['showID'].'" >'.stripslashes($row['title']).'</option>';
	}
	
	$query = "select distinct(season) from files where showID=$id order by season";
	$result = mysql_query($query);
	echo '</select>';
	bbdd_close();

?>