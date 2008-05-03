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

	define("LOG_updateprop", 1);
	define("LOG_deleteLanguage", 2);
	define("LOG_deleteVersion", 3);
	define("LOG_deleteFile", 4);
	define("LOG_deleteLink", 5);
	define("LOG_merge", 6);
	define("LOG_troll", 7);
	
	
	
	
	function log_insert($tipo, $texto, $userID, $subID, $moderator)
	{
		$mytexto = addslashes($texto);
		$query = "insert into `log`(action,text,date,userID,moderator,subID) ";
		$query .= "values($tipo,'$mytexto',NOW(),$userID,";
		if ($moderator) $query .= '1'; else $query .='0';
		$query.=",$subID)";
		mysql_query($query);
	}
	
	function log_addAction($texto)
	{
		$mytexto = addslashes($texto);
		$query = "insert into log_actions(actionDESC) values('$mytexto')";
		mysql_query($query);
		
		return mysql_insert_id();
	}
	
	function log_getAction($id)
	{
		$query = "select actionDESC from log_actions where actionID=$id";
		$result = mysql_query($query);
		
		return stripslashes(@mysql_result($result, 0));
	}


?>