<?php
	include('includes/includes.php');
	
	session();
	if (!isset($_SESSION['userID'])) exit();
	
	$id = $_POST['id'];
	$seq = $_POST['seqnumber'];
	$lang = $_POST['lang'];
	$fversion = $_POST['fversion'];
	$text = $_POST['ttext'];
	$mytext = addslashes($text);
	
	
		$query = "select version,sequence,start_time,start_time_fraction,end_time,end_time_fraction,estart_time,estart_time_fraction,eend_time,eend_time_fraction from subs where subID=$id and lang_id=$lang and fversion=$fversion and edited_seq=$seq order by version DESC limit 1";
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
		$query = "select version from subs where subID=$id and lang_id=$lang and fversion=$fversion and edited_seq=$seq and last=1";
		$result = mysql_query($query);
		$lastversion = mysql_result($result, 0);
		$query = "update subs set last=0 where subID=$id and lang_id=$lang and fversion=$fversion and edited_seq=$seq and version=$lastversion";
		mysql_query($query);
		
			
		$autorID = $_SESSION['userID'];
		$myversion++;
		
		$query =  "insert into subs(subID,sequence,authorID,version,original,locked,in_date,start_time,start_time_fraction,end_time,end_time_fraction,text,lang_id,edited_seq, last,estart_time,estart_time_fraction,eend_time,eend_time_fraction,fversion)";
		$query .= " values($id,$original_seq,$autorID,$myversion,0,0,NOW(),'$start_time',$start_time_fraction,'$end_time',$end_time_fraction,'$mytext',$lang,$seq,1,'$estart_time',$estart_time_fraction,'$eend_time',$eend_time_fraction,$fversion)";
		mysql_query($query);
		
		$query = "update flangs set totalseq = totalseq +1 where subID=$id and fversion=$fversion and lang_id=$lang";
		mysql_query($query);
	 
	bbdd_close();
	
	echo '<font color="blue">'.nl2br(stripslashes($text)).'</font>';

?>
