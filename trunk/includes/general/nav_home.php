<div class="nav">
	<ul>
		<li><a href="/" class="selected"><?= _("Home") ?></a></li>
		<li><a href="/newsub.php"><?= _("Upload a new subtitle") ?></a></li>
		<li><a href="/shows.php"><?= _("Browse by shows") ?></a></li>
		<li><a href="/topuploaders.php"><? echo $wikilang_top_uploaders; ?></a></li>
		<li><a href="/log.php?mode=downloaded"><?php echo $wikilang_most_downloaded; ?></a></li>
		<li class="searchli"><form id="form1" name="form1" method="get" action="/search.php"><input name="search" type="search" id="search" size="20" value="Ej. Dexter 01 x 02" /></div>
	    </form></li>
	</ul>
</div>