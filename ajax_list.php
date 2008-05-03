<?php
	include_once('includes/includes.php');
	
	$MAX_PAGE = 20;
	
	$id = $_GET['id'];
	$lang = $_GET['lang'];
	$fversion = $_GET['fversion'];
	if (!isset($fversion)) $fversion = 0;
	
	$user_filter = $_GET['user'];
	$timefrom = $_GET['timefrom'];
	$timeto = $_GET['timeto'];
	$search = $_GET['search'];
	if ($search=='') unset($search);
	if ($user_filter=='0') unset($user_filter);
	
		
	
	$start = $_GET['start'];
	if (!isset($start)) $start = 0;
	
	$updated =$_GET['updated'];
	if ($updated=="true") $mode = "updated"; else $mode = "all";
	$author = $_GET['user'];
	
	$slang=$_GET['slang'];
	if ($slang=="") unset($slang);
	if ($slang==0) unset($slang);
		
	$squery = "select authorID,version,original,locked,text,lang_id,edited_seq,last,estart_time,estart_time_fraction,eend_time,eend_time_fraction,fversion from subs ";
	$query ="where subID=$id and fversion=$fversion";
	$query .= " and lang_id=$lang";
	
	if (isset($timefrom))
		$query.= " and estart_time>='$timefrom' and eend_time<='$timeto'";
	
	if (isset($user_filter))
	{
		$query .=" and authorID=$user_filter";
	}
	
	if (isset($search))
	{
		$query .= " and text like '%$search%'";
	}
	
	if ($mode == "all")
		$query .= " order by edited_seq,version";
	else if ($mode == "updated")
		$query .= " and last=1 order by edited_seq,version";
	else if ($mode == "user")
		$query .= " and authorID=$author order by edited_seq,version,entryID";
	

	$fquery.=" limit $start,$MAX_PAGE";
	
	$finalresult = mysql_query($squery.$query.$fquery);
	
	$countquery = "select count(*) from subs ".$query;
		
	$result2 = mysql_query($countquery);
	$total = mysql_result($result2, 0);
	
	$lock = bd_userIsModerador() || (logged() && (bd_getFVersionAuthor($id,$fversion)==$_SESSION['userID']));
	
?>
&nbsp;&nbsp;
<table align="center" width="100%" border="0">
<tr><td align="center">
<?php
	if (!isset($timefrom))
	{ 
		if ($mode=="updated")
		echo "<input type=\"checkbox\" name=\"updated\" value=\"true\" onchange=\"list('$start','','$slang');\" checked/>";
		else
		echo "<input type=\"checkbox\" name=\"updated\" value=\"true\" onchange=\"list('$start','true','$slang');\"/>";
	}
	else 
	{
		if ($mode=="updated")
		echo "<input type=\"checkbox\" name=\"updated\" value=\"true\" onchange=\"apply_filter('$slang','','$start');\" checked/>";
		else
		echo "<input type=\"checkbox\" name=\"updated\" value=\"true\" onchange=\"apply_filter('$slang','true','$start');\"/>";
	}

	echo $wikilang_view_most_updated;
?>
&nbsp;
<?php
	$lquery = "select DISTINCT lang_id from flangs where subID=$id and fversion=$fversion";
	$lresult = mysql_query($lquery);
	$lnumresults = mysql_affected_rows();
	
	if (($lnumresults>1) && ($mode!="user"))
	{
			echo "$wikilang_secondary_language ";
			$func = "slang('$start','$updated');";
			echo '<select name="slang" id="slang" class="inputCool" onchange="'.$func.'">';
			echo '<option value="0">'.$wikilang_none.'</option>';
			while ($langrow = mysql_fetch_assoc($lresult))
			{
				$lang_id = $langrow['lang_id'];
				if ($lang_id!=$lang)
				{
					$mylangName = bd_getLangName($lang_id);
					if (isset($slang) && ($lang_id==$slang))
					echo "<option value=\"$lang_id\" selected>$mylangName</option>";
					else
					echo "<option value=\"$lang_id\">$mylangName</option>";
				}
			}
			echo '</select>';
	}
