<?php include_once('includes/includes.php'); ?>
<?
setlocale(LC_ALL, 'es-ES');
bindtextdomain('messages', './locales/');
textdomain('messages');
?>	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Subtítols – Your favourite TV Show, movies subtitles made by the people <?= _("hello") ?></title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<script type="text/javascript" src="js/mootools.v1.11.js"></script>
	<script type="text/javascript">
		<?php include('js/quicksearch.php'); ?>
	</script>
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
		<h2><a href="/shows.php"><?php echo $wikilang_explore_by_show ; ?></a> or <a href="/log.php?mode=downloaded"><?php echo $wikilang_most_downloaded; ?></a><br /><?php
			$query = "select sum(downloads) from files";
			$result = mysql_query($query);
			$dtotal = number_format(mysql_result($result, 0),0,',','.');
			$utotal = bd_totalUsers();
			$ftotal = bd_totalFiles();
			echo "<strong>$dtotal</strong> $wikilang_downloaded_subtitles · <strong>$utotal</strong> $wikilang_users · <strong>$ftotal</strong> $wikilang_completed_files</dd>";
			echo "</dl>\n";
		?></h2>
	</div>

<?php
	if (isset($_SESSION['userID']))
	{
    	echo '&middot; <a href="'.$SCRIPT_PATH.'admin_log.php">'.$wikilang_activity_log.'</a> ';
    	$query = "select count(*) from downloads where userID=".$_SESSION['userID'];
    	$result = mysql_query($query);
    	$dcount = mysql_result($result, 0);
    	echo ' &middot; <a href="'.$SCRIPT_PATH.'mydownloads.php">';
		echo $wikilang_my_downloads; 
    	echo '('.$dcount.')</a>';
	}
	
?>

<div id="content-listados">
	
<!-- empieza el content -->

<table class="table-universal">
	<caption><?php echo $wikilang_last_uploaded_episodes; ?></caption>
	<tbody>
		<?php

			$lastcompleted_query = "select subID,author,title from files where finished=1 and temp=0 and is_episode=1 order by end_date DESC limit $MAX_CATEGORY";
			$lastcompleted_result = mysql_query($lastcompleted_query);
			$lastcompleted_numresult = mysql_affected_rows();

			for ($c=0; ($c<$MAX_CATEGORY/3) && ($row=mysql_fetch_assoc($lastcompleted_result)); $c++)
			{
				$id1 = $row['subID'];
				$titulo1 = stripslashes($row['title']);
				$autor1 = $row['author'];
				$autorName1 = bd_getUsername($autor1);
				$lang1 = bd_getOriginalLang($id1, 0);
				$langName1 = bd_getLangName($lang1);
				$url1 = bd_getUrl($id1);

				if ($row=mysql_fetch_assoc($lastcompleted_result))
				{
					$id2 = $row['subID'];
					$titulo2 = stripslashes($row['title']);
					$autor2 = $row['author'];
					$autorName2 = bd_getUsername($autor2);
					$lang2 = bd_getOriginalLang($id2, 0);
					$langName2 = bd_getLangName($lang2);
					$url2 = bd_getUrl($id2);

				}

				if ($row=mysql_fetch_assoc($lastcompleted_result))
				{
					$id3 = $row['subID'];
					$titulo3 = stripslashes($row['title']);
					$autor3 = $row['author'];
					$autorName3 = bd_getUsername($autor3);
					$lang3 = bd_getOriginalLang($id3, 0);
					$langName3 = bd_getLangName($lang3);
					$url3 = bd_getUrl($id3);

				}

				echo '<tr>
						<td><em class="ico itv" title="This is a TV Show subtitle">TV Show</em></td>
						<td><big><a href="'.$url1.'">'.$titulo1.' </a></big>
						<small>'.$langName1.' · '.$wikilang_uplodaded_by.' <strong><a href="'.$SCRIPT_PATH.'user/'.$autor1.'">'.$autorName1.'</a></strong></td>
					<tr>
					<tr>
						<td><em class="ico itv" title="This is a TV Show subtitle">TV Show</em></td>
						<td><big><a href="'.$url2.'">'.$titulo2.'</a></big><small>'.$langName2.' · '.$wikilang_uplodaded_by.' <strong><a href="'.$SCRIPT_PATH.'user/'.$autor2.'">'.$autorName2.'</a></strong></small></td>
					</tr>
					<tr>
						<td><em class="ico itv" title="This is a TV Show subtitle">TV Show</em></td>
		    			<td><big><a href="'.$url3.'">'.$titulo3.'</a></big><small>'.$langName3.' · '.$wikilang_uplodaded_by.' <strong><a href="'.$SCRIPT_PATH.'user/'.$autor3.'">'.$autorName3.'</a></strong></small></td>
		  			</tr>';	
			}
		?>
	</tbody>
