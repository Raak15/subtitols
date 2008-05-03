<?php
	include_once('includes/includes.php');
	
	$me =$_SESSION['userID'];
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
<table width="90%" align="center">
<tr>
	<td colspan="4" align="center"><img src="/images/email_go.png" /> <a href="msginbox.php"><?php echo $wikilang_inbox; ?></a> <img src="/images/email_open_image.png" /> <b><?php echo $wikilang_outbox; ?></b> <img src="/images/email_edit.png" /> <a href="msgcreate.php"><?php echo $wikilang_compose; ?></a></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td class="NewsTitle"><?php echo $wikilang_msg_to; ?></td>
	<td class="NewsTitle"><?php echo $wikilang_msg_subject; ?></td>
	<td class="NewsTitle"><?php echo $wikilang_date_date; ?></td>
</tr>
<?php
	$query = "select msgID,`to`,date,subject from msgs where `from`=$me and fromDelete=0 order by DATE DESC";
	$result = mysql_query($query);
	$nummsgs = mysql_affected_rows();
	
	$tickcount = 0;
	while ($row=mysql_fetch_assoc($result))
	{
		$msgID = $row['msgID'];
		$date = obtenerFecha($row['date']);
		$subject = utf8_decode(stripslashes($row['subject']));
		$to = $row['to'];
		$toName = bd_getUsername($to);
		$opened = $row['opened'] == '1';
		
		echo '<tr><td>';
		echo "<input type=\"checkbox\" name=\"tick$tickcount\" value=\"$msgID\" />";
		if ($opened)
			echo '<img src="/'.$SCRIPT_PATH.'email_open.png" /></td>';
			else 
			echo '<img src="'.$SCRIPT_PATH.'images/email_go.png" /></td>';
		echo "<td><a href=\"msgcreate.php?to=$to\">$toName</a></td>";
		echo "<td><a href=\"msgview.php?id=$msgID\">$subject</a></td>";
		echo "<td>$date</td>";
		echo  '</tr>';
		
		$tickcount++;
	}
?>
<tr>
<td colspan="4" align="center">
<input type="submit" value="<?php echo $wikilang_delete; ?>" name="outboxdel" class="coolBoton"/>
<input type="submit" value="<?php echo $wikilang_delete_all; ?>" name="outboxdelall" class="coolBoton"/>
</td>
</tr>
</table>
<?php
	hidden("totalmsgs", $nummsgs);
?>
</form>

<?php
	include('footer.php');
	bbdd_close();
?>
</body></html>