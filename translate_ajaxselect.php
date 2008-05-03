<?php
	include('includes/includes.php');
	include('translate_fns.php');
	
	$id = $_GET['id'];
	$fversion = $_GET['fversion'];
	$langto = $_GET['langto'];
	$langfrom = $_GET['langfrom'];
	$seq = $_GET['seq'];
	$userID = $_SESSION['userID'];
	
	$query = "select count(*) from translating where subID=$id and fversion=$fversion and lang_id=$langto";
	$result = mysql_query($query);
	$count = mysql_result($result, 0);
	if ($count==0)
	{
			$query = "select count(*) from testing where subID=$id and fversion=$fversion and lang_id=$langto";
		    $result = mysql_query($query);
			$count = mysql_result($result, 0);
			if ($count<1)
			{
				$total = bd_langVersion0Count($id,$langfrom, $fversion);

				tn_check($id, $fversion, $langfrom, $langto);
				
				$cstate = bd_getLangState($id, $langto, $fversion);
				echo "This subtitles has been tested.<br />Current state:$cstate</b> If it is not completed, please <b>reload</b> this page and fill the gaps.";
				
				if ($cstate=="$wikilang_completed")
				{
					$query = "select count(*) from lasttranslated where subID=$id and fversion=$fversion and lang_id=$langto";
					$result = mysql_query($query);
					$count = mysql_result($result, 0);
					
					if ($count<1)
					{
						$query = "insert into lasttranslated(subID,fversion,lang_id,date) values($id,$fversion,$langto, NOW())";
						mysql_query($query);
					}
				}
				
			}
			else
			 echo "Current subtitle is being tested. Please check its state in a few seconds";
	}
	else 
	{
		$query = "select count(*) from translating where subID=$id and fversion=$fversion and lang_id=$langto and tokened=0";
		$result = mysql_query($query);
		$count = mysql_result($result, 0);
		if ($count == 0)
		{
			$query = "update translating set tokened=0 where subID=$id and fversion=$fversion and lang_id=$langto";
			mysql_query($query);
		}

		$query = "select tokened,userID from translating where subID=$id and fversion=$fversion and lang_id=$langto and sequence=$seq";
		$result = mysql_query($query);
		$num = mysql_affected_rows();

		//ya esta editada
		if ($num==0)
		{
			$query = "select text from subs where subID=$id and fversion=$fversion and lang_id=$langto and sequence=$seq and last=1";
			$result = mysql_query($query);
			$text = stripslashes(mysql_result($result,0));
			echo "<form method=\"post\" onsubmit=\"return update('o', '$seq');\" id=\"of$seq\">";
			hidden("id", $id);
			hidden("fversion", $fversion);
			hidden("langto", $langto);
			hidden("langfrom",$langfrom);
			hidden("seq", $seq);
			echo "<textarea name=\"ttext\" cols=\"60\">";
			echo $text;
			echo "</textarea>";
			echo '<input name="Save" type="submit" class="coolBoton" value="'.$wikilang_save.'" />';
			echo '</form>';
		}
		else
		{
			$row = mysql_fetch_assoc($result);
			$tokened = $row['tokened'];
			$tokened_user = $row['userID'];

			if (!$tokened || ($tokened_user==$_SESSION['userID']))
			{
				//set token
				$query = "update translating set tokened=1,userID=$userID where subID=$id and fversion=$fversion and lang_id=$langto and sequence=$seq";
				mysql_query($query);
				echo "<form method=\"post\" onsubmit=\"return update('o', '$seq');\" id=\"of$seq\">";
				hidden("id", $id);
				hidden("fversion", $fversion);
				hidden("langto", $langto);
				hidden("langfrom",$langfrom);
				hidden("seq", $seq);
				echo '<input type="hidden" name="Cancel" id="Cancel'.$seq.'" value="false">';
				echo "<textarea name=\"ttext\" cols=\"60\">";
				echo "</textarea>";
				echo '<input name="Save" type="submit" class="coolBoton" value="'.$wikilang_save.'" />';
				echo '<input name="Cancel" type="submit" class="coolBoton" value="'.$wikilang_cancel.'" onclick="setCancel('."'$seq'".')"/>';
				echo '</form>';
			}
			else
			{
				$myuser = bd_getUsername($tokened_user);
				echo "<a href=\"/user/$myuserID\">$myuser</a> $wikilang_currently_translating";
			}
		}
	}
	
	bbdd_close();
?>