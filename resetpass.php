<?php
	include('includes/includes.php');
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo $wikilang_password_reset;?> - wikisubtitles
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
</head>

<body>
<?php include ('header.php'); ?>

<div align="center" class="titulo"><img src="images/lock.png" /> <?php echo $wikilang_password_reset; ?></div>
<form action="/resetpass_do.php" method="post">
<table align="center" width="50%" border="0">
 <tr>
<td colspan="1" class="NewsTitle"><img src="images/email.png" /> <?php echo $wikilang_email; ?> </td>
<td>
<input type="text" name="mail" size="20" class="inputCool" maxlength="100" />
</td>
</tr>
<tr><td colspan="2" align="center"><input type="submit" value="<?php echo $wikilang_password_reset_action; ?>" name="submit" class="coolBoton"/></td></tr>
</table>
<?php
	include('footer.php');
	bbdd_close();
?>
</form>
</body>
</html>