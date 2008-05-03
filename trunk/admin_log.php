<?php
	include('includes/includes.php');
	
	$page = $_GET['page'];
	if (!isset($page)) $page = 1;
	$max = $_GET['max'];
	if (!isset($max)) $max = 50;



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo $wikilang_activity_log.' - wikisubtitles'; ?>
</title>
<link href="css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
</head>
<body>
<?php include('header.php'); ?>
<div align="center" class="titulo"><?php echo $wikilang_activity_log; ?></div>
<br/>
<table width="90%" border="0" align="center">
<tr class="SectionTitle">
	<td><?php echo $wikilang_date_date; ?></td>
	<td><?php echo $wikilang_user; ?></td>
	<td><?php echo $wikilang_id; ?></td>
	<td><?php echo $wikilang_title; ?></td>
	<td><?php echo $wikilang_action; ?></td>
	<td><?php echo $wikilang_info; ?></td>
</tr>
<?php

	$query = "select * from log order by entryID DESC";
	
	$offset = ($page-1) * $max;
	
	if ($offset<1) $offset=0;
	
	$query .= " limit $offset,$max";
	
	$result = mysql_query($query);
	$numresults = mysql_affected_rows();
	
	while ($row=mysql_fetch_assoc($result))
	{
		echo '<tr>';
			echo '<td>'.obtenerFecha($row['date']).'</td>';
			echo '<td><a href="/user/'.$row['userID'].'">'.bd_getUsername($row['userID']).'</a></td>';
			echo '<td>'.$row['subID'].'</td>';
			echo '<td>'.bd_getIcon($row['subID']).'&nbsp;<a href="'.bd_getUrl($row['subID']).'">'.bd_getTitle($row['subID']).'</a></td>';
			echo '<td>'.log_getAction($row['action']).'</td>';
			echo '<td>'.stripslashes($row['text']).'</td>';
		echo '</tr>';
	}
	
	echo '<tr><td colspan=6></td></tr>';
	
	echo '<tr>';
		echo '<td>';
			if ($page!=1)
			{
				$ant = $page - 1;
				echo '<img src="'.$SCRIPT_PATH.'images/arrow_left.png" border="0" with="16" heigth="16" />  <a href="/admin_log.php?page='.$ant.'&max='.$max.'">';
				echo '<b>'.$wikilang_prev_page.'</b></a>';
			}
		echo '</td>';
		
		echo '<td colspan="2"></td>';
		echo '<td colspan="3" align="right">';
		if ($numresults>0)
		{
			$next = $page +1;
			echo '<a href="'.$SCRIPT_PATH.'admin_log.php?page='.$next.'&max='.$max.'">';
				echo '<b>'.$wikilang_next_page.'</b></a> <img src="'.$SCRIPT_PATH.'images/arrow_right.png" border="0" with="16" heigth="16" />';
		}
		echo '</td>';
	
?>
</table>
<?php
	include('footer.php');
	bbdd_close();
?>
</body>
</html>
