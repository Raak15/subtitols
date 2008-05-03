<div id="footer">
	
	<?php
	if (isset($_SESSION['userID']))
		{
		?>
			<?php
			$query = "select userID,username from users where last > (NOW() - INTERVAL 5 MINUTE ) and hide=1";
			$result = mysql_query($query);
			$hidden = mysql_affected_rows();

			$query = "select userID,username from users where last > (NOW() - INTERVAL 5 MINUTE ) and hide=0";
			$result = mysql_query($query);
			$ucount = mysql_affected_rows();

			echo "<p>$wikilang_registered_online $ucount/$hidden $wikilang_hidden</p>: ";

		while ($row = mysql_fetch_assoc($result))
		{
			echo '<a href="/user/'.$row['userID'].'" >'.$row['username'].'</a>, ';
		}
	?>
	


<?
	}
?>
<p><?php echo $wikilang_connected_users; ?></p>

	<h4>About Subtitols</h4>
	<p>Subtitols is based on Wikisubtitles code. You can download original project and our branch, licenced on GPL3.</p>
	
</div>