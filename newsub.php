<?php include('includes/includes.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Subtítols – <?= _("Upload a new subtitle")?></title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<script type="text/javascript" src="js/mootools.v1.11.js"></script>
	<script type="text/javascript">
		<?php include('js/quicksearch.php'); ?>
		<?php include('js/title_change.php'); ?>
	</script>
	<style type="text/css" media="screen">
		@import url(/css/main.css);
	</style>
	
</head>

<body>

<?php include("header.php"); ?>

<?php include("includes/general/nav_home.php"); ?>

<?php include("includes/general/subnav_search.php"); ?>

<div id="sitebody">
	
	<div id="slogan">
		
		<h2><?= _("Upload a new subtitle"); ?></h2>
	
	</div>
	
	<div id="sub-nav">
		<ul>
			<li class="active"><span><?= _("TV Shows"); ?></span></li>
			<li><a href="/upload-movie.php"><?= _("Movies"); ?></a></li>
			<li><a href="/upload-screencast.php"><?= _("Screencasts"); ?></a></li>
		</ul>
	</div>

	<div id="content-listados">
		
		<div id="formulario">
			
			<form action="uploadnewsub.php" method="post" enctype="multipart/form-data" name="step1" id="step1" onsubmit="return check();">

				<div id="gentitle"><?php echo $wikilang_title_on_wikisubtitles; ?></div>
							
					<input name="type" type="hidden" value="ep" checked="checked" id="epop" />
		
					<div class="grilla1">
						<label for="show"><?= _("Select a TV Show"); ?></label>
						<select name="showID" id="show" onchange="titlechange();checkEp();">	
						<?php
						$query = "select * from shows order by title";
						$result = mysql_query($query);
						while ($row=mysql_fetch_assoc($result))
							{
								$sID= $row['showID'];
								$title = stripslashes($row['title']);
								echo "<option value=\"$sID\">$title</option>";
							}
							?>
			        </select>
			
					<span id="newshow"><a href="javascript:newShow();"><?= _("Add a new TV Show"); ?></a></span>
					
				</div>
				
				<div class="grilla1">
					<label><?= _("Season"); ?></label>
					<input name="season" id="season" type="text" size="3" maxlength="2" onkeyup="titlechange();checkEp();" />
				</div>
				
				<div class="msgerror"><span id="epexists"></span></div>
				
				<div class="grilla1">
					<label><?= _("Episode"); ?></label>
					<input name="epnumber" id="epnumber" type="text" size="3" maxlength="2" onkeyup="titlechange();checkEp();"/>
				</div>
				
				<div class="grilla1">
					<label><?= ("Title"); ?></label>
					<input type="text" name="eptitle" id="eptitle" maxlength="200" size="60" onkeyup="titlechange();"/>
				</div>
					
				<div class="grilla1">
					<label><?= _("Language"); ?></label>
					<select name="lang" id="lang">
						<option id="0"><?= _("Pick one from the list"); ?></option>
						<?php
						$query = "select * from languages order by lang_name";
						$result = mysql_query($query);

							while ($row=mysql_fetch_assoc($result))
								{
									echo '<option value="'.$row[langID].'">'.$row['lang_name'].'</option>';
								}
								utf
								?>
				      		</select>
				</div>
				
				<div class="grilla1">
					<label><?= _("File"); ?></label>
					<input type="hidden" name="MAX_FILE_SIZE" value="500000"><input type="file" name="file" />
				</div>

				<div class="grilla1">
					<label><?= _("Version <small>(LOL/DVDRIP/FLAC)</small>"); ?></label>
					<input type="text" class="inputCool" name="version" id="version" maxlength="20" size="7" />
				</div>
					
				<div class="grilla1">
					<label><?= _("Size (in <abbr title=\"Megabytes\">MB</abbr>)"); ?></label>
					<input type="text" class="inputCool" name="fsize" id="fversion" maxlength="10" size="4" />
				</div>

				<div class="grilla1">
				 	<label><?= _("Character Encoding"); ?></label>
					<select name="charset">
				    	<option value="d" selected>ISO-8859-1 (<?php echo $wikilang_occidental_char; ?>) (<?php echo $wikilang_default; ?>)</option>
						<option value="k">kio8-r (Cyrillic)</option>
						<option value="w">windows-1251 (Cyrillic)</option>
						<option value="i">iso-8859-5 (Cyrillic)</option>
						<option value="a">x-cp866 (Cyrillic)</option>
						<option value="m">x-mac-cyrillic (Cyrillic)</option>
						<option value="u">UTF-8</option>
				    </select>
				</div>
				
				<div class="grilla1">
					<label><?= _("Comments"); ?></label>
				 	<textarea name="comment" cols="90" rows="2"></textarea>
				</div>
					
				<div class="sendbutton">
					<p><input name="Submit" type="submit" class="coolBoton" value="<?php echo $wikilang_upload; ?>" /></p>
				</div>
				
				</form>
				
				</div>

				<hr />

  <table width="90%" border="0" align="center">
    <tr>

      <td><input name="type" type="radio" value="ep" checked="checked" id="epop" onclick="moep();"/>
        <?php echo $wikilang_is_episode; ?> 
        </td>
      <td><span id="epzone"><?php echo $wikilang_show; ?> 
        <select name="showID" id="show" onchange="titlechange();checkEp();">
        <option id="0"><?php echo $wikilang_select_a_show; ?></option>
<?php
	$query = "select * from shows order by title";
	$result = mysql_query($query);
	
	while ($row=mysql_fetch_assoc($result))
	{
		$sID= $row['showID'];
		$title = stripslashes($row['title']);
		echo "<option value=\"$sID\">$title</option>";
		
	}
?>
        </select>

       <?php echo $wikilang_season; ?>      
      <input name="season" id="season" type="text" size="3" maxlength="2" onkeyup="titlechange();checkEp();"/> 
     <?php echo $wikilang_episode; ?> 
      <input name="epnumber" id="epnumber" type="text" size="3" maxlength="2" onkeyup="titlechange();checkEp();"/> <span id="newshow"><a href="javascript:newShow();"><?php echo $wikilang_add_new_show; ?></a>
      </span><span id="epexistsa"> </span><br />
      <?php echo $wikilang_episode_title; ?> <input type="text" name="eptitle" id="eptitle" maxlength="200" size="60" onkeyup="titlechange();"/></span></td></tr>

    <tr>
      <td>&nbsp;</td>
      <td><input name="type" type="radio" value="movie" id="movieop" onclick="moep();"/>
        <?php echo $wikilang_is_movie; ?> </td>
      <td><span id="moviespan"><?php echo $wikilang_title; ?> 
        <input type="text" name="movietitle" id="movietitle" maxlength="200" size="70" onkeyup="titlechange();"/>
        <?php echo $wikilang_year; ?>
        <input type="text" name="year" id="year" onkeyup="titlechange();"/></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
    </tr>

<!-- termina seleccion subtítulo -->

    <tr>
      <td class="NewsTitle"><img src="images/subtitle.gif" width="22" height="14" /><?php echo $wikilang_language; ?></td>
      <td colspan="2"><select name="lang" id="lang"> 
      <option id="0"><?php echo $wikilang_select_a_language; ?></option>
<?php
	$query = "select * from languages order by lang_name";
	$result = mysql_query($query);
	
	while ($row=mysql_fetch_assoc($result))
	{
		echo '<option value="'.$row[langID].'">'.$row['lang_name'].'</option>';
	}
	utf
?>
      </select>      </td>
    </tr>
    <tr>
      <td class="NewsTitle"> <img src="images/package.png" width="16" height="16" /> <?php echo $wikilang_srt_file; ?></td>
      <td colspan="2"><input type="hidden" name="MAX_FILE_SIZE" value="500000"><input type="file" name="file" /></td>
    </tr>
    <tr>
    	<td class="NewsTitle"> <img src="images/folder_page.png" width="16" height="16" /> <?php echo $wikilang_version; ?></td>
    	<td colspan="2"><?php echo $wikilang_video_version; ?> (DVDRip/XOR/LoL, etc) <input type="text" class="inputCool" name="version" id="version" maxlength="20" size="7" />
<?php echo $wikilang_size_mbytes; ?> <input type="text" class="inputCool" name="fsize" id="fversion" maxlength="10" size="4" /></td>
    	
    </tr>
    <tr>
    <td class="NewsTitle"><?php echo $wikilang_charset; ?></td>
    <td colspan="2">
    <select name="charset">
    <option value="d" selected>ISO-8859-1 (<?php echo $wikilang_occidental_char; ?>) (<?php echo $wikilang_default; ?>)</option>
    <option value="k">kio8-r (Cyrillic)</option>
    <option value="w">windows-1251 (Cyrillic)</option>
    <option value="i">iso-8859-5 (Cyrillic)</option>
    <option value="a">x-cp866 (Cyrillic)</option>
    <option value="m">x-mac-cyrillic (Cyrillic)</option>
    <option value="u">UTF-8</option>
    </select>
    &nbsp;
    </td>
    
    </tr>
    <tr>
      <td class="NewsTitle"><img src="images/user_comment.png" width="16" height="16" /><?php echo $wikilang_comments; ?></td>
      <td colspan="2"><textarea name="comment" cols="90" rows="2"></textarea></td>
    </tr>
    <tr>
      <td colspan="3"><div align="center">
        <input name="Submit" type="submit" class="coolBoton" value="<?php echo $wikilang_upload; ?>" />
      </div></td>
    </tr>
  </table>
</form>
<br /><br />
<?php
	include('footer.php');
	bbdd_close();
?>
</body>
</html>