<?php
	include('includes/includes.php');
	
	$count = $_GET['count'];
	$me = $_SESSION['userID'];
	
	$query = "update msgs set noticed=1 where `to`=$me and noticed=0";
	mysql_query($query);
	bbdd_close();
	
?>
<html>
<head>
<title>Wikisubtitles messages</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script language="javascript">

	function centrar() {
    	eje_x=(screen.width-document.body.clientWidth) / 2;
     	eje_y=(screen.height-document.body.clientHeight) / 2;
     	moveTo(eje_x,eje_y);
     	
     	var save = document.getElementById("save");
     	save.style.visibility = "hidden";
	 }
	 
function red()
{
	window.opener.location = "/msginbox.php";
	close();
}
</script>
</head>
<body onload="centrar();">
<div align="center"><img src="/images/logo2.gif" /></div>
<p align="center"><strong>
<?php echo "$count $wikilang_unread_messages" ?>
</strong></p>
<p align="center"><a href="javascript:red();"><?php echo $wikilang_inbox; ?>  </a></p>
</body>
</html>