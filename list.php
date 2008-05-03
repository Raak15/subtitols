<?php
	include_once('includes/includes.php');
	
	$id = $_GET['id'];
	$lang = $_GET['lang'];
	$fversion = $_GET['fversion'];
		
	$title = bd_getTitle($id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php echo "$wikilang_listing $title - wikisubtitles"; ?>
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
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

<?php
	if (isset($_SESSION['userID']))
	{
	
?>
	function mouseclick(tipo,seq)
	{
		if(tipo=="o")
		{
			var texto = "text" + seq;
			var html = $(texto).innerHTML;
			
			var cinco = html.substr(0,5);
			if ((cinco!="<form") && (cinco!="<FORM"))
			{
			html = html.replace(/<br>/g,"");
			html = html.replace(/<BR>/g,"\n");
			
			var myform = "<form id=\"of"+seq+"\" onsubmit=\"return update('o','"+seq+"');\">";
				myform += '<textarea name="ttext" cols="60">' + html+'</textarea>';
<?php echo '	myform += \'<input type="hidden" name="id" value="'.$id.'" />\'; '; 			   ?>
<?php echo '	myform += \'<input type="hidden" name="fversion" value="'.$fversion.'" />\'; '; ?>
<?php echo '	myform += \'<input type="hidden" name="lang" value="'.$lang.'" />\'; ';         ?>
				myform += '<input type="hidden" name="seqnumber" value="'+seq+'" />';
				myform += '<input name="Submit" type="submit" class="coolBoton" value="<?php echo $wikilang_save; ?>" /></form>';
			$(texto).innerHTML = myform;
			}
		}
	}
<?php
	}
	else 
	{
?>
	function mouseclick(tipo,seq)
	{
<?php echo		"alert('$wikilang_register_alert');"; ?>
	}
<?php
	}
?>
	
	function update(tipo,seq)
	{
		if (tipo=="o")
		{
			
			var paramString = $("of"+seq).toQueryString(); 
			$("of"+seq).innerHTML = '<img src="/images/loader.gif">';
			new Ajax('<?php echo $SCRIPT_PATH; ?>ajax_editText.php',{
				method: 'post',
				postBody: paramString,
				update: $("of"+seq)
			}).request();
		}
		
		return false;
	}
	
	function list(start, updated, secondary)
	{
<?php
		echo "var id = '$id';";
		echo "var fversion = '$fversion';";
		echo "var lang = '$lang';";
?>
		var peticion = '<?php echo $SCRIPT_PATH; ?>ajax_list.php?id='+id+'&fversion='+fversion+'&lang='+lang+'&start='+start+'&updated='+updated+'&slang='+secondary;
		
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
		var filtered = $("filtered").value == "on";
		if (filtered)
			apply_filter(valor, updated, start);
			else
			list(start, updated, valor);
			
	}
	
	function timeover(seq)
	{
		var tiempo = "time" + seq;
		$(tiempo).style.fontWeight= "900";
	}
	
	function timeleave(seq)
	{
		var tiempo = "time" + seq;
		$(tiempo).style.fontWeight= "100";
	}
	

<?php
	if (isset($_SESSION['userID']))
	{
	
?>
	function timeclick(seq)
	{
		var tiempo = $("time" + seq);
		var html = tiempo.innerHTML;
		
		var cinco = html.substr(0,5);
		if ((cinco!="<form") && (cinco!="<FORM"))
			{
		
				var miArray = new Array();
				miArray = html.split("--");
		
			var myform = "<form id=\"ti"+seq+"\" onsubmit=\"return updatetime('"+seq+"');\">";
			myform += '<input type="text" name="stime" value="'+miArray[0]+'" /> --- ';
			myform += '<input type="text" name="etime" value="'+miArray[1].substr(5)+'" /> ';
<?php echo '	myform += \'<input type="hidden" name="id" value="'.$id.'" />\'; '; 			   ?>
<?php echo '	myform += \'<input type="hidden" name="fversion" value="'.$fversion.'" />\'; '; ?>
<?php echo '	myform += \'<input type="hidden" name="lang" value="'.$lang.'" />\'; ';         ?>
				myform += '<input type="hidden" name="seqnumber" value="'+seq+'" />';
				myform += '<input name="Submit" type="submit" class="coolBoton" value="<?php echo $wikilang_save; ?>" /></form>';
		
			$(tiempo).innerHTML = myform;
			}
		
	}
<?php
	}
	else 
	{
?>
	function timeclick(seq){
<?php echo		"alert('$wikilang_register_alert');"; ?>
	}
<?php
	}
?>

	function updatetime(seq)
	{
		var paramString = $("ti"+seq).toQueryString(); 
		$("ti"+seq).innerHTML = '<img src="/images/loader.gif">';
		new Ajax('<?php echo $SCRIPT_PATH; ?>ajax_editTime.php',{
				method: 'post',
				postBody: paramString,
				update: $("ti"+seq)
		}).request();
		
		return false;	
	}
	
	function lock(unlock, seq)
	{
		var tr = $("trseq"+seq);
		$("lock"+seq).innerHTML = '<img src="/images/loader.gif">';
		<?php echo "var peticion='<?php echo $SCRIPT_PATH; ?>ajax_lock.php?id=$id&lang=$lang&fversion=$fversion&seq='+seq;"; ?>
		
		new Ajax(peticion, {
			method:'get',
			update:$("lock"+seq)
		}).request();
		
	}
	
	function apply_filter(slang,updated,start)
	{
<?php
		echo "var id='$id';";
		echo "var fversion='$fversion';";
		echo "var lang='$lang';";
?>
		var paramString = $("filter").toQueryString(); 
		var peticion = '<?php echo $SCRIPT_PATH; ?>ajax_list.php?id='+id+'&fversion='+fversion+'&lang='+lang+'&slang='+slang+'&updated='+updated+'&start='+start+'&'+paramString;
		
		$("lista").innerHTML = '<img src="/images/loader.gif">';
		
		new Ajax(peticion, {
			method: 'get',
			update: $("lista")
		}).request();
		
	}

	window.addEvent('domready', function() { carga(); } );
</script>
</head>

<body>
<?php include('header.php'); ?>
<p>&nbsp;</p>
<div align="center">
<?php
	$url = bd_getUrl($id); 
echo "$wikilang_return_to <a href=\"$url\"><i>$title</i></a> $wikilang_summary"; ?>
</div>
<p>&nbsp;</p>
<span id="lista">&nbsp;</span>

<p>&nbsp;</p>


<?php
	include ('footer.php');
	bbdd_close();
?>
</body>
</html>
