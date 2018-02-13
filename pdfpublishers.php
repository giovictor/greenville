<?php
include "gvcpdf.php";
require "dbconnect.php";

	$pdf = new gvcpdf();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont("Times","B",15);
	$pdf->SetTitle("Publishers' Report");
	$pdf->Cell(0,10,"Publishers' Report",0,1,"C");
	if(isset($_POST['query'])) {
		$query = $_POST['query'];
		$query_run = mysqli_query($dbconnect, $query);
		$data = mysqli_fetch_assoc($query_run);

		$pdf->SetFont("Times","B",12);
		$pdf->Cell(50,10,"Publisher ID",1,0,"C");
		$pdf->Cell(140,10,"Publisher",1,0,"C");
		$pdf->ln();
		
		do {
			$pdf->SetFont("Times","",10);
			$pdf->Cell(50,10,$data['publisherID'],1,0,"C");
			$pdf->Cell(140,10,$data['publisher'],1,0,"C");
			$pdf->ln();
		} while($data = mysqli_fetch_assoc($query_run));
	}


	$pdf->Output();

?>