?>
</td></tr>
<tr><td align="center">
<?php
	echo '<form name="filter" method="post" action="/noscript.php" onsubmit="return apply_filter(\''.$slang.'\',\''.$update.'\',\'0\');" id="filter">';
	
	echo $wikilang_time_from;
	if (isset($timefrom)) 
		$value = $timefrom;
		else 
		{
			$query = "select estart_time from subs where subID=$id and fversion=$fversion and lang_id=$lang and edited_seq=(select min(edited_seq) from subs where subID=$id and fversion=$fversion and lang_id=$lang)";
			$result = mysql_query($query);
			$value = mysql_result ($result, 0);
		}
	echo '<input type="text" name="timefrom" size="10" maxlength="9" value="'.$value.'" class="inputCool"/>';
	
	if (isset($timeto)) 
		$value = $timeto;
		else 
		{
			$query = "select eend_time from subs where subID=$id and fversion=$fversion and lang_id=$lang and edited_seq=(select max(edited_seq) from subs where subID=$id and fversion=$fversion and lang_id=$lang)";
			$result = mysql_query($query);
			$value = mysql_result ($result, 0);
		}
	echo ' '.$wikilang_time_to.' <input type="text" name="timeto" size="10" maxlength="9" value="'.$value.'" class="inputCool"/>';
	
	echo " $wikilang_author ";
	echo '<select name="user" class="inputCool"><option value="0">'.$wikilang_all.'</option>';
	$uquery = "select distinct(authorID) from subs where subID=$id and fversion=$fversion and lang_id=$lang";
	$uresult = mysql_query($uquery);
	
	while ($urow = mysql_fetch_assoc($uresult))
	{
		$authorID = $urow['authorID'];
		$username = bd_getUsername($authorID);
		
		if (isset($user_filter) && ($user_filter==$authorID))
			echo '<option value="'.$authorID.'" selected>'.$username.'</option>';
			else 
			echo '<option value="'.$authorID.'">'.$username.'</option>';
		
	}
	echo '</select>';
	echo $wikilang_search_text;
 	textbox("search", $search, 15,50,"","inputCool");
 	echo '<input type="submit" name="submit" class="coolBoton" value="'.$wikilang_apply_filter.'" />';
 ?>

</td></tr>
</form>
</table>


<table width="98%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
<?php
	if ($lock)
		echo '<td class="NewsTitle">&nbsp;</td>';
?>
    <td class="NewsTitle"><div align="center"><?php echo $wikilang_sequence_abbr; ?></div></td>
    <td class="NewsTitle"><div align="center"><?php echo $wikilang_version_abbr;?></div></td>
    <td class="NewsTitle"><div align="center"><?php echo $wikilang_state; ?></div></td>
    <td class="NewsTitle"><div align="center"><?php echo $wikilang_author; ?></div></td>
    <td class="NewsTitle"><div align="center"><?php echo $wikilang_times; ?></div></td>
    <?php
	if (isset($slang))
    	echo '<td class="NewsTitle">'.$wikilang_secondary_language.' ('.bd_getLangName($slang).')</td>';
?>
    <td class="NewsTitle"><?php echo $wikilang_text; ?></td>
  </tr>
