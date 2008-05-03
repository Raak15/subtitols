	function carga()
	{
		$("comments").innerHTML = '<img src="/images/loader.gif">';
<?php echo "new Ajax('/ajax_getComments.php?id=$myID',{"; ?>
	method: 'get',
	update: $("comments")
	}).request();
		
	}
	
	function enviar()
	{
		var paramString = $("newc").toQueryString(); 
		$("comments").innerHTML = '<img src="/images/loader.gif">';
	new Ajax('/ajax_getComments.php',{
		method: 'post',
		postBody: paramString,
		update: $("comments")
	}).request();
	
	return false;
	}
	
	function notifyModerator(fversion, lang)
	{
<?php
	echo "var subID='$myID';";
?>		
		var peticion = '/ajax_notify.php?id='+subID+'&fversion='+fversion+'&lang='+lang;
		
		if (confirm('Are you sure that you want to report a problem to the moderators?'))
		{
		new Ajax(peticion, {
			method: 'get',
			onComplete: function()
			{
				alert("The notification has sent to the moderators. Please, be patient");
			}
		}).request();
		}
	}
	
	function delComment(cid)
	{
		var peticion='/ajax_delComment.php?cid='+cid;
		
		new Ajax(peticion,{
			method: 'get',
			onComplete: function(){
				$("comments").innerHTML = "<img src='/images/loader.gif' />";
				new Ajax('<?php echo $SCRIPT_PATH; ?>ajax_getComments.php?cid='+cid, {
					method:'get',
					update: $("comments")
				}).request();
			}
		}).request();
	}
	
	window.addEvent('domready', function(){ carga(); })