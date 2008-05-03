<?php
	include_once('includes/includes.php');
	if ($_SESSION['userID']!=1) exit();


	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tranlating clear - wikisubtitles</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" media="screen" />
</head>

<body>
  <?php include('header.php');?>
  
<table width="90%" border="0" align="center">
<?php

	$query = "select distinct (subID),lang_id,fversion from translating";
	$result = mysql_query($query);
	
	while ($row=mysql_fetch_assoc($result))
	{
		$subID = $row['subID'];
		$lang = $row['lang_id'];
		$lang_name = bd_getLangName($lang);
		$fversion = $row['fversion'];
		$state = bd_getLangState($subID, $lang, $fversion);
		$title = bd_getTitle($subID);
		
		if (($state=="0.00% $wikilang_completed") || ($state == "$wikilang_completed"))
		{
			$query = "delete from translating where subID=$subID and fversion=$fversion and lang_id=$lang";
			mysql_query($query);
		}
		
		if ($state=="0.00% completed")
		{
			$query = "delete from flangs where subID=$subID and fversion=$fversion and lang_id=$lang";
			mysql_query($query);
		}
		
		if ($state=="% completed")
		{
			$query = "delete from translating where subID=$subID and fversion=$fversion and lang_id=$lang";
			mysql_query($query);
		}
		
		echo '<tr>';
		$url = bd_getUrl($subID);
		echo "<td>$subID</td><td><a href=\"$url\">$title</a></td><td>$lang_name</td><td>$state</td>";
		echo '</tr>';
	}
?>
</table>  
<?php 
 	include('footer.php');
 	bbdd_close();
?>
</body>
</html>
