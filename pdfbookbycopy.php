<?php
include "gvcpdf.php";
require "dbconnect.php";

	$pdf = new gvcpdf("L","mm", array(215.9,279.4));
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont("Times","B",15);
	$pdf->SetTitle("Books' Inventory");
	//$pdf->Cell(0,10,"Books' Report (By Copy)",0,1,"C");
	if(isset($_POST['query'])) {
		$query = $_POST['query'];
		$query_run = mysqli_query($dbconnect, $query);
		$data = mysqli_fetch_assoc($query_run);

		$pdf->SetFont("Times","B",12);
		$pdf->Cell(25,10,"Acc. No.",1,0,"C");
		$pdf->Cell(90,10,"Title",1,0,"C");
		$pdf->Cell(55,10,"Author",1,0,"C");
		$pdf->Cell(65,10,"Publisher",1,0,"C");
		$pdf->Cell(25,10,"Year",1,0,"C");
		$pdf->ln();
		
		do {
			$booktitle = $data['booktitle'];
			if(strlen($booktitle) > 55) {
				$booktitle = substr($booktitle, 0, 54)."...";
			}
			$pdf->SetFont("Times","",10);
			$pdf->Cell(25,10,$data['accession_no'],1,0,"C");
			$pdf->Cell(90,10,$booktitle,1,0,"L");
			$pdf->Cell(55,10,$data['authors'],1,0,"C");
			$pdf->Cell(65,10,$data['publisher'],1,0,"C");
			$pdf->Cell(25,10,$data['publishingyear'],1,0,"C");
			$pdf->ln();
		} while($data = mysqli_fetch_assoc($query_run));
	}


	$pdf->Output();

?>
