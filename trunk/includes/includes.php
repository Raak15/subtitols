<?php

/*
    This file is part of wikisubtitles.

    wikisubtitles is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*/

	session_start();
	include_once('config.php');
	
	if (($BBDD_user=='') || ($BBDD_host=='') || ($BBDD_password=='') || ($BBDD_database==''))
	{
		header("Location: ".$SCRIPT_PATH.'install/INSTALL');
		exit();
	}
	include_once ('includes/fn_bbdd.php');
	include_once ('includes/fn_bbdd_utils.php');
	bbdd_connect();
	
	
	include_once ('includes/fn_auth.php');
	remember_me();
	
	include_once('includes/languages.php');
	include_once ('includes/phputils.php');
	include_once ('includes/cyrillic.php');
	include_once('includes/rssreader.php');
	include_once('includes/logfns.php');
	
	
	$MAX_CATEGORY = 15;
?>