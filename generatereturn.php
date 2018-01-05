<?php
require "dbconnect.php";
if(isset($_POST['idnumber'])) {
	$idnumber = $_POST['idnumber'];
		$borrowerSQL = "SELECT borrower.*, COUNT(*) AS rows FROM borrower WHERE IDNumber='$idnumber' AND status='Active'";
		$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
		$borrower = mysqli_fetch_assoc($borrowerQuery);
		$borrowerrows = $borrower['rows'];
		$borrowername = $borrower['lastname'].", ".$borrower['firstname']." ".$borrower['mi'];

		$booklogSQL = "SELECT booklogID, book.accession_no, booktitle, callnumber, barcode, dateborrowed, duedate FROM booklog JOIN book ON book.accession_no=booklog.accession_no WHERE IDNumber='$idnumber' AND datereturned IS NULL";
		$booklogQuery = mysqli_query($dbconnect, $booklogSQL);
		$booklog = mysqli_fetch_assoc($booklogQuery);
		$booklogrows = mysqli_num_rows($booklogQuery);
		$currentdate = date("Y-m-d");

		$settingsSQL = "SELECT * FROM settings";
		$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
		$settings = mysqli_fetch_assoc($settingsQuery);

		$holidaySQL = "SELECT * FROM holiday";
		$holidayQuery = mysqli_query($dbconnect, $holidaySQL);
		$holiday = mysqli_fetch_assoc($holidayQuery);
		$holidayrows = mysqli_num_rows($holidayQuery);
		$holidayarray = array();
		if($holidayrows > 0) {
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
		}


		$day = date("D");
		$date = date("Y-m-d");
		if($day=="Sat" || $day=="Sun") {
			echo "Weekends";
		} else if(in_array($date, $holidayarray)) {
			echo "Weekends";
		} else if($borrowerrows==0) {
			echo "Invalid";
		} else if($booklogrows==0) {
			echo "Did Not Borrow";
		} else if($borrowerrows==1 && $booklogrows>=1) {
?>
<form id="borrowerinfo" class="form-inline">
	<div class="form-group">
		<label for="borrower">Borrower: </label>
		<input type="text" id="borrower" class="form-control" value="<?php echo $borrowername;?>" size="20" disabled>
	</div>
	<div class="form-group">
		<label for="course">Course: </label>
		<input type="text" id="course" class="form-control" value="<?php echo $borrower['course'];?>" size="20" disabled>
	</div>
</form>
<div class="borrowedbooks">
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
				<td><?php echo $duedate = $booklog['duedate'];?></td>
				<td>
					<?php
						if(strtotime($currentdate) > strtotime($duedate)) {
							$duedatetime = new Datetime($duedate);
							$currentdatetime = new Datetime($currentdate);
							$datediff = $currentdatetime->diff($duedatetime);
							$daysoverdue = $datediff->days;

							$betweendates = new DatePeriod($duedatetime, new DateInterval('P1D'), $currentdatetime);

							foreach($betweendates AS $dates) {
								$date = $dates->format("D");
								if($date=="Fri" || $date=="Sat") {
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
						if(strtotime($currentdate) > strtotime($duedate)) {
							$duedatetime = new Datetime($duedate);
							$currentdatetime = new Datetime($currentdate);
							$datediff = $currentdatetime->diff($duedatetime);
							$daysoverdue = $datediff->days;

							$betweendates = new DatePeriod($duedatetime, new DateInterval('P1D'), $currentdatetime);

							foreach($betweendates AS $dates) {
								$date = $dates->format("D");
								if($date=="Fri" || $date=="Sat") {
									$daysoverdue--;
								} else if(in_array($dates->format("Y-m-d"), $holidayarray)) {
									$daysoverdue--;
								}
							}
							echo $penalty = $daysoverdue * $settings['penalty'].".00";
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
</div>
<form id="data">
	<input type="hidden" name="idnumber" id="idnumber" value="<?php echo $idnumber;?>">
</form>
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
}
?>