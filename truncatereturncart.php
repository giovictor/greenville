<?php
require "dbconnect.php";
	$truncatereturncartSQL = "TRUNCATE TABLE returncart";
	$truncatereturncart = mysqli_query($dbconnect, $truncatereturncartSQL);

?>