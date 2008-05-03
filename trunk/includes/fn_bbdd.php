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
	
	function bbdd_connect()
	{
		include('config.php');
		
		if (!mysql_connect($BBDD_host, $BBDD_user, $BBDD_password))
		{
			echo "Database connection error";
			exit();
		}
		mysql_select_db($BBDD_database);
		mysql_query('SET NAMES utf8');
	}
	
	function bbdd_close()
	{
		mysql_close();
	}
?>