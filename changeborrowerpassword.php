<?php
session_start();
require "dbconnect.php";
if(isset($_POST['currentpassword']) && isset($_POST['newpassword']) && isset($_POST['confirmnewpassword'])) {
	$currentpassword = md5($_POST['currentpassword']);
	$newpassword = md5($_POST['newpassword']);
	$borrowername = $_SESSION['borrower'];

	$borrowerSQL = "SELECT * FROM borrower WHERE firstname='$borrowername'";
	$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
	$borrower = mysqli_fetch_assoc($borrowerQuery);
	$borrowerpassword = $borrower['password'];

	if($currentpassword!=$borrowerpassword) {
		echo "Invalid";
	} else {
		$changepasswordSQL = "UPDATE borrower SET borrower.password='$newpassword' WHERE firstname='$borrowername'";
		$changepassword = mysqli_query($dbconnect, $changepasswordSQL);
	}
}
?>