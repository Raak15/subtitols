<?php
	include_once('includes/includes.php');
	
	$mode = $_GET['mode'];
	if (!isset($mode)) exit();
	$items = $_GET['items'];
	if (!isset($items)) $items = 10;
	
	$ids = $_GET['ids'];
	if (!isset($ids)) 
		$ids = false;
		else 
		$ids=true;
		
	$MAX_ENTRIES = 10;
	
	
	
	if ($mode == "completed")
	{
		$query = "select subID,title,comment,end_date from files where finished=1 and temp=0 order by end_date DESC limit $items";
		$rsstitle = "wikisubtitles.net - last uploaded";
	}
		elseif ($mode == "started")
		{
			$query = "select subID,title,comment,end_date from files where finished=0 and temp=0 order by start_date DESC limit $items";
			$rsstitle = "wikisubtitles.net - last started";
		}
		elseif ($mode == "edited")
		{
			$query = "select distinct(subID) from subs where version>0 order by entryID DESC limit $items";
			$rsstitle = "wikisubtitles.net - last edited";
		}
		elseif ($mode == "translated")
		{
			$query = "select subID,lang_id from lasttranslated order by date DESC limit $items";
			$rsstitle = "wikisubtitles.net - last translated";
		}
		elseif ($mode=="versions")
		{
			$query="select subID,fversion,versionDesc,indate from fversions where fversion>0 order by entryID DESC limit $items";
			$rsstitle = "wikisubtitles.net - last new versions";
		}
		
			else exit();
	
	header('Content-Type: text/xml; charset=utf-8');
	$salida = "";
	
	$salida.= '<?xml version="1.0" encoding="utf-8"?>'; 
	$salida.= '<rss version="2.0">';
	
	//main
	 $salida.= ' <channel>';
     $salida.= "	<title>$rsstitle</title>";
     $salida.= '	<link>http://www.wikisubtitles.net/</link>
    		<language>en-us</language>
    		<description>Wikisubtitles RSS System</description>
    		<generator>wikisubtitles.net</generator>';
	
	$result = mysql_query($query);

	while ($row = mysql_fetch_assoc($result))
	{
		if (($mode=="edited") || ($mode=="translated"))
		{
			$query = "select end_date,title,comment from files where subID=".$row['subID'];
			$result2 = mysql_query($query);
			$row2 = mysql_fetch_assoc($result2);
			$date = $row2['end_date'];
			$title = stripslashes($row2['title']);
			$comment = stripslashes($row2['comment']);
		}
		elseif($mode=="versions")
		{
			$date = $row['indate'];
			$title = bd_getTitle($row['subID']);
			$comment = $row['versionDesc'];
		}
		else
		{
			$date = $row['end_date'];
			$title = bd_getTitle($row['subID']);
			$comment = stripslashes($row['comment']);
		}
		$newstime = $date;
		
		list($date, $hours) = split(' ', $newstime);
		list($year,$month,$day) = split('-',$date);
		list($hour,$min,$sec) = split(':',$hours); 
		$date = date(r,mktime($hour, $min, $sec, $month, $day, $year));
		
		$salida.= '<item>';
		$title = str_ireplace('&', '&amp;', $title);
		$salida.= '<title>'.$title.'</title>';
		if (!$ids)
			$salida.= '<link>http://wikisubtitles.net'.bd_getUrl($row['subID']).'</link>';
			else 
			$salida.= '<link>http://wikisubtitles.net/sub/'.$row['subID'].'</link>';
			
		$salida.= '<pubDate>'.$date.'</pubDate>';
		if (!$ids)
			$salida.= '<guid>http://wikisubtitles.net'.bd_getUrl($row['subID']);
			else 
			$salida.= '<guid>http://wikisubtitles.net/sub/'.$row['subID'];
		
		if ($mode=='versions') $salida.=$row['fversion'];
		$salida .= '</guid>';
			
		if ($mode=="translated")
		{
			$langname = utf8_decode(bd_getLangName($row['lang_id']));
			$description = "translated to $langname";
		}
		else 
			$decription = $comment;
			
		$salida.= '<description>'.$description.'</description>';
		$salida.= '</item>';
	}
	
	$salida.= '</channel></rss>';
	
	echo utf8_encode($salida);
	bbdd_close();

?>