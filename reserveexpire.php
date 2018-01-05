<?php
require "dbconnect.php";
	$currentdate = date("Y-m-d");
	$updatebookstatusSQL = "UPDATE book JOIN reservation ON book.accession_no=reservation.accession_no SET status='On Shelf' WHERE expdate <= '$currentdate'";
	$updatebookstatusQuery = mysqli_query($dbconnect,$updatebookstatusSQL);

	$expreserveSQL = "DELETE FROM reservation WHERE expdate <= '$currentdate'";
	$expreserveQuery = mysqli_query($dbconnect, $expreserveSQL);


?>