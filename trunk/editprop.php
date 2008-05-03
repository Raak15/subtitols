<?php
	include('includes/includes.php');
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	
	$user = $_SESSION['userID'];
	
	$query = "select author from fversions where subID=$id";
	if (isset($version)) $query .= " and fversion=$fversion";
	$query.= " limit 1";
	$result = mysql_query($query);
	
	$author = mysql_result($result, 0);
	
	
	$query = "select title,is_episode,showID,season,season_number from files where subID=$id";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$title = stripslashes($row['title']);
	$is_episode = $row['is_episode'];
	$showID = $row['showID'];
	$season = $row['season'];
	$epnumber = $row['season_number'];
	
	if (isset($fversion))
		$lang = bd_getOriginalLang($id, $fversion);
		else $lang = bd_getOriginalLang($id, 0);
		
	$query = "select versionDesc,size,comment from fversions where subID=$id and fversion=";
	if (isset($fversion))
		$query .= $fversion;
		else 
		$query .= '0';
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	
	$vDesc = $row['versionDesc'];
	$size = $row['size'];
	$comment = stripslashes($row['comment']);
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo "$wikilang_edit_properties - $title - Wikisubtitles"; ?>
</title>
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
		$("epexists").style.visibility = "hidden";
<?php
	if ($is_episode)
		echo '$("moviespan").style.visibility = "hidden";';
		else echo '$("epzone").style.visibility = "hidden";';
		
?>
	}
	
	function check()
	{	
		if ($("epop").checked)
		{
			if ($("show").value<1)
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
			$("epexists").innerHTML = '<img src="/images/loader.gif">';
			new Ajax(peticion, {
				method: 'get',
				update: $("epexists"),
				onComplete: function() { $("epexists").style.visibility = "visible"; }
			}).request();
		}
	}
</script>
</head>

<body onload="load();">
<?php
	include('header.php');
?>
<div align="center" class="titulo"><img src="images/upload.png" width="16" height="16" /> 
<?php 
	$url = bd_getUrl($id);
	echo "$wikilang_edit_properties <a href=\"$url\">$title</a>"; 
?>
</div>
<form action="editprop_do.php" method="post" enctype="multipart/form-data" name="step1" id="step1" onsubmit="return check();">
  <table width="90%" border="0" align="center">

