<?php
	include('includes/includes.php');
	
	$userID = $_SESSION['userID'];
	
	if (!isset($userID))
	{
		echo "Not logged";
		bbdd_close();
		exit();
	}
	$username = bd_getUsername($userID);
	
	$page = $_GET['page'];
	$max = 40;
	
	if (!isset($page)) $page=1;
	$offset = ($page - 1) * $max;
	$query = "select subID,fversion,lang,cuando from downloads where userID=$userID order by cuando DESC limit $offset,$max";
	$dresult = mysql_query($query);
	
	$query = "select count(*) from downloads where userID=$userID";
	$total = mysql_result(mysql_query($query), 0);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo $wikilang_downloads.' - wikisubtitles'; ?>
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
</head>
<body>
<?php include('header.php'); ?>
<div align="center" class="titulo"> 
<?php echo $wikilang_downloads.' - '.$username; ?></div><div class="newsDate" align="center">
<?php echo $total." $wikilang_downloads"; ?>
</div>
<br />
<table align="center" width="70%" border="0">
<?php
	while ($row = mysql_fetch_assoc($dresult))
	{
		echo '<tr>';
			echo '<td class="newsDate">'.obtenerFecha($row['cuando']).'</td>';
			echo '<td><img src="images/download.png" border="0" width="16" height="16" />';
			echo ' <a href="updated/'.$row['lang'].'/'.$row['subID'].'/'.$row['fversion'].'">'.$wikilang_download.'</a>';
			echo '</td>';
			echo '<td>'.bd_getIcon($row['subID']);
			echo '<a href="'.bd_getUrl($row['subID']).'">'.bd_getTitle($row['subID']).'</a> ';
			echo ' '.$wikilang_version.' '.bd_getFVersion($row['subID'], $row['fversion']).', '.bd_getFVersionSize($row['subID'], $row['fversion']).' MBs ';
			echo ' '.$wikilang_language.' '.bd_getLangName($row['lang']).'';
			echo '</td>';
			
			echo '</tr>';
	}
?>
</table>
<?php
	$numpaginas = ceil($total/$max);
	$pagactual = floor($offset/$max);
	echo "$wikilang_pages ";
	for ($c=1; $c<=$numpaginas; $c++)
	{
		$mystart = ($c -1 ) * $max;
		$newpage = floor($mystart/$max);
		$plus = $newpage +1;

		$myfunc = "/mydownloads.php?page=$plus";

		if ($newpage != $pagactual)
			echo "<a href=\"$myfunc\">$c</a> ";
			else 
			echo "$c ";
	}
	
	include('footer.php');
	bbdd_close(); 
?>
	
</body>
</html>