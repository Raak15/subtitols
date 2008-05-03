<?php
	include_once('includes/includes.php');
	include_once('includes/phpmailer/class.phpmailer.php');
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	$url = $_POST['url'];
	$remember = $_POST['remember'] == 'true';
	
	if (isset($username) || isset($password))
	{
	
		$query = "select userID,password from users where username='$username'"	;
		$result = mysql_query($query);
		$numresults = mysql_affected_rows();
	
		if ($numresults>0)
		{
	
			$row = mysql_fetch_assoc($result);
			if (md5($password)==$row['password'])
			{
				session_start();
				$cuser = $row['userID'];
				$_SESSION['userID'] = $cuser;
				$_SESSION['username'] = $username;
				if ($remember)
				{
					setcookie("wikisubtitlesuser", $cuser,time()+60*60*24*100, "/");
					setcookie("wikisubtitlespass", md5($password),time()+60*60*24*100, "/");
				}
				
				$query = "update users set last=NOW(),ip='".$_SERVER['REMOTE_ADDR']."' where userID=$cuser";
				mysql_query($query);
				
				
				if (isset($url))
					header("Location: $url");
					else
					header("Location: index.php");
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>wikisubtitles</title>
<link href="css/wikisubtitles.css rel="stylesheet" type="text/css" media="screen" />
</head>

<body>
<?php 
	include('header.php');
	
	if ((!isset($username)) || (!isset($password)))
	{
		echo "<p align=\"center\">Not logged propertly</p>";
	}
	else	
	if ($numresults<1)
	{
		echo "<p align=\"center\">$wikilang_user <b>$username</b> $wikilang_dont_exists </p>";
		echo "<p align=\"center\"><a href=\"login.php\">$wikilang_try_again</a> </p>";
	}
	else 
	if (md5($password)!=$row['password'])
	{
		echo "<p align=\"center\">$wikilang_incorrect_password </p>";
	}
	
	

	include('footer.php');
	bbdd_close();
?>
</body>
</html>