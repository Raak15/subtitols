<?php
	include('includes/includes.php');
	if (!isset($_SESSION['userID']))
	{
		header("Location: /login.php");
		exit();
	}
	
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	
	$titulo = bd_getTitle($id);
	
	$query = "select lang_id from flangs where subID=$id and fversion=$fversion";
	$rresult = mysql_query($query);
	$numresults = mysql_affected_rows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo "$wikilang_new_translation -  $title - wikisubtitles"; ?>
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
<script type="text/javascript">
	function langto()
	{
		var from = $("langfrom").value;
		
		if (from>0)
		{
			$("langto").innerHTML = '<img src="<?php echo $SCRIPT_PATH; 
?>images/loader.gif">';
<?php echo "var dir = '".$SCRIPT_PATH."ajax_getLangs.php?id=$id&fversion=$fversion'; "; ?>
			new Ajax(dir, {
				method: 'get',
				update: $("langto")
			}).request();
		}
	}
	
<?php
	if ($numresults<2)
	{
		echo "window.addEvent('domready', function(){ langto(); });";
	}
?>

</script>
</head>
<body>
<?php include('header.php'); ?>

<form action="/translate.php" method="POST">
<?php
	hidden("id", $id);
	hidden("fversion", $fversion);
	
	
?>
<table align="center" width="40%" border="0">
<tr>
	<td colspan="2" class="NewsTitle" align="center">
<?php
	echo "$wikilang_translate <i>$titulo</i>"; 
?>
	</td>
</tr>
<tr>
	<td><?php echo $wikilang_language_from; ?></td>
	<td><select name="langfrom" id="langfrom" class="inputCool" onchange="langto();">
		<option value="0"><?php echo $wikilang_select_a_language; ?></option>
<?php

	while ($row=mysql_fetch_assoc($rresult))
	{
		$lid = $row['lang_id'];
		$lname = bd_getLangName($lid);
		if ($numresults>1)
			echo "<option value=\"$lid\">$lname</option>";
			else 
			echo "<option value=\"$lid\" selected>$lname</option>";
	}
?>
</select>
	</td>
</tr>
<tr>
	<td><?php echo $wikilang_language_to; ?></td>
	<td><span id="langto">&nbsp;</span></td>
</tr>
<tr>
<td colspan="2" align="center"><input class="coolBoton" type="submit" name="submit" value="<?php echo $wikilang_translate; ?>"></td>
</tr>

</table>
</form><br /><br />
<?php include('footer.php'); 
	bbdd_close();
?>
</body>
</html>
