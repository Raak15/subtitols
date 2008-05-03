<?php
	include('includes/includes.php');
	

	if (isset($_POST['newcomment']))
	{
		$myID = $_POST['id'];
		$newc = addslashes($_POST['newcomment']);
		$uID = $_SESSION['userID'];
		$username = bd_getUsername($uID);
		
		$query = "insert into comments(subID,userID,username,date,comment) ";
		$query.= "values($myID, $uID, '$username', NOW(),'$newc')";
		mysql_query($query);
	}
	else 
	{
		$myID = $_GET['id'];
		if (isset($_GET['cid']))
		{
			$query = "select subID from comments where commentid=".$_GET['cid'];
			$result = mysql_query($query);
			$myID = mysql_result($result, 0);
		}
	}
?>

<form action="/ajax_getComments.php" method="post" onsubmit="return enviar();" id="newc">

<h4><?php echo $wikilang_comments; ?></h4>

<?php
	$query = "select * from comments where subID=$myID and deleted=0";
	$result = mysql_query($query);
	
	?>
<div id="comments-list">	
	<ol>
	<?php  while ($row=mysql_fetch_assoc($result)) { ?>
	<li>
		<ul>
			<?php 
				if (bd_userIsModerador() || ($row['userID']==$_SESSION['userID'])) {
						echo '<li><strong>Moderate:</strong> <a href="javascript:delComment(\''.$row['commentid'].'\');">';
						echo 'Delete</a></li>';
						}
						echo "<li><strong>Author:</strong> <a href='/user/".$row['userID']."'>".$row['username'].'</a></li>'; ?>
						<li><strong>Date:</strong> <?php echo obtenerFecha($row['date']); ?></li>
					</ul>
		<p><?php echo stripslashes($row['comment']); ?></p><div class="clear"></div></li>
		
		<?php } ?>
</ol>

</div>

<?php
	if (isset($_SESSION['userID']))
	{
?>
		  <tr>
    <td>&nbsp;</td>
    <td>
      <textarea name="newcomment" id="newcomment" cols="70" rows="3"></textarea>
    </td>
    <td>
<?php echo '      <input type="submit" name="button" id="button" value="'.$wikilang_send_comment.'" class="coolBoton"/>'; ?>
    </td>
    <td>&nbsp;</td>
  </tr>
<?php
		hidden("id", $myID);
	}
?>
</table></form>
<?php
	bbdd_close();
?>