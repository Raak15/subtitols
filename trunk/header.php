<div id="header">

	<h1><a href="/index.php">Subtítols</a></h1>
	
	<?php
		if (isset($_SESSION['userID']))
		{
			$meme = $_SESSION['userID'];
			$username = bd_getUsername(($_SESSION['userID']));
			$queryheader = "select count(*) from msgs where `to`=$meme and noticed=0";
			$resultheader = mysql_query($queryheader);
			$noticedcount = mysql_result($resultheader, 0);
			$queryheader = "select count(*) from msgs where `to`=$meme and opened=0";
			$resultheader = mysql_query($queryheader);
			$openedcount = mysql_result($resultheader, 0);
			if (($noticedcount>0) && ($openedcount>0) && (!isset($_SESSION['msgadvised'])))
			{

	?>
		<script language="javascript">
		<?php   echo "var url=\"/msgspopup.php?count=$openedcount\";"; ?>
				editwin = window.open(url, "msgswin", 'height=200,width=350,toolbar=0,location=0,statusbar=0,menubar=0'); 
				if (editwin.focus) {editwin.focus()}
		</script>
		
		<?php
				}
				echo "<dl class=\"country\">\n";
				echo "<dt>".$wikilang_welkome .' ';
		    	echo "<a href=\"/myaccount.php\">$username</a></dt>\n";
		    	echo '<dd><a href="/msginbox.php">'.$openedcount; 
		    	echo ' '.$wikilang_unread_messages.'</a> · <a href="/newsub.php">'.$wikilang_upload_new.'</a> · <a href="/logout.php">'.$wikilang_logout.'</a></dd>';
				echo "</dl>";
			}

			else 
			{
				echo "<dl class=\"country\">\n";
				echo "<dt>Hello Guest!</dt>";
				echo '<dd><a href="/newaccount.php"> '.$wikilang_new_account.'</a> · <a href="/login.php">'.$wikilang_login.'</a> · <a href="/forum/">'.$wikilang_forum.'</a> · <a href="/wikifaq/">'.$wikilang_faq.'</a></dd>';
				echo '</dl>';
			}
			
			echo '<dl class="language">';
			echo '<dt>'.$wikilang_site_language.'</dt>';
			echo '<dd>';
			comboAppLanguages();
			echo '</dd></dl>'
		?>
	
	<div class="clear"></div>
    
</div>
