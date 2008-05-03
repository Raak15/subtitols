<?php
include_once('includes/includes.php');

if (!isset($_SESSION['userID']))
{
	echo "Not logged";
	exit();
}

$userID = $_SESSION['userID'];
$subID = $_POST['id'];

$error = false;
$errordesc = "";

//file
	if ($_FILES['file']['error']>0)
	{
		$error = true;
		echo "$wikilang_problem: ";
		switch ($userfile_error)
		{
			case 1: $errordesc = $wikilang_error_filetoolong;  break;
			case 2: $errordesc = $wikilang_error_filetoolong;  break;
			case 3: $errordesc = $wikilang_error_uploading; break;
			case 4: $errordesc = $wikilang_error_notuploaded;break;
			default: $errordesc = $wikilang_error_unknow;
		}
	}
	else {

		$upfile = $_FILES['file']['tmp_name'];
		$upfile_name = $_FILES['file']['name'];

		//upload file to bbdd
		$fichero = file($upfile);
		$lineas = count($fichero);

		$subID = $_POST['id'];
		$fversion = $_POST['fversion'];
		$userID = $_SESSION['userID'];
		$version = $_POST['versionDesc'];
		$size = $_POST['size'];
		
		$query = "insert into links(subID,fversion,author,downloads,enabled,versionDESC,versionSize) ";
		$query .="values($subID,$fversion,$userID,0,0,'$version','$size')";
		mysql_query($query);
		
		$newLinkID = mysql_insert_id();
		
		

		$clin = 0;
		$secuencia = 0;
		while ($clin<$lineas)
		{
			$basura=trim($fichero[$clin]);
			$secuencia++;
			if (($basura!="") && ($basura!="\r\n"))
			{
				$clin++;
				$tiempo=trim($fichero[$clin]);
				$tiempo = str_replace(',', '.',$tiempo);
				$tiempos = explode(' --> ', $tiempo);
				$tiempos_start_fraction = substr(strstr($tiempos[0], '.'),1);
				$tiempos_end_fraction = substr(strstr($tiempos[1], '.'),1);

				$clin++;

				$texto = '';
				do{
					$readline = $fichero[$clin];
					$readline = str_replace("\r",'', $readline);
					$clin++;
					if ($readline!="\n") $texto = $texto.$readline;
				} while (($readline!="\n") && ($clin<$lineas));
				
				$query = "insert into links_data(linkID,sequence,start_time,start_time_fraction,end_time,end_time_fraction) ";
				$query .="values ($newLinkID, $secuencia,'".$tiempos[0]."',$tiempos_start_fraction,'".$tiempos[1]."',$tiempos_end_fraction)";
				mysql_query($query);
			}
			else $clin++;

		}



		$newduration = $tiempos[1];
		$novalido = !isset($newduration) || ($newduration=='') ;


		if ($novalido)
		{
			$error = true;
			$errordesc = "$wikilang_error_wrongformat";

		}
		
		$query = "select count(*) from links_data where linkID=$newLinkID";
		$result = mysql_query($query);
		$count = mysql_result($result, 0);
		
		if ($count<5)
		{
			$error = true;
			$errordesc = $wikilang_error_less5;
		}
		
		$olang = bd_getOriginalLang($subID, $fversion);
		$original_count = bd_getLastSequence($subID, $olang, $fversion);
		
		//$secuencia--;
		if ($original_count!=$secuencia)
		{
			$error = true;
			$errordesc = $wikilang_error_sequences; 
		}
			
	}

if ($error)
{
	$query = "delete from links_data where linkID=$newLinkID"; mysql_query($query);
	$query = "delete from links where linkID=$newLinkID"; mysql_query($query);
}
else 
{
	$query = "update links set enabled=1 where linkID=$newLinkID";
	mysql_query($query);
	
	$url = bd_getUrl($subID);
	location($url);
}
if ($error)
{
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo "$wikilang_error: $wikilang_link_version"; ?></title>
<link href="css/wikisubtitles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
	include('header.php');
?>
<br />
<div align="center" class="title"><b><?php echo $wikilang_error; ?></b><br/>&nbsp;
<?php
	echo $errordesc;
?>
<br /><br/><br />
<?php
	$title = bd_getTitle($subID);
	$url = bd_getUrl($subID);
	
	echo "$wikilang_return_to <a href=\"$url\">$title</a> $wikilang_summary"
?>

</div><br /><br /><br /><br />
<?php
	include('footer.php');
	echo '</body></html>';
}
	bbdd_close();
?>