<?php
	include('includes/includes.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $wikilang_upload_new; ?></title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>

<script type="text/javascript">

	function titlechange()
	{
		var span = $("gentitle");
		var epop = $("epop");
		var movieop = $("movieop");
		var serie = $("show");
		var season = $("season").value;
		var epnumber = $("epnumber").value;
		if (epnumber.length<2) epnumber = "0" + epnumber; 
		if (season.length<2) season= "0" + season; 
		var eptitle = $("eptitle").value;
		var movietitle = $("movietitle").value;
		var year = $("year").value;
		
		if (epop.checked)
		{
			var myserie;
			
			if ((!serie.enabled) && ($("newshowname")!=null))
				myserie = $("newshowname").value;
				else
				if (serie.value!=0) 
				myserie = serie.options[serie.selectedIndex].text;
				else
				myserie ="";
			
			span.innerHTML = "<?php echo $wikilang_title_on_wikisubtitles; ?>: <b>" + myserie+ " - " + season +"x"+epnumber+ " - "+eptitle +"</b>";
		}
		else
			span.innerHTML = "<?php echo $wikilang_title_on_wikisubtitles; ?>: <b>"+movietitle+" ("+year+")</b>";
			
	}
	
	function moep()
	{
		var epop = $("epop");
		var movieop = $("movieop");
		
		if (movieop.checked)
		{
			$("moviespan").style.visibility = "visible";
			$("epzone").style.visibility = "hidden";
		}
		else
		{
			$("moviespan").style.visibility = "hidden";
			$("epzone").style.visibility = "visible";	
		}
	}
	
	function load()
	{
		$("moviespan").style.visibility = "hidden";
		$("epexists").style.visibility = "hidden";
	}
	
	function check()
	{	
		if ($("epop").checked)
		{
			if (($("show").value<1) && ($("newshowname")==null))
			{
				alert("<?php echo $wikilang_must_show; ?>");
				return false;
			}
			
			if ($("epnumber").value.length<1)
			{
				alert("<?php echo $wikilang_must_epnumber; ?>");
				return false;
			}
			
			if ($("season").value.length<1)
			{
				alert("<?php echo $wikilang_must_season; ?>");
				return false;
			}
			var myep = $("epexists");
			var exists = myep.innerHTML.indexOf('already') > -1;
			if (exists)
			{
				alert('<?php echo $wikilang_ep_already_exists; ?>');
				return false;
			}
		}
		else
		{
			if ($("movietitle").value.length<1)
			{
				alert("<?php echo $wikilang_must_movie; ?>");
				return false;
			}
			
			if ($("year").value.length<1)
			{
				alert("<?php echo $wikilang_must_year; ?>");
				return false;
			}
			
		}
		
		if ($("lang").value<1)
		{
			alert("<?php echo $wikilang_must_language; ?>");
			return false;
		}
		
		
		return true;
	}
	
	function newShow()
	{
		$("show").enabled = false;
		$("show").style.visibility="hidden";
		$("newshow").innerHTML = '<?php echo $wikilang_new_show_name; ?>: <input type="text" name="newshow" size="20" maxlength="255" class="inputCool" id="newshowname" onkeyup="titlechange();"/>';
	}
	
	function checkEp()
	{
		var epnumber = $("epnumber").value;
		var season = $("season").value;
		var serie = $("show");
		
		
		if ((serie.value>0) && (season.length>0) && (epnumber.length>0))
		{
			var peticion = "/ajax_checkep.php?show="+serie.value+"&epnumber="+epnumber+"&season="+season;
			$("epexists").innerHTML = '<img src="images/loader.gif">';
			new Ajax(peticion, {
				method: 'get',
				update: $("epexists"),
				onComplete: function() { $("epexists").style.visibility = "visible"; }
			}).request();
		}
	}
	window.addEvent('domready', function(){load();});
</script>
</head>

<body>
<?php
	include('header.php');
?>
<div align="center" class="titulo"><img src="images/upload.png" width="16" height="16" /> <?php echo $wikilang_upload_new; ?> </div>
<form action="uploadnewsub.php" method="post" enctype="multipart/form-data" name="step1" id="step1" onsubmit="return check();">
  <table width="90%" border="0" align="center">
    <tr>
      <td class="NewsTitle" colspan="2"><img src="images/television_add.png" width="16" height="16" /> <?php echo $wikilang_episode_or_movie; ?> </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
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
      </span><span id="epexists"> </span><br />
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
      <td><div align="right" id="gentitle" ><?php echo $wikilang_title_on_wikisubtitles; ?></div></td>
    </tr>
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