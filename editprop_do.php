<?php
	include('includes/includes.php');
	$userID = $_SESSION['userID'];
	
	if (!isset($userID))
	{
		bbdd_close();
		exit();
	}
	
	$id = $_POST['id'];
	$fversion = $_POST['fversion'];
	if (!isset($_POST['fversion'])) 
	{
		unset($fversion);
		$rversion = 0;
	}
	else 
		$rversion = $fversion;
	
	
	
	$newshow = addslashes($_POST['newshow']);
	$showID =$_POST['showID'];
	if (isset($_POST['newshow']))
	{
		$query = "select showID from shows where showName='$newshow'";
		$result = mysql_query($query);
		$count = mysql_affected_rows();

		if ($count<1)
		{
			$query = "insert into shows (showName) values('$newshow')";
			mysql_query($query);
			$query = "select showID from shows where showName='$newshow'";
			$result = mysql_query($query);
			$showID = mysql_result($result, 0);
		}
		else
		$showID=mysql_result($result, 0);

	}

	$type = $_POST['type'];
	$is_episode = $type == "ep";
	$season = $_POST['season'];
	$epnumber = $_POST['epnumber'];
	$eptitle = $_POST['eptitle'];
	$movietitle = addslashes($_POST['movietitle']);
	$year = $_POST['year'];
	$comment = addslashes(strip_tags($_POST["comment"]));
	$version = $_POST['version'];
	$size = $_POST['fsize'];
	$lang = $_POST['lang'];
	
	$query = "update fversions set versionDesc='$version',size='$size',comment='$comment' where subID=$id and fversion=$rversion";
	mysql_query($query);
	
	$prev_lang = bd_getOriginalLang($id, $rversion);
	if ($lang!=$prev_lang)
	{
		$query = "update subs set lang_id=$lang where subID=$id and fversion=$rversion and lang_id=$prev_lang";
		mysql_query($query);
		$query = "update flangs set lang_id=$lang where subID=$id and fversion=$rversion and lang_id=$prev_lang";
		mysql_query($query);
	}
	
	if (!isset($fversion))
	{
		if ($is_episode)
		{
			$showname = bd_getShowTitle($showID);
			if (strlen($season)<2) $season = '0'.$season;
			if (strlen($epnumber)<2) $epnumber = '0'.$epnumber;
			$title = $showname.' - '.$season.'x'.$epnumber.' - '.$eptitle;
			$title = addslashes($title);
			$query = "update files set is_episode=1,title='$title',season=$season,season_number=$epnumber where subID=$id";
			mysql_query($query);
		}
		else 
		{
			$title = $movietitle." ($year)";
			$tile = addslashes($title);
			$query = "update files set is_episode=0,title='$title' where subID=$id";
			mysql_query($query);
		}
	}
	
	$title = bd_getTitle($id);
	log_insert(LOG_updateprop, '',$userID, $id, bd_userIsModerador() );
	
	$url = bd_getUrl($id);
	location("$url");
	bbdd_close();
?>