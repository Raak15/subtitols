<?php
	include('includes/includes.php');
	
	if (!bd_userIsAdministrator())
	{
		bbdd_close();
		echo "You're not administrator";
		exit();
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
User admin - wikisubtitles
</title>
<link href="/css/wikisubtitles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
<script type="text/javascript">
 	function checkname()
	{
		var username = $("to").value;
		$("cuser").innerHTML = '<img src="/images/loader.gif" />';
		if (username.length>3)
		{
			new Ajax('/msg_checkuser.php?name='+username,
			{
				method:'get',
				update:$("cuser")
			}).request();
		}
	}

</script>
</head>
<?php include ('header.php'); ?>
<div class="titulo">User administation</div>
<br />
<form method="post">
<div id="cuser">&nbsp;</div>
User lookup 
<?php
	if (!isset($_POST['to']))
	echo '<input type="text" size="20" maxlength="20" name="to" id="to" class="inputCool" onkeyup="checkname();"/>';
	else
	echo '<input type="text" size="20" maxlength="20" name="to" id="to" class="inputCool" onkeyup="checkname();" value="'.$_POST['to'].'" />';
?>	 
	
<input type="submit" name="submit" value="<?php echo $wikilang_search; ?>" class="coolBoton"/>

</form>
<br />

<?php

	if (isset($_POST['email']))
	{
		$email = $_POST['email'];
		$website = $_POST['website'];
		$hide = (isset($_POST['hide'])) && $_POST['hide']=='true';
		$applang = $_POST['applang'];
		$range = $_POST['rol'];
		$pass = $_POST['pass'];
		$delete = isset($_POST['delete']) && $_POST['delete'] == 'true';
		$username = $_POST['username'];
		$cuserID = $_POST['userID'];
		
		if (isset($_POST['delete']))
		{
			$query = "delete from users where userID=$cuserID";
			mysql_query($query);
			
		}
		else 
		{
			if ((isset($pass)) && ($pass!=""))
			{
				$md5 = md5($pass);
				$query = "update users set password='$md5' where userID=$cuserID";
				mysql_query($query);
			}
			
			$query = "update users set username='$username',mail='$email',website='$website',applang='$applang',range=$range,hide=";
			if ($hide) $query.='1'; else $query.='0';
			$query.=' where userID='.$cuserID;
			mysql_query($query);
	
		}
	}
	if (isset($_POST['to']))
	{
		$username = $_POST['to'];
		$query = "select * from users where username='$username'";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
	
		$cuserID = $row['userID'];
		$mail = $row['mail'];
		$website = $row['website'];
		$joined = $row['joined'];
		$uploads = $row['uploads'];
		$range = $row['range'];
		$ip = $row['ip'];
		$last = $row['last'];
		$navegate = $row['navegate'];
		$hide = $row['hide'];
		$applang = $row['applang'];
		
?>
<form method="post">
<table align="center" border="0" width="80%">
<tr width="10%">
	<td class="SectionTitle">userID</td>
	<td><?php echo $cuserID; ?></td>
</tr>
<tr>
	<td class="SectionTitle"><?php echo $wikilang_user; ?></td>
	<td><input type="text" name="username" class="inputCool" size="30" maxlength="200" value="<?php echo $username; ?>"/></td>
</tr>
<tr>
	<td class="SectionTitle"><?php echo $wikilang_email; ?></td>
	<td><input type="text" name="email" class="inputCool" size="30" maxlength="200" value="<?php echo $mail; ?>"/></td>
</tr>
<tr>
	<td class="SectionTitle"><?php echo $wikilang_website; ?></td>
	<td><input type="text" name="website" class="inputCool" size="30" maxlength="200" value="<?php echo $website; ?>"/></td>
</tr>
<tr>
	<td class="SectionTitle"><?php echo $wikilang_password; ?></td>
	<td><input type="password" name="pass" class="inputCool" size="30" maxlength="200" /></td>
</tr>
<tr>
	<td class="SectionTitle"><?php echo $wikilang_account_creation; ?></td>
	<td><?php echo $joined." (".obtenerFecha($joined).")"; ?></td>
</tr>
<tr>
	<td class="SectionTitle"><?php echo $wikilang_files_create; ?></td>
	<td><?php echo $uploads; ?></td>
</tr>
<tr>
	<td class="SectionTitle">Rol</td>
	<td>
	<select name="rol" class="inputCool">
	<option value="0" <?php if ($range==0) echo "selected"; ?>>User</option>
	<option value="1" <?php if ($range==1) echo "selected"; ?>>Moderator</option>
	<option value="2" <?php if ($range==2) echo "selected"; ?>>Administrator</option>
	</select>
	
	</td>
</tr>
<tr>
	<td class="SectionTitle">Last IP</td>
	<td><?php echo $ip; ?></td>
</tr>
<tr>
	<td class="SectionTitle">Last request</td>
	<td><?php echo $navegate; ?></td>
</tr>
<tr>
	<td class="SectionTitle">Last access</td>
	<td><?php echo $last." (".obtenerFecha($last).")"; ?></td>
</tr>
<tr>
	<td class="SectionTitle"><?php echo $wikilang_hide_status; ?></td>
	<td><input type="checkbox" name="hide" value="true" <?php if ($hide) echo "checked"; ?> /></td>
</tr>
<tr>
	<td class="SectionTitle"><?php echo $wikilang_site_language; ?></td>
	<td>
<?php
		echo '<select name="applang" class="inputCool">';
		$query = "select * from applangs";
		$result = mysql_query($query);
		
		while ($row = mysql_fetch_assoc($result))
		{
			
			if ($row['lang_code']!=$applang)
				echo '<option value="'.$row['lang_code'].'">'.$row['lang_name'].'</option>';
				else 
					echo '<option selected value="'.$row['lang_code'].'">'.$row['lang_name'].'</option>';
		}
		echo '</select>';
?>
	</td>
</tr>
<tr>
	<td class="SectionTitle">Delete this user</td>
	<td><input type="checkbox" name="delete" value="true"/> Delete</td>
</tr>
<tr align="center">
<td colspan="2"><input type="submit" value="<?php echo $wikilang_update; ?>" class="coolBoton"/></td>
</tr>

</table>
<?php
	hidden('userID', $cuserID);
	hidden('to', $username);
?>
</form>
<?php
	}
?>

<?php include ('footer.php'); 
	bbdd_close();
?>

<body>
</body>
</html>