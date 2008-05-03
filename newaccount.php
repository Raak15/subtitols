<?php include('includes/includes.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo "$wikilang_new_account - Subtítols"; ?></title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
	<script type="text/javascript"><?php include('js/quicksearch.php'); ?></script>
	<style type="text/css" media="screen">
		@import url(/css/main.css);
	</style>
	
	<script type="text/javascript">
		alert(document.getElementById("izqu").style.height);
		document.getElementById("izqu").style.height = document.getElementById("dere").style.height;
	</script>

<script type="text/javascript">
	function checkname()
	{
		var username = $("username").value;
		$("cuser").innerHTML = '<img src="/images/loader.gif" />';
		if (username.length>3)
		{
			new Ajax('<?php echo $SCRIPT_PATH; ?>ajax_checkuser.php?name='+username,
			{
				method:'get',
				update:$("cuser")
			}).request();
		}
	}
	
	function checkpassword()
	{
		var pass1= $("pass1").value;
		var pass2= $("pass2").value;
		var span = $("cpass");
		
		if ((pass1.length>0) && (pass2.length>0))
		{
			if (pass1==pass2)
				span.innerHTML = '<font color="green"><?php echo $wikilang_passwords_match; ?></font>';
			else
				span.innerHTML = '<font color="red"><?php echo $wikilang_passwords_dontmatch; ?></font>';
		}
		else
			span.innerHTML = '&nbsp;';
		
	}
	
	function check()
	{
		var pass1= $("pass1").value;
		var pass2= $("pass2").value;
		if ((pass1!=pass2) || (pass2.length<1))
		{
			alert("<?php echo $wikilang_passwords_dontmatch; ?>")
			return false;
		}
		
		var username = $("username").value;
		if (username.length<4)
		{
			alert("<?php echo $wikilang_error_shortuser; ?>")
			return false;
		}
		
		
		return true;
	}
</script>

</head>

	<body>

		<?php include("includes/general/moderator_bar.php"); ?>

		<?php include("header.php"); ?>

		<?php include("includes/general/nav_home.php"); ?>

		<div id="sitebody">

			<div id="slogan">

				<h2><?php echo $wikilang_new_account; ?></h2>

			</div>

			<div id="contenido">
				
				<div class="divisor-1">
					
					<div id="form-user">
						
						<h3>Not an user? Register here.<br />Easy as 1-2-3</h3>
						
						<form action="/newaccount_do.php" method="post" onsubmit="return check();">
							
							<div class="modulo">
								<div class="labels">
									<label for="username"><?php echo $wikilang_user; ?></label>
								</div>
								<div class="inputs">
									<input name="username" type="text" class="inputCool" id="username" onkeyup="checkname();" />
								</div>
								<span id="cuser">&nbsp;</span>
							</div>
							
							<div class="modulo">
								<div class="labels">
									<label for="mail"><?php echo $wikilang_email; ?></label>
								</div>
								<div class="inputs">
									<input name="mail" type="text" class="inputCool" id="mail" maxlength="100" size="30"/>
								</div>
							</div>
						
							<div class="modulo">
								<div class="labels">
									<label for="website"><?php echo $wikilang_website; ?></label>
								</div>
								<div class="inputs">
									<input name="website" type="text" class="inputCool" id="site" size="50" maxlength="255"/>
								</div>
							</div>
							
							<div class="modulo">
								<div class="labels">
									<label for="pass1"><?php echo $wikilang_password; ?></label>
								</div>
								<div class="inputs">
									<input name="pass1" type="password" class="inputCool" id="pass1" onkeyup="checkpassword();"/>
								</div>
							</div>
							
							<div class="modulo">
								<div class="labels">
									<label for="pass1"><?php echo $wikilang_password_confirm; ?></label>
								</div>
								<div class="inputs">
									<input name="pass2" type="password" class="inputCool" id="pass2" onkeyup="checkpassword();"/>&nbsp;&nbsp;<span id="cpass">&nbsp;</span>
								</div>
							</div>
						
							<p><input name="Submit" type="submit" class="coolBoton" value="<?php echo $wikilang_join; ?>" /></p>

						</div>

						</form>

					</div>
					
				</div>
				
				<div class="divisor-2">
					
					<div class="register-tips" id="dere">
						
						<h3>Why you need to register</h3>
						
						<p>Te podrás registrar en Subtitols! con tres simples pasos. Sólo tienes que contestar unas preguntas, escoger tu nombre de usuario y contraseña, y listo.</p>
						<ol>
							<li>Users can edit and participate on the subtitles translations.</li>
							<li></li>
						
					</div>
					
				</div>
				
				<div class="clear"></div>

			</div>

 <?php include('footer.php'); 
	  bbdd_close();
?>



</body>
</html>