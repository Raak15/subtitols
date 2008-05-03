<?php
	include('includes/includes.php');;
	
	$showID=$_GET['showid'];
	$showName=bd_getShowTitle($showID);
	$season = $_GET['season'];
	
	$totalseasons = bd_countSeasonsShow($showID);
	$totaleps = bd_countEpisodesShow($showID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Subtítols – <?php echo "$showName - wikisubtitles"; ?></title>
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
			new Ajax('/ajax_loadShow.php?show='+show+'&season='+season, {
				method: 'get',
				update: $("episodes")	
			}).request();
		}
	</script>
</head>

<?php
	$query = "select min(season) from files where showID=$showID";
	$result = mysql_query($query);
	$min = mysql_result($result, 0);
	
	if (!isset($season) )
		$fseason = $min;
		else 
		$fseason = $season;
	echo "<body onload=\"loadShow($showID,$fseason);\">";
?>

<?php include("header.php"); ?>

<?php include("includes/general/nav_home.php"); ?>

<div id="sitebody">
	
	<div id="slogan">
		
		<h2><a href="/shows.php"><?php echo $wikilang_explore_by_show; ?></a> / <?php echo $showName; ?> (<?php echo " $totalseasons $wikilang_seasons "; ?> · <?php echo " $totaleps $wikilang_episodes" ?>)</h2>
		
	</div>

	<span id="episodes"></span>
	
<table width="40%" border="1">
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" class="titulo">

    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="titulo">&nbsp;</td>
    <td><span class="titulo">
<?php
	echo $wikilang_season.' &nbsp;';
    $query = "select distinct(season) from files where is_episode=1 and showID=$showID order by season";
	$sresult = mysql_query($query);
	
	for ($c=0; $c<$totalseasons; $c++)
	{
		$myseason = mysql_result($sresult, $c, 0);
		
		echo "<a href=\"javascript:loadShow($showID,$myseason)\">$myseason</a> ";
	}
?>
    </span></td>
  </tr>
</table>


<?php
	include('footer.php');
	bbdd_close();
?>
</body>
</html>
