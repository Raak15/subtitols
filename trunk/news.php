<?php
	include('includes/includes.php');
	
	if (!bd_userIsModerador()) exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
Insert news
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
</head>
<body>
<?php
	include('header.php');
?>

<form action="news_do.php" method="post">
<textarea name="texto" cols="80"></textarea>
<input type="submit" value="Insert" class="coolBoton" />
</form>

<?php
	include('footer.php');
	bbdd_close();
?>
</body>
</html>