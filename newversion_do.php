<?php
include_once('includes/includes.php');
include_once('translate_fns.php');

if (!isset($_SESSION['userID']))
{
	echo "Not logged";
	exit();
}

$userID = $_SESSION['userID'];
$subID = $_POST['id'];

$error = false;
$errordesc = "";

//Params
$tmptitle = $_POST['title'];
$tmptitle = str_replace('"',"'",$tmptitle);
$title = addslashes($tmptitle);

$comment = addslashes(strip_tags($_POST["comment"]));
$version = $_POST['version'];
$size = $_POST['size'];
$lang = $_POST['lang'];
$charset = $_POST['charset'];

if (!$error)
{
	//file
	if ($_FILES['file']['error']>0)
	{
		$error = true;
		echo "Problem: ";
		switch ($userfile_error)
		{
			case 1: $errordesc = $wikilang_error_filetoolong; break;
			case 2: $errordesc = "$wikilang_error_filetoolong"; break;
			case 3: $errordesc = $wikilang_error_uploading; break;
			case 4: $errordesc = $wikilang_error_notuploaded;break;
			default: $errordesc = "$wikilang_error_unknow";
		}
	}
	else {

		$upfile = $_FILES['file']['tmp_name'];
		$upfile_name = $_FILES['file']['name'];

		//upload file to bbdd
		$fichero = file($upfile);
		$lineas = count($fichero);

		//get fversion
		$query = "select max(fversion) from fversions where subID=$subID";
		$result = mysql_query($query);
		$fversion = mysql_result($result, 0) + 1;
		
		$query = "insert into fversions(subID,fversion,author,versionDesc,size,comment,indate) ";
		$query .="values($subID,$fversion,$userID,'$version','$size','$comment',NOW())";
		mysql_query($query);

		$clin = 0;
		$secuencia = 0;
		while ($clin<$lineas)
		{
			$basura=trim($fichero[$clin]);
			$secuencia++;
			if (($secuencia!="") && ($secuencia!="\r\n"))
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

				$texto = addslashes($texto);
				
				$texto = addslashes($texto);
				if ($charset=='d')
					$texto = utf8_encode($texto);
					elseif ($charset!='u')
						$texto = cyrillic2utf($texto, $charset);
						
				$query = "insert into subs (subID,sequence,authorID,version,original,locked,in_date,start_time,start_time_fraction,end_time,end_time_fraction,text,lang_id,edited_seq, last,estart_time,estart_time_fraction,eend_time,eend_time_fraction,fversion) ";
				$query .= "values($subID,$secuencia,$userID,0,1,0,NOW(),'".$tiempos[0]."',$tiempos_start_fraction,'".$tiempos[1]."',$tiempos_end_fraction,'$texto',$lang,$secuencia,1,'".$tiempos[0]."',$tiempos_start_fraction,'".$tiempos[1]."',$tiempos_end_fraction,$fversion)";
				mysql_query($query);
			}
			else $clin++;

		}

		$query = "update files set duration='".$tiempos[1]."' where subID=$subID";
		mysql_query($query);
		
		tn_duplicates($subID, 0,$lang);

		$newduration = $tiempos[1];
		$novalido = !isset($newduration) || ($newduration=='') ;


		if (!$novalido)
		{
			$utf = $charset!='d';
			if ($utf) $utfval = 1; else $utfval=0;
			$query = "insert into flangs(subID,fversion,lang_id,state,testing,original,totalseq,totalVersion0,cyrillic)";
			$query.= " values($subID,$fversion,$lang,100,0,1,$secuencia,$secuencia, $utfval)";
			mysql_query($query);
			
			$query = "update users set uploads=uploads+1 where userID=$userID";
			mysql_query($query);
		}
		else
		{
			$error = true;
			$errordesc = $wikilang_error_wrongformat;

		}
		
		$query = "select count(*) from subs where subID=$subID";
		$result = mysql_query($query);
		$count = mysql_result($result, 0);
		
		if ($count<5)
		{
			$error = true;
			$errordesc = $wikilang_error_less5; 
		}
	}
}

if ($error)
{
	$query = "delete from subs where subID=$subID and fversion=$fversion"; mysql_query($query);
	$query = "delete from fversions where subID=$subID and fversion=$fversion"; mysql_query($query);
	$query = "delete from flangs where subID=$id and fversion=$fversion"; mysql_query($query);
	$query = "delete from files where subID=$id and fversion=$fversion"; mysql_query($query);
	
}
else 
{
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
<title>Error: Upload a new version</title>
<link href="css/wikisubtitles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
	include('header.php');
?>
<br />
<div align="center" class="title"<?php echo $wikilang_error; ?>:<br/>
<?php
	echo $errordesc;
?>

</div><br /><br /><br /><br />
<?php
	include('footer.php');
	echo '</body></html>';
}
	bbdd_close();
?>
