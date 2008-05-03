<?php
	include_once('includes/includes.php');
	include_once('translate_fns.php');
	
	$MAX_PAGE = 20;
	
	$id = $_GET['id'];
	$langto = $_GET['langto'];
	$langfrom = $_GET['langfrom'];
	$fversion = $_GET['fversion'];
	$untranslated = getBool('untraslated');
	if (!isset($fversion)) $fversion = 0;
	
	tn_check($id, $fversion, $langfrom, $langto);
	
	
	$start = $_GET['start'];
	if (!isset($start)) $start = 0;
	
	$updated ="updated";
	$mode = "updated"; 
	$author = $_GET['user'];
	
	$slang=$langfrom;
		
	$squery = "select authorID,version,original,locked,text,lang_id,edited_seq,last,estart_time,estart_time_fraction,eend_time,eend_time_fraction,fversion from subs ";
	$query ="where subID=$id and fversion=$fversion";
	$query .= " and lang_id=$langfrom";
	
	if ($untranslated)
		$query .= " and edited_seq= ANY (select sequence from translating where subID=$id and fversion=$fversion and lang_id=$langto)"; 
	
	$query .= " and last=1 order by edited_seq,version";
	
			
	$fquery.=" limit $start,$MAX_PAGE";
	
	$finalresult = mysql_query($squery.$query.$fquery);
	
	$countquery = "select count(*) from subs ".$query;
		
	$result2 = mysql_query($countquery);
	$total = mysql_result($result2, 0);
	
	$langtoName = bd_getLangName($langto);
	$langfromName = bd_getLangName($langfrom);

	$state = bd_getLangState($id, $langto, $fversion);
	echo "&nbsp;&nbsp;$wikilang_translation_state <b><span id=\"current_state\">$state</span></b>&nbsp;";
	if ($untranslated)
		echo '<input name="unt" type="checkbox" id="unt" value="true" onclick="untraslated();" checked/>';
		else 
		echo '<input name="unt" type="checkbox" id="unt" value="true" onclick="untraslated();"/>';
	echo $wikilang_view_untraslated;
	
	//comentarios
?>

<div id="content">

<table class="translation">
  <thead>
	<tr>
    	<th><?php echo $wikilang_sequence_abbr; ?></th>
   		<th><?php echo $wikilang_author; ?></th>
    	<?php
    		echo '<th>'.$wikilang_language_from.': '.$langfromName.'</th>';
    		echo '<th>'.$wikilang_translation.' ('.$langtoName.')</th>';
		?>
    
  </tr>
<?php
	$last_authorID=0;
	while ($row = mysql_fetch_assoc($finalresult))
	{
		$fquery ="select authorID,text from subs where subID=$id and fversion=$fversion and last=1 and edited_seq=".$row['edited_seq']." and lang_id=$langto";
		$fresult = mysql_query($fquery);
		$fnum = mysql_affected_rows();
		if ($fnum>0)
		{
			$frow = mysql_fetch_assoc($fresult);
			$ttext = stripslashes($frow['text']);
			$tauthor = $frow['authorID'];
			$last_authorID = $tauthor;
			$myauthor = bd_getUsername($last_authorID);
		}
		else 
		{
			$myauthor = "";
			$ttext = "<i> - $wikilang_untraslated - </i>";
		}
		
		
		$text = stripslashes($row['text']);
		
		if ($row['last']=='1') 
			$class = 'originalText';
			else 
			$class = 'quotedText';
		if ($row['lang_id']!=$lang)
			$class = '';
		
		if ($fnum<1)
			echo '<tr>';
			else 
			echo '<tr class="translated">';
			echo '<td>'.$row['edited_seq'].'</td>';
	#	echo '<td>'.$row['version'].'</td>';
	#	echo '<td><img src="images/';
	#	if (mysql_affected_rows()<1)
	#		echo 'table_row_insert.png';
	#		else
	#		echo 'table_save.png';
#
#		
#		echo '" width="16" height="16" /></div></td>';
		echo '<td><a href="/user/'.$last_authorID.'">'.$myauthor.'</a></td>';
			
		echo '<td>'.nl2br($text).'</td>'; #imprime el texto original del idioma a traducir.

			
			$over = "mouseover('o','".$row['edited_seq']."');";
			$leave = "mouseleave('o','".$row['edited_seq']."');";
			$click = "mouseclick('o','".$row['edited_seq']."');";
			echo '<td class="cursorEdit" id="text'.$row['edited_seq'].'" onmouseover="'.$over.'" onmouseout="'.$leave.'" onclick="'.$click.'">'.nl2br(stripslashes($ttext)).'</td>';


		
		echo '</tr>';
	}
?>
</tbody>
<tfoot>
	<tr>
		<td colspan="4">
			<?php
				$numpaginas = ceil($total/$MAX_PAGE);
				$pagactual = floor($start/$MAX_PAGE);
				echo "$wikilang_pages ";
				for ($c=1; $c<=$numpaginas; $c++)
	{
		$mystart = ($c -1 ) * $MAX_PAGE;
		$newpage = floor($mystart/$MAX_PAGE);
		if ($newpage != $pagactual)
			echo "<a href=\"javascript:list('$mystart');\">$c</a> ";
			else 
			echo "$c ";
	}
?></td>
</tr>
</tfoot>
</table>

<br /><br />
&nbsp;&nbsp;&nbsp;<img src="images/table_row_insert.png"><?php echo $wikilang_untraslated; ?> <img src="images/table_save.png"> <?php echo $wikilang_translated; ?> <b>(<?php echo $wikilang_sequence_abbr; ?>)</b> <?php echo $wikilang_sequence; ?> <b>(<?php echo $wikilang_version_abbr; ?>)</b> <?php echo $wikilang_version; ?>

<table width="15%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr><td colspan="3" align="center" class="NewsTitle">
<?php echo $wikilang_users_translatings; ?>
</td></tr>
<?php
	$query = "select userID,sequence from translating where subID=$id and fversion=$fversion and lang_id=$langto and tokened=1";
	$result = mysql_query($query);
	while ($row = mysql_fetch_assoc($result))
	{
		$tuserID  = $row['userID'];
		$tuser = bd_getUsername($tuserID);
		$tseq = $row['sequence'];
		echo '<tr>';
		echo "<td>$tseq</td>";
		echo "<td><img src=\"images/<a href=\"/user/$tuserID\">$tuser</a></td>";
		echo "<td id=\"release$tseq\"><img src=\"images/cross.png\" /><a href=\"javascript:release('$tseq');\">$wikilang_release</a></td>";
		echo "</tr>";
	}

		echo '<tr><td colspan="3" aling="center" id="rall"><a href="javascript:releaseAll();">'.$wikilang_releaseAll.'</a></td></tr>';
?>
</table>


<span id="comments">
<form action="/translate_comments.php" method="post" onsubmit="return enviar();" id="newc">
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="2" class="NewsTitle"><?php echo $wikilang_comments; ?></td>
  </tr>
<?php
	$query = "select * from transcomments where subID=$id and fversion=$fversion and lang_id=$langto";
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
		<input name="Submit" type="submit" class="coolBoton" value="'.$wikilang_send_comment.'" />
		</div></td>
		<td><textarea name="newcomment" cols="70"></textarea></td>
		</tr>';
		hidden("id", $id);
		hidden("fversion", $fversion);
		hidden("langto", $langto);
	}
?>
</table></form>
</span>

<?php
	bbdd_close();
?>
