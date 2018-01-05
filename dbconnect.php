<?php
$dbconnect = mysqli_connect("localhost","root","","greenville"); 
if(!$dbconnect) {
	echo "Connection to database failed.".mysqli_error();
}

?>