<?php
	if (!isset($fversion))
	{
	
?>
    <tr>
      <td class="NewsTitle" colspan="2"><img src="/images/television_add.png" width="16" height="16" /> <?php echo $wikilang_episode_or_movie; ?> </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
<?php
	if ($is_episode)
		echo '<input name="type" type="radio" value="ep" checked="checked" id="epop" onclick="moep();"/>';
		else 
		echo '<input name="type" type="radio" value="ep" id="epop" onclick="moep();"/>';
?>
        <?php echo $wikilang_is_episode; ?> </td>
      <td><span id="epzone"><?php echo $wikilang_show; ?>
        <select name="showID" id="show" onchange="titlechange();">
<?php
	$query = "select * from shows order by title";
	$result = mysql_query($query);
	
	if (!$is_episode)
		echo '<option value="0">'.$wikilang_select_a_show.'</option>';
	
	while ($row=mysql_fetch_assoc($result))
	{
		$sID= $row['showID'];
		$stitle = stripslashes($row['title']);
		if ( $is_episode && ($sID==$showID))
			echo "<option value=\"$sID\" selected>$stitle</option>";
			else 
			echo "<option value=\"$sID\">$stitle</option>";	
	}
?>
        </select>
       <?php echo $wikilang_season; ?> 
<?php
	if ($is_episode)     
      	echo '<input name="season" id="season" type="text" size="3" maxlength="2" onkeyup="titlechange();" value="'.$season.'"/>';
      	else 
      	echo '<input name="season" id="season" type="text" size="3" maxlength="2" onkeyup="titlechange();"/>';
?>
     <?php echo $wikilang_episode; ?> 
<?php
	if ($is_episode)
     	 echo '<input name="epnumber" id="epnumber" type="text" size="3" maxlength="2" onkeyup="titlechange();" value="'.$epnumber.'" />';
     	 else 
     	 echo '<input name="epnumber" id="epnumber" type="text" size="3" maxlength="2" onkeyup="titlechange();"/>';
?>
       <span id="newshow"><a href="javascript:newShow();"><?php echo $wikilang_add_new_show; ?></a>
      </span><span id="epexists"> </span><br />
      <?php echo $wikilang_episode_title; ?>: 
<?php
	  $title_array = split(" - ", $title);
	  $eptitle = trim($title_array[2]);
	  if ($is_episode)
      	echo '<input type="text" name="eptitle" id="eptitle" maxlength="200" size="60" onkeyup="titlechange();" value="'.$eptitle.'" />';
      	else 
      	echo '<input type="text" name="eptitle" id="eptitle" maxlength="200" size="60" onkeyup="titlechange();"/>';
      	
?>
      </span></td></tr>

    <tr>
      <td>&nbsp;</td>
      <td>
<?php
      if (!$is_episode)
      	echo '<input name="type" type="radio" checked="checked" value="movie" id="movieop" onclick="moep();"/>';
      	else 
      	echo '<input name="type" type="radio" value="movie" id="movieop" onclick="moep();"/>';
?>
        <?php echo $wikilang_is_movie; ?> </td>
      <td><span id="moviespan"><?php echo $wikilang_title; ?>
        
<?php
	$ppos = strpos($title, '(');
	$mtitle = substr($title, 0, $ppos-1);
	$pppos = strpos($title, ')');
	$year = substr ($title, $ppos+1, $pppos-$ppos-1);
	if (!$is_episode)
		echo '<input type="text" name="movietitle" id="movietitle" maxlength="200" size="70" onkeyup="titlechange();" value="'.$mtitle.'" />';
		else 
		echo '<input type="text" name="movietitle" id="movietitle" maxlength="200" size="70" onkeyup="titlechange();"/>';
?>
       <?php echo $wikilang_year; ?>
<?php
	if (!$is_episode)
        echo '<input type="text" name="year" id="year" onkeyup="titlechange();" value="'.$year.'"/></span></td>';
        else 
        echo '<input type="text" name="year" id="year" onkeyup="titlechange();"/></span></td>';
?>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right" id="gentitle" >
<?php echo "$wikilang_title_on_wikisubtitles <b>$title</b>" ?>
      </div></td>
    </tr>
<?php
	}
?>
    <tr>
      <td class="NewsTitle"><img src="images/subtitle.gif" width="22" height="14" /><?php echo $wikilang_language; ?></td>
      <td colspan="2"><select name="lang" id="lang"> 
<?php
	$query = "select * from languages order by lang_name";
	$result = mysql_query($query);
	
	while ($row=mysql_fetch_assoc($result))
	{
		if ($row['langID']!=$lang)
			echo '<option value="'.$row['langID'].'">'.$row['lang_name'].'</option>';
			else 
			echo '<option value="'.$row['langID'].'" selected>'.$row['lang_name'].'</option>';
	}
?>
      </select>      </td>
    </tr>
    <tr>
    	<td class="NewsTitle"> <img src="images/folder_page.png" width="16" height="16" /> <?php echo $wikilang_version; ?></td>
    	<td colspan="2"><?php echo $wikilang_video_version; ?> (DVDRip/XOR/LoL, etc)
<?php
    	echo '<input type="text" class="inputCool" name="version" id="version" maxlength="20" size="7" value="'.$vDesc.'" />';

    	echo $wikilang_size_mbytes; 
	echo '<input type="text" class="inputCool" name="fsize" id="fversion" maxlength="10" size="4" value="'.$size.'" />';
?>
</td>
    	
    </tr>
    <tr>
      <td class="NewsTitle"><img src="images/user_comment.png" width="16" height="16" /><?echo $wikilang_comments; ?></td>
      <td colspan="2"><textarea name="comment" cols="90" rows="2">
<?php echo $comment; ?>    
      </textarea></td>
    </tr>
    <tr>
      <td colspan="3"><div align="center">
        <input name="Submit" type="submit" class="coolBoton" value="<?php echo $wikilang_update; ?>" />
      </div></td>
    </tr>
  </table>
<?php
	hidden("id", $id);
	if (isset($fversion)) hidden("fversion", $fversion);
?>
</form>
<br /><br />
<?php
	include('footer.php');
	bbdd_close();
?>
</body>
</html>
