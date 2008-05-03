<?php
	include('includes/includes.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload a new subtitle</title>
<link href="css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
</head>
<body>
<?php include('header.php'); ?>
<br />
<table border="0" align="center" width="90%">
<?php
	$query = "select entryID,subID from flangs where state>=100 and original=0 and merged=0 and subID != ANY (select subID from lasttranslated)";
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_assoc($result))
	{
		$id = $row['subID'];
		$entry = $row['entryID'];
		
		$query = "select count(*) from lasttranslated where subID=$id";
		$tresult = mysql_query($query);
		$count = mysql_result($tresult, 0);
		
		if ($count<1)
		{
			
		$title = bd_getTitle($id);
		$icon = bd_getIcon($id);
		$link = bd_getUrl($id);
		echo '<tr>';
			echo "<td>$icon</td>";
			echo "<td>$id</td>";
			echo "<td><a href=\"$link\">$title</a></td>";
		echo '</tr>';		
		}
	}
?>
</table>
<?php include ('footer.php'); 
	  bbdd_close();
?>
</body>
