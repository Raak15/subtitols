<?php
	include('includes/includes.php');
	
	if (!bd_userIsModerador())
	{
		bbdd_close();
		echo "You are not a moderator";
		exit();
	}
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo $wikilang_delete_show.' - wikisubtitles'; ?>
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include('header.php'); ?>
<span class="titulo"><?php echo $wikilang_delete_show; ?></span>
<form action="admin_shows_do.php" method="post">
<?php
	$query = "select * from shows order by title";
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_assoc($result))
	{
		$sID = $row['showID'];
		$titulo = stripslashes($row['title']);
		$seasons = bd_countEpisodesShow($sID);
		
		echo "&nbsp; <input type=\"checkbox\" name=\"s$sID\" value=\"1\" /><img src=\"".$SCRIPT_PATH."images/package.png\" />&nbsp;";
		if ($seasons==0)
		echo "<b>$titulo ($seasons $wikilang_episodes)</b><br />";
		else 
		echo "$titulo ($seasons $wikilang_episodes)<br />";
	}
?>
<input type="submit" name="Delete" value="<?php echo $wikilang_delete_show; ?>"/>
</form>
<?php include('footer.php'); 
	  bbdd_close();
?>

</body>
</html>