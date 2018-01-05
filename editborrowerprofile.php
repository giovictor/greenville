<?php
session_start();
require "dbconnect.php";
if(isset($_POST['lastname']) && isset($_POST['firstname']) && isset($_POST['mi']) && isset($_POST['contactnumber'])) {
	$lastname = ucwords($_POST['lastname']);
	$firstname = ucwords($_POST['firstname']);
	$mi = $_POST['mi'];
	$contactnumber = $_POST['contactnumber'];
	$borrowername = $_SESSION['borrower'];

	if(!is_numeric($contactnumber)) {
		echo "Invalid contact number";
	} else if(strlen($mi)>3) {
		echo "Invalid middle initial";
	} else {
		$updateprofileSQL = "UPDATE borrower SET lastname='$lastname', firstname='$firstname', mi='$mi', contactnumber='$contactnumber' WHERE firstname='$borrowername'";
		$updateprofile = mysqli_query($dbconnect, $updateprofileSQL);
	}


}

?>