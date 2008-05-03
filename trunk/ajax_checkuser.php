<?php
	$name = $_GET['name'];
	
	include('includes/includes.php');
	
	$query = "select count(*) from users where username='$name'";
	$result = mysql_query($query);
	$count = mysql_result($result, 0);
	
	echo "&nbsp;&nbsp;";
	
	if (strlen($name)<4)
	{
		echo '<font color="red">'.$wikilang_not_enough_users.'</font>';
	}
	elseif($count<1)
	{
		echo '<font color="green">'.$wikilang_user_available.'</font>';
	}
	else 
		echo '<font color="red">'.$wikilang_user_taken.'</font>';
	bbdd_close();

?>