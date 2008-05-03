<?php
	include ('includes/includes.php');
	
	$mysubID = $_GET['id'];
	$lang = $_GET['lang'];
	$fversion = $_GET['fversion'];
	if (!isset($fversion)) $fversion = 0;

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
	
	
	$query = "select * from subs where subID=$mysubID and lang_id=$lang and last=1 and fversion=$fversion order by edited_seq";
	$result = mysql_query($query);
	$numresults = mysql_affected_rows();
	
	if ($numresults>0)
	{
		for ($c = 0; $c<$numresults; $c++)
		{
			$row = mysql_fetch_assoc($result);
			$secuencia = $row['sequence'];
			$start_time = $row['estart_time'];
			$end_time = $row['eend_time'];
			$start_time_fraction = $row['estart_time_fraction'];
			$end_time_fraction = $row['eend_time_fraction'];
			$text = $row['text'];
			$text = stripcslashes($text);
			if (!bd_isUTF($mysubID, $fversion, $lang))
				$text = utf8_decode($text);
			
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
	
		//anotate download
	if (isset($_SESSION['userID'])) 
		$suserID = $_SESSION['userID'];
		else 
		$suserID = 0;
	$ip = $_SERVER['REMOTE_ADDR'];
	$orlang = bd_getOriginalLang($mysubID, $fversion);
	$query = "insert into downloads(userID,ip,cuando,subID,fversion,lang) ";
	$query.= "values($suserID,'$ip',NOW(),$mysubID,$fversion, $lang)";
	mysql_query($query);
	
	bbdd_close();
?>
