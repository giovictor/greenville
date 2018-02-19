<?php
session_start();
require "dbconnect.php";
	$librarian = $_SESSION['librarian'];
	$sql = "SELECT * FROM user WHERE username='$librarian'";
	$query = mysqli_query($dbconnect, $sql);
	$res = mysqli_fetch_assoc($query);
	$userID = $res['userID'];

	if(isset($_POST['bookid']) && isset($_POST['idnumber']) && isset($_POST['borrowsessionID'])) {
		$idnumber = $_POST['idnumber'];
		$bookid = $_POST['bookid'];
		$borrowsessionID = $_POST['borrowsessionID'];

		$checkInputSQL = "SELECT * FROM book WHERE barcode='$bookid' OR accession_no='$bookid'";
		$checkInputQuery = mysqli_query($dbconnect, $checkInputSQL);
		$checkInput = mysqli_fetch_assoc($checkInputQuery);
		$borrowcounter = $checkInput['borrowcounter'];
		$newborrowcounter = $borrowcounter + 1;

		if($bookid==$checkInput['barcode']) {
			$bookid = $checkInput['accession_no'];
		} else {
			$bookid = $_POST['bookid'];
		}

		$checkborrowSQL = "SELECT COUNT(*) AS noofborrowedbooks FROM booklog WHERE IDNumber='$idnumber' AND datereturned IS NULL";
		$checkborrowQuery = mysqli_query($dbconnect, $checkborrowSQL);
		$checkborrow = mysqli_fetch_assoc($checkborrowQuery);

		$settingsSQL = "SELECT * FROM settings";
		$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
		$settings = mysqli_fetch_assoc($settingsQuery);

		if($checkborrow['noofborrowedbooks']==$settings['borrowlimit']) {
			echo "Limit";
		} else if($checkborrow['noofborrowedbooks']<$settings['borrowlimit']) {
			$dateborrowed = date("Y-m-d");

			$holidaySQL = "SELECT * FROM holiday";
			$holidayQuery = mysqli_query($dbconnect, $holidaySQL);
			$holiday = mysqli_fetch_assoc($holidayQuery);
			$startdate = $holiday['startdate'];
			$enddate = $holiday['enddate'];

			$dateborrowedtime = new DateTime($dateborrowed);
			$dateborrowedtimestamp = $dateborrowedtime->getTimestamp();
			$startdatetime = new DateTime($startdate);
			$endddatetime = new DateTime($enddate);
			$realenddatetime = $endddatetime->modify("+ 1 day");
			$holidaydates = new DatePeriod($startdatetime, new DateInterval('P1D'), $realenddatetime);

			foreach($holidaydates AS $dates) {
				$holidaydate[] = $dates->format("Y-m-d");
			}

			for($i=0; $i<2; $i++) {
				$addday = 86400;
				$nextday = date("D", ($dateborrowedtimestamp + $addday));
				$nextdaydate = date("Y-m-d",($dateborrowedtimestamp + $addday));

				if($nextday=="Sun") {
					$i--;
				} else if(in_array($nextdaydate, $holidaydate)) {
					$i--;
				}

				$dateborrowedtimestamp = $dateborrowedtimestamp + $addday;
			}

			$dateborrowedtime->setTimestamp($dateborrowedtimestamp);
			$duedate = $dateborrowedtime->format("Y-m-d");
			
			$borrowSQL = "INSERT INTO booklog(IDNumber, accession_no, dateborrowed, duedate, userID, borrowsessionID) VALUES('$idnumber','$bookid','$dateborrowed','$duedate','$userID','$borrowsessionID')";
			$borrowQuery = mysqli_query($dbconnect, $borrowSQL);

			$deleteReservationSQL = "UPDATE reservation SET showstatus=0 WHERE accession_no='$bookid' AND idnumber='$idnumber' AND showstatus=1";
			$deleteReservation = mysqli_query($dbconnect, $deleteReservationSQL);

			$updatebookstatusSQL = "UPDATE book SET status='Borrowed' WHERE accession_no='$bookid'";
			$updatebookstatusQuery = mysqli_query($dbconnect,$updatebookstatusSQL);

			$updateborrowcounterSQL = "UPDATE book SET borrowcounter='$newborrowcounter' WHERE accession_no='$bookid'";
			$updateborrowcounterQuery = mysqli_query($dbconnect, $updateborrowcounterSQL);

			$truncateborrowcartSQL = "TRUNCATE TABLE borrowcart";
			$truncateborrowcart = mysqli_query($dbconnect, $truncateborrowcartSQL);
?>
<div id="borrowresponse">
	<div id="borrowalert" class="alert">
		Books were borrowed successfully. 
		<button class="btn btn-link btn-md">
			<a href="?page=vbr">Click here to view all borrowed books.</a>
		</button>
	</div>
	<form target="_blank" action="borrowtransactionreceipt.php" method="POST">
		<button id="printbutton" class="btn btn-success"><span class="glyphicon glyphicon-print"></span> Print Receipt</button>
		<input type="hidden" name="idnumber" value="<?php echo $idnumber;?>">
		<input type="hidden" name="borrowsessionID" value="<?php echo $borrowsessionID;?>">
	</a>
</div>
<?php
	}
}
?>