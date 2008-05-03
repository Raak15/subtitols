<?php
	include('includes/includes.php');
	include('includes/showsub_header.php');
	include('translate_fns.php');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo "Subtitols - $wikilang_subtitles_for $title"; ?></title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
	<script type="text/javascript"><?php include('/js/quicksearch.php'); ?></script>
	<script type="text/javascript"><?php include('/js/showsub.php'); ?></script>
	<style type="text/css" media="screen">
		@import url(/css/main.css);
	</style>
</head>

<body>
	
	<?php include("includes/general/moderator_bar.php"); ?>
	
	<?php include("header.php"); ?>
	
	<?php include("includes/general/nav_home.php"); ?>
	
	<?php include("includes/general/subnav_search.php"); ?>

	<div id="sitebody">

		<div id="slogan">

			<h2><?php echo $title; ?> <small><?php
    if ($is_episode){
 	echo "<a href=\"$SCRIPT_PATH"."show/$showID\">$show</a>, <a href='$SCRIPT_PATH"."season/$showID/$season'>$wikilang_season $season</a>, $wikilang_episode $epnumber"; 
    }
?>
<?php 
	if (($authorID == $_SESSION['userID']) || bd_userIsModerador())
    	echo ' <a href="'.$SCRIPT_PATH.'editprop.php?id='.$myID.'">'.$wikilang_edit_properties.'</a> · <a href="'.$SCRIPT_PATH.'delsub.php?id='.$myID.'">'.$wikilang_delete.'</a>';
?>
	  
<?php
	echo $duration;
?>
<?php echo $downloads; ?>
   </small></h2>

	</div>

