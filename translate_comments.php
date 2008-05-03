<?php
	include('includes/includes.php');
	$myID = $_POST['id'];
	$newc = addslashes($_POST['newcomment']);
	$uID = $_SESSION['userID'];
	$username = bd_getUsername($uID);
	$fversion = $_POST['fversion'];
	$langto = $_POST['langto'];
	

	if (isset($_POST['newcomment']))
	{
		
		
		
		$query = "insert into transcomments(subID,lang_id,author,text,fversion) ";
		$query.= "values($myID, $langto, $uID, '$newc',$fversion)";
		mysql_query($query);
	}
	else $myID = $_GET['id'];
?>
<form action="/translate_comments.php" method="post" onsubmit="return enviar();" id="newc">
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="2" class="NewsTitle"><?php echo $wikilang_comments; ?></td>
  </tr>
<?php
	$query = "select * from transcomments where subID=$myID and fversion=$fversion and lang_id=$langto";
	$result = mysql_query($query);
	
	while ($row=mysql_fetch_assoc($result))
	{
		$cid = $row['author'];
		$cuser = bd_getUsername($cid);
		$cc = stripslashes($row['text']);
		
  		echo '<tr>
    	<td width="21%"><img src="images/user_comment.png" width="16" height="16" />';
    	echo "<a href=\/user/$cid\">$cuser</a>";
    	echo '</td>';
    	echo '<td width="66%">';
    	echo $cc;
    	echo '</td></tr>';
	}

	if (isset($_SESSION['userID']))
	{
		echo'<tr>
		<td><div align="right">
		<input name="Submit" type="submit" class="coolBoton" value="'.$wikilang_send_comment.' " />
		</div></td>
		<td><textarea name="newcomment" cols="70"></textarea></td>
		</tr>';
		hidden("id", $myID);
		hidden("fversion", $fversion);
		hidden("langto", $langto);
	}
?>
</table></form>
<?php
	bbdd_close();
?>