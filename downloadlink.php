<?php
	include('includes/includes.php');
	$linkID = $_GET['linkID'];
	$lang = $_GET['lang'];
	
	$mysubID = bd_link_getSubID($linkID);
	$fversion = bd_link_getFversion($linkID);
	
	$query = "select count(*) from files where subID=$mysubID";
	$result = mysql_query($query);
	$mycount = mysql_result($result, 0);
	if ($mycount<1)
	{
		header("Location: /index.php"); 
		exit();
	}
	
	$title = bd_getTitle($mysubID);
	bd_increaseDownloads($mysubID);
	$langname = bd_getLangName($lang);
	$title .= " ($langname).srt";
	
	header('Content type: text/srt');
	header("Content-Disposition: attachment; filename=\"$title\"");	
	
	
	$query = "select text,sequence from subs where subID=$mysubID and lang_id=$lang and last=1 and fversion=$fversion order by edited_seq";
	$result = mysql_query($query);
	$numresults = mysql_affected_rows();
	
	$orlang = bd_getOriginalLang($mysubID, $fversion);
	
	if ($numresults>0)
	{
		for ($c = 0; $c<$numresults; $c++)
		{
			$row = mysql_fetch_assoc($result);
			$secuencia = $row['sequence'];
			$text = $row['text'];
			$text = stripcslashes($text);
			
			
			
			if (!bd_isUTF($mysubID, $fversion, $orlang))
				$text = utf8_decode($text);
			
			$query = "select * from links_data where linkID=$linkID and sequence=$secuencia";
			$lresult = mysql_query($query);
			$lrow = mysql_fetch_assoc($lresult);
			
			$start_time = $lrow['start_time'];
			$start_time_fraction = $lrow['start_time_fraction'];
			$end_time = $lrow['end_time'];
			$end_time_fraction = $lrow['end_time_fraction'];
			
			echo "$secuencia\r\n";
			echo "$start_time,$start_time_fraction --> $end_time,$end_time_fraction\r\n";	
			$text = str_replace("\n", "\r\n",$text);
			$text = str_replace("\r\r", "\r", $text);	
			echo $text;
			
			if (substr($text, strlen($text)-1)!="\n") 
				echo "\r\n";
			echo "\r\n";
		}
		
	}
	
	if (isset($_SESSION['userID'])) 
		$suserID = $_SESSION['userID'];
		else 
		$suserID = 0;
	$ip = $_SERVER['REMOTE_ADDR'];
	
	
	$query = "insert into downloads(userID,ip,cuando,subID,fversion,lang) ";
	$query.= "values($suserID,'$ip',NOW(),$mysubID,$fversion, $lang)";
	mysql_query($query);
	
	$query = "update links set downloads = downloads +1 where linkID=$linkID";
	mysql_query($query);
	
	
	bbdd_close();
?>