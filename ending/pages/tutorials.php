<?php
require_once("./connect.php");
$query = "SELECT * FROM tbl_logo";
$q = mysql_query_cheat($query);
?>
<style>
.mainectable > div > div {
    border: 1px solid #ccc1c1 ;
}
.mainectable > div {
	display: inline-table;
	width: 25%;
	
}
.mainectable > div > div { 
	background: linear-gradient(-35deg,white 15px, #ccc1c1 15px,white 19px, white 3px);
	
}
</style>
<?php
	while($row=mysqli_fetch_array_cheat($q)){	
?>
			<h1><?php echo $row['title']; ?></h1>
				<div class='row'>
					<div class='contentnews col-lg-12 col-md-12'>
				
						<?php echo $row['header']; ?>
					</div>
				</div>
			<hr>
<?php
	}
?>

