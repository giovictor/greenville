<?php
session_start();
require "dbconnect.php";
if(isset($_POST['username']) && isset($_POST['password'])) {
	$username = mysqli_real_escape_string($dbconnect,$_POST['username']);
	$password = md5(mysqli_real_escape_string($dbconnect,$_POST['password']));
		$adminLoginSQL = "SELECT * FROM user WHERE username='$username' AND password='$password'";
		$adminLoginQuery = mysqli_query($dbconnect, $adminLoginSQL);
		$checkAdmin = mysqli_num_rows($adminLoginQuery);

		$borrowerLoginSQL = "SELECT * FROM borrower WHERE IDNumber='$username' AND password='$password' AND status='Active'";
		$borrowerLoginQuery = mysqli_query($dbconnect, $borrowerLoginSQL);
		$checkBorrower = mysqli_num_rows($borrowerLoginQuery);

		if($checkAdmin==0 && $checkBorrower==0) {
			echo "Invalid";
		} else if($checkAdmin>=1) {
			$admin = mysqli_fetch_assoc($adminLoginQuery);
			$_SESSION['librarian']=$admin['username'];
			$_SESSION['userID']=$admin['userID'];
			echo "Librarian Login";
		} else if($checkBorrower>=1) {
			$borrower = mysqli_fetch_assoc($borrowerLoginQuery);
			$IDNumber = $borrower['IDNumber'];
			$logindatetime = date("Y-m-d H:i");
			$borrowerlogSQL = "INSERT INTO attendance(IDNumber, logindatetime) VALUES('$IDNumber','$logindatetime')";
			$borrowerlog = mysqli_query($dbconnect, $borrowerlogSQL);
			$_SESSION['borrowerID']=$borrower['IDNumber'];
			$_SESSION['borrower']=$borrower['firstname'];
			echo "Borrower Login";
		}
}
?>
