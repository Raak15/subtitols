<div id="quicksearch">
   
    <span id="qssShow"><?php echo $wikilang_quick_search; ?>
<?php 
	if (!isset($_SESSION['quicksearch']))
	{
?>
    <select name="qsShow" onchange="showChange(0);" id="qsShow">
  	<option value="0"><?php echo $wikilang_select_a_show; ?></option>
<?php
	$query = "select * from shows order by title";
	$result = mysql_query($query);
	while ($row = mysql_fetch_assoc($result))
	{
		echo '<option value="'.$row['showID'].'" >'.stripslashes($row['title']).'</option>';
	}
?>
    </select>
<?php
	}
	else 
	{
		echo '';
?>
		<script type="text/javascript">
		
		window.addEvent('domready', function() { showChange(<?php echo $_SESSION['quicksearch_show']; ?> ); } );
		
		</script>
<?php
	}
?>    
    </span>
    <span id="qsSeason"><?php
	if (isset($_SESSION['quicksearch_season']))
	{	
		echo '<script type="text/javascript">';
		echo "window.addEvent('domready', function() { seasonChange(".$_SESSION['quicksearch_show'].",".$_SESSION['quicksearch_season'].") } );";
		echo '</script>';
	}
?></span> <span id="qsEp"></span> <a href="javascript:qsClear();">Clear search</a> 
       
</div>