<?php
	include('includes/includes.php');
	
	$query = "select userID,username,navegate from users where last > (NOW() - INTERVAL 5 MINUTE ) and hide=0";
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $wikilang_connected_users.' - wikisubtitles'; ?></title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" media="screen" />
</head>

<body>
  <?php include('header.php');?>
  
<table align="center" width="80%" border="0">
<?php
	$query = "select userID,username,navegate from users where last > (NOW() - INTERVAL 5 MINUTE )";
	$result = mysql_query($query);
	
	while ($row=mysql_fetch_assoc($result))
	{
		$myuserID = $row['userID'];
		$myusername = $row['username'];
		$navegate = $row['navegate'];
		
		echo '<tr>';
		echo "<td><a href=\"/user/$myuserID\">$myusername</a></td>";
		echo "<td><a href=\"$navegate\">$navegate</a></td>";
		echo '</tr>';
		 
	}
?>

</table>
  
  <?php include('footer.php');?>
  
</body>
</html>
  
  