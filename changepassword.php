<?php
session_start();
require "dbconnect.php";
if(isset($_POST['currentpassword']) && isset($_POST['newpassword']) && isset($_POST['confirmnewpassword'])) {
	$currentpassword = md5($_POST['currentpassword']);
	$newpassword = md5($_POST['newpassword']);

	if(isset($_SESSION['borrowerID'])) {
		$borrowerID = $_SESSION['borrowerID'];
		$borrowerSQL = "SELECT * FROM borrower WHERE IDNumber='$borrowerID'";
		$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
		$borrower = mysqli_fetch_assoc($borrowerQuery);
		$borrowerpassword = $borrower['password'];

		if($currentpassword!=$borrowerpassword) {
			echo "Invalid";
		} else {
			$changepasswordSQL = "UPDATE borrower SET borrower.password='$newpassword' WHERE IDNumber='$borrowerID'";
			$changepassword = mysqli_query($dbconnect, $changepasswordSQL);
		}
	} else if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$userSQL = "SELECT * FROM user WHERE userID='$userID'";
		$userQuery = mysqli_query($dbconnect, $userSQL);
		$user = mysqli_fetch_assoc($borrowerQuery);
		$userpassword = $user['password'];

		if($currentpassword!=$borrowerpassword) {
			echo "Invalid";
		} else {
			$changepasswordSQL = "UPDATE user SET user.password='$newpassword' WHERE userID='$userID'";
			$changepassword = mysqli_query($dbconnect, $changepasswordSQL);
		}
	}
}
?>