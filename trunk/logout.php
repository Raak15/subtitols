<?php
	include('includes/includes.php');
	unset ($_SESSION['username']);
	unset ($_SESSION['userID']);
	session_destroy();
	setcookie("wikisubtitlesuser", "", time()-3600);
	setcookie("wikisubtitlespass", "", time()-3600);
	header('Location: '.$SCRIPT_PATH);
?>