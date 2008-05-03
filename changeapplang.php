<?php
	include('includes/includes.php');
	$applang = $_GET['applang'];
	$_SESSION['applang'] = $applang;
	if (isset($_SESSION['userID']))
	{
		$query = "update users set applang='$applang' where userID=".$_SESSION['userID'];
		mysql_query($query);
	}
	
	bbdd_close();	
?>
<script type="text/javascript">
	history.go(-1);
</script>