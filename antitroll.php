<?php
	include('includes/includes.php');
	session();
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	$lang = $_GET['lang'];
	
	$title = bd_getTitle($id);
	$url = bd_getUrl($id);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo "$wikilang_anti_troll $title - wikisubtitles"; ?>
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
</head>
<body>
<?php include ('header.php'); ?>
<div align="center" class="titulo"><img src="images/antitroll.png" border="0" align="absmiddle"><?php echo $wikilang_anti_troll; ?>
<?php echo '<a href="'.$url.'">'.$title.'</a>'; ?>
</div>
<form action="antitroll_do.php" method="post">
<table align="center" width="80%" border="0">
<tr class="SectionTitle">
	<td>&nbsp;</td>
	<td><?php echo $wikilang_user; ?></td>
	<td><?php echo $wikilang_total_lines; ?></td>
	<td><?php echo $wikilang_total_lines_current; ?></td>
	<td><?php echo $wikilang_total_lines_version0; ?></td>
</tr>
<?php
	$query = "select distinct(authorID) from subs where subID=$id and fversion=$fversion and lang_id=$lang";
	$result = mysql_query($query);
	
	$contador = 0;
	
	while ($row=mysql_fetch_assoc($result))
	{
		$authorID = $row['authorID'];
		$authorname = bd_getUsername($authorID);
		$totallines = bd_countModificationsByUser($id, $lang, $authorID, $fversion);
		$totalOriginal = bd_countOriginalLinesByUserSub($id, $lang, $fversion, $authorID);
		$totalLast = bd_countLastLinesByUserSub($id, $lang, $fversion, $authorID);
		
		echo '<tr>';
			echo '<td><input type="radio" name="author" value="'.$authorID.'" /></td>';
			echo '<td><a href="/user/'.$authorID.'">'.$authorname.'</a></td>';
			echo '<td>'.$totallines.'</td>';
			echo '<td><b>'.$totalLast.'</b></td>';
			echo '<td>'.$totalOriginal.'</td>';
		echo '<tr>';
	}
	
?>
<tr>
	<td colspan="6" align="center"><input type="checkbox" name="notoriginal" value="true" /> <?php echo $wikilang_dont_delete_original; ?> <input type="submit" value="<?php echo $wikilang_rollback_delete; ?>" class="coolBoton" /></td>

</tr>
</table>
<?php
	hidden("id", $id);
	hidden("fversion", $fversion);
	hidden("lang", $lang);
?>
</form>
<?php include('footer.php'); ?>
</body>
</html>
