<?php
	include('includes/includes.php');
	
	if (!isset($_SESSION['userID']))
	{
		location('/login.php');
		exit();
	}
	
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	
	$title = bd_getTitle($id);
	$olang = bd_getOriginalLang($id, $fversion);
	$totalseq = bd_getLastSequence($id, $olang, $fversion);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo "$wikilang_link_version $title - wikisubtitles"; ?>
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
</head>
<body>
<?php include('header.php'); ?>
<div align="center" class="titulo">
<img src="images/link.png" border="0" height="16" width="16"><?php echo $wikilang_link_version; ?>&nbsp;
<?php
	$url = bd_getUrl($id); 
	echo " <a href=\"$url\">$title</a>"
?>
</div><br/>
<div align="center">
<b>
<?php echo "$wikilang_need_compound_by $totalseq $wikilang_sequences, $wikilang_only_times"; ?>
</b></div><br/>
<form method="post" action="linkupload_do.php" enctype="multipart/form-data">
<table align="center" width="60%">
<tr>
	<td><img src="images/folder_page.png" width="16" height="16" border="0" /> <?php echo $wikilang_version; ?></td>
	<td><input type="text" size="20" maxlength="20" class="inputCool" name="versionDesc"></td>
</tr>
<tr>
	<td><img src="images/film_save.png" width="16" height="16" border="0" /> <?php echo $wikilang_size_mbytes; ?></td>
	<td><input type="text" size="20" maxlength="8" class="inputCool" name="size"></td>
</tr>
<tr>
	<td><img src="images/page_white_text.png" width="16" height="16" border="0" /> <b><?php echo $wikilang_srt_file; ?></b></td>
	<td><input type="hidden" name="MAX_FILE_SIZE" value="500000"><input type="file" name="file" /></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="<?php echo $wikilang_upload; ?>" class="coolBoton"></td>
</tr>
</table>
<?php
	hidden("id", $id);
	hidden("fversion", $fversion);
?>
</form>
<?php include('footer.php'); 
	bbdd_close();
?>
</body>