<?php
	$last_authorID=0;
	while ($row = mysql_fetch_assoc($finalresult))
	{
		
		if ($row['last']=='1') 
			$class = 'originalText';
			else 
			$class = 'quotedText';
		if ($row['lang_id']!=$lang)
			$class = '';
		if ($row['locked'] && $row['last'])
			$class = 'lockedText';
		
		if ($last_authorID!=$row['authorID'])
		{
			$last_authorID=$row['authorID'];
			$myauthor = bd_getUsername($last_authorID);
		}
		
		echo '<tr class="'.$class.'" valign="top" id="trseq'.$row['edited_seq'].'">';
		if ($lock)
		{
			if ($row['last'])
			{
				if ($row['locked'])
					echo '<td align="right" id="lock'.$row['edited_seq'].'"><a href="javascript:lock(true, \''.$row['edited_seq'].'\');" ><img src="'.$SCRIPT_PATH.'images/lock.png" border="0" /></a></td>';
					else 
					echo '<td align="right" id="lock'.$row['edited_seq'].'"><a href="javascript:lock(false, \''.$row['edited_seq'].'\');" ><img src="'.$SCRIPT_PATH.'images/lock_open.png" border="0" /></a></td>';
			}
			else echo '<td>&nbsp;</td>';
				
		}
		
		echo '<td>'.$row['edited_seq'].'</td>';
		echo '<td><div align="center">'.$row['version'].'</div></td>';
		echo '<td><div align="center"><img src="images/';
		if ($row['original'])
			echo 'table_row_insert.png';
			elseif($row['last'])
			echo 'table_save.png';
			else echo 'table_row_delete.png';
		
		echo '" width="16" height="16" /></div></td>';
		echo '<td><div align="center"><a href="/user/'.$last_authorID.'">'.$myauthor.'</a></div></td>';
		//times
		if ((!$row['last']) || $row['locked'])
			echo '<td align="center">'.$row['estart_time'].','.$row['estart_time_fraction'].' --> '.$row['eend_time'].','.$row['eend_time_fraction'].'</td>';
			else 
			{
				$over = "timeover('".$row['edited_seq']."');";
				$leave = "timeleave('".$row['edited_seq']."');";
				$click = "timeclick('".$row['edited_seq']."');";
				echo  '<td class="cursorEdit" align="center" id="time'.$row['edited_seq'].'" onmouseover="'.$over.'" onmouseout="'.$leave.'" onclick="'.$click.'">';
				echo $row['estart_time'].','.$row['estart_time_fraction'].' --> '.$row['eend_time'].','.$row['eend_time_fraction'].'</td>';
			}
		
		if (isset($slang))
		{
			$fquery ="select text from subs where subID=$id and fversion=$fversion and last=1 and edited_seq=".$row['edited_seq']." and lang_id=$slang";
			$fresult = mysql_query($fquery);
			if (mysql_affected_rows()>0)
				$trans = stripslashes(mysql_result($fresult, 0));
				else 
				$trans = "";
			echo '<td bgcolor="white">'.nl2br(stripslashes($trans)).'</td>';
		}
		
		if ($row['last'] && (!$row['locked']))
		{
			$over = "mouseover('o','".$row['edited_seq']."');";
			$leave = "mouseleave('o','".$row['edited_seq']."');";
			$click = "mouseclick('o','".$row['edited_seq']."');";
			echo '<td class="cursorEdit" id="text'.$row['edited_seq'].'" onmouseover="'.$over.'" onmouseout="'.$leave.'" onclick="'.$click.'">'.nl2br(stripslashes($row['text'])).'</td>';
		}
			else 
			echo '<td>'.nl2br(stripslashes($row['text'])).'</td>';
		
		echo '</tr>';
	}
?>
</table><br />


<?php
if (isset($timefrom))
{
	echo '<input type="hidden" name="filtered" id="filtered" value="on" />';
}
	else echo '<input type="hidden" name="filtered" id="filtered" value="off" />';
	$numpaginas = ceil($total/$MAX_PAGE);
	$pagactual = floor($start/$MAX_PAGE);
	echo "$wikilang_pages ";
	for ($c=1; $c<=$numpaginas; $c++)
	{
		$mystart = ($c -1 ) * $MAX_PAGE;
		$newpage = floor($mystart/$MAX_PAGE);
		
		if (!isset($timefrom))
			$myfunc = "javascript:list('$mystart','$updated','$slang');";
			else 
			$myfunc = "javascript:apply_filter('$slang', '$updated', '$mystart');";

		if ($newpage != $pagactual)
			echo "<a href=\"$myfunc\">$c</a> ";
			else 
			echo "$c ";
	}
	
	bbdd_close();
?>
<br /><br />LELEL
&nbsp;&nbsp;&nbsp;<img src="images/table_row_insert.png"> <?php echo $wikilang_original; ?> <img src="images/table_save.png"> <?php echo $wikilang_current; ?> <img src="images/table_row_delete.png"> <?php echo $wikilang_previous; ?> <b>(<?php echo $wikilang_sequence_abbr; ?>)</b> <?php echo $wikilang_sequence; ?> <b>(<?php echo $wikilang_version_abbr; ?>)</b> <?php echo $wikilang_version; ?>
