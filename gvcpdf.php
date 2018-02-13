<?php
include "fpdf/fpdf.php";
class gvcpdf extends FPDF {
	function header() {
		$this->Image("pics/gvclogo.png", 10, 6, 20, 20);
		$this->SetFont("Times","",15);
		$this->Cell(0,10,"GREENVILLE COLLEGE LIBRARY",0,1,"C");
		$this->SetFont("Times","",10);
		$this->Cell(0, 10,"112 Belfast Street Corner San Salvador, Greenpark Village, Manggahan, Pasig City",0,1,"C");
		$this->Cell(0, 10,"682-37-12 | 681-35-54",0,1,"C");
	}

	function footer() {
		$this->SetY(-15);
		$this->SetFont('Times','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

} 
?>