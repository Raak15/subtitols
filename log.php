<?php
	include('includes/includes.php');
	
	$mode = $_GET['mode'];
	if (!isset($mode))
	{
		bbdd_close();
		exit();
	}
	$page = $_GET['page'];
	if (!isset($page)) $page = 1;
	$max = $_GET['max'];
	if (!isset($max)) $max=50;
	
	
	if ($mode=="episodes")
		$pagetitle =  $wikilang_last_uploaded_episodes;
		elseif($mode=="movies")
			$pagetitle =  $wikilang_last_uplodaded_moviesç;
			elseif($mode=="translated")
				$pagetitle =  $wikilang_last_translated;
				elseif($mode=="versions")
					$pagetitle = $wikilang_last_fversion; 
					elseif ($mode=="downloaded")
					$pagetitle = $wikilang_most_downloaded;
					elseif ($mode=="news")
						$pagetitle = $wikilang_site_news;

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Subtítols – <?php echo "$pagetitle - wikisubtitles"; ?></title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
	<script type="text/javascript">
		<?php include('js/quicksearch.php'); ?>
	</script>
	<style type="text/css" media="screen">
		@import url(/css/main.css);
	</style>
	<script type="text/javascript">
		function loadShow(show, season)
		{
			$("episodes").innerHTML = '<img src="/images/loader.gif" />';
			new Ajax('<?php echo $SCRIPT_PATH; ?>ajax_loadShow.php?show='+show+'&season='+season, {
				method: 'get',
				update: $("episodes")	
			}).request();
		}
	</script>
</head>

<body>

<?php include('header.php'); ?>

<?php include("includes/general/nav_home.php"); ?>

<div id="sitebody">
	
	<div id="slogan">

		<h2><?php echo $pagetitle; ?></h2>
		
	</div>
	
<div id="content-listados">

	<table class="table-universal">
		<theah>
		<tr>
			<td>&nbsp;</td>
			<td><?php echo $wikilang_title; ?></td>
			<td><?php echo $wikilang_info; ?></td>
			<td><?php echo $wikilang_date_date; ?></td>
		</tr>
		</theah>
		<tbody>
<?php
	if ($mode=="episodes")
		$query = "select subID,author,title,end_date from files where finished=1 and temp=0 and is_episode=1 order by end_date DESC";
		elseif($mode=="movies")
			$query = "select subID,author,title,end_date from files where finished=1 and temp=0 and is_episode=0 order by end_date DESC";
			elseif($mode=="translated")
				$query = "select subID,lang_id,date from lasttranslated order by id DESC";
				elseif($mode=="versions")
					$query = "select subID,author,fversion,versionDesc,size,indate from fversions where fversion>0 order by entryID DESC";
					elseif($mode=="downloaded")
					$query = "select subID,title,end_date,downloads from files where finished=1 and temp=0 order by downloads DESC";
					elseif ($mode=="news")
					$query = "select * from news order by date DESC";
	
	$offset = ($page-1) * $max;
	
	if ($offset<1) $offset=0;
	
	$query .= " limit $offset,$max";
	$result = mysql_query($query);
	$numresults = mysql_affected_rows();
	
	while ($row=mysql_fetch_assoc($result))
	{
		$subID = $row['subID'];
		if ($mode=="episodes")
		{
			$title = stripslashes($row['title']);
			$date = $row['end_date'];
			$info = bd_getLangName(bd_getOriginalLang($subID, 0));
		}
		elseif($mode=="movies")
			{
				$title = stripslashes($row['title']);
				$date = $row['end_date'];
				$info = bd_getLangName(bd_getOriginalLang($subID, 0));
			}
			elseif ($mode=="downloaded")
			{
				$title = stripslashes($row['title']);
				$date = $row['end_date'];
				$info = $row['downloads'].' '.$wikilang_downloads;
			}
			elseif($mode=="translated")
			{
				$title = bd_getTitle($row['subID']);
				$date = $row['date'];
				$info = bd_getLangName($row['lang_id']);
			}
			elseif($mode=="versions")
			{
				$title = bd_getTitle($row['subID']);
				$date = $row['indate'];
				$info = bd_getFVersion($subID,$row['fversion']).', '.bd_getFVersionSize($subID, $row['fversion']);
			}
			elseif ($mode=="news")
			{
				$title = stripslashes($row['text']);
				$date = $row['date'];
				$info = '';
			}
		
		echo '<tr>';
			echo '<td>';
				if ($mode!="news") echo bd_getIcon($subID);
			echo '</td>';
	
			echo "<td>";
			if ($mode!="news") echo "<big><a href=\"".bd_getUrl($subID)."\">";
			echo $title;
			if ($mode!="news") echo '</a></big>';
			echo "</td>";
			echo "<td><big>$info</big></td>";
			echo "<td><big>".obtenerFecha($date)."</big></td>";
			
		echo '</tr>';	
	}
	
	echo '<tr><td colspan=4></td></tr>';
	
	echo '<tr>';
		echo '<td>';
			if ($page!=1)
			{
				$ant = $page - 1;
				echo '<img src="images/arrow_left.png" border="0" with="16" heigth="16" />  <a href="/log.php?mode='.$mode.'&page='.$ant.'&max='.$max.'">';
				echo '<b>'.$wikilang_prev_page.'</b></a>';
			}
		echo '</td>';
		
		echo '<td colspan="2"></td>';
		echo '<td>';
		if ($numresults>0)
		{
			$next = $page +1;
			echo '<a href="/log.php?mode='.$mode.'&page='.$next.'&max='.$max.'">';
				echo '<b>'.$wikilang_next_page.'</b></a> <img src="images/arrow_right.png" border="0" with="16" heigth="16" />';
		}
		echo '</td>';
	echo '</tr>';
		
		
		
?>

</table>

<?php
	include('footer.php');
	bbdd_close();
?>
</body>
</html>