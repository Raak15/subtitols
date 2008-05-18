<?php include('includes/includes.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Subtítols – <?php echo $wikilang_explore_by_show; ?></title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<script type="text/javascript" src="js/mootools.v1.11.js"></script>
	<script type="text/javascript">
		<?php include('js/quicksearch.php'); ?>
	</script>
	<style type="text/css" media="screen">
		@import url(/css/main.css);
	</style>
</head>

<body>

<?php include("header.php"); ?>

<?php include("includes/general/nav_home.php"); ?>

<?php include("includes/general/subnav_search.php"); ?>

<div id="sitebody">
	
	<div id="slogan">
		
		<h2><?php echo $wikilang_explore_by_show; ?></h2>
	
	</div>
	
	

	<div id="content-listados">
	
		<!-- empieza el content -->


		<table border="0" class="table-universal">
			<caption>Alphabetical List</caption>
			<tbody>
				<?php
				$query = "select showID,title from shows order by title";
				$result = mysql_query($query);
				$cuenta = mysql_affected_rows();
				$micuenta = 0;


				$row=mysql_fetch_assoc($result);
				$s1Name = stripslashes($row['title']);
				$letra = $s1Name[0];

				$continue = false;

				do
					{	
						if (!$continue)
						{
							echo '<tr><td><em class="ico ifolder">Order</td><td><big>'.$letra.'</big>';
								$continue = true;
							}
							$s1 = $row['showID'];
							$s1Name = stripslashes($row['title']);
							$s1Episodes = bd_countEpisodesShow($s1);
							$s1Seasons = bd_countSeasonsShow($s1);
							$micuenta++;

						if ($letra!=$s1Name[0])
						{
							$continue = false;
							$letra = $s1Name[0];
						}

				if  (!$continue) $row = mysql_fetch_assoc($result);

				if ($continue && ($row=mysql_fetch_assoc($result)))
				{
					$s2 = $row['showID'];
					$s2Name = stripslashes($row['title']);
					$s2Episodes = bd_countEpisodesShow($s2);
					$s2Seasons = bd_countSeasonsShow($s2);	
					if ($letra!=$s2Name[0])
					{
						$continue = false;
						$letra = $s2Name[0];
						$s2Name = "";
					}else $micuenta++;
				} else $s2Name = "";

				if ($continue && $row=mysql_fetch_assoc($result))
				{
					$s3 = $row['showID'];
					$s3Name = stripslashes($row['title']);
					$s3Episodes = bd_countEpisodesShow($s3);
					$s3Seasons = bd_countSeasonsShow($s3);	

					if ($letra!=$s3Name[0])
					{
						$continue = false;
						$letra = $s3Name[0];
						$s3Name = "";
					}else $micuenta++;
				}else $s3Name = "";

				if ($continue && $row=mysql_fetch_assoc($result))
				{
					$s4 = $row['showID'];
					$s4Name = stripslashes($row['title']);
					$s4Episodes = bd_countEpisodesShow($s4);
					$s4Seasons = bd_countSeasonsShow($s4);	

					if ($letra!=$s4Name[0])
					{
						$continue = false;
						$letra = $s4Name[0];
						$s4Name = "";
					}else $micuenta++;
				}else $s4Name = "";

				if ($continue) 
				{
					$row=mysql_fetch_assoc($result);

					$s5 = $row['showID'];
					$s5Name = stripslashes($row['title']);
					if ($letra!=$s5Name[0])
					{
						$continue = false;
						$letra = $s5Name[0];
					}
				}

					echo '<small><a href="/show/'.$s1.'">'.$s1Name.'</a>';

		      	 if ($s2Name!="")
		      	 	echo ', <a href="/show/'.$s2.'">'.$s2Name.'</a>';
		      	 	else 
				 	echo '</small>';

				 if ($s3Name!="")	
		      	 	echo ', <a href="/show/'.$s3.'">'.$s3Name.'</a>';
		      	 	else 
				 	echo '</small>';

		      	 if ($s4Name!="")
		      	 	echo ', <a href="/show/'.$s4.'">'.$s4Name.'</a>';
		      	 	else 
				 	echo '</small>';
		   		 echo '</tr>';
			}
			
			while ($micuenta<$cuenta);
		?>
	</tbody>
</table>
  
</div>

<div id="menus">
	
	<h1>LOLO</h1>

</div>

<div class="clear"></div>

</div>

<?php
	include ('footer.php');
	bbdd_close();
?>

</body>
</html>
