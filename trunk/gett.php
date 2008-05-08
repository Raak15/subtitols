<?
$language="es_ES";
putenv("LC_ALL=$language");
setlocale(LC_ALL, 'es_ES');
echo bindtextdomain('messages', '/nfs/c01/h02/mnt/10123/domains/subtitols.minid.net/html/locale');
textdomain('messages');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<pre>

</pre>
<?= gettext("hello")?>

</body>
</html>
