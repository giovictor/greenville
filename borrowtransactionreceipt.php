<?php
session_start();
require "dbconnect.php";
require "fpdf/fpdf.php";
if(isset($_POST['idnumber']) && isset($_POST['borrowsessionID'])) {
	$idnumber = $_POST['idnumber'];
	$borrowsessionID = $_POST['borrowsessionID'];
	$displaydate = date("F j, Y");
	$transactionSQL = "SELECT borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, barcode, callnumber, dateborrowed, duedate, datereturned FROM booklog JOIN borrower ON borrower.IDNumber=booklog.IDNumber JOIN book ON book.accession_no=booklog.accession_no WHERE borrower.IDNumber='$idnumber' AND borrowsessionID='$borrowsessionID'";
	$transactionQuery = mysqli_query($dbconnect, $transactionSQL); 
	$transaction = mysqli_fetch_assoc($transactionQuery);
	$borrowername = $transaction['lastname'].", ".$transaction['firstname']." ".$transaction['mi'];
	$borroweridnum = $transaction['IDNumber'];

	$fpdf = new FPDF("P","mm",array(90,125));
	$fpdf->AddPage();
	$fpdf->SetFont("Arial","",10);
	$fpdf->Image("pics/gvclogo.png",5,7,-500);
	$fpdf->Cell(75,5,"GREENVILLE COLLEGE LIBRARY",0,1,"C");

	$fpdf->SetFont("Arial","",5.5);
	$fpdf->Cell(80,5,"112 Belfast Street Corner San Salvador, Greenpark Village, Manggahan, Pasig City",0,1,"C");

	$fpdf->SetFont("Arial","",10);
	$fpdf->Cell(0,5,"Borrow Receipt",0,1,"C");
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
		$duedate = $transaction['duedate'];
		$fpdf->SetFont("Arial","",7);
		$fpdf->Cell(0,4,"$i. $booktitle",0,1);
		$fpdf->Cell(3,4,"",0,0);
		$fpdf->Cell(0,4,"Accession Number: $accession_no",0,1);
		$fpdf->Cell(3,4,"",0,0);
		$fpdf->Cell(0,4,"Barcode: $barcode",0,1);
		$fpdf->Cell(3,4,"",0,0);
		$fpdf->Cell(0,4,"Due Date: $duedate",0,1);
	} while($transaction = mysqli_fetch_assoc($transactionQuery));
	$fpdf->Cell(0,5,"THANK YOU FOR BORROWING AT GREENVILLE COLLEGE",0,1,"C");
	$fpdf->SetFont("Arial","",6);
	$fpdf->Cell(0,3,"Note: All books should be returned on or before the due date or a penalty",0,1,"C");
	$fpdf->Cell(2,3,"payment will be imposed.",0,1);
	$fpdf->Output();
}
?>