<?php
require "dbconnect.php";
require "fpdf/fpdf.php";
if(isset($_GET['idnumber'])) {
	$idnumber = $_GET['idnumber'];
	$currentdate = date("Y-m-d");
	$displaydate = date("F j, Y");
	$getdataSQL = "SELECT borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, barcode, callnumber, dateborrowed, duedate, datereturned FROM booklog JOIN borrower ON borrower.IDNumber=booklog.IDNumber JOIN book ON book.accession_no=booklog.accession_no WHERE datereturned IS NULL AND dateborrowed='$currentdate' AND borrower.IDNumber='$idnumber'";
	$getdataQuery = mysqli_query($dbconnect, $getdataSQL);
	$getdatarows = mysqli_num_rows($getdataQuery);

	if($getdatarows==2) {
		$transactionSQL = "SELECT borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, barcode, callnumber, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN borrower ON borrower.IDNumber=booklog.IDNumber JOIN book ON book.accession_no=booklog.accession_no WHERE datereturned='$currentdate' AND borrower.IDNumber='$idnumber' ORDER BY booklogID DESC LIMIT 1";

		$gettotalfineSQL = "SELECT SUM(penalty) AS totalfine FROM booklog JOIN borrower ON borrower.IDNumber=booklog.IDNumber JOIN book ON book.accession_no=booklog.accession_no WHERE datereturned='$currentdate' AND borrower.IDNumber='$idnumber' ORDER BY booklogID DESC LIMIT 1";
	} else if($getdatarows==1) {
		$transactionSQL = "SELECT borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, barcode, callnumber, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN borrower ON borrower.IDNumber=booklog.IDNumber JOIN book ON book.accession_no=booklog.accession_no WHERE datereturned='$currentdate' AND borrower.IDNumber='$idnumber' ORDER BY booklogID DESC LIMIT 2";

		$gettotalfineSQL = "SELECT SUM(penalty) AS totalfine FROM booklog JOIN borrower ON borrower.IDNumber=booklog.IDNumber JOIN book ON book.accession_no=booklog.accession_no WHERE datereturned='$currentdate' AND borrower.IDNumber='$idnumber' ORDER BY booklogID DESC LIMIT 2";
	} else if($getdatarows==0) {
		$transactionSQL = "SELECT borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, barcode, callnumber, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN borrower ON borrower.IDNumber=booklog.IDNumber JOIN book ON book.accession_no=booklog.accession_no WHERE datereturned='$currentdate' AND borrower.IDNumber='$idnumber' ORDER BY booklogID DESC LIMIT 3";

		$gettotalfineSQL = "SELECT SUM(penalty) AS totalfine FROM booklog JOIN borrower ON borrower.IDNumber=booklog.IDNumber JOIN book ON book.accession_no=booklog.accession_no WHERE datereturned='$currentdate' AND borrower.IDNumber='$idnumber' ORDER BY booklogID DESC LIMIT 3";
	}
	$transactionQuery = mysqli_query($dbconnect, $transactionSQL);
	$transaction = mysqli_fetch_assoc($transactionQuery);
	$gettotalfineQuery = mysqli_query($dbconnect, $gettotalfineSQL);
	$gettotalfine = mysqli_fetch_assoc($gettotalfineQuery);

	$borrowername = $transaction['lastname'].", ".$transaction['firstname']." ".$transaction['mi'];
	$borroweridnum = $transaction['IDNumber'];
	$totalfine = $gettotalfine['totalfine'];


	$fpdf = new FPDF("P","mm",array(90,125));
	$fpdf->AddPage();
	$fpdf->SetFont("Arial","",10);
	$fpdf->Image("pics/gvclogo.png",5,7,-500);
	$fpdf->Cell(75,5,"GREENVILLE COLLEGE LIBRARY",0,1,"C");

	$fpdf->SetFont("Arial","",5.5);
	$fpdf->Cell(80,5,"112 Belfast Street Corner San Salvador, Greenpark Village, Manggahan, Pasig City",0,1,"C");

	$fpdf->SetFont("Arial","",10);
	$fpdf->Cell(0,5,"Return Receipt",0,1,"C");
	$fpdf->Cell(0,5,"$displaydate",0,1,"C");

	$fpdf->SetFont("Arial","",8);
	$fpdf->Cell(0,5,"Borrower: $borrowername",0,1);
	$fpdf->Cell(0,5,"ID Number: $borroweridnum",0,1);
	$i = 0;
	do {
		$i++;
		$booktitle = $transaction['booktitle'];
		$accession_no = $transaction['accession_no'];
		$barcode = $transaction['barcode'];
		$datereturned = $transaction['datereturned'];

		$getbookconditionSQL = "SELECT * FROM book WHERE accession_no='$accession_no'";
		$getbookconditionQuery = mysqli_query($dbconnect, $getbookconditionSQL);
		$getbookcondition = mysqli_fetch_assoc($getbookconditionQuery);
		$bookcondition = $getbookcondition['bookcondition'];

		//if($bookcondition=="On Shelf") {
			$penalty = $transaction['penalty'];
		//} else {
			//$penalty = $transaction['penalty'] + $getbookcondition['price'].".00";
		//}
		
		$fpdf->SetFont("Arial","",7);
		$fpdf->Cell(0,3.5,"$i. $booktitle",0,1);
		$fpdf->Cell(3,3.5,"",0,0);
		$fpdf->Cell(0,3.5,"Accession Number: $accession_no",0,1);
		$fpdf->Cell(3,3.5,"",0,0);
		$fpdf->Cell(0,3.5,"Barcode: $barcode",0,1);
		$fpdf->Cell(3,3.5,"",0,0);
		$fpdf->Cell(0,3.5,"Date Returned: $datereturned",0,1);
		$fpdf->Cell(3,3.5,"",0,0);
		$fpdf->Cell(0,3.5,"Penalty: $penalty",0,1);
	} while($transaction = mysqli_fetch_assoc($transactionQuery));
	$fpdf->Cell(3,4,"",0,0);
	$fpdf->SetFont("Arial","",6);
	$fpdf->Output();
}
?>