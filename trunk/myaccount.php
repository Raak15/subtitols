<?php
	include_once('includes/includes.php');
	
	if (!isset($_SESSION['userID']))
	{
		header('Location: /login.php');
		exit();
	}
	$userID=$_SESSION['userID'];
	
	
	if (isset($_POST['sent']))
	{
		$password =$_POST['pass1'];
		$mail = $_POST['mail'];
		$web = $_POST['web'];
		$hide = $_POST['hide'];
		
		$hide = isset($hide) && ($hide=="true");
		
		$username = $_SESSION['username'];
		
		if (isset($password) && ($password!='') && (strlen($password)>3))
		{
			$md5pass = md5($password);
			$query = "update users set mail='$mail',website='$web',password='$md5pass'";
			
		}
		else $query = "update users set mail='$mail',website='$web'";
		
		if ($hide) 
			$query .=',hide=1';
			else 
			$query .=',hide=0';
		
		$query.=" where userID=$userID";
		
		
		mysql_query($query);
	}
	else
	{
		$query = "select username,mail,website,hide from users where userID=$userID";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		
		$username = $row['username'];
		$mail = $row['mail'];
		$web = $row['website'];
		$hide = $row['hide'];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>wikisubtitles</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>

<script language="javascript">

	function checkForm()
	{
		var p1 = document.getElementById("pass1").value;
		var p2 = document.getElementById("pass2").value;
		
		if (p1!=p2)
		{
			alert("<?php echo $wikilang_error_pass_dontmatch; ?>");
			return false;
		}
		if ((p1.length<4) && (p1.length>0))
		{
			alert("<?php echo $wikilang_error_pass_4char; ?>");
			return false;
		}
		return true;
	}
</script>
</head>

<body>
<div align="center">
<?php 
	include('header.php'); 
	
	if (isset($_POST['sent']))
	{
		echo "<p><b>Your account has been updated</b></p>";	
	}
?>
  
  <p><?php echo $wikilang_welkome; ?>, <strong>
<?php echo $username; ?>
  </strong>
  </p>
  <p><?php echo $wikilang_myaccount_info; ?></p>
  <form id="form1" name="form1" method="post" action="/myaccount.php" onsubmit="return checkForm();">
    <table width="70%" border="0">
      <tr>
        <td><?php echo $wikilang_password; ?></td>
        <td><input name="pass1" type="password" class="inputCool" id="pass1" size="24" maxlength="12" /></td>
      </tr>
      <tr>
        <td><?php echo $wikilang_password_confirm; ?> </td>
        <td><input name="pass2" type="password" class="inputCool" id="pass2" size="24" maxlength="12" /></td>
      </tr>
      <tr>
        <td><?php echo $wikilang_email; ?></td>
        <td>
<?php echo '<input name="mail" type="text" id="mail" size="30" maxlength="100" value="'.$mail.'" />'; ?>
        </td>
      </tr>
      <tr>
        <td><?php echo $wikilang_website; ?></td>
        <td>
<?php echo '<input name="web" type="text" size="60" maxlength="255" value="'.$web.'" />'; ?>
        </td>
      </tr>
      <tr>
      	<td><?php echo $wikilang_hide_status; ?></td>
      	<td>
<?php
	if (!$hide)
		echo '<input type="checkbox" name="hide" value="true" />';
		else 
		echo '<input type="checkbox" name="hide" value="true" checked/>';
?> <?php echo $wikilang_hide; ?>
      	</td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
          <input name="sent" type="submit" id="sent" value="<?php echo $wikilang_update; ?>" class="buttonCool" />
        </div></td>
      </tr>
    </table>
  </form>
  <p>
<?php echo '<a href="/user/'.$userID.'" >'; ?>
  <?php echo $wikilang_view_profile; ?> </a></p>
<?php 
	include('footer.php');
	bbdd_close();
?>
  
</div>
</body>
</html>