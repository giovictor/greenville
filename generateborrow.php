<?php
session_start();
require "dbconnect.php";
if(isset($_POST['idnumber'])) {
	$idnumber = $_POST['idnumber'];
		$borrowerSQL = "SELECT borrower.*, COUNT(*) AS rows, accountbalance FROM borrower WHERE IDNumber='$idnumber' AND status='Active'";
		$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
		$borrower = mysqli_fetch_assoc($borrowerQuery);
		$rows = $borrower['rows'];
		$borrowername = $borrower['lastname'].", ".$borrower['firstname']." ".$borrower['mi'];

		$booklogSQL = "SELECT * FROM booklog WHERE IDNumber='$idnumber' AND datereturned IS NULL";
		$booklogQuery = mysqli_query($dbconnect, $booklogSQL);
		$booklogrows = mysqli_num_rows($booklogQuery);

		$truncateborrowcartSQL = "TRUNCATE TABLE borrowcart";
		$truncateborrowcart = mysqli_query($dbconnect, $truncateborrowcartSQL);

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
		} else if($rows==0) {
			echo "Invalid";
		} else if($booklogrows==3) {
			echo "Limit";
		} else if($borrower['accountbalance'] > 0.00) {
			echo "Not Allowed";
		} else if($rows==1) {
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
<div id="showreservations">
<?php 
	$reservationsSQL ="SELECT reservationID, book.accession_no, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, booktitle, callnumber, reservationdate, expdate, showstatus FROM reservation JOIN borrower ON borrower.IDNumber=reservation.IDNumber JOIN book ON book.accession_no=reservation.accession_no LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID WHERE borrower.IDNumber='$idnumber' AND showstatus=1 GROUP BY book.accession_no";
	$reservationsQuery = mysqli_query($dbconnect, $reservationsSQL);
	$reservations = mysqli_fetch_assoc($reservationsQuery);
	$checkDB = mysqli_num_rows($reservationsQuery);

	if($checkDB>=1) {
?>
<h5>Reservations</h5>
<table class='table table-hover'>
	<tr>
		<th>Accession Number</th>
		<th>Title</th>
		<th>Reservation Date</th>
		<th>Expiration Date</th>
		<th> </th>
	</tr>
	<?php
		do {
	?>
	<tr>
		<td><?php echo $reservations['accession_no']; ?></td>
		<td><?php echo $reservations['booktitle']; ?></td>
		<td><?php echo $reservations['reservationdate']; ?></td>
		<td><?php echo $reservations['expdate']; ?></td>
		<td>
			<button class="btn btn-danger btn-sm deletereserve" data-id="<?php echo $reservations['reservationID'];?>" data-toggle="modal" data-target="#confirmadmincancelreserve">Cancel Reservation</button>
		</td>
	</tr>
	<?php
		} while($reservations = mysqli_fetch_assoc($reservationsQuery));
	?>
</table>
<?php
}
?>
</div>
<form id="booksearchform" class="form-inline">
	<div class="form-group">
		<label for="barcode">Search for book: </label>
		<select name="searchtype" id="booksearchtype" class="form-control">
			<option value="booktitle">Title</option>
			<option value="accession_no">Accession Number</option>
			<option value="barcode">Barcode</option>
			<option value="callnumber">Call Number</option>
		</select>
		<input type="text" name="booksearch" id="booksearch" class="form-control" placeholder="Search for title, accession number, barcode or callnumber">
	</div>
	<div class="form-group">
		<button id="searchbookbutton" class="btn btn-success btn-sm button">Search for Book <span class="glyphicon glyphicon-search"></span></button>
	</div>
</form>
<div id="booksearchresults"></div>

<form id="inputdata" class="form-inline">
	<div class="form-group">
		<label for="barcode">Barcode/Accession Number: </label>
		<input type="text" name="bookid" id="bookid" class="form-control" placeholder="Scan barcode or input accession number for borrowing" size="20">
	</div>
	<div class="form-group">
		<button id="findbookbutton" class="btn btn-success btn-sm">Find Book <span class="glyphicon glyphicon-search"></span></button>
	</div>
		<input type="hidden" name="idnumber" id="idnumber" value="<?php echo $idnumber;?>">
</form> 
<div id="borrowcart"></div>

<script>
	$(document).ready(function(){
		$("#inputdata").submit(function(e){
			e.preventDefault();
			var bookid = $("#bookid").val();
			var idnumber = $("#idnumber").val();
			if(bookid=="") {
				$("#emptybookid").modal("show");
			} else {
				$.ajax({
					url:"borrowcart.php",
					method:"POST",
					data:{bookid:bookid, idnumber:idnumber},
					beforeSend:function() {
						$("#findbookbutton").html("Finding Book...");
					},
					success:function(data) {
						if(data=="Invalid") {
							$("#invalidbookid").modal("show");
							$("#findbookbutton").html("Find Book <span class='glyphicon glyphicon-search'></span>");
							$("#bookid").val("");
						} else if(data=="Unavailable"){
							$("#notavailable").modal("show");
							$("#findbookbutton").html("Find Book <span class='glyphicon glyphicon-search'></span>");
							$("#bookid").val("");
						} else if(data=="Limit") {
							$("#limitborrow").modal("show");
							$("#findbookbutton").html("Find Book <span class='glyphicon glyphicon-search'></span>");
							$("#bookid").val("");
						} else if(data=="No Duplicate") {
							$("#noduplicate").modal("show");
							$("#findbookbutton").html("Find Book <span class='glyphicon glyphicon-search'></span>");
							$("#bookid").val("");
						} else if(data=="Already In Cart") {
							$("#alreadyincart").modal("show");
							$("#findbookbutton").html("Find Book <span class='glyphicon glyphicon-search'></span>");
							$("#bookid").val("");
						} else {
							$("#findbookbutton").html("Find Book <span class='glyphicon glyphicon-search'></span>");
							$("#borrowcart").html(data);
							$("#bookid").val("");
							$("#bookid").focus();
						}
					}
				});
			}
		});

	$("#emptybookid").on("hide.bs.modal",function(){
		$("#bookid").focus();
	});

	$("#invalidbookid").on("hide.bs.modal",function(){
		$("#bookid").focus();
	});

	$("#notavailable").on("hide.bs.modal",function(){
		$("#bookid").focus();
	});

	$("#limitborrow").on("hide.bs.modal",function(){
		$("#bookid").focus();
	});

	$("#noduplicate").on("hide.bs.modal",function(){
		$("#bookid").focus();
	});

	$("#deletefromborrowcart").on("hide.bs.modal",function(){
		$("#bookid").focus();
	});
	
	$("#booksearchform").submit(function(e){
		e.preventDefault();
		var booksearchtype = $("#booksearchtype").val();
		var booksearch = $("#booksearch").val();
		if(booksearch=="") {
			$("#emptysearch").modal("show");
		} else {
			$.ajax({
				url:"borrowbooksearchresults.php",
				method:"POST",
				data:{booksearchtype:booksearchtype, booksearch:booksearch},
				beforeSend:function() {
					$("#searchbookbutton").html("Searching...")
				},
				success:function(data) {
					if(data=="Invalid") {
						$("#invalidsearch").modal("show");
						$("#searchbookbutton").html("Search for Book <span class='glyphicon glyphicon-search'></span>");
					} else {
						$("#booksearchresults").html(data);
						$("#searchbookbutton").html("Search for Book <span class='glyphicon glyphicon-search'></span>");
					}
				}
			});
		}
	});
	
	$("#emptysearch").on("hide.bs.modal", function(){
		$("#booksearch").focus();
	});

	$("#invalidsearch").on("hide.bs.modal", function(){
		$("#booksearch").focus();
	});

	$(document).on("click",".deletereserve",function(){
		var reservationID = $(this).data("id");
		$(".confirmcancelreserve").data("id",reservationID);
	});

	$(".confirmcancelreserve").click(function(){
		var reservationID = $(this).data("id");
		$.ajax({
			url:"deletereserve.php",
			method:"POST",
			data:{reservationID:reservationID},
			success:function(data) {
				$("#confirmadmincancelreserve").modal("hide");
				$("#showreservations").html(data);
			}
		});
	});
});
</script>

<?php
	}
}
?>