<?php
	include_once('includes/includes.php');
	include_once('includes/phpmailer/class.phpmailer.php');
	
	
	$email = trim($_POST['mail']);
	
	$query = "select userID,username from users where mail='$email'";
	$result = mysql_query($query);
	$numresults = mysql_affected_rows();
	
	if ($numresults>0)
	{
		$row = mysql_fetch_assoc($result);
		$username = $row['username'];
		$userID = $row['userID'];
		srand((double) microtime()*1000000);
		$rand_number = rand(0, 10000);
		$new_password = "wikisubt_$rand_number";
		$md5pass = md5($new_password);
		$query = "update users set password='$md5pass' where userID=$userID";
		mysql_query($query);
		
		$mail = new PHPMailer();

		$mail->IsSendMail();        
		$mail->Host     = "localhost"; 
	
		$mail->From     = "webmaster@wikisubtitles.net";
		$mail->FromName = "wikisubtitles.net";
		$mail->AddAddress($email,$username); 


		$mail->WordWrap = 50;                              // set word wrap

		$mail->IsHTML(true);                               // send as HTML

		$mail->Subject  =  "Wikisubtitles - New password for $username";
		
		//html body
		$mail->Body     =  '<a href="http://www.wikisubtitles.net">wikisubtitles.net</a><br /><br />';
		$mail->Body		.= "Someone, probably you, has requeseted a new password for yours wikisubtitles account ($username).<br /><br />";
		$mail->Body     .= "The new login information is:<br />";
		$mail->Body     .= "User: <a href=\"http://www.wikisubtitles.net/user/$userID\">$username</a><br />";
		$mail->Body     .= "<b>New password</b>: $new_password<br /><br />";
		$mail->Body     .= "Please <a href=\"http://www.wikisubtitles.net/login.php\">login</a> as $username, and change your password if you wish.<br /><br />".
		$mail->Body		.= "<a href=\"mailto:webmaster@wikisubtitles.net\" ><i>Wikisubtitles.net's webmaster</i></a><br />";
		$mail->Body		.='<a href="http://www.wikisubtitles.net">wikisubtitles.net</a><br />';
		
		//text only
		$mail->AltBody = "Wikisubtitles.net\n\n";
		$mail->AltBody .="Someone, probably you, has requested a new password for your wikisubtitles account.\n\n";
		$mail->AltBody .="The new login information is:\n";
		$mail->AltBody .="User: $username\n";
		$mail->AltBody .="Password: $new_password\n\n";
		$mail->AltBody .="Please, login as $username, and change your password if you wish.\n\n";
		$mail->AltBody .= "Wikisubtitles.net's webmaster\n";
		$mail->AltBody .= "http://www.wikisubtitles.net\n";

		$mail->Send();
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>wikisubtitles</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" media="screen" />
</head>

<body>
<div align="center">
  <?php include('header.php'); 
  if ($numresults>0)
  {
  	echo "<p><b>$username</b>, $wikilang_password_reseted.</p>";
  	echo "<p>$wikilang_email_sent_to <b>$email</b> $wikilang_password_reseted_info</p>";
  }
  else 
  {
  	echo "<p>$email $wikilang_email_notfound</p>";
  }
  ?>
  
  <p><a href="index.php"><?php echo $wikilang_index; ?></a> &middot; <a href="login.php"><?php echo $wikilang_login; ?></a> </p>
 <?php 
 	include('footer.php');
 	bbdd_close();
 ?>
  
</div>
</body>
</html>