<?php

	function msgError($message)
	{
?>
		<html>
		<head>
		<title>Wikisubtitles messages</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
		</head>
		<body>
		<?php include ('header.php') ?>
		<div align="right"><img src="imagenes/e-mail_icon.png" width="513" height="131" /></div>
		<div align="center"><h2>
		<?php echo $message ?>
		</h2></div>
		</body>
		</html>
<?php
	}
	
	
	
	include('includes/includes.php');
	include_once('includes/phpmailer/class.phpmailer.php');
	
	$toName = $_POST['to'];
	$subject = utf8_encode($_POST['subject']);
	if ((!isset($subject)) || ($subject=='')) $subject = '(no subject)';
	$mysubject = addslashes($subject);
	$text = $_POST['msgtext'];
	$mytext = addslashes($text);
	$from = $_SESSION['userID'];
	
	$query = "select userID from users where username='$toName'";
	$result = mysql_query($query);
	$numusers = mysql_affected_rows();
	if (($numusers==0) || ($toName==''))
	{
		msgError("User doesn't exist.");
		exit();
	}
		
	$to = mysql_result($result, 0);
	
	$query = "insert into msgs(`from`,`to`,date,text,opened,fromDelete,toDelete,subject) ";
	$query .="values($from,$to,NOW(),'$mytext',0,0,0,'$mysubject')";
	mysql_query($query);
	
	$fromName = bd_getUsername($from);
	
	$query = "select mail from users where userID=$to";
	$result = mysql_query($query);
	$tomail = mysql_result($result,0);
	
	if ($tomail!='')
	{
	
			$mail = new PHPMailer();

			$mail->IsSendMail();        
			$mail->Host     = "localhost"; 
	
			$mail->From     = "no-reply@wikisubtitles.net";
			$mail->FromName = "wikisubtitles.net";
			$mail->AddAddress($tomail,$toName); 
			$mail->WordWrap = 50;                              // set word wrap

			$mail->IsHTML(true);                               // send as HTML

			$mail->Subject  =  "New message from $fromName - Wikisubtitles.net";
			
			//html
			$mail->Body = "You have received a new private message from $fromName, using Wikisubtitles' messages<br />";
			$mail->Body .="Please go to <a href=\"http://www.wikisubtitles.net\">wikisubtitles.net</a>, and check it!<br />";
			$mail->Body .="<br /><hr />";
			$mail->Body .="<b>From:</b> $fromName<br />";
			$mail->Body .="<b>To:</b> $toName<br /></br />";
			$mail->Body .= $text;
			
			$mail->Send();
	}
	
	
	header('Location: /msgoutbox.php');
	
	
	bbdd_close();

?>