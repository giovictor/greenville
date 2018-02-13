<?php
include "gvcpdf.php";
require "dbconnect.php";

	$pdf = new gvcpdf();
	$pdf->AliasNbPages();
	$pdf->AddPage("L","A4");
	$pdf->SetFont("Times","B",15);
	$pdf->SetTitle("Reserved Books' Report");
	$pdf->Cell(0,10,"Reserved Books' Report",0,1,"C");
	$pdf->Cell(0,10,"",0,1,"C");
	if(isset($_POST['query'])) {
		$query = $_POST['query'];
		$query_run = mysqli_query($dbconnect, $query);
		$data = mysqli_fetch_assoc($query_run);

		$pdf->SetFont("Times","",12);
		$pdf->Cell(50,10,"ID No.",1,0,"C");
		$pdf->Cell(50,10,"Borrower",1,0,"C");
		$pdf->Cell(20,10,"Acc. No.",1,0,"C");
		$pdf->Cell(80,10,"Title",1,0,"C");
		$pdf->Cell(40,10,"Reserve Date",1,0,"C");
		$pdf->Cell(40,10,"Expiration Date",1,0,"C");
		$pdf->ln();
		
		do {
			$booktitle = $data['booktitle'];
			if(strlen($booktitle) > 45) {
				$booktitle = substr($booktitle, 0, 44)."...";
			}
			$pdf->SetFont("Times","",10);
			$pdf->Cell(50,10,$data['IDNumber'],1,0,"C");
			$pdf->Cell(50,10,$data['lastname'].", ".$data['firstname']." ".$data['mi'],1,0,"C");
			$pdf->Cell(20,10,$data['accession_no'],1,0,"C");
			$pdf->Cell(80,10,$booktitle,1,0,"L");
			$pdf->Cell(40,10,$data['reservationdate'],1,0,"C");
			$pdf->Cell(40,10,$data['expdate'],1,0,"C");
			$pdf->ln();
		} while($data = mysqli_fetch_assoc($query_run));
	}


	$pdf->Output();

?>