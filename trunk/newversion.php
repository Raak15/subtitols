<?php
	include('includes/includes.php');
	if (!isset($_SESSION['userID'])) exit();
	
	$id = $_POST['id'];
	if (!isset($id)) $id =$_GET['id'];
	$title = bd_getTitle($id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo "$wikilang_upload $wikilang_version $title - wikisubtitles"; ?>
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
<script type="text/javascript">

	function check()
	{
		var lang = $("lang");
		if (lang.value<1)
		{
			alert("You must choose an initial language");
			return false;
		}
		
		return true;
	}

</script>
</head>
<body>
<?php
	include('header.php');
	$url = bd_getUrl($id);
?>
<br />
<div align="center" class="titulo">
<?php echo "$wikilang_upload_new_version <a href=\"$url\">$title</a>"; ?>
</div><br />
<form name="newversion" method="post" enctype="multipart/form-data" action="newversion_do.php" onsubmit="return check();">
<table align="center" width="90%" cellpadding="2" cellspacing="0">
<tr>
<td class="NewsTitle" width="20%"><?php echo $wikilang_title; ?></td>
<td><?php echo "<b>$title</b>"; ?></td>
</tr>
<tr>
<td class="NewsTitle"><img src="images/folder_page.png" width="16" height="16" /> <?php echo $wikilang_version; ?></td>
<td><input type="text" name="version" maxlength="20" size="10" class="inputCool"></td>
</tr>
<tr>
<td class="NewsTitle"><img src="images/package.png" width="16" height="16" /> <?php echo $wikilang_size_mbytes; ?></td>
<td><input type="text" name="size" maxlength="8" size="7" class="inputCool"></td>
</tr>
<td class="NewsTitle"> <img src="images/subtitle.gif" width="16" height="16" /> <?php echo $wikilang_language; ?></td>
<td><select name="lang" id="lang" class="inputCool"> 
      <option value="0"><?php echo $wikilang_select_a_language; ?></option>
<?php
	$query = "select * from languages order by lang_name";
	$result = mysql_query($query);
	
	while ($row=mysql_fetch_assoc($result))
	{
		echo '<option value="'.$row[langID].'">'.$row['lang_name'].'</option>';
	}
?>
      </select></td>
</tr>
<tr>
<td class="NewsTitle"><img src="images/upload.png" width="16" height="16" /> <?php echo $wikilang_srt_file; ?></td>
<td><input type="hidden" name="MAX_FILE_SIZE" value="500000"><input type="file" name="file" /></td>
</tr>
<tr>
    <td class="NewsTitle"><?php echo $wikilang_charset; ?></td>
    <td colspan="2">
    <select name="charset">
    <option value="d" selected>ISO-8859-1 (<?php echo $wikilang_occidental_char; ?>) (<?php echo $wikilang_default; ?>)</option>
    <option value="k">kio8-r (Cyrillic)</option>
    <option value="w">windows-1251 (Cyrillic)</option>
    <option value="i">iso-8859-5 (Cyrillic)</option>
    <option value="a">x-cp866 (Cyrillic)</option>
    <option value="m">x-mac-cyrillic (Cyrillic)</option>
    <option value="u">UTF-8</option>
    </select>
    &nbsp;
    </td>
    
</tr>
<td class="NewsTitle"><img src="images/user_comment.png" width="16" height="16" /> <?php echo $wikilang_comments; ?></td>
<td><textarea name="comment" cols="90" rows="2"></textarea></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" value="<?php echo $wikilang_upload; ?>" class="coolBoton"></td>
</tr>
</table>
<?php
	hidden("id", $id);
?>
</form>
<?php
	include('footer.php');
	bbdd_close();
?>
</body></html>
