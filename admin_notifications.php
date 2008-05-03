<?php
	include('includes/includes.php');
	
	if (!bd_userIsModerador())
	{
		bbdd_close();
		exit();
	}
	
	$page = $_GET['page'];
	if (!isset($page)) $page = 1;
	$max = $_GET['max'];
	if (!isset($max)) $max=25;
	$offset = ($page-1) * $max;
	
	if ($offset<1) $offset=0;
	
	if (isset($_POST['entry']))
	{
		$entry = $_POST['entry'];
		$comment = addslashes($_POST['newcomment']);
		$userID = $_SESSION['userID'];
		
		$query = "update moderations set active=0,moderator=$userID,comment='$comment' where entry=$entry";
		mysql_query($query);
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo $wikilang_moderator_notificacions.' - wikisubtitles'; ?>
</title>
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
<link href="css/wikisubtitles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include('header.php') ?>
<table align="center" width="95%" border="0">
<tr class="SectionTitle">
	<td><?php echo $wikilang_active; ?></td>
	<td><?php echo $wikilang_subtitle; ?></td>
	<td><?php echo $wikilang_info; ?></td>
	<td><?php echo $wikilang_moderator_notificacions; ?></td>
	<td><?php echo $wikilang_moderator; ?></td>
	<td><?php echo $wikilang_comments; ?></td>
</tr>
<?php
	$query = "select * from moderations order by entry DESC limit $offset,$max";
	$result = mysql_query($query);
	$numresults = mysql_affected_rows();
	
	while ($row=mysql_fetch_assoc($result))
	{
		$entry = $row['entry'];
		$subID = $row['subID'];
		$fversion = $row['fversion'];
		$lang = $row['lang'];
		$active = $row['active'];
		$counter = $row['counter'];
		$moderator = $row['moderator'];
		$comment = stripslashes($row['comment']);
		
		if ($active) $myclass = "originalText"; else $myclass="quotedText";
		echo "<tr class=\"$myclass\">";
			echo '<td>';
				if ($active) echo "<b>$wikilang_active</b>"; else echo "$wikilang_closed";
			echo '</td>';
			echo '<td>';
				$title = bd_getTitle($subID);
				$url = bd_getUrl($subID);
				echo '<a href="'.$url.'">'.$title.'</a>';
			echo '</td>';
			echo '<td>';
				$langname = bd_getLangName($lang);
				$versionDESC = bd_getFVersion($subID, $fversion);
				$versionSize = bd_getFVersionSize($subID, $fversion);
				echo "$wikilang_version $fversion ($versionDESC, $versionSize Mbs), $wikilang_language $langname";
			echo '</td>';
			echo '<td>'.$counter.'</td>';
			echo '<td>';
				if (!$active)
					echo bd_getUsername($moderator);
					else 
					echo '&nbsp';
			echo '</td>';
			echo '<td>';
				if (!$active)
					echo $comment;
					else {
						echo '<form method="post">';
						hidden('entry', $entry);
						echo '<textarea name="newcomment" cols="40"></textarea>';
						echo '<input type="submit" value="Resolve" class="coolBoton" />';
						echo '</form>';
					}
			echo '</td>';
		echo '</tr>';
				
	}
	
		echo '<tr>';
		echo '<td>';
			if ($page!=1)
			{
				$ant = $page - 1;
				echo '<img src="'.$SCRIPT_PATH.'images/arrow_left.png" border="0" with="16" heigth="16" />  <a href="/admin_notifications.php?page='.$ant.'&max='.$max.'">';
				echo '<b>'.$wikilang_prev_page.'</b></a>';
			}
		echo '</td>';
		
		echo '<td colspan="4"></td>';
		echo '<td>';
		if ($numresults>0)
		{
			$next = $page +1;
			echo '<a href="/admin_notifications.php?page='.$next.'&max='.$max.'">';
				echo '<b>'.$wikilang_next_page.'</b></a> <img src="'.$SCRIPT_PATH.'images/arrow_right.png" border="0" with="16" heigth="16" />';
		}
		echo '</td>';
	echo '</tr>';
?>
</table>

<?php include ('footer.php'); 
	bbdd_close();
?>
</body>
</html>