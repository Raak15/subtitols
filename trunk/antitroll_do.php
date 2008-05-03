<?php
	include('includes/includes.php');
	include_once('translate_fns.php');
	
	$id = $_POST['id'];
	$fversion = $_POST['fversion'];
	$lang = $_POST['lang'];
	$author = $_POST['author'];
	$notoriginal = isset($_POST['notoriginal']) && ($_POST['notoriginal'] == 'true'); 
	
	if (!isset($author))
	{
		bbdd_close();
		echo "No user selected. Go back";
		exit();
	}
	
	$numresults = 1;
	
	while ($numresults>0)
	{
		$query = "select entryID,edited_seq,version from subs where subID=$id and fversion=$fversion and lang_id=$lang and authorID=$author and last=1";
		$result = mysql_query($query);
		$numresults = mysql_affected_rows();

		while ($row=mysql_fetch_assoc($result))
		{
			$entry = $row['entryID'];
			$seq = $row['edited_seq'];
			$version = $row['version'];
			
			if (!$notoriginal)
			{
				$query = "delete from subs where entryID=$entry";
				mysql_query($query);
			}
			
			if ($version>0)
			{
				if ($notoriginal)
				{
					$query = "delete from subs where entryID=$entry";
					mysql_query($query);
				}
				$minver = $version -1;
				$query = "update subs set last=1 where subID=$id and fversion=$fversion and lang_id=$lang and edited_seq=$seq and version=$minver";
				mysql_query($query);
			}
		}
	}
	
	
	if ((bd_getOriginalLang($id, $fversion)!=$lang) && (!bd_isMerged($id, $fversion, $lang)))
	{
		tn_check($id, $fversion, bd_getOriginalLang($id, $fversion), $lang);
		bd_confirmTranslated($id, $fversion, $lang);
	}
	
	$authorName = bd_getUsername($authorName);
	log_insert(LOG_troll, "User $authorname", $_SESSION['userID'], $id, bd_userIsModerador());	
	location("/antitroll.php?id=$id&fversion=$fversion&lang=$lang");
	bbdd_close();
	
?>