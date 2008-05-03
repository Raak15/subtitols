<?php
	
	include_once('includes/includes.php');
	include_once('includes/phpmailer/class.phpmailer.php');
	include_once('forum/config.php');
	
	$username = $_POST['username'];
	$password = $_POST['pass1'];
	$email = $_POST['mail'];
	$website = $_POST['website'];
	
	$query  = "select username from users where mail='$email'";
	$result = mysql_query($query);
	$numresults = mysql_affected_rows();
	
	if ($numresults<1)
	{
	
		$query = "select userID from users where username='$username'";
		mysql_query($query);
		$numresults = mysql_affected_rows();
		if ($numresults<1)
		{
			$md5pass = md5($password);
			$query = "insert into users (username,password,mail,website,joined) ";
			$query .="values('$username','$md5pass','$email','$website',NOW())";
			mysql_query($query);

			//enviar mail
			
			$mail = new PHPMailer();

			$mail->IsSendMail();        
			$mail->Host     = "localhost"; 
	
			$mail->From     = "webmaster@wikisubtitles.net";
			$mail->FromName = "wikisubtitles.net";
			$mail->AddAddress($email,$username); 
			$mail->WordWrap = 50;                              // set word wrap

			$mail->IsHTML(true);                               // send as HTML

			$mail->Subject  =  "Wikisubtitles.net - New account for $username";
			
			//html
			$mail->Body = "Welcome to <a href=\"http://www.wikisubtitles.net\">wikisubtitles.net</a><br /><br />";
			$mail->Body .="Your new account has been created<br />";
			$mail->Body .= "This is <i>only a reminder</i> of your login data, you <b>don't need to confirm</b> the account.<br /><br />";
			$mail->Body .= "User: <b>$username</b><br />";
			$mail->Body .= "Password: <b>$password</b><br /><br />";
			$mail->Body .= "This account has been created <b>at the forum</b> too.<bt/>";
			$mail->Body .= "Enjoy the site!<br />";
			$mail->Body .= "<a href=\"mailto:webmaster@wikisubtitles.net\" >Wikisubtitle's webmaster</a><br />";
			$mail->Body .= "<a href=\"http://www.wikisubtitles.net\">http://www.wikisubtitles.net<br /></a>";
			
			//text
			$mail->AltBody = "Welcome to wikisubtitles.net\n\n";
			$mail->AltBody .="Your new account has been created\n";
			$mail->AltBody .= "This is only a reminder of your login data, you don't need to confirm the account.\n\n";
			$mail->AltBody .= "User: $username\n";
			$mail->AltBody .= "Password: $password\n\n";
			$mail->AltBody .= "This account has been created at the forum too.";
			$mail->AltBody .= "Enjoy the site!\n";
			$mail->AltBody .= "Wikisubtitle's webmaster\n";
			$mail->AltBody .= "http://www.wikisubtitles.net\n";
			

			$mail->Send();
			
			//crear en el foro
			mysql_select_db('wikisubt_foro');
			$query = "select username from users where username='$username'";
			$resultforo = mysql_query($query);
			$numresults = mysql_affected_rows();
			if ($numresults==0)
			{
				$query = "select max(user_id) from users";
				$tresult=mysql_query($query);
				$max = intval(mysql_result($tresult, 0));
				$max++;
				$query = "insert into users(user_id, username,user_password,user_regdate) values($max, '$username','$md5pass', NOW())";
				mysql_query($query);
			}
		}
	}
	
	
	bbdd_close();
	location("/login.php");
?>