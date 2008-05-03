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

	function mysqlDatetimeToUnixTimestamp($datetime){
        $val = explode(" ",$datetime);
        $date = explode("-",$val[0]);
        $time = explode(":",$val[1]);
        return @mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);
    }
    
     function restarFechaSp($dFecIni, $dFecFin)
     {
     	
		$dFecIni = str_replace("-","",$dFecIni);
		$dFecIni = str_replace("/","",$dFecIni);
		$dFecFin = str_replace("-","",$dFecFin);
		$dFecFin = str_replace("/","",$dFecFin);


		ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
		ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);

		$date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
		$date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);
		return round(($date2 - $date1) / (60 * 60 * 24));
	}
    
    
    function hidden($name, $value)
	{
		echo "<input type=\"hidden\" name=\"$name\" value=\"$value\" />";
	}
	
	function uncampo($campo, $tabla, $condicion, $valor)
	{
		$query = "select $campo from tabla where $condicion = $valor";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function getBool($name)
	{
		return (isset($_GET[$name]) && ($_GET[$name]=='1'));
	}
    
	function location($url)
	{
		@header("Location: $url");
	}
	
	function logged()
	{
		return isset($_SESSION['userID']);
	}
	
	function textbox($name, $value, $size, $maxlength, $id, $class)
	{
		echo "<input type=\"text\" name=\"$name\" class=\"$class\" size=\"$size\" maxlength=\"$maxlength\" value=\"$value\" />";
	}
?>