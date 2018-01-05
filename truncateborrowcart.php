<?php
require "dbconnect.php";
	$truncateborrowcartSQL = "TRUNCATE TABLE borrowcart";
	$truncateborrowcart = mysqli_query($dbconnect, $truncateborrowcartSQL);

?>