<?php
require "dbconnect.php";
	if(isset($_POST['booklogID']) && isset($_POST['condition'])) {
		$booklogID = $_POST['booklogID'];
		$condition = $_POST['condition'];
		$datereturned = date("Y-m-d");
		$currentdate = date("Y-m-d");

		$returnBookSQL = "UPDATE booklog SET datereturned='$datereturned' WHERE booklogID='$booklogID'";
		$returnBookQuery = mysqli_query($dbconnect, $returnBookSQL);

		$getbooklogSQL = "SELECT booklogID, IDNumber, book.accession_no, booktitle, callnumber, barcode, dateborrowed, duedate, price FROM booklog JOIN book ON book.accession_no=booklog.accession_no WHERE booklogID='$booklogID'";
		$getbooklogQuery = mysqli_query($dbconnect, $getbooklogSQL);
		$getbooklog = mysqli_fetch_assoc($getbooklogQuery);
		$idnumber = $getbooklog['IDNumber'];
		$accession_no = $getbooklog['accession_no'];
		$duedate = $getbooklog['duedate'];
		$price = $getbooklog['price'];


		if($condition=="onshelf") {
			$updatebookstatusSQL = "UPDATE book SET status='On Shelf' WHERE accession_no='$accession_no'"; 
			$updatebookstatusQuery = mysqli_query($dbconnect,$updatebookstatusSQL);	
			$price = 0.00;
		} else if($condition=="lost") {
			$updatebookstatusSQL = "UPDATE book SET status='Archived', bookcondition='Lost' WHERE accession_no='$accession_no'"; 
			$updatebookstatusQuery = mysqli_query($dbconnect,$updatebookstatusSQL);
			$price = $getbooklog['price'];
		} else if($condition=="damaged") {
			$updatebookstatusSQL = "UPDATE book SET status='Archived', bookcondition='Damaged' WHERE accession_no='$accession_no'"; 
			$updatebookstatusQuery = mysqli_query($dbconnect,$updatebookstatusSQL);
			$price = $getbooklog['price'];
		}

		$settingsSQL = "SELECT * FROM settings";
		$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
		$settings = mysqli_fetch_assoc($settingsQuery);

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

		if(strtotime($datereturned) > strtotime($duedate)) {
			$duedatetime = new Datetime($duedate);
			$datereturnedtime = new Datetime($datereturned);
			$datediff = $datereturnedtime->diff($duedatetime);
			$daysoverdue = $datediff->days;

			$betweendates = new DatePeriod($duedatetime, new DateInterval('P1D'), $datereturnedtime);

			foreach($betweendates AS $dates) {
				$date = $dates->format("D");
				if($date=="Sat") {
					$daysoverdue--;
				} else if(in_array($dates->format("Y-m-d"), $holidayarray)) {
					$daysoverdue--;
				}
			}
			$penalty = $daysoverdue * $settings['penalty'];
		} else {
			$penalty = 0.00;
		}
	

		$penaltySQL = "UPDATE booklog SET penalty='$penalty' WHERE booklogID='$booklogID'";
		$penaltyQuery = mysqli_query($dbconnect, $penaltySQL);

		$borrowerSQL = "SELECT * FROM borrower WHERE IDNumber='$idnumber'";
		$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
		$borrower = mysqli_fetch_assoc($borrowerQuery);
		$newbalance = $borrower['accountbalance'] + $penalty + $price;

		$accountbalanceSQL = "UPDATE borrower SET accountbalance='$newbalance' WHERE IDNumber='$idnumber'";
		$accountbalance = mysqli_query($dbconnect, $accountbalanceSQL);

		$booklogSQL = "SELECT booklogID, book.accession_no, booktitle, callnumber, barcode, dateborrowed, duedate FROM booklog JOIN book ON book.accession_no=booklog.accession_no WHERE IDNumber='$idnumber' AND datereturned IS NULL";
		$booklogQuery = mysqli_query($dbconnect, $booklogSQL);
		$booklog = mysqli_fetch_assoc($booklogQuery);
		$booklogrows = mysqli_num_rows($booklogQuery);


		if($booklogrows>=1) {
?>
<table class="table table-hover" id="returntable">
	<tr>
		<th>Accession Number</th>
		<th>Title</th>
		<th>Date Borrowed</th>
		<th>Due Date</th>
		<th>Days Overdue</th>
		<th>Pending Penalty</th>
		<th> </th>
	</tr>
	<?php
		do {
	?>
			<tr>
				<td><?php echo $booklog['accession_no'];?></td>
				<td><?php echo $booklog['booktitle'];?></td>
				<td><?php echo $booklog['dateborrowed'];?></td>
				<td><?php echo $booklogduedate = $booklog['duedate'];?></td>
				<td>
					<?php
						if(strtotime($currentdate) > strtotime($booklogduedate)) {
							$duedatetime = new Datetime($booklogduedate);
							$currentdatetime = new Datetime($currentdate);
							$datediff = $currentdatetime->diff($duedatetime);
							$daysoverdue = $datediff->days;

							$betweendates = new DatePeriod($duedatetime, new DateInterval('P1D'), $currentdatetime);

							foreach($betweendates AS $dates) {
								$date = $dates->format("D");
								if($date=="Sat") {
									$daysoverdue--;
								} else if(in_array($dates->format("Y-m-d"), $holidayarray)) {
									$daysoverdue--;
								}
							}
							echo "$daysoverdue day(s)";
						} else {
							echo "0 day(s)";
						}
					?>
				</td>
				<td>
				<?php
					if(strtotime($currentdate) > strtotime($booklogduedate)) {
							$duedatetime = new Datetime($booklogduedate);
							$currentdatetime = new Datetime($currentdate);
							$datediff = $currentdatetime->diff($duedatetime);
							$daysoverdue = $datediff->days;

							$betweendates = new DatePeriod($duedatetime, new DateInterval('P1D'), $currentdatetime);

							foreach($betweendates AS $dates) {
								$date = $dates->format("D");
								if($date=="Sat") {
									$daysoverdue--;
								} else if(in_array($dates->format("Y-m-d"), $holidayarray)) {
									$daysoverdue--;
								}
							}
							echo $daysoverdue * $settings['penalty'].".00";
						} else {
							echo "0.00";
						}
					?>
				</td>
				<td>
					<button class="btn btn-success btn-sm button returnbutton" data-id="<?php echo $booklog['booklogID'];?>" data-toggle="modal" data-target="#confirmreturn">Return</button>
				</td>
			</tr>
	<?php
		} while($booklog = mysqli_fetch_assoc($booklogQuery));
	?>
</table>
<?php
	} else {
?>
	<div id="returnalert" class="alert">
		Books were returned successfully.
		<button class="btn btn-link btn-md">
			<a href="?page=bklogs">Click here to view all book logs.</a>
		</button>
	</div>
<?php
	}

	$returnedbooksSQL = "SELECT * FROM booklog WHERE IDNumber='$idnumber' AND booklogID='$booklogID'";
?>
<a target="_blank" href="returntransactionreceipt.php?idnumber=<?php echo $getbooklog['IDNumber'];?>">
	<button id="printbutton" class="btn btn-success"><span class="glyphicon glyphicon-print"></span> Print Receipt</button>
</a>
<script>
$(document).ready(function(){
	$(document).on("click",".returnbutton",function(){
		var booklogID = $(this).data("id");
		$(".confirmreturn").data("id", booklogID);
	});

	$("#confirmreturnform").submit(function(e){
		e.preventDefault();
		var booklogID = $(".confirmreturn").data("id");
		var condition = $("input[name=condition]:checked").val();
		$.ajax({
			url:"returnbook.php",
			method:"POST",
			data:{booklogID:booklogID, condition:condition},
			success:function(data) {
				$(".borrowedbooks").html(data);
				$("#confirmreturn").modal("hide");
			}
		});
	});

	/*$("#confirmreturn").on("hide.bs.modal", function(){
		$(".condition").prop("checked", false);
	});*/
});
</script>
<?php
}
?>
