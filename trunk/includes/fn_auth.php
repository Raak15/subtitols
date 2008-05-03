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

	function session()
	{
		@session_start();
		
	}
	
	function remember_me()
	{
		if (!isset($_SESSION['userID']))
		{

			$cuser = $_COOKIE['wikisubtitlesuser'];
			$cpass = $_COOKIE['wikisubtitlespass'];
			
			if (isset($cuser) && isset($cpass))
			{
				$query = "select password,username,applang from users where userID=$cuser";
				$result = mysql_query($query);
				$numresults = mysql_affected_rows();
				if ($numresults>0)
				{
					$row = mysql_fetch_assoc($result);
					if ($row['password'] == $cpass )
					{
						$_SESSION['userID'] = $cuser;
						$_SESSION['username']= $row['username'];
						
						$query = "update users set last=NOW(),ip='".$_SERVER['REMOTE_ADDR']."' where userID=$cuser";
						mysql_query($query);
						
						$applang = $row['applang'];
						if ($applang=='')
						{
							$query = "update users set applang='en' where userID=$cuser";
							mysql_query($query);
							$applang = 'en';
						}
						$_SESSION['applang'] = $applang;
						
					}
				}
			}
			else 
			{
				if (!isset($_SESSION['applang']))
					$_SESSION['applang'] = 'en';
			}
		}
		else {
			$cuser = $_SESSION['userID'];
			$query = "update users set last=NOW(),navegate='".$_SERVER['REQUEST_URI']."',ip='".$_SERVER['REMOTE_ADDR']."' where userID=$cuser";
			mysql_query($query);
		}
	}

?>