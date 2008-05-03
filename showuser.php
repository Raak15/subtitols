<?php
	include('includes/includes.php');
	
	$vuser = $_GET['userid'];
	$vname = bd_getUsername($vuser);
	
	$query = "select website,joined from users where userID=$vuser";
	$result = mysql_query($query);
	
	$row = mysql_fetch_assoc($result);
	$website = stripslashes($row['website']);
	$joined = $row['joined'];
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo "$wikilang_user $vname - wikisubtitles.net"; ?>
</title>


</head>

<body>
<?php 
	include('header.php');
?>
<table width="90%" border="0">
<tr><td class="titulo" colspan="4">
&nbsp;
<?php echo $vname; ?>
</td></tr>
<tr>
	<td ><img src="/images/invisible.gif" width="50" height="5"/></td>
	<td class="NewsTitle"><?php echo $wikilang_account_creation; ?></td>
	<td><?php echo $joined; ?></td>
	<td>&nbsp</td>
</tr>
<tr>
	<td ><img src="/images/invisible.gif" width="50" height="5"/></td>
	<td class="NewsTitle"><?php echo $wikilang_website; ?></td>
	<td><?php echo "<a href=\"$website\">$website</a>"; ?></td>
	<td>&nbsp</td>
</tr>
<?php
	$query = "select distinct(subID) from files where author=$vuser";
	$result = mysql_query($query);
	$total_created = mysql_affected_rows();
?>
<tr>
	<td ><img src="/images/invisible.gif" width="50" height="5"/></td>
	<td class="NewsTitle"><?php echo $wikilang_files_create; ?></td>
	<td><?php echo "$total_created"; ?></td>
	<td>&nbsp</td>
</tr>
<?php
	while ($row=mysql_fetch_assoc($result))
	{
		$myID = $row['subID'];
		$titulo = bd_getTitle($myID);
		$url = bd_getUrl($myID);
		echo '<tr>
		<td ><img src="'.$SCRIPT_PATH.'images/invisible.gif" width="50" height="5"/></td>
		<td>&nbsp</td>
		<td><img src="'.$SCRIPT_PATH.'images/package.png" width="16" height="16"/>&nbsp;<a href="'.$url.'">'.$titulo.'</a></td>
		<td>&nbsp</td>
	</tr>';
	}
	
	$query = "select distinct(subID) from subs where authorID=$vuser and version>0";
	$result = mysql_query($query);
	$total_created = mysql_affected_rows();
?>
<tr>
	<td ><img src="/images/invisible.gif" width="50" height="5"/></td>
	<td class="NewsTitle"><?php echo $wikilang_files_edited; ?></td>
	<td><?php echo "$total_created"; ?></td>
	<td>&nbsp</td>
</tr>
<?php
	while ($row=mysql_fetch_assoc($result))
	{
		$myID = $row['subID'];
		$titulo = bd_getTitle($myID);
		$url = bd_getUrl($myID);
		echo '<tr>
		<td ><img src="'.$SCRIPT_PATH.'images/invisible.gif" width="50" height="5"/></td>
		<td>&nbsp</td>
		<td><img src="'.$SCRIPT_PATH.'images/package.png" width="16" height="16"/>&nbsp;<a href="'.$url.'">'.$titulo.'</a></td>
		<td>&nbsp</td>
	</tr>';
	}

	
?>

</table>

<?php
	include('footer.php');
	bbdd_close();
?>
</body>
</html>
