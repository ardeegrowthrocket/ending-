<?php
error_reporting(0);
function mysql_query_cheat($query){
$con=mysqli_connect("localhost","root","","ending");

$data = mysqli_query($con,$query);

mysqli_close($con);

return $data;
}

function mysqli_fetch_array_cheat($result){
	return mysqli_fetch_array($result,MYSQLI_ASSOC);
}

?>