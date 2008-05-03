<?php
	include ('includes/includes.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo "$wikilang_login - Subtítols"; ?></title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<script type="text/javascript" src="/js/mootools.v1.11.js"></script>
	<script type="text/javascript"><?php include('/js/quicksearch.php'); ?></script>
	<script type="text/javascript"><?php include('/js/showsub.php'); ?></script>
	<style type="text/css" media="screen">
		@import url(/css/reset-fonts-grids.css);
		@import url(/css/main.css);
	</style>
</head>

<body>

	<?php include("includes/general/moderator_bar.php"); ?>
	
	<?php include("header.php"); ?>
	
	<?php include("includes/general/nav_home.php"); ?>
	
	<div id="sitebody">

		<div id="slogan">
			
			<h2><?php echo "$wikilang_login"; ?></h2>
		
		</div>
			
		<div id="contenido-dividido">
			
  			<form action="dologin.php" method="post" name="loginform">
  				
				<div class="moduloB">
				
				<label><?php echo $wikilang_user; ?> <input name="username" type="text" class="inputCool" id="username" maxlength="20" /></label>
  
				<label><?php echo $wikilang_password; ?> <input name="password" type="password" class="inputCool" id="password" maxlength="30" /></label>
    			
				<label><input name="remember" type="checkbox" id="remember" value="true" /> <?php echo $wikilang_remember_me; ?></label>
        		
				<p><input name="Submit" type="submit" class="coolBoton" value="<?php echo $wikilang_login; ?>" /></p>
				
				</div>

   	<td colspan="2"><a href="newaccount.php"><?php echo $wikilang_new_account; ?></a> &middot; <a href="/resetpass.php"><?php echo $wikilang_recover_password; ?></a></td>
   </tr>
</table>
  </form>

  <p>&nbsp;</p>
  <?php include("footer.php"); 
  	bbdd_close();
  ?>
</div>
</body>
</html>
