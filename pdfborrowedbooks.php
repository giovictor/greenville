<?php
include "gvcpdf.php";
require "dbconnect.php";

	$pdf = new gvcpdf("L","mm", array(215.9,279.4));
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont("Times","B",15);
	$pdf->SetTitle("Borrowed Books' Report");
	$pdf->Cell(0,10,"Borrowed Books' Report",0,1,"C");
	$pdf->Cell(0,10,"",0,1,"C");
	if(isset($_POST['query'])) {
		$query = $_POST['query'];
		$query_run = mysqli_query($dbconnect, $query);
		$data = mysqli_fetch_assoc($query_run);

		$pdf->SetFont("Times","",12);
		$pdf->Cell(35,10,"ID No.",1,0,"C");
		$pdf->Cell(45,10,"Borrower",1,0,"C");
		$pdf->Cell(20,10,"Acc. No.",1,0,"C");
		$pdf->Cell(50,10,"Title",1,0,"C");
		$pdf->Cell(30,10,"Date Borrowed",1,0,"C");
		$pdf->Cell(30,10,"Due Date",1,0,"C");
		$pdf->Cell(30,10,"Days Overdue",1,0,"C");
		$pdf->Cell(25,10,"Penalty",1,0,"C");
		$pdf->ln();

		$holidaySQL = "SELECT * FROM holiday";
		$holidayQuery = mysqli_query($dbconnect, $holidaySQL);
		$holiday = mysqli_fetch_assoc($holidayQuery);
		$holidayarray = array();
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

		$settingsSQL = "SELECT * FROM settings";
		$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
		$settings = mysqli_fetch_assoc($settingsQuery);

		
		do {
			$currentdate = date("Y-m-d");
			$duedate = $data['duedate'];
			$booktitle = $data['booktitle'];
			if(strlen($booktitle) > 25) {
				$booktitle = substr($booktitle, 0, 24)."...";
			}
			$pdf->SetFont("Times","",10);
			$pdf->Cell(35,10,$data['IDNumber'],1,0,"C");
			$pdf->Cell(45,10,$data['lastname'].", ".$data['firstname']." ".$data['mi'],1,0,"C");
			$pdf->Cell(20,10,$data['accession_no'],1,0,"C");
			$pdf->Cell(50,10,$booktitle,1,0,"L");
			$pdf->Cell(30,10,$data['dateborrowed'],1,0,"C");
			$pdf->Cell(30,10,$duedate,1,0,"C");


			if(strtotime($currentdate) > strtotime($duedate)) {
				$duedatetime = new Datetime($duedate);
				$currentdatetime = new Datetime($currentdate);
				$datediff = $currentdatetime->diff($duedatetime);
				$daysoverdue = $datediff->days;
		
				$betweendays = new DatePeriod($duedatetime, new DateInterval("P1D"), $currentdatetime);
				foreach($betweendays AS $days) {
					$day = $days->format("D");
					if($day=="Sun") {
						$daysoverdue--;
					} else if(in_array($days->format("Y-m-d"), $holidayarray)) {
						$daysoverdue--;
					}
				}
				$pdf->Cell(30,10,$daysoverdue." day(s)",1,0,"C");
			} else {
				$pdf->Cell(30,10,"0 days(s)",1,0,"C");
			}

			if(strtotime($currentdate) > strtotime($duedate)) {
				$duedatetime = new Datetime($duedate);
				$currentdatetime = new Datetime($currentdate);
				$datediff = $currentdatetime->diff($duedatetime);
				$daysoverdue = $datediff->days;
		
				$betweendays = new DatePeriod($duedatetime, new DateInterval("P1D"), $currentdatetime);
				foreach($betweendays AS $days) {
					$day = $days->format("D");
					if($day=="Sun") {
						$daysoverdue--;
					} else if(in_array($days->format("Y-m-d"), $holidayarray)) {
						$daysoverdue--;
					}
				}
				$pdf->Cell(25,10,$daysoverdue * $settings['penalty'].".00",1,0,"C");
			} else {
				$pdf->Cell(25,10,"0.00",1,0,"C");
			}
			
			
			$pdf->ln();
		} while($data = mysqli_fetch_assoc($query_run));
	}


	$pdf->Output();

	
					
			
	
?>