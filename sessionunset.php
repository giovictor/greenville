<?php
session_start();
require "dbconnect.php";
if(isset($_SESSION['borrower'])) {
	$borrowername = $_SESSION['borrower'];

	$getborrowerdataSQL = "SELECT MAX(attendanceID) AS attendanceID FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE firstname='$borrowername'";
	$getborrowerdataQuery = mysqli_query($dbconnect, $getborrowerdataSQL);
	$getborrowerdata = mysqli_fetch_assoc($getborrowerdataQuery);
	$attendanceID = $getborrowerdata['attendanceID'];

	$logoutdatetime = date("Y-m-d H:i");
	$logoutdatetimeSQL = "UPDATE attendance SET logoutdatetime='$logoutdatetime' WHERE attendanceID='$attendanceID'";
	$logoutdatetime = mysqli_query($dbconnect, $logoutdatetimeSQL);
	
	unset($_SESSION['borrower']);
	unset($_SESSION['borrowerID']);
} else {
	unset($_SESSION['librarian']);
	unset($_SESSION['userID']);
}


//localhost
header("Location:index.php");
//remote server
//header("Location:http://greenvillecollegelibrary.comli.com/index.php");
?>