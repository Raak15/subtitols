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
	</script>
	<style type="text/css" media="screen">
		@import url(/css/main.css);
	</style>
	
	<script type="text/javascript" charset="utf-8">
		<?php include('js/title_change.php'); ?>
	</script>
	
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
			<li><a href="/newmov.php"><?= _("Movies"); ?></a></li>
		</ul>
	</div>

	<div id="content-listados">
		
		<div id="formulario">
			
			<form action="uploadnewsub.php" method="post" enctype="multipart/form-data" name="step1" id="step1" onsubmit="return check();">

				<div id="gentitle"></div>
											
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
					<label><?= _("Size <small>(in <abbr title=\"Megabytes\">MB</abbr>)</small>"); ?></label>
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
				 	<textarea name="comment" cols="5f0" rows="2"></textarea>
				</div>
					
				<div class="sendbutton">
					<p><input name="Submit" type="submit" class="coolBoton" value="<?php echo $wikilang_upload; ?>" /></p>
				</div>
				
				</form>
				
				</div>

			</div>

			<div id="menus">

				<h1>LOLO</h1>

			</div>

			<div class="clear"></div>

			</div>

			<?php
				include ('footer.php');
				bbdd_close();
			?>

			</body>
			</html>
	