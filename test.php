<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Ubuntu" rel="stylesheet">
	
</head>
<body>
	<?php
		require "dbconnect.php";
		$holidaySQL = "SELECT * FROM holiday";
		$holidayQuery = mysqli_query($dbconnect, $holidaySQL);
		$holiday = mysqli_fetch_assoc($holidayQuery);
		$holidayrows = mysqli_num_rows($holidayQuery);
		$holidayarray = array();
		if($holidayrows > 0) {
			do {
				$startdate = $holiday['startdate'];
				$enddate = $holiday['enddate'];
				$startdateobj = new DateTime($startdate);
				$enddateobj = new DateTime($enddate);
				$enddateobj->modify("+1 day");
				$holidaydates = new DatePeriod($startdateobj, new DateInterval("P1D"), $enddateobj);
				foreach($holidaydates AS $dates) {
					$holidayarray[] = $dates->format("Y-m-d");
				}
			} while($holiday = mysqli_fetch_assoc($holidayQuery));
		}

		print_r($holidayarray);
	?>
	<script src="jquery-3.2.0.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>  
</body>
</html>