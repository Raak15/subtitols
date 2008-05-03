<?php
	include('includes/includes.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo "$wikilang_top_uploaders - Wikisubtitles"; ?>
</title>
<link href="css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
</head>
<body>
<?php include('header.php'); ?>
<div align="center" class="titulo"><?php echo $wikilang_top_uploaders; ?></div>
<table align="center"  border="0" cellpadding="0" cellspacing="0">
<?php
	$query = "select userID,uploads from users order by uploads DESC limit 50";
	$result = mysql_query($query);
	
	while($row=mysql_fetch_assoc($result))
	{
		$uid = $row['userID'];
		$uname = bd_getUsername($uid);
		$uploads = $row['uploads'];
		
		echo '<tr>';
		echo '<td class="NewsTitle"><a href="/user/'.$uid.'">'.$uname.'</a></td>';
		echo '<td>'.$uploads.' '.$wikilang_files.'</td>';
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