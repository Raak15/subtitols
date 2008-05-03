<?php
	include('includes/includes.php');
	include('translate_fns.php');
	if (!isset($_SESSION['userID']))
	{
		header("Location: /login.php");
		exit();
	}
	
	$id = $_POST['id'];
	$fversion = $_POST['fversion'];
	$langto = $_POST['langto'];
	$langfrom = $_POST['langfrom'];
	
	$state = bd_getLangState($id, $langto, $fversion);
	if (state!=$wikilang_completed)
		tn_start($id, $fversion, $langfrom, $langto);
	
	$title = bd_getTitle($id);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title> <?php echo $wikilang_translating.' '; ?> / <?php echo "$title - wikisubtitles"; ?></title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
	<script type="text/javascript"><?php include('/js/quicksearch.php'); ?></script>
	<script type="text/javascript"><?php include('/js/showsub.php'); ?></script>
	<style type="text/css" media="screen">
		@import url(/css/main.css);
	</style>

	<script type="text/javascript">

		function mouseover(tipo, seq)
		{
			if(tipo=="o")
			{
				var texto = "text" + seq;
				$(texto).style.fontWeight= "900";
			}
		}

		function mouseleave(tipo, seq)
		{
			if(tipo=="o")
			{
				var texto = "text" + seq;
				$(texto).style.fontWeight= "100";
			}
		}

		function mouseclick(tipo,seq)
		{
			var texto = "text" + seq;
			html = $(texto).innerHTML;

			if ((html.search("<input")<0) && (html.search("<INPUT")<0))
			{
				$(texto).innerHTML = '<img src="/images/loader.gif">';
				<?php echo "var peticion = '/translate_ajaxselect.php?id=$id&fversion=$fversion&langto=$langto&langfrom=$langfrom&seq=';"; ?>
				new Ajax(peticion+seq, {
					method: 'get',
					update: $(texto)
				}).request();
			}
		}

		function update(tipo,seq)
		{
			if (tipo=="o")
			{

				var paramString = $("of"+seq).toQueryString(); 
				$("of"+seq).innerHTML = '<img src="images/loader.gif">';
				new Ajax('/translate_ajaxedit.php',{
					method: 'post',
					postBody: paramString,
					update: $("of"+seq),
					onComplete: function(){
						<?php echo "var peticion='/translate_getstate.php?id=$id&fversion=$fversion&langto=$langto&langfrom=$langfrom';"; ?>
						$("current_state").innerHTML='<img src="/images/loader.gif">';
						new Ajax(peticion,
						{
							method: 'get',
							update: $("current_state")
						}).request();	
					}
				}).request();
			}

			return false;
		}

		function list(start)
		{
	<?php
			echo "var id = '$id';";
			echo "var fversion = '$fversion';";
			echo "var langto = '$langto';";
			echo "var langfrom = '$langfrom';";
	?>
			var peticion = '/translate_ajaxlist.php?id='+id+'&fversion='+fversion+'&langto='+langto+'&langfrom='+langfrom+'&start='+start;

			var unt = $("unt");
			if ((unt!=null) && unt.checked) peticion = peticion + '&untraslated=1';

			$("lista").innerHTML = '<img src="/images/loader.gif">';

			new Ajax(peticion, {
				method: 'get',
				update: $("lista")
			}).request();
		}

		function carga()
		{
			list("0","false","");
		}

		function slang(start, updated)
		{
			var valor = $("slang").value;
			list(start, updated, valor);

		}

		function setCancel(seq)
		{
			var myinput = $("Cancel"+seq);
			myinput.value = "true";
		}

		function release(seq)
		{
			var mytd = $('release'+seq);
			mytd.innerHTML='<img src="/images/loader.gif">';
	<?php   echo "var peticion = '/translate_release.php?id=$id&fversion=$fversion&langto=$langto&seq='; "; ?>
			new Ajax(peticion +seq, {
				method: 'get',
				update: mytd
			}).request();
		}

		function releaseAll()
		{
			$("rall").innerHTML = '<img src="/images/loader.gif">';
			<?php   echo "var peticion = '/translate_releaseall.php?id=$id&fversion=$fversion&lang=$langto'; "; ?>
			new Ajax(peticion, {
				method: 'get',
				update: $("rall")
			}).request();

		}

		function untraslated()
		{
			var check = $("unt");
	<?php
			echo "var id = '$id';";
			echo "var fversion = '$fversion';";
			echo "var langto = '$langto';";
			echo "var langfrom = '$langfrom';";
	?>
			if (!check.checked)
				list(0);
			else
			{
				var peticion = '/translate_ajaxlist.php?id='+id+'&fversion='+fversion+'&langto='+langto+'&langfrom='+langfrom+'&start=0'+'&untraslated=1';

				$("lista").innerHTML = '<img src="/images/loader.gif">';

				new Ajax(peticion, {
					method: 'get',
					update: $("lista")
				}).request();
			}


		}

		function enviar()
		{
			var paramString = $("newc").toQueryString();
			new Ajax('/translate_comments.php',{
				method: 'post',
				postBody: paramString,
				update: $("comments")
			}).request();

			return false;
		}

		window.addEvent('domready', function(){ carga(); });

	</script>


</head>

<body>

<?php include("includes/general/moderator_bar.php"); ?>

<?php include("header.php"); ?>

<?php include("includes/general/nav_home.php"); ?>

<div id="sitebody">

	<div id="slogan">
		
		<h2><?php echo $wikilang_translating; ?>  <?php 
			$url = bd_getUrl($id);
			echo "<a href=\"$url\">$title</a>"; 
			?></h2>

</div>

<span id="lista">&nbsp;</span>

<?php
	include ('footer.php');
	bbdd_close();
?>

</body>
</html>