</table>

<div class="list-footer"></div>

<table class="table-universal">
	<caption><?php echo $wikilang_last_uplodaded_movies; ?></caption>
	<tbody>
		<?php
		$lastcompleted_query = "select subID,author,title from files where finished=1 and is_episode=0 and temp=0 order by end_date DESC limit $MAX_CATEGORY";
		$lastcompleted_result = mysql_query($lastcompleted_query);
		$lastcompleted_numresult = mysql_affected_rows();
	
		for ($c=0; ($c<$MAX_CATEGORY/3) && ($row=mysql_fetch_assoc($lastcompleted_result)); $c++)
		{
		$id1 = $row['subID'];
		$titulo1 = stripslashes($row['title']);
		$autor1 = $row['author'];
		$autorName1 = bd_getUsername($autor1);
		$lang1 = bd_getOriginalLang($id1, 0);
		$langName1 = bd_getLangName($lang1);
		$url1 = bd_getUrl($id1);
		
		if ($row=mysql_fetch_assoc($lastcompleted_result))
		{
			$id2 = $row['subID'];
			$titulo2 = stripslashes($row['title']);
			$autor2 = $row['author'];
			$autorName2 = bd_getUsername($autor2);
			$lang2 = bd_getOriginalLang($id2, 0);
			$langName2 = bd_getLangName($lang2);
			$url2 = bd_getUrl($id2);
			
		}
		
		if ($row=mysql_fetch_assoc($lastcompleted_result))
		{
			$id3 = $row['subID'];
			$titulo3 = stripslashes($row['title']);
			$autor3 = $row['author'];
			$autorName3 = bd_getUsername($autor3);
			$lang3 = bd_getOriginalLang($id3, 0);
			$langName3 = bd_getLangName($lang3);
			$url3 = bd_getUrl($id3);
			
		}
		
		echo '<tr>
   			 	<td><em class="ico imovie">Movie</td>
				<td><big><a href="'.$url1.'">'.$titulo1.'</a></big> <small>'.$langName1.' &middot; '.$wikilang_uplodaded_by.' <strong><a href="'.$SCRIPT_PATH.'user/'.$autor1.'">'.$autorName1.'</a></strong></small></td>
			</tr>
			<tr>
	   		 	<td><em class="ico imovie">Movie</td>
				<td><big><a href="'.$url2.'">'.$titulo2.'</a></big> <small>'.$langName2.' &middot; '.$wikilang_uplodaded_by.' <strong><a href="'.$SCRIPT_PATH.'user/'.$autor2.'">'.$autorName2.'</a></strong></small></td>
			</tr>
			<tr>
		   		<td><em class="ico imovie">Movie</td>
				<td><big><a href="'.$url3.'">'.$titulo3.'</a></big> <small>'.$langName3.' &middot; '.$wikilang_uplodaded_by.' <strong><a href="'.$SCRIPT_PATH.'user/'.$autor3.'">'.$autorName3.'</a></strong></small></td>
			</tr>';	
	}
?>
	</tbody>
</table>

<div class="list-footer"></div>

<table class="table-universal">
	<caption><?php echo $wikilang_last_translated;?></caption>
	<tbody>
		<?php
			$lastedited_query="select subID,lang_id from lasttranslated order by date  DESC limit $MAX_CATEGORY";
			$lastedited_result = mysql_query($lastedited_query);
			$lastedited_numresult = mysql_affected_rows();

			for ($c=0; ($c<$MAX_CATEGORY/3) && ($row=mysql_fetch_assoc($lastedited_result)); $c++)
			{
				$id1 = $row['subID'];
				$titulo1 = bd_getTitle($id1);
				$lang1 = $row['lang_id'];
				$langName1 = bd_getLangName($lang1);
				$url1 = bd_getUrl($id1);
				$image1= bd_getIcon($id1);

				if ($row=mysql_fetch_assoc($lastedited_result))
				{
					$id2 = $row['subID'];
					$titulo2 = bd_getTitle($id2);
					$lang2 = $row['lang_id'];
					$langName2 = bd_getLangName($lang2);
					$url2 = bd_getUrl($id2);	
					$image2= bd_getIcon($id2);					
				}

				if ($row=mysql_fetch_assoc($lastedited_result))
				{
					$id3 = $row['subID'];
					$titulo3 = bd_getTitle($id3);
					$lang3 = $row['lang_id'];
					$langName3 = bd_getLangName($lang3);
					$url3 = bd_getUrl($id3);
					$image3= bd_getIcon($id3);

				}

				echo '<tr>
		   			 	<td>'.$image1.'</td>
						<td><big><a href="'.$url1.'">'.$titulo1.'</a></big><small>'.$langName1.'</small></td>
					</tr>
					<tr>
						<td>'.$image2.'</td>
						<td><big><a href="'.$url2.'">'.$titulo2.'</a></big><small>'.$langName2.'</small></td>
		    		</tr>
		    		<tr>
						<td>'.$image3.'</td>
						<td><big><a href="'.$url3.'">'.$titulo3.'</a></big><small>'.$langName3.'</small></td>
		    		</tr>';	
			}
		?>
		</tbody>
	</table>
	<div class="list-footer">
	</div>
				
	<table class="table-universal">
		<caption><?php echo $wikilang_last_fversion;?></caption>
		<tbody>
		<?php
			$lastversion_query="select subID,versionDesc,author from fversions where fversion>0 order by entryID DESC limit $MAX_CATEGORY";
			$lastversion_result = mysql_query($lastversion_query);
			$lastversion_numresult = mysql_affected_rows();

			for ($c=0; ($c<$MAX_CATEGORY/3) && ($row=mysql_fetch_assoc($lastversion_result)); $c++)
			{
				$id1 = $row['subID'];
				$titulo1 = bd_getTitle($id1);
				$version1 = $row['versionDesc'];
				$author1= $row['author'];
				$authorName1 = bd_getUsername($author1);
				$url1 = bd_getUrl($id1);
				$image1= bd_getIcon($id1);

				if ($row=mysql_fetch_assoc($lastversion_result))
				{
					$id2 = $row['subID'];
					$titulo2 = bd_getTitle($id2);
					$version2 = $row['versionDesc'];
					$author2= $row['author'];
					$authorName2 = bd_getUsername($author2);
					$url2 = bd_getUrl($id2);
					$image2= bd_getIcon($id2);

				}

				if ($row=mysql_fetch_assoc($lastversion_result))
				{
					$id3 = $row['subID'];
					$titulo3 = bd_getTitle($id3);
					$version3 = $row['versionDesc'];
					$author3= $row['author'];
					$authorName3 = bd_getUsername($author3);
					$url3 = bd_getUrl($id3);
					$image3= bd_getIcon($id3);

				}

				echo '<tr>
		   			 	<td>'.$image1.'</td>
						<td><big><a href="'.$url1.'">'.$titulo1.'</a></big><small>'.$version1.' · '.$wikilang_uplodaded_by.' <strong><a href="/user/'.$author1.'">'.$authorName1.'</a></strong></small></td>
					</tr>
					<tr>
						<td>'.$image2.'</td>
						<td><big><a href="'.$url2.'">'.$titulo2.'</a></big><small>'.$version2.' · '.$wikilang_uplodaded_by.' <strong><a href="/user/'.$author2.'">'.$authorName2.'</a></strong></small></td>
					</tr>
					<tr>
						<td>'.$image3.'</td>
						<td><big><a href="'.$url3.'">'.$titulo3.'</a></big><small>'.$version3.' · '.$wikilang_uplodaded_by.' <strong><a href="/user/'.$author3.'">'.$authorName3.'</a></strong></small></td>
		  			</tr>';	
			}
		?>
		</tbody>
	</table>
	<div class="list-footer"></div>
	
	
	<table class="table-universal">
		<caption><?php echo $wikilang_last_edited; ?></caption>
		<tbody>

	<?php
		$lastedited_query="select distinct(subID) from subs where version>0 order by entryID DESC limit $MAX_CATEGORY";
		$lastedited_result = mysql_query($lastedited_query);
		$lastedited_numresult = mysql_affected_rows();

		for ($c=0; ($c<$MAX_CATEGORY/3) && ($row=mysql_fetch_assoc($lastedited_result)); $c++)
		{
			$id1 = $row['subID'];
			$titulo1 = bd_getTitle($id1);
			$mquery = "select authorID from subs where subID=$id1 and version>0 order by entryID DESC limit 1";
			$mresult = mysql_query($mquery);
			$author1= mysql_result($mresult,0);
			$authorName1 = bd_getUsername($author1);
			$url1 = bd_getUrl($id1);
			$image1= bd_getIcon($id1);

			if ($row=mysql_fetch_assoc($lastedited_result))
			{
				$id2 = $row['subID'];
				$titulo2 = bd_getTitle($id2);
				$mquery = "select authorID from subs where subID=$id2 and version>0 order by entryID DESC limit 1";
				$mresult = mysql_query($mquery);
				$author2= mysql_result($mresult,0);
				$authorName2 = bd_getUsername($author2);
				$url2 = bd_getUrl($id2);
				$image2= bd_getIcon($id2);

			}

			if ($row=mysql_fetch_assoc($lastedited_result))
			{
				$id3 = $row['subID'];
				$titulo3 = bd_getTitle($id3);
				$mquery = "select authorID from subs where subID=$id3 and version>0 order by entryID DESC limit 1";
				$mresult = mysql_query($mquery);
				$author3= mysql_result($mresult,0);
				$authorName3 = bd_getUsername($author3);
				$url3 = bd_getUrl($id3);
				$image3= bd_getIcon($id3);

			}

			echo '<tr>
	    			<td>'.$image1.'</td>
					<td><big><a href="'.$url1.'">'.$titulo1.'</a></big> <small>'.$wikilang_edited_by.' <strong><a href="/user/'.$author1.'">'.$authorName1.'</a></strong></small></td>
				</tr>
				<tr>
		    		<td>'.$image2.'</td>
					<td><big><a href="'.$url2.'">'.$titulo2.'</a></big> <small>'.$wikilang_edited_by.' <strong><a href="/user/'.$author2.'">'.$authorName2.'</a></strong></small></td>
				</tr>
	    		<tr>
		    		<td>'.$image3.'</td>
					<td><big><a href="'.$url3.'">'.$titulo3.' </a></big> <small>'.$wikilang_edited_by.' <strong><a href="/user/'.$author3.'">'.$authorName3.'</a></strong></small></td>
				</tr>';	
		}
	?>
		</tbody>
	</table>
	<div class="list-footer"></div>
	
	
	
	

	
	
	

