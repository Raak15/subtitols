<?php
	include('includes/includes.php');
	
	$showID = $_GET['show'];
	$season = $_GET['season'];
	
	$query = "select subID,title from files where is_episode=1 and showID=$showID and season=$season order by season_number";
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_assoc($result))
	{
		$epID = $row['subID'];
		$title = stripslashes($row['title']);

?>


<table width="80%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="17%">&nbsp;</td>
    <td width="1%" class="NewsTitle">&nbsp;</td>
    <td colspan="5" class="NewsTitle"><img src="/images/package.png" /> &nbsp;
<?php
	$url = '/'.bd_getUrl($epID);
	echo "<a href='$url'>$title</a>";
?>
    </td>
  </tr>
<?php
	$vquery = "select distinct(fversion),versionDesc from fversions where subID=$epID order by fversion";
		$vresult = mysql_query($vquery);
		
		while ($vrow = mysql_fetch_assoc($vresult))
		{
			$cfversion = $vrow['fversion'];
			$versionDesc = $vrow['versionDesc'];
			
?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="1%" class="newsClaro">&nbsp;</td>
    <td colspan="3" class="newsClaro"><img src="/images/folder_page.png" width="16" height="16" /> 
<?php    echo "$wikilang_version $versionDesc, ".bd_getFVersionSize($epID, $cfversion)." Mbs"; ?>
    </td>
    <td width="19%">&nbsp;</td>
  </tr>
<?php

			$lquery = "select distinct(lang_id) from flangs where subID=$epID and fversion=$cfversion order by original DESC";
			$lresult = mysql_query($lquery);
			
			while ($lrow=mysql_fetch_assoc($lresult))
			{
				$lang = $lrow['lang_id'];
				$langName = bd_getLangName($lang);
			
?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="4%"><img src="/images/invisible.gif" width="18" height="11" /></td>
    <td width="41%" class="language">
<?php echo $langName; ?>
    </td>
    <td width="17%">
<?php echo bd_getLangState($epID, $lang, $cfversion); ?>
    </td>
    <td><img src="/images/download.png" width=16" height="16" />
<?php echo '<a href="/updated/'.$lang.'/'.$epID.'/'.$cfversion.'">'.$wikilang_download; ?></a>
    </td>
  </tr>
<?php
			}
	$query = "select linkID,versionDesc,versionSize from links where subID=$epID and fversion=$cfversion";
	$mresult = mysql_query($query);
	$lnum = mysql_affected_rows();
	
	if ($lnum>0)
	{
?>
	<tr>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3" class="SectionTitle"><img src="/images/link.png" width="16" height="16" /> 
<?php echo $wikilang_linked_versions; ?>
   </td>
  </tr>
<?php
	
	while ($mrow = mysql_fetch_assoc($mresult))
	{
	
	
?>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="newsClaro"><img src="/images/folder_page.png" width="16" height="16" />
<?php
	echo $mrow['versionDesc'].', ';
	echo $mrow['versionSize'].' MBs';
?>
    </td>
    <td colspan="2">&nbsp;</td>
  </tr>
<?php
	$lquery = "select distinct(lang_id) from flangs where subID=$epID and fversion=$cfversion order by original DESC";
	$llresult = mysql_query($lquery);
	

	while ($llrow = mysql_fetch_assoc($llresult))
	{
		
?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="language">
<?php 
	echo bd_getLangName($llrow['lang_id']);
?>
    </td>
    <td><img src="/images/download.png" width="16&quot;" height="16" />&nbsp;
<?php echo '<a href="/downloadlink.php?linkID='.$lrow['linkID'].'&lang='.$llrow['lang_id'].'">';    ?>
    Download</a>
    </td>
  </tr>
<?
	}
	}
	}
		}
?>
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
</table>
<?php
	}
	
	bbdd_close();
?>