<?php
	for ($contav=0; $contav<$nversions; $contav++)
	{
		$versionname = $version[$contav]['name'];
		$v = $version[$contav]['num'];
		$vauthor = $version[$contav]['authorID'];
		$vauthorName = $version[$contav]['authorName'];
		$size = $version[$contav]['size'];
		$vcomment = $version[$contav]['comment'];
		$voriginalLang = bd_getOriginalLang($myID, $contav);
		$vdowns = $version[$contav]['downloads'];
		
		if (!isset($mylastv)) $mylastv = $v;
?>

<div id="content-listados">

	<div class="sub-profile">
		
		<table class="table-universal traducciones">
			<caption><?php echo "$wikilang_version $versionname, $size MBs";
				echo "<em>by <a href='/user/$vauthor'>$vauthorName</a>";
				echo "".obtenerFecha($version[$contav]['date']);
				echo "</em>";
				echo '<small><a href="/starttranslation.php?id='.$myID.'&fversion='.$v.'">'.$wikilang_new_translation.'</a> ';
				echo "<a href=\"/linkupload.php?id=$myID&fversion=$v\">$wikilang_link_version</a>";
			?> 	<?php 
					if (($vauthor == $_SESSION['userID']) || bd_userIsModerador())
					{
				    	echo '<a href="/delsub.php?id='.$myID.'&fversion='.$v.'">Delete</a> ';
				    	echo '<a href="/editprop.php?id='.$myID.'&fversion='.$v.'">Edit Properties</a>';
					}
					if (bd_userIsModerador() && ($v!=0))
					{
				    	echo '<a href="/mergev.php?id='.$myID.'&fversion='.$v.'"></a>';
					}
				?></small></caption>
			<thead>
			<tbody>
				<tr>
			  		<td colspan="6"><small class="extra"><strong>Description:</strong> <?php echo $vcomment; ?></small></td>
			  	</tr>
				<tr>
					<th colspan="2">Subtitle</th>
					<th>Sequences</th>
					<th>Status</th>
					<th>Last updated</th>
				</tr>
	<?php
  	//idiomas
		for ($contalang=0; $contalang<$version[$contav]['numlanguages']; $contalang++)
		{
			$clangID = $langs[$v][$contalang][0];
			$clangName = $langs[$v][$contalang][1];
			$clangState = $langs[$v][$contalang][2];
			$clangEdited = $langs[$v][$contalang][3];
			$clangDownloads = $langs[$v][$contalang][4];
	?>
	
  	<tr>
    	<td><em class="ico isubtitle">Subtitle</em></td>
		<td><big><?php echo $clangName; ?></big><small><?php
			if (($clangID==$voriginalLang) && ($clangEdited==0))
				echo '<a href="/original/'.$myID.'/'.$v.'">'.$wikilang_download.'</a>';
				elseif($clangID!=$voriginalLang)
					echo '<a href="/'.$clangID.'/'.$myID.'/'.$v.'">'.$wikilang_download.'</a>';
					else 
					{
						echo $wikilang_download.' · <a href="/original/'.$myID.'/'.$v.'">original</a>';
		    			echo ' · <a href="/updated/'.$clangID.'/'.$myID.'/'.$v.'">'.$wikilang_most_updated.'</a>';
					}
		?> 			<?php
						$ref = "?id=$myID&fversion=$v&lang=$clangID";
						if (isset($_SESSION['userID']))
						{
							echo " · <a href=\"javascript:notifyModerator('$v','$clangID');\">Moderate</a>";
							if ($clangID != $voriginalLang)
								echo " · <a href=\"/startest.php$ref\">Refresh</a>";
						}
						if (bd_userIsModerador())
						{
					    	echo " · <a href=\"/dellang.php$ref\">".'Delete</a>';
					    	echo " · <a href=\"/antitroll.php$ref\">".'Troll</a>';
						}
					?> 	<?php
							$secs = bd_flangsVersion0($myID, $v, $clangID);
							echo " · $clangEdited $wikilang_editions · $clangDownloads $wikilang_downloads" 
						?> 	<?php
								if ($clangState!="$wikilang_completed")
							    	echo ' · <a href="/jointranslation.php'.$ref.'">'.$wikilang_join_translation.'</a> ';

							    echo ' · <a href="/list.php'.$ref.'">'.$wikilang_view_edit.'</a>';
							?></small></td>
		<td><?php
			 $secs = bd_flangsVersion0($myID, $v, $clangID);
			echo "$secs $wikilang_sequences" 
			?></td>
		<td><?php if (($clangState==$wikilang_completed) && ($clangID!=$voriginalLang)) {
					$query = "select count(*) from lasttranslated where subID=$myID and fversion=$v and lang_id=$clangID";
					$result = mysql_query($query);
					if (mysql_result($result, 0)==0)
					{	tn_check($myID, $fversion, $voriginalLang, $clangID);
						$clangState = bd_getLangState($myID, $clangID, $version);
						}
					}
					echo $clangState; 
					?></td>
					<td>
						<?php 
	if ($clangEdited>0)
		echo "$wikilang_edited ".obtenerFecha(bd_getLastTimeEdited($myID,$v,$clangID)); 
?>
    </td>
  </tr>
 <?php
 	$query = "select count(*) from lasttranslated where subID=$myID and fversion=$v and lang_id=$clangID";
    $cresult = mysql_query($query);
    
    if ((mysql_result($cresult, 0)>0) && (!bd_isMerged($myID,$fversion, $clangID)))
    {
?>
  <tr>
    <td>lel&nbsp;</td>
    <td><span class="newsDate">
    <?php echo $wikilang_translated_by;?>
    </span></td>
    <td colspan="5" class="newsDate">
<?php
		$query = "select distinct(authorID) from subs where subID=$myID and fversion=$v and lang_id=$clangID";
    	$result = mysql_query($query);
    	while ($urow = mysql_fetch_assoc($result))
    	{
    		echo '<a href="/user/'.$urow['authorID'].'" >';
    		echo bd_getUsername($urow['authorID']);
    		echo '</a> ';
    		$userlines = bd_countLastLinesByUserSub($myID, $clangID, $v, $urow['authorID']);
    		$percent = ($userlines / $secs)*100;
    		$percent = number_format($percent, 1);
    		echo "($percent %), ";
    		
    	}
?>
    </td>
  </tr>
<?php
    }
?>

<?php
	}
?>

<?php
//LINKED VERSIONS
	$query = "select linkID,author,downloads,versionDESC,versionSize from links where subID=$myID and fversion=$v and enabled=1";
	$result = mysql_query($query);
	$linkcount = mysql_affected_rows();
	
	if ($linkcount>0)
	{
?>
  <tr>
    <td colspan="6"><small class="extra"><strong><?php echo $wikilang_linked_versions;?></strong>
<?php
  while ($linkrow = mysql_fetch_assoc($result))
		{
?> <?php echo $linkrow['versionDESC'].', '.$linkrow['versionSize'].' MBs';?>
    ·
<?php echo "$wikilang_uplodaded_by <a href='/user/".$linkrow['author']."'>".bd_getUsername($linkrow['author']).'</a>'; ?>
    
<?php
    echo $linkrow['downloads']." $wikilang_downloads ";
    if (bd_userIsModerador())
    	echo "<a href='/dellink.php?linkid=".$linkrow['linkID']."'>".'Delete</a>'
    
?>
<?php
$query = "select distinct(lang_id) from flangs where subID=$myID and fversion=$v";
			$lresult = mysql_query($query);
			while ($llrow=mysql_fetch_assoc($lresult))
			{
				$llid = $llrow['lang_id'];
				$llname = bd_getLangName($llid);
				$llstate = bd_getLangState($myID, $llid,$v);
?> <?php echo $llname; ?>
<?php
	echo '<a href="'.$SCRIPT_PATH.'downloadlink.php?linkID='.$linkrow['linkID'].'&lang='.$llid.'">';
	echo $wikilang_download;
?>
    </a>
    </td>
  </tr>
<?php
			}//linklang
?>

<?php
			
	}//links
}//if link
	
	?>
</table>

</div>

</div>

<?php
	}//version
?>




<div id="menus">

</div>

	<div class="clear"></div>
</div>

<div align="center">
<?php
	if (isset($_SESSION['userID']))
	{
		echo '&nbsp;<form name="version" action="/newversion.php" method="post"><input type="submit" value="'.$wikilang_upload_new_version.'" class="coolBoton"/>';
		hidden("id", $myID);
		echo '</form>';
	}
?>
</div>
<p>&nbsp;</p>

<p>&nbsp;	</p>
<span id="comments"></span>
<p>&nbsp;</p>

<?php 
	include('footer.php');
	bbdd_close(); 
?>
</body>

</html>