<!-- termina content -->

</div>

<div id="menus">
	
	
	
	<div id="top-list">

				<h2><?php echo $wikilang_most_today;?></h2>

				<table border="0">
					<?php
						$lastedited_query="select count(*) as cuenta,subID from downloads where TO_DAYS(cuando)=TO_DAYS(CURDATE()) group by subID order by cuenta DESC limit $MAX_CATEGORY";
						$lastedited_result = mysql_query($lastedited_query);
						$lastedited_numresult = mysql_affected_rows();


						for ($c=0; ($c<$MAX_CATEGORY/3) && ($row=mysql_fetch_assoc($lastedited_result)); $c++)
						{

							$id1 = $row['subID'];
							$titulo1 = bd_getTitle($id1);
							$url1 = bd_getUrl($id1);
							$image1= bd_getIcon($id1);
							$downs1 = $row['cuenta'];

							if ($row=mysql_fetch_assoc($lastedited_result))
							{
								$id2 = $row['subID'];
								$titulo2 = bd_getTitle($id2);
								$url2 = bd_getUrl($id2);
								$image2= bd_getIcon($id2);
								$downs2 = $row['cuenta'];

							}

							if ($row=mysql_fetch_assoc($lastedited_result))
							{
								$id3 = $row['subID'];
								$titulo3 = bd_getTitle($id3);
								$url3 = bd_getUrl($id3);
								$image3= bd_getIcon($id3);
								$downs3 = $row['cuenta'];

							}

							echo '<tr>
									<th><a href="'.$url1.'">'.$titulo1.' </a></th>
									<td><strong>'.$downs1.'</strong></td>
								</tr>
					   			 <tr>
									<th><a href="'.$url2.'">'.$titulo2.' </a></th>
									<td><strong>'.$downs2.'</strong></td>
								</tr>
								<tr>
									<th><a href="'.$url3.'">'.$titulo3.' </a></th>
									<td><strong>'.$downs3.'</strong></td>
								</tr>';
						}
					?>
				</table>

				<div class="cite">

					<p>128.002 subs downloads this week, yay!</p>

				</div>
	
		<div class="clear"></div>
		
	</div>	
	
	
	
	
	
	
	
	
	<table width="70%" border="1"  align="center">
	<tr>
		<td colspan="2" class="NewsTitle">
		<?php echo $wikilang_site_news; ?>
		</td>
	</tr>
	<?php
		$query = "select * from news order by id DESC limit 3";
		$result =mysql_query($query);
		while ($row=mysql_fetch_assoc($result))
		{
			$date = obtenerFecha($row['date']);
			$text = stripslashes($row['text']);
			echo '<tr><td class="newsDate" width="20%">'.$date.'</td>';
			echo '<td><img src="images/newspaper.png" /> '.$text.'</td></tr>';
		}
	?>

	</table>
	
</div>

<div class="clear"></div>

</div>

	<?php include('footer_index.php'); bbdd_close(); ?>

</body>
</html>