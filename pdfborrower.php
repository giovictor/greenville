<?php
include "gvcpdf.php";
require "dbconnect.php";

	$pdf = new gvcpdf("L","mm", array(215.9,279.4));
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont("Times","B",15);
	$pdf->SetTitle("Borrowers' Report");
	$pdf->Cell(0,10,"Borrowers' Report",0,1,"C");
	$pdf->Cell(0,10,"",0,1,"C");
	if(isset($_POST['query'])) {
		$query = $_POST['query'];
		$query_run = mysqli_query($dbconnect, $query);
		$data = mysqli_fetch_assoc($query_run);

		$pdf->SetFont("Times","",10);
		$pdf->Cell(30,10,"ID No.",1,0,"C");
		$pdf->Cell(60,10,"Name",1,0,"C");
		$pdf->Cell(30,10,"Contact No.",1,0,"C");
		$pdf->Cell(50,10,"Course.",1,0,"C");
		$pdf->Cell(30,10,"Date Registered",1,0,"C");
		$pdf->Cell(30,10,"Balance",1,0,"C");
		$pdf->Cell(30,10,"Status",1,0,"C");
		$pdf->ln();
		
		do {
			$pdf->Cell(30,10,$data['IDNumber'],1,0,"C");
			$pdf->Cell(60,10,$data['lastname']." ,".$data['firstname']." ".$data['mi'],1,0,"C");
			$pdf->Cell(30,10,$data['contactnumber'],1,0,"C");
			$pdf->Cell(50,10,$data['course'],1,0,"C");
			$pdf->Cell(30,10,$data['dateregistered'],1,0,"C");
			$pdf->Cell(30,10,$data['accountbalance'],1,0,"C");
			$pdf->Cell(30,10,$data['status'],1,0,"C");
			$pdf->ln();
		} while($data = mysqli_fetch_assoc($query_run));
	}


	$pdf->Output();

?>