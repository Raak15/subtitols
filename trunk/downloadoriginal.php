<?php
	include ('includes/includes.php');
	$mysubID = $_GET['id'];
	
	$fversion = $_GET['fversion'];
	if (!isset($fversion)) $fversion = 0;
	
	
	$title  = bd_getTitle($mysubID).'.srt';
	bd_increaseDownloads($mysubID);
	$orlang = bd_getOriginalLang($mysubID, $fversion);
	
	
	header('Content type: text/srt');
	header("Content-Disposition: attachment; filename=\"$title\"");	
	
	
	$query = "select * from subs where subID=$mysubID and original=1 and fversion=$fversion order by sequence";
	$result = mysql_query($query);
	$numresults = mysql_affected_rows();
	
	if ($numresults>0)
	{
		for ($c = 0; $c<$numresults; $c++)
		{
			$row = mysql_fetch_assoc($result);
			$secuencia = $row['sequence'];
			$start_time = $row['start_time'];
			$end_time = $row['end_time'];
			$start_time_fraction = $row['start_time_fraction'];
			$end_time_fraction = $row['end_time_fraction'];
			$text = stripslashes($row['text']);
			if (!bd_isUTF($mysubID, $fversion, $orlang))
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
	
	$query = "insert into downloads(userID,ip,cuando,subID,fversion,lang) ";
	$query.= "values($suserID,'$ip',NOW(),$mysubID,$fversion, $orlang)";
	mysql_query($query);
	
	
	bbdd_close();
?>