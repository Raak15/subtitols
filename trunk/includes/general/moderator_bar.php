<?php if (bd_userIsModerador())
	{ ?>

		<div id="moderator">
		<h4><?php echo $wikilang_moderator; ?>:</h4>
		<ul>
			<li><a href="/admin_shows.php"><?php echo $wikilang_delete_show; ?></a></li>
			<li><a href="/translating.php"><?php echo $wikilang_clear_translations; ?></a></li>
			<li><a href="/admin_userdoing.php"><?php echo $wikilang_connected_users; ?></a></li>
		</ul>
		
		<?php 
				$query = "select count(*) from moderations where active=1";
				$result = mysql_query($query);
				$modcount = mysql_result($result, 0);
				if ($modcount>0);
				
				?>
				 <h4>Admin:</h4>
				<?php
					echo '<ul>';
					echo '<li><a href="'.$SCRIPT_PATH.'admin_notifications.php">'.$wikilang_moderator_notificacions.' ('.$modcount.')</a></li>';
					if ($modcount>0);	
	
					if (bd_userIsAdministrator())
					{
						echo '<li><a href="'.$SCRIPT_PATH.'admin_useradmin.php">User admin</a></li>';
						echo '</ul>';
					}
					
					?>
					
					<?php
					
					echo '</div>';
					echo '<div class="clear"></div>';
			}
?>