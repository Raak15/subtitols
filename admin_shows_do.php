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
Admin shows
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include('header.php'); ?>
<span class="titulo">Admin shows</span><br/>
<?php
	foreach ($_POST as $nombre => $valor) {
		if ($nombre!="Delete")
		{
			$sid = substr($nombre, 1);
			$sname = bd_getShowTitle($sid);
			$query = "delete from shows where showID=$sid";
			mysql_query($query);
			
			echo $sname.' deleted<br />';
		}
	}
?>
<?php include('footer.php'); 
	  bbdd_close();
?>

</body>