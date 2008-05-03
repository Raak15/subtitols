<?php
	include('includes/includes.php');
	include("includes/fckeditor/fckeditor.php");
	
	$to = $_GET['to'];
	if (isset($to)) $toName = bd_getUsername($to);
	
	$action = $_GET['action'];
	
	if (isset($action))
	{
		$msgid = $_GET['msgid'];
		
		if ($action=='reply')
		{
			$query = "select `from`,text,subject from msgs where msgID=$msgid";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			$to = $row['from'];
			$toName = bd_getUsername($to);
			$text = '<br /><br /><br /><br /><hr /><font color="#0000ff">'.stripslashes($row['text']).'</font>';
			$subject = "Re: ".stripslashes(utf8_decode($row['subject']));
		}
		
		if ($action=='forward')
		{
			$query = "select text,subject from msgs where msgID=$msgid";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			$text = '<br /><br /><br /><br /><hr />'.stripslashes($row['text']);
			$subject = "Fwd: ".stripslashes(utf8_decode($row['subject']));
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>wikisubtitles</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
<script type="text/javascript">
	function checkname()
	{
		var username = $("to").value;
		$("cuser").innerHTML = '<img src="/images/loader.gif" />';
		if (username.length>3)
		{
			new Ajax('<?php echo $SCRIPT_PATH; ?>msg_checkuser.php?name='+username,
			{
				method:'get',
				update:$("cuser")
			}).request();
		}
	}
</script>
</head>
<body>
<?php
	include('header.php');
?>

<form action="/msgsend.php" method="post">
<table width="90%" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="4" align="center"><img src="/images/email_go.png" /> <a href="msginbox.php"><?php echo $wikilang_inbox; ?></a> <img src="/images/email_open_image.png" /> <a href="msgoutbox.php"><?php echo $wikilang_outbox; ?></a> <img src="/images/email_edit.png" /> <b><?php echo $wikilang_compose; ?></b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td class="NewsTitle"><?php echo $wikilang_msg_to; ?></td>
	<td>
<?php
	if (isset($to))
		echo '<input type="text" name="to" id="to" size="10" maxlength="20" class=inputCool onkeyup="checkname();" value="'.$toName.'" />';
		else 
		echo '<input type="text" name="to" id="to" size="10" maxlength="20" class=inputCool onkeyup="checkname();"/>'
?>
	<span id="cuser">&nbsp;</span></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td class="NewsTitle"><img src="images/user_comment.png" /> <?php echo $wikilang_msg_subject; ?></td>
	<td><input type="text" name="subject" size="30" maxlength="100" class="inputCool" /></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td colspan="4">&nbsp;</td>
</tr>
<tr>
<td colspan="4">
<?php
$oFCKeditor = new FCKeditor('msgtext');
$oFCKeditor->BasePath = $SCRIPT_PATH.'includes/fckeditor/';
if (isset($text))
	$oFCKeditor->Value = $text;
	else
	$oFCKeditor->Value = '';
	
$oFCKeditor->Create();
?>
</td>
</tr>
<tr>
	<td colspan="4" align="center"><input type="submit" class="coolBoton" name="submit" value="<?php echo $wikilang_send; ?>"/></td>
</tr>

<?php
	include('footer.php');
	bbdd_close();
?>
</body></html>