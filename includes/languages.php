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

	$query = "select appfile from applangs where lang_code = '".$_SESSION['applang']."'";
	$result = mysql_query($query);
	$mylang = @mysql_result($result, 0);
	if ((!isset($mylang)) || ($mylang=='')) $mylang='english.php';
	include('languages/'.$mylang);
	
	define('my_completed', $wikilang_completed);
	define('date_prefix', $wikilang_date_prefix);
	define('date_suffix', $wikilang_date_suffix);
	define('hours', $wikilang_date_hours);
	define('minutes', $wikilang_date_minutes);
	define('seconds', $wikilang_date_seconds);
	define('date_sufix', $wikilang_date_suffix);
	define('days', $wikilang_date_days);
	

	function comboAppLanguages()
	{
		echo '<select name="applang" class="inputCool" onchange="changeAppLang();" id="comboLang">';
		$query = "select * from applangs";
		$result = mysql_query($query);
		
		while ($row = mysql_fetch_assoc($result))
		{
			
			if ($row['lang_code']!=$_SESSION['applang'])
				echo '<option value="'.$row['lang_code'].'">'.$row['lang_name'].'</option>';
				else 
					echo '<option selected value="'.$row['lang_code'].'">'.$row['lang_name'].'</option>';
		}
		echo '</select>';
	}
	function obtenerFecha($datetime)
    {
		
    	$timestamp = mysqlDatetimeToUnixTimestamp($datetime);
    	$actual = time();
    	
    	$resta = $actual - $timestamp;
    	$minutos = round($resta / 60);
    	$horas = round($minutos/60);
    	$dias = round($horas / 24);
    	
    	if ($horas<24)
    	{
    		if ($horas<1)
    		{
    			if ($minutos<1)
    				return date_prefix." $resta ".' '.seconds.' '.date_suffix;
    				else 
    				return date_prefix." $minutos ".' '.minutes.' '.date_suffix;
    		}
    		else 
    			return date_prefix." $horas ".hours.' '.date_suffix;
    	}
    	else 
    		return date_prefix." $dias ".days.' '.date_suffix;
    	
    }
    
    function bd_getLangState($subID, $langID, $fversion)
	{
		
		$query = "select state,testing from flangs where subID=$subID and lang_id=$langID and fversion=$fversion";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$testing = $row['testing'] == '1';
		$state = $row['state'];
		
		if ($testing)
		{
			$query = "select total,current from testing where subID=$subID and lang_id=$langID and fversion=$fversion";
			$result = mysql_query($query);
			$numrows = mysql_affected_rows();
			if ($numrows>0)
			{
				$row = mysql_fetch_assoc($result);
				$percent = ($row['current'] / $row['total']) * 100;
				$percent = number_format($percent, 2);
				return "Testing... ($percent%)";
			}
		}
		

		
		if ($state>=100)
			return my_completed;
			else 
			return "$state% ".my_completed;
	}
	
?>