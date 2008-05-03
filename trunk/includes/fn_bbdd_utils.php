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

if (!function_exists('str_ireplace')) {
    function str_ireplace($needle, $str, $haystack) {
        return preg_replace("/$needle/i", $str, $haystack);
    }
} 

	function bd_getUsername($userid)
	{
		$query = "select username from users where userID=$userid";
		$result = mysql_query($query);
		if (mysql_affected_rows()>0)
			return mysql_result($result, 0);
			else 
			return "";
	}
	
	function bd_getTitle($subID)
	{
		$mquery = "select title from files where subID=$subID";
		$mresult = mysql_query($mquery);
		return stripslashes(@mysql_result($mresult, 0));
	}
	
	function bd_totalSequences()
	{
		$query = "select MAX(entryID) from subs";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_totalFiles()
	{
			$query = "select count(*) from flangs where state=100";
			$result = mysql_query($query);
			return mysql_result($result, 0);
	}
	
	function bd_totalUsers()
	{
		$query = "select count(*) from users";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_getShowTitle($showID)
	{
		$query = "select title from shows where showID=$showID";
		$result = mysql_query($query);

		return stripslashes(@mysql_result($result, 0));
	}
	
	function bd_getLangName($langID)
	{
		$querylang = "select lang_name from languages where langID=$langID";
		$resultlang = mysql_query($querylang);
		return @mysql_result($resultlang, 0);
	}
	
	function bd_langVersion0Count($subID, $langID, $fversion)
	{
		$querycompleted = "select count(*) from subs where subID=$subID and fversion=$fversion and lang_id=$langID and version=0";
		$result = mysql_query($querycompleted);
		return mysql_result($result, 0);
	}
	
	function bd_countSubNoOriginal($subID, $fversion)
	{
		$query = "select count(*) from subs where subID=$subID and fversion=$fversion and original=0";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_countSubModifications($subID, $fversion)
	{
		$query = "select count(*) from subs where subID=$subID and fversion=$fversion and version>0";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_countSubLangModifications($subID, $langID, $fversion)
	{
		$query = "select count(*) from subs where subID=$subID and fversion=$fversion and lang_id=$langID and version>0";
		$result = mysql_query($query);
		return mysql_result($result, 0);
		
	}
	
	function bd_getOriginalLang($subID, $fversion)
	{
		$query = "select lang_id from flangs where subID=$subID and fversion=$fversion and original=1 limit 1";
		$result = mysql_query($query);
		return @mysql_result($result, 0);
	}
	
	function bd_getLastSequence($subID, $langID, $fversion)
	{
		$query = "select totalVersion0 from flangs where subID=$subID and fversion=$fversion and lang_id=$langID";
		$result = mysql_query($query);
		return intval(mysql_result($result, 0));
	}
	
	
	
	function bd_increaseDownloads($subID)
	{
		$query = "update files set downloads = downloads + 1  where subID=$subID";
		$result = mysql_query($query);
	}
	
	function bd_getLastModification($id, $lang, $fversion)
	{
		$query = "select in_date from subs where subID=$id and fversion=$fversion and lang_id=$lang order by in_date DESC limit 1";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	
	function bd_countModificationsByUser($id, $lang, $user, $fversion)
	{
		$query = "select count(*) from subs where subID=$id and fversion=$fversion and lang_id=$lang and authorID=$user";
		$result = mysql_query($query);
		return mysql_result($result, 0);
		
	}
	
	function bd_countOriginalLinesByUserSub($id, $lang, $fversion, $author)
	{
		$query = "select count(*) from subs where subID=$id and fversion=$fversion and lang_id=$lang and authorID=$author and version=0";
		$result = mysql_query($query);
		return @mysql_result($result, 0);
	}
	
	function bd_countLastLinesByUserSub($id, $lang, $fversion, $author)
	{
		$query = "select count(*) from subs where subID=$id and fversion=$fversion and lang_id=$lang and authorID=$author and last=1";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_countFilesByUser($user)
	{
		$query = "select count(*) from fversions where author=$user";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_countLinesByUser($user)
	{
		$query = "select count(*) from subs where authorID=$user";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_countLastLinesByUser($user)
	{
		$query = "select count(*) from subs where authorID=$user and last=1";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_countVersion0LinesByUser($user)
	{
		$query = "select count(*) from subs where authorID=$user and version=0";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_countOriginalLinesByUser($user)
	{
		$query = "select count(*) from subs where authorID=$user and original=1";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_countEditedLinesByUser($user)
	{
		$query = "select distinct subID,lang_id from subs where authorID=$user and version>0";
		$resultedited = mysql_query($query);
		return mysql_affected_rows();
	}
	
	function bd_countSeasonsShow($showID)
	{
		$query = "select count(distinct (season)) from files where is_episode=1 and showID=$showID";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_countEpisodesShow($showID)
	{
		$query = "select distinct season,season_number from files where is_episode=1 and showID=$showID";
		$result = mysql_query($query);
		return mysql_affected_rows();
	}
	
	function bd_getFVersion($subID, $fversion)
	{
		$query = "select versionDesc from fversions where subID=$subID and fversion=$fversion";
		$result = mysql_query($query);
		$desc = @mysql_result($result, 0);
		if ($desc=='') return 'Unspecific';
		else return $desc;
	}
	
	function bd_getFVersionAuthor($subID, $fversion)
	{
		$query = "select author from fversions where subID=$subID and fversion=$fversion";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}		//echo $querylang;
	
	function bd_countLinesEditedByLang($subID, $langID, $fversion)
	{
		$query = "select count(*) from subs where subID=$subID and fversion=$fversion and lang_id=$langID and version>0";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_getFVersionComment($myID, $v)
	{
		$query = "select comment from fversions where subID=$myID and fversion=$v";
		$result = mysql_query($query);
		return stripslashes(mysql_result($result, 0));
	}
	
	function bd_getFVersionSize($myID, $v)
	{
		$query = "select size from fversions where subID=$myID and fversion=$v";
		$result = mysql_query($query);
		$size= @mysql_result($result, 0);
		if ($size==0) return '-'; else return $size;
		
	}
	
	function bd_userIsModerador()
	{
		if (!isset($_SESSION['userID'])) return false;
		
		$userID = $_SESSION['userID'];
		$query = "select range from users where userID=$userID";
		$result = mysql_query($query);
		$range = mysql_result($result, 0);
		return ($range>0);
	}
	
	function bd_userIsAdministrator()
	{
		if (!isset($_SESSION['userID'])) return false;
		
		$userID = $_SESSION['userID'];
		$query = "select range from users where userID=$userID";
		$result = mysql_query($query);
		$range = mysql_result($result, 0);
		return ($range>1);
	}
	
	
	
	function bd_countLinesByLang($subID, $fversion, $lang)
	{
		$query = "select count(*) from subs where subID=$subID and fversion=$fversion and lang_id=$lang";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	
	function cleanTranslated()
	{
		$query = "select id,subID,fversion,lang_id,count(*) from lasttranslated group by subID,fversion,lang_id" ;
		$result = mysql_query($query);

		while ($row = mysql_fetch_assoc($result))
		{
			$id = $row['id'];
			$subID = $row['subID'];
			$fversion = $row['fversion'];
			$lang_id = $row['lang_id'];
			$count = $row['count(*)'];

			if ($count>1)
			{
				$menos = $count - 1;
				$query = "delete from lasttranslated where subID=$subID and fversion=$fversion and lang_id=$lang_id limit $menos";
				mysql_query($query);
			}
		}

	}
	
	function bd_getUrl($id)
	{
		
		$query = "select is_episode,showID,season,season_number,title from files where subID=$id";
		$result = mysql_query($query);
		
		$row = @mysql_fetch_assoc($result);
		$isep = $row['is_episode'];
		$title = stripslashes($row['title']);
		if ($isep)
		{
			$season = $row['season'];
			$season_number = $row['season_number'];
			$showID=$row['showID'];
			

			$titlearray = split(' - ', $title);
			$eptitle = $titlearray[2];

			$sName = bd_getShowTitle($showID);
			$sName = str_ireplace(' ', '_',$sName);
			$sName = str_ireplace('&','@', $sName);
			$sName = urlencode($sName);
			$eptitle = str_ireplace(' ', '_',$eptitle);
			$url = "serie/$sName/$season/$season_number/$eptitle";
		}
		else 
		{
			$title = str_ireplace(' ','_',$title);
			$title = str_ireplace('&','@',$title);
			$title = urlencode($title);
			$url = "film/$title";
		}
		
		return $SCRIPT_PATH.$url;
		
	}
	
	function bd_getIcon($id){
		$query = "select is_episode from files where subID=$id";
		$result = mysql_query($query);
		
		if (@mysql_result($result, 0))
		    return '<em class="ico itv">TV Show</em>';
			else return '<em class="ico imovie">Pelicula</em>';
	}
	
	function bd_clear_flang($subID)
	{
		$query = "select fversion,lang_id from flangs where subID=$subID";
		$result = mysql_query($query);
		while ($row=mysql_fetch_assoc($result))
		{
			$fversion = $row['fversion'];
			$lang = $row['lang_id'];
			$versiones[$fversion][$lang]++;
			if ($versiones[$fversion][$lang]>1)
			{
				$query = "delete from flangs where subID=$subID and fversion=$fversion and lang_id=$lang limit 1";
				mysql_query($query);
				$versiones[$fversion][$lang]--;
			}
		}
	}
	
	function bd_clear_ALLFlangs()
	{
		$query = "select distinct(subID) from flangs";
		$result =mysql_query($query);
		
		while ($row=mysql_fetch_assoc($result))
		{
			$id = $row['subID'];
			bd_clear_flang($id);
		}
	}
	
	function bd_link_getSubID($linkID)
	{
		$query = "select subID from links where linkID=$linkID";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_link_getFversion($linkID)
	{
		$query = "select fversion from links where linkID=$linkID";
		$result = mysql_query($query);
		return mysql_result($result, 0);
	}
	
	function bd_isUTF($subID,$fversion,$lang)
	{
		$query = "select cyrillic from flangs where subID=$subID and fversion=$fversion and lang_id=$lang";
		$result = mysql_query($query);
		
		return (mysql_result($result,0)==1);
	}
	
	function bd_flangsVersion0($subID, $fversion, $lang)
	{
		$query = "select totalVersion0 from flangs where subID=$subID and fversion=$fversion and lang_id=$lang";
		$result = mysql_query($query);
		return mysql_result($result ,0);
	}
	
	function bd_isMerged($subID, $fversion, $lang)
	{
		$query = "select merged from flangs where subID=$subID and fversion=$fversion and lang_id=$lang";
		$result = mysql_query($query);
		if (mysql_affected_rows()<1) return false;
		return (mysql_result($result, 0) == 1);
	}
	
	function bd_cleanLineas($subID, $fversion, $lang)
	{
		$query = "select distinct(edited_seq) from subs where subID=$id and fversion=$fversion and lang_id=$lang order by edited_seq";
		$result = mysql_query($query);
		$seq = 0;
	
		while ($row=mysql_fetch_assoc($result))
		{
			$seq++;
			$oldseq = $row['edited_seq'];
			$query = "update subs set sequence=$seq,edited_seq=$seq where subID=$id and fversion=$fversion and lang_id=$lang and edited_seq=$oldseq";
			mysql_query($query);
		
		}
	}
	
	function bd_fixTranslated()
	{
		$query = "select subID,fversion,lang_id from flangs where state=100 and original=0 and merged=0";
		$result = mysql_query($query);

		while ($row=mysql_fetch_assoc($result))
		{
			$subID = $row['subID'];
			$fversion = $row['fversion'];
			$lang = $row['lang_id'];

			$query = "select count(*) from lasttranslated where subID=$subID and fversion=$fversion and lang_id=$lang";
			$sresult = mysql_query($query);
			$count = mysql_result($sresult, 0);

			if ($count<1)
			{
				tn_check($subID, $fversion, bd_getOriginalLang($subID, $fversion), $lang);
				$titulo = bd_getTitle($subID);
			}
		}
	}
	
	function bd_testAll()
	{
		$query = "select subID,fversion,lang_id from flangs where state<=100";
		$result = mysql_query($query);

		while ($row=mysql_fetch_assoc($result))
		{
			$subID = $row['subID'];
			$fversion = $row['fversion'];
			$lang = $row['lang_id'];

			$original = bd_getOriginalLang($subID, $fversion);
			$title = bd_getTitle($subID);
			echo $title.'<br />';
			tn_check($subID, $fversion, $original, $lang);
		}
	}
	
	function bd_getLastTimeEdited($subID, $fversion, $lang)
	{
			$query = "select in_date from subs where subID=$subID and fversion=$fversion and lang_id=$lang order by entryID DESC limit 1";
			$result = mysql_query($query);
			
			return @mysql_result($result, 0);		
	}
	
	function bd_confirmTranslated($id, $fversion, $lang)
	{
		include_once('languages.php');
		$state = bd_getLangState($id, $fversion, $lang);
		
		if ($state!=my_completed)
		{
			$query = "delete from lasttranslated where subID=$id and fversion=$fversion and lang_id=$lang";
			mysql_query($query);
		}
	}

?>
