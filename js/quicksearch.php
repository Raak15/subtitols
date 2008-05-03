	function changeAppLang()
	{
		value = $("comboLang").value;
		if (value!='<?php echo $_SESSION['applang']; ?>')
		{
			window.location= "/changeapplang.php?applang=" + value;
		}
	}
	
	function showChange(showID)
	{
		if (showID==0)
			valor = $("qsShow").value;
			else
			valor = showID;
		if (valor>0)
		{
			$("qsSeason").innerHTML = '<img src="/images/loader.gif">';
			$("qsEp").innerHTML = ' ';
			$("qssShow").innerHTML = ' ';

			new Ajax('/ajax_getSeasons.php?showID='+valor , {
				method: 'get',
				update: $("qsSeason")
			}).request();
		}

	}
	
	function seasonChange(showID, season)
	{
	
		valor = showID;

		if (valor>0)
		{
			$("qsEp").innerHTML = '<img src="/images/loader.gif">';
			
			if (season==-1)
				myseason = $("qsiSeason").value;
				else
				myseason=season;
			
			new Ajax('/ajax_getEpisodes.php?showID='+valor+'&season='+myseason , {
				method: 'get',
				update: $("qsEp")

			}).request();
		}

	}
	
	function changeEp()
	{
		var valor = $("qsiEp").value;
		window.location = 're_episode.php?ep=' + valor;
	}
	
	function qsClear()
	{
		$("qssShow").innerHTML = '<img src="/images/loader.gif">';
		$("qsSeason").innerHTML = ' ';
		$("qsEp").innerHTML = ' ';
		
		new Ajax('/ajax_getShows.php',{
			method:'get',
			update: $("qssShow")
		}).request();
	}