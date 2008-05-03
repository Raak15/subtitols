<?php
	include_once('includes/includes.php');
	
	if (!isset($_SESSION['userID']))
	{
		echo "Not logged";
		exit();
	}
	
	$me = $_SESSION['userID'];
	
	$msgid = $_GET['id'];
	
	$query = "select * from msgs where msgID=$msgid";
	$result = mysql_query($query);
	$nummsgs = mysql_affected_rows();
	if ($nummsgs<1)
	{
		showError("Message doesn't exists");
		exit();
	}
	
	$row = mysql_fetch_assoc($result);
	
	$from = $row['from'];
	$to = $row['to'];
	$date = $row['date'];
	$text = stripslashes($row['text']);
	$subject = utf8_decode($row['subject']);
	$opened = $row['opened'] == '1';
	
	if (($me!=$to) && ($me!=$from))
	{
		echo "¿Creiste que iba a funcionar, listillo?";
		exit();
	}
	
	$toName = bd_getUsername($to);
	$fromName = bd_getUsername($from);
	
	if ((!$opened) && ($to == $me))
	{
		$query = "update msgs set opened=1 where msgID=$msgid";
		mysql_query($query);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>wikisubtitles</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
</head>
<body>
<?php
	include('header.php');
?>
<form action="/mailactions.php" method="post">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="4" align="center"><img src="/images/email_go.png" /> <a href="/msginbox.php">Inbox</a> <img src="images/email_open_image.png" /> <a href="/msgoutbox.php">Outbox</a> <img src="images/email_edit.png" /> <a href="/msgcreate.php">Compose</a></td>
</tr>
  <tr>
    <td class="NewsTitle"><?php echo $wikilang_msg_from; ?></td><td>&nbsp;</td
    <td class="titulo">
<?php echo $fromName; ?>
    </td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td class="NewsTitle"><?php echo $wikilang_msg_to; ?></td><td>&nbsp;</td
    <td>
<?php echo $toName; ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="NewsTitle"><img src="/images/clock.png" /> <?php echo $wikilang_date_date; ?></td><td>&nbsp;</td
    <td>
<?php echo $date; ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="NewsTitle"><img src="/images/email_open.png" /> <?php echo $wikilang_msg_subject; ?></td><td>&nbsp;</td
    <td class="titulo">
<?php echo $subject; ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td><td>&nbsp;</td
    <td colspan="2"><div align="right">
      <input name="reply" type="submit" class="coolBoton" value="<?php echo $wikilang_msg_reply; ?>" />
      <input name="forward" type="submit" class="coolBoton" value="<?php echo $wikilang_msg_forward; ?>" />
      <input name="delete" type="submit" class="coolBoton" value="<?php echo $wikilang_delete; ?>" />
<?php
	echo '<input type="hidden" name="msgid" value="'.$msgid.'"/>';
?>
    </div></td>
    </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td background="images/linea.jpg" />&nbsp;</td
    <td colspan="2">
<?php echo $text; ?>
    </td>
    </tr>
</table>
</form>


<?php
	include('footer.php');
	bbdd_close();
?>
</body></html>