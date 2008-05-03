<?php
	include('includes/includes.php');
	if (!isset($_SESSION['userID']))
	{
		header("Location: /login.php");
		exit();
	}
	
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	$lang = $_GET['lang'];
	
	$titulo = bd_getTitle($id);
	$query = "select * from languages where langID=ANY (select lang_id from flangs where subID=$id and fversion=$fversion and lang_id!=$lang);";
	$rresult = mysql_query($query);
	$numresults = mysql_affected_rows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo "$wikilang_join_translation $title - wikisubtitles"; ?>
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
<script type="text/javascript">
	function enviar()
	{
		$("myform").submit();
	}
<?php
	if ($numresults<2)
		echo "window.addEvent('domready', function(){ enviar(); });";
?>
</script>
</head>

<body>
<?php include('header.php'); ?>
<div align="center" class="titulo"><?php echo $wikilang_join_translation.' - '; ?>
<?php echo $titulo; ?>
</div>
<div align="center">
<form name="join" method="POST" action="/translate.php" id="myform">
<?php echo $wikilang_language_from; ?>
&nbsp;<select name="langfrom" class="inputCool">
<?php
	
	while ($row = mysql_fetch_assoc($rresult))
	{
		$lid = $row['langID'];
		$lname = $row['lang_name'];
		echo "<option value=\"$lid\">$lname</option>";
	}
?>
</select><br />
<input type="submit" name="foo" value="<?php echo $wikilang_join; ?>" class="coolBoton" />
<?php
	hidden("id", $id);
	hidden("fversion", $fversion);	
	hidden("langto", $lang);
?>
</form>
</div>
<br /><br /><br /><br />
<?php include('footer.php'); 
	bbdd_close();
?>
</body>
</html>