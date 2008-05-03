<?php

	include('includes/includes.php');
	include('translate_fns.php');
	
	if (!isset($_SESSION['userID'])) exit();
	
	$id = $_POST['id'];
	$seq = $_POST['seq'];
	$langto = $_POST['langto'];
	$langfrom = $_POST['langfrom'];
	$fversion = $_POST['fversion'];
	$text = $_POST['ttext'];
	$mytext = addslashes($text);
	$cancel = isset($_POST['Cancel']) && ($_POST['Cancel'] == "true");
	$userID = $_SESSION['userID'];
	
	
	
	if ($cancel)
	{
		$query = "update translating set tokened=0 where subID=$id and fversion=$fversion and lang_id=$langto and sequence=$seq";
		mysql_query($query);
		
		$query = "select text from subs where subID=$id and fversion=$fversion and lang_id=$langto and last=1 and edited_seq=$seq";
		$result = mysql_query($query);
		if (mysql_affected_rows()>0)
		{
			$texto = stripslashes(mysql_result($result, 0));
			echo nl2br($texto);
		}
		else 
			echo '<i>- '.$wikilang_untraslated.' -</i>';
	}
	else //no cancel
	{
		$query = "select seqcount from translating where subID=$id and fversion=$fversion and lang_id=$langto and sequence=$seq";
		$result = mysql_query($query);
		
		$already = (mysql_affected_rows()<1);
		
		if (!$already)
		{
			//translate
			//leer anterior
			$query = "select estart_time,estart_time_fraction,eend_time,eend_time_fraction,sequence,edited_seq from subs where subID=$id and fversion=$fversion and edited_seq=$seq and lang_id=$langfrom and last=1 limit 1";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);

			$authorID = $_SESSION['userID']	;
			$old_start_time = $row['estart_time'];
			$old_start_time_fraction = $row['estart_time_fraction'];
			$old_end_time = $row['eend_time'];
			$old_end_time_fraction = $row['eend_time_fraction'];

			//inserta el nuevo
			$query = "insert into subs(subID,sequence,authorID,version,original,locked,in_date,start_time,start_time_fraction,end_time,end_time_fraction,text,lang_id,edited_seq,last,estart_time,estart_time_fraction,eend_time,eend_time_fraction, fversion) ";
			$query .= "values($id,$seq,$authorID,0,0,0,NOW(),'$old_start_time',$old_start_time_fraction,'$old_end_time',$old_end_time_fraction,'$mytext',$langto,$seq,1,'$old_start_time',$old_start_time_fraction,'$old_end_time',$old_end_time_fraction,$fversion)";
			mysql_query($query);
			
			//update flangs
			$myoriginalLast = bd_langVersion0Count($id, $langfrom, $fversion);
			$mylangLast = bd_langVersion0Count($id, $langto, $fversion);
			$percent = ($mylangLast / $myoriginalLast) * 100;
			$percent = number_format($percent, 2);
			$query = "update flangs set state=$percent,totalseq=totalseq + 1,totalVersion0 = totalVersion0 + 1 where subID=$id and  fversion=$fversion and lang_id=$langto";
			mysql_query($query);
			
			//borrar de translating
			$query = "delete from translating where subID=$id and fversion=$fversion and lang_id=$langto and sequence=$seq";
			mysql_query($query);
			
			$state = bd_getLangState($id, $langto, $fversion);
			if ($state == "$wikilang_completed" ) tn_start($id, $fversion, $langfrom, $langto);
			
			echo '<font color="blue">'.nl2br(stripslashes($text)).'</font>';

			
		}
		else  //editar
		{
			$query = "select version,sequence,start_time,start_time_fraction,end_time,end_time_fraction,estart_time,estart_time_fraction,eend_time,eend_time_fraction from subs where subID=$id and lang_id=$langto and fversion=$fversion and edited_seq=$seq order by version DESC limit 1";
			$result = mysql_query($query);
			$myversion = intval(mysql_result($result, 0,0));
			$original_seq = mysql_result($result, 0,1);
			$start_time = mysql_result($result, 0,2);
			$start_time_fraction = mysql_result($result, 0,3);
			$end_time = mysql_result($result, 0,4);
			$end_time_fraction = mysql_result($result, 0,5);
			$estart_time = mysql_result($result, 0,6);
			$estart_time_fraction = mysql_result($result, 0,7);
			$eend_time = mysql_result($result, 0,8);
			$eend_time_fraction = mysql_result($result, 0,9);

			//****

			//marcar la anterior como que no es la last
			$query = "select version from subs where subID=$id and lang_id=$langto and fversion=$fversion and edited_seq=$seq and last=1";
			$result = mysql_query($query);
			$lastversion = mysql_result($result, 0);
			$query = "update subs set last=0 where subID=$id and lang_id=$langto and fversion=$fversion and edited_seq=$seq and version=$lastversion";
			mysql_query($query);


			$autorID = $_SESSION['userID'];
			$myversion++;

			$query =  "insert into subs(subID,sequence,authorID,version,original,locked,in_date,start_time,start_time_fraction,end_time,end_time_fraction,text,lang_id,edited_seq, last,estart_time,estart_time_fraction,eend_time,eend_time_fraction,fversion)";
			$query .= " values($id,$original_seq,$autorID,$myversion,0,0,NOW(),'$start_time',$start_time_fraction,'$end_time',$end_time_fraction,'$mytext',$langto,$seq,1,'$estart_time',$estart_time_fraction,'$eend_time',$eend_time_fraction,$fversion)";
			mysql_query($query);

			$query = "update flangs set totalseq = totalseq +1 where subID=$id and fversion=$fversion and lang_id=$langto";
			mysql_query($query);
			
			echo '<font color="red">'.nl2br(stripslashes($text)).'</font>';
		}
	}

	
	bbdd_close();
?>