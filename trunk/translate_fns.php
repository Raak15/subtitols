<?php

function tn_start($subID,$fversion,$lang_from,$lang_to)
{
	$query = "select count(*) from translating where subID=$subID and lang_id=$lang_to and fversion=$fversion";
	$result = mysql_query($query);
	$count = intval(mysql_result($result, 0));
	if ($count == 0)
	{
		$query = "select edited_seq from subs where subID=$subID and fversion=$fversion and lang_id=$lang_from and last=1 order by edited_seq";
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result))
		{
			$myseq = $row['edited_seq'];
			$query = "insert into translating(subID,fversion,lang_id,sequence) values($subID,$fversion,$lang_to,$myseq)";
			mysql_query($query);
		}
		
		//flangs
		
		$query = "select utf from languages where langID=$lang_to";
		$result = mysql_query($query);
		$cyrillic = mysql_result($result, 0);
		
		$query = "insert into flangs(subID,fversion,lang_id,state,testing,original,totalSeq,totalVersion0,cyrillic) ";
		$query .="values($subID,$fversion,$lang_to,0,0,0,0,0, $cyrillic)";
		mysql_query($query);
	}
}

function tn_check($subID,$fversion,$originalLangID,$targetLangID)
{
	bd_clear_flang($subID);
	cleanTranslated();
	last_translated($subID, $fversion, $targetLangID);
	
	$query = "select edited_seq from subs where subID=$subID and fversion=$fversion and lang_id=$originalLangID and last=1 order by edited_seq";
	$tresult = mysql_query($query);
	$numresults = mysql_affected_rows();
	
	
	while ($row = mysql_fetch_assoc($tresult))
	{
		$c = $row['edited_seq'];
		$contadores[$c] = 0;
	}
	
	$query = "select edited_seq from subs where subID=$subID and fversion=$fversion and lang_id=$targetLangID and last=1 order by edited_seq";	
	$uresult = mysql_query($query);
	$unumresults = mysql_affected_rows();
	
	while ($row = mysql_fetch_assoc($uresult))
	{
		$c = $row['edited_seq'];
		$contadores[$c]++;
	}
	
	mysql_data_seek($tresult, 0);
	$counter = 0;
	while ($row = mysql_fetch_assoc($tresult))
	{
		$c = $row['edited_seq'];
		$count = $contadores[$c];
		
		$counter++;
		
		
		if ($count<1)
		{
			$query = "select count(*) from translating where subID=$subID and fversion=$fversion and lang_id=$targetLangID and sequence=$c";
			$result = mysql_query($query);
			$ccount = mysql_result($result ,0);
			if ($ccount<1)
			{
				$query = "insert into translating(subID,fversion,lang_id,sequence) values($subID,$fversion,$targetLangID,$c)";
				mysql_query($query);
			}
		}
		elseif($count>1)
		{
			$mycount = $count - 1;
			$query = "delete from subs where subID=$subID and lang_id=$targetLangID and fversion=$fversion and edited_seq=$c and last=1 order by entryID limit $mycount";
			mysql_query($query);
			
		}
		elseif($count==1)
		{
			$query = "delete from translating where subID=$subID and lang_id=$targetLangID and fversion=$fversion and sequence=$c";
			mysql_query($query);
		}
		
	}
	
	
	
	
	//cuenta de lineas manual
	$query = "select count(*) from subs where subID=$subID and fversion=$fversion and lang_id=$targetLangID and last=1";
	$result = mysql_query($query);
	$l0count = mysql_result($result, 0);
	
	$query = "select count(*) from subs where subID=$subID and fversion=$fversion and lang_id=$targetLangID";
	$result = mysql_query($query);
	$lcount = mysql_result($result, 0);
	
	$query = "update flangs set totalseq=$lcount,totalVersion0=$l0count where subID=$subID and fversion=$fversion and lang_id=$targetLangID";
	mysql_query($query);
	
	//manual get lang State
	$moriginal = bd_langVersion0Count($subID, $originalLangID, $fversion);
	$new = bd_langVersion0Count($subID,$targetLangID, $fversion);
	$percent = number_format(($new / $moriginal)*100,2);
	
	$query = "update flangs set state=$percent where subID=$subID and fversion=$fversion and lang_id=$targetLangID";
	mysql_query($query);
	
		$state = bd_getLangState($subID, $targetLangID, $fversion);
		if ($state==my_completed)
		{
				//borra comentarios de traduccion
				
				$query = "delete from translating where subID=$subID and lang_id=$targetLangID fversion=$fversion";
				mysql_query($query); 
				
				$query = "select count(*) from lasttranslated where subID=$subID and fversion=$fversion and lang_id=$targetLangID";
				$result = mysql_query($query);
				
				if (mysql_result($result, 0)<1)
				{
								
					$query = "insert into lasttranslated(subID,fversion,lang_id,date) values($subID, $fversion, $targetLangID, NOW())";
					mysql_query($query);
				}
		}
	cleanTranslated();
	last_translated($subID, $fversion, $targetLangID);
}

function tn_super_test($subID, $fversion, $lang)
{
	$original = bd_getOriginalLang($subID, $fversion);
	tn_check($subID, $fversion, $original, $lang);
	tn_start($subID, $fversion, $original, $lang);
	//tn_check($subID, $fversion, $original, $lang);
}

function tn_duplicates($subID, $fversion, $lang)
{
	$query = "select distinct(edited_seq) as secuencia from subs where subID=$subID and fversion=$fversion and lang_id=$lang";
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_assoc($result))
	{
		$secuencia = $row['secuencia'];
		$query = "select edited_seq,version,count(*) as cuenta from subs where subID=$subID and fversion=$fversion and lang_id=$lang and edited_seq=$secuencia group by version";
		$sresult = mysql_query($query);
		$srow = mysql_fetch_assoc($sresult);
		$cuenta = $srow['cuenta'];
		$version = $srow['version'];
		if ($cuenta>1)
		{
			$cuenta--;
			$query = "delete from subs where subID=$subID and fversion=$fversion and lang_id=$lang and edited_seq=$secuencia and version=$version order by entryID DESC limit $cuenta";
			mysql_query($query);
		}
	}
	
	$version0 = bd_langVersion0Count($subID, $lang, $fversion);
	$query = "update flangs set totalVersion0=$version0 where subID=$subID and fversion=$fversion and lang_id=$lang";
	mysql_query($query);
}

function last_translated($subID,$fversion,$lang)
{
	include_once('includes/languages.php'); 
	$query = "select count(*) from lasttranslated where subID=$subID and fversion=$fversion and lang_id=$lang";
	$result = mysql_query($query);
	$cuenta = mysql_result($result, 0);
	
	if ($cuenta>0)
	{
		$state = bd_getLangState($subID,$lang,$fversion);
		if ($state!=my_completed)
		{
			$query = "delete from lasttranslated where subID=$subID and fversion=$fversion and lang_id=$lang";
			mysql_query($query);
		}
	}
}

?>