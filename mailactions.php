<?php
	include('includes/includes.php');
	$me = $_SESSION['userID'];
	
	$msgid = $_POST['msgid'];
	
	//reply
	$reply = $_POST['reply'];
	if (isset($reply))
	{
		header("Location: /msgcreate.php?action=reply&msgid=$msgid");
		bbdd_close();
		exit();
		
	}
	
	$forward = $_POST['forward'];
	if (isset($forward))
	{
		header("Location: /msgcreate.php?action=forward&msgid=$msgid");
		bbdd_close();
		exit();
	}
	
	$delete = $_POST['delete'];
	if (isset($delete))
	{
				
		
		$query = "select `from`,`to`,fromDelete,toDelete from msgs where msgID=$msgid";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		
		$to = $row['to'];
		$from = $row['from'];
		$fromDelete = $row['fromDelete'] == '1';
		$toDelete = $row['toDelete'] == 1;
		
		if (($fromDelete) || ($toDelete))
		{
			$query = "delete from msgs where msgID=$msgid";
			mysql_query($query);
		}
		else 
		{
			if ($me == $to)
			{
				$query = "update msgs set toDelete=1 where msgID=$msgid";
				mysql_query($query);
			}
			else 
			{
				$query = "update msgs set fromDelete=1 where msgID=$msgid";
				mysql_query($query);
			}
		}
		
		if ($me == $to)
			header("Location: /msginbox.php");
			else 
			header("Location: /msgoutbox.php");
		exit();
	}
	
	$inboxdelete = $_POST['inboxdel'];
	if (isset($inboxdelete))
	{
		$totalmsgs = $_POST['totalmsgs'];
		for ($c=0; $c<$totalmsgs; $c++)
		{
			$tickset = $_POST['tick'.$c];
			if (isset($tickset))
			{
				$query = "select toDelete,fromDelete from msgs where  msgID=$tickset";
				$result = mysql_query($query);
				$row = mysql_fetch_assoc($result);
				$toDelete = $row['toDelete'] == '1';
				$fromDelete = $row['fromDelete'] == '1';
				
				if ($fromDelete)
				{
					$query = "delete from msgs where msgID=$tickset";
					mysql_query($query);
				}
				else
				{
					$query = "update msgs set toDelete=1 where msgID=$tickset";
					mysql_query($query);
				}	
			}
		}
		header("Location: /msginbox.php");
		exit();
	}
	
	$inboxdeleteall = $_POST['inboxdelall'];
	if (isset($inboxdeleteall))
	{
		$query = "select msgID,toDelete,fromDelete from msgs where `to`=$me";
		$result = mysql_query($query);
		
		while ($row=mysql_fetch_assoc($result))
		{
			$toDelete = $row['toDelete'] == '1';
			$fromDelete = $row['fromDelete'] == '1';
			$msgid = $row['msgID'];
			
				if ($fromDelete)
				{
					$query = "delete from msgs where msgID=$msgid";
					mysql_query($query);
				}
				else
				{
					$query = "update msgs set toDelete=1 where msgID=$msgid";
					mysql_query($query);
				}	
		}
		header("Location: /msginbox.php");
		exit();
	}
	
	//outbox
	$outboxdelete = $_POST['outboxdel'];
	if (isset($outboxdelete))
	{
		$totalmsgs = $_POST['totalmsgs'];
		for ($c=0; $c<$totalmsgs; $c++)
		{
			$tickset = $_POST['tick'.$c];
			if (isset($tickset))
			{
				$query = "select toDelete,fromDelete from msgs where  msgID=$tickset";
				$result = mysql_query($query);
				$row = mysql_fetch_assoc($result);
				$toDelete = $row['toDelete'] == '1';
				$fromDelete = $row['fromDelete'] == '1';
				
				if ($toDelete)
				{
					$query = "delete from msgs where msgID=$tickset";
					mysql_query($query);
				}
				else
				{
					$query = "update msgs set fromDelete=1 where msgID=$tickset";
					mysql_query($query);
				}	
			}
		}
		header("Location: /msgoutbox.php");
		exit();
	}
	
	$outboxdeleteall = $_POST['outboxdelall'];
	if (isset($outboxdeleteall))
	{
		$query = "select msgID,toDelete,fromDelete from msgs where `from`=$me";
		$result = mysql_query($query);
		
		while ($row=mysql_fetch_assoc($result))
		{
			$toDelete = $row['toDelete'] == '1';
			$fromDelete = $row['fromDelete'] == '1';
			$msgid = $row['msgID'];
			
				if ($toDelete)
				{
					$query = "delete from msgs where msgID=$msgid";
					mysql_query($query);
				}
				else
				{
					$query = "update msgs set fromDelete=1 where msgID=$msgid";
					mysql_query($query);
				}	
		}
		header("Location: /msgoutbox.php");
		exit();
	}
	
	
	
	

?>