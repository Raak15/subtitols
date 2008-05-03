<?php
	include_once('includes/includes.php');
	
	$search = $_GET['search'];
	
	$query = "select showID,title from shows where title like '%$search%' limit 1";
	$result = mysql_query($query);
	$numrows = mysql_affected_rows();
	
	$isshow = $numrows > 0;
	if ($isshow)
	{
		$row = mysql_fetch_assoc($result);
		$showID = $row['showID'];
		$showName = stripslashes($row['title']);
	}
	
	$mysearch = addslashes($search);
	$query = "select subID,title,is_episode from files where title like '%$mysearch%' order by title";
	$sresult = mysql_query($query);
	$numresults = mysql_affected_rows();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php	
echo "$wikilang_search \"$search\" -wikisubtitles";
?>
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
</head>
<body>
<?php
	include('header.php');
?>
<br /><br />
<form action="/search.php" method="get">
<div align="center">
<?php echo '<input name="search" type="text" id="search" size="50" value="'.$search.'" class="inputCool"/>'; ?>
&nbsp;
 <input name="Submit" type="submit" class="coolBoton" value="<?php echo $wikilang_search; ?>" /></form><br />
<?php echo "<b>$numresults $wikilang_results_found</b>"; ?> </div><br />
<?php
	if ($isshow)
	{
?>
&nbsp;&nbsp;<img src="images/package.png" /> <span class="titulo">
<?php echo $wikilang_are_looking.' <a href="/show/'.$showID.'" >'.$wikilang_show.' <i>'.$showName; ?>
</i></a>&nbsp;?</span><br /><br />
<?php
	}
?>
<table align="center" width="80%" border="0">
<?php
	while ($row=mysql_fetch_assoc($sresult))
	{
		$subID = $row['subID'];
		$title = stripslashes($row['title']);
		$is_episode = $row['is_episode'];
		
		echo '<tr>';
		if ($is_episode)
			echo '<td><img src="images/television.png" /></td>';
			else 
			echo '<td><img src="images/film.png" /></td>';
		$url = bd_getUrl($subID);
		echo '<td><a href="'.$url.'" >'.$title.'</a></td>';
		echo '</tr>';
		echo '<tr>';
	}
?>
</table>

<?php
	include('footer.php');
	bbdd_close();
?>
</body></html>