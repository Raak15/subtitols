<?php	
	/*
    This file is part of wikisubtitles.

    wikisubtitles is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    wikisubtitles is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*/
	
	$myID = $_GET['id'];
	
	$ushow = $_GET['ushow'];
	$ufilm = $_GET['ufilm'];
	if (isset($ushow))
	{
		$ushow = urldecode($ushow);
		$ushow = str_ireplace('_',' ', $ushow);
		$ushow = str_ireplace('@','&', $ushow);
		
		$useason = $_GET['useason'];
		$uepisode = $_GET['uepisode'];
		$ushow = addslashes($ushow);
		
		
		$query = "select showID from shows where title='$ushow'";
		$result = mysql_query($query);
		
		$showID = mysql_result($result, 0);
		
		$query = "select subID from files where showID=$showID and season=$useason and season_number=$uepisode";
		$result = mysql_query($query);
		
		
		if (mysql_affected_rows()<1)
		{
			bbdd_close();
			location('/index.php');
			exit();
		}
		
		$myID = mysql_result($result, 0);
	}
	elseif (isset($ufilm)) {
		$ufilm = urldecode($ufilm);
		$ufilm = str_ireplace('_',' ', $ufilm);
		$ufilm = str_ireplace('@','&', $ufilm);
		$ufilm = addslashes($ufilm);
		
		$query = "select subID from files where is_episode=0 and title like '$ufilm%' limit 1";
		$result = mysql_query($query);
		
		if (mysql_affected_rows()<1)
		{
			bbdd_close();
			location('/index.php');
			exit();
		}
		
		$myID=mysql_result($result, 0);
		
		
	}
	

	$query = "select * from files where subID=$myID";
	$result = mysql_query($query);
	$subfound = mysql_affected_rows()>0;
	
	if ($subfound)
	{
		$row = mysql_fetch_assoc($result);

		$title = stripslashes($row['title']);
		$is_episode = $row['is_episode'];
		$showID = $row['showID'];
		$season = $row['season'];
		$epnumber = $row['season_number'];
		$finished = $row['finished'];
		$duration = $row['duration'];
		$comment = stripslashes($row['comment']);
		$authorID = $row['author'];
		$downloads = $row['downloads'];
		
		$_SESSION['quicksearch'] = true;
		$_SESSION['quicksearch_show'] = $showID;
		$_SESSION['quicksearch_season'] = $season;
		$valor = $showID.'-'.$season.'x'.$epnumber;
		$_SESSION['quicksearch_epID'] = $valor;

		$author = bd_getUsername($authorID);

		if ($is_episode) $show = bd_getShowTitle($showID);
			else 
			{
				unset($_SESSION['quicksearch']);
				unset($_SESSION['quicksearch_show']);
				unset($_SESSION['quicksearch_season']);
				unset($_SESSION['quicksearch_epID']);
			}

		//versions
		$query = "select distinct fversion from fversions where subID=$myID";
		$vresult = mysql_query($query);
		$nversions = mysql_affected_rows();
		$contav = 0;
		if ($nversions>0)
		while ($vrow = mysql_fetch_assoc($vresult))
		{
			$v = $vrow['fversion'];
			
			$query = "select versionDesc,size,author,comment,indate from fversions where subID=$myID and fversion=$v";
			$vinforesult = mysql_query($query);
			$vinfo = mysql_fetch_assoc($vinforesult);
						
			$version[$contav]['num'] = $v;
			$version[$contav]['name'] = $vinfo['versionDesc'];
			if ($version[$contav]['name'] == '') $version[$contav]['name'] = 'Unspecific';
			$version[$contav]['authorID'] = $vinfo['author'];
			$version[$contav]['authorName'] = bd_getUsername($version[$contav]['authorID'])	;
			$version[$contav]['size'] = $vinfo['size'];
			$version[$contav]['comment'] = stripslashes($vinfo['comment']);
			$version[$contav]['date'] = $vinfo['indate'];
			
			$query = "select count(*) from downloads where subID=$myID and fversion=$v";
			$dresult = mysql_query($query);
			$version[$contav]['downloads'] = mysql_result($dresult, 0);
			$contav++;
		}

		
		for ($contav=0; $contav<$nversions; $contav++)
		{
			$v = $version[$contav]['num'];
			//*************** languages
			$query = "SELECT DISTINCT lang_id from flangs where subID=$myID and fversion=$v order by entryID";
			//echo $query;
			$result = mysql_query($query);
			$numlanguages = mysql_affected_rows();
			$version[$v]['numlanguages'] = $numlanguages;

				for ($c=0; $c<$numlanguages; $c++)
				{
					$row = mysql_fetch_assoc($result);
					$currentLangID = $row['lang_id'];
					$langs[$v][$c][0] = $currentLangID;
					$langs[$v][$c][1] = bd_getLangName($currentLangID);
					$langs[$v][$c][2] = bd_getLangState($myID, $currentLangID, $v);
					$langs[$v][$c][3] = bd_countLinesEditedByLang($myID, $currentLangID, $v);
					
					$query = "select count(*) from downloads where subID=$myID and fversion=$v and lang=$currentLangID";
					$dresult = mysql_query($query);
					$langs[$v][$c][4] = mysql_result($dresult, 0);

				}
			//********************************
		}

		$query = "select count(*) from subs where subID=$myID and version>0";
		$result = mysql_query($query);
		$nummod = mysql_result($result, 0);
		
		$candel = ($nummod<1) && ($nversion==1);
		
	}
	
	
	$meID=$_SESSION['userID'];
	if (isset($meID))
		$me = bd_getUsername($meID);
	$ahora = date("F j, Y, g:i a");	
?>