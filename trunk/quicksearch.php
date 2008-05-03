<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="70%" align="center">    
    <a href="javascript:qsClear();"><img src="images/view.png" border="0"></a> <span id="qssShow"><b>
<?php echo $wikilang_quick_search; ?>
    </b>
<?php 
	if (!isset($_SESSION['quicksearch']))
	{
?>
    <select name="qsShow" onchange="showChange(0);" id="qsShow">
  	<option value="0">
<?php echo $wikilang_select_a_show; ?>
  	</option>
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
		echo '  &nbsp;';
?>
		<script type="text/javascript">
		
		window.addEvent('domready', function() { showChange(<?php echo $_SESSION['quicksearch_show']; ?> ); } );
		
		</script>
<?php
	}
?>    
    </span>
    <span id="qsSeason">&nbsp;
<?php
	if (isset($_SESSION['quicksearch_season']))
	{	
		echo '<script type="text/javascript">';
		echo "window.addEvent('domready', function() { seasonChange(".$_SESSION['quicksearch_show'].",".$_SESSION['quicksearch_season'].") } );";
		echo '</script>';
	}
?>    
    
    </span> &nbsp;
    <span id="qsEp">&nbsp;

</span>
       </td>
    <td><form id="form1" name="form1" method="get" action="/search.php">
      <div align="center">
<?php echo $wikilang_search_subtitles; ?>
      &nbsp;
        <input name="search" type="text" id="search" size="20" /> &nbsp;

<?php echo '<input name="Submit" type="submit" class="coolBoton" value="'.$wikilang_search.'" />'; ?>
      </div>
    </form>
    </td>
  </tr>
</table>