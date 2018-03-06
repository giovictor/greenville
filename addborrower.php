<?php
require "dbconnect.php";

if(!empty($_POST)) {
	$idnumber = $_POST['idnumber'];
	$lastname = ucwords($_POST['lastname']);
	$firstname = ucwords($_POST['firstname']);
	$mi = ucwords($_POST['mi']);
	$contactnumber = $_POST['contact'];
	$course = $_POST['course'];
	$acctype = $_POST['accounttype'];


	//ACCOUNT TYPE 
	if($acctype=='Student') {
		$acctype="Student";
	} else if($acctype=='Faculty') {
		$acctype="Faculty";
	}

	//DATE REGISTERED
	$date = date("Y-m-d");

	//PASSWORD 
	$password = substr($idnumber, 6, 5);
	$passwordmd5 = md5($password);

	if(!is_numeric($contactnumber)) {
		echo "Need Numeric Values";
	} else {

		$sql = "INSERT INTO borrower(IDNumber, lastname, firstname, mi, contactnumber, course, dateregistered, accounttype, password) VALUES ('$idnumber','$lastname', '$firstname','$mi','$contactnumber','$course','$date','$acctype','$passwordmd5')";

		$query = mysqli_query($dbconnect, $sql);

	}
}
?>