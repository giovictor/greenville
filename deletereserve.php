<?php
require "dbconnect.php";
if(isset($_POST['reservationID'])) {
	$reservationID = $_POST['reservationID'];

	$getdataSQL = "SELECT accession_no, IDNumber FROM reservation WHERE reservationID='$reservationID'";
	$getdataQuery = mysqli_query($dbconnect, $getdataSQL);
	$getdata = mysqli_fetch_assoc($getdataQuery);
	$accession_no = $getdata['accession_no'];
	$idnumber = $getdata['IDNumber'];

	$updatebookstatusSQL = "UPDATE book SET status='On Shelf' WHERE accession_no='$accession_no'";
	$updatebookstatusQuery = mysqli_query($dbconnect, $updatebookstatusSQL);

	$deleteReserveSQL = "UPDATE reservation SET showstatus=0 WHERE reservationID='$reservationID'";
	$deleteReserveQuery = mysqli_query($dbconnect, $deleteReserveSQL);

	$reservationsSQL ="SELECT reservationID,book.accession_no, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, booktitle, callnumber, GROUP_CONCAT(DISTINCT author.author SEPARATOR', ') AS authors, reservationdate, expdate, showstatus FROM reservation JOIN borrower ON borrower.IDNumber=reservation.IDNumber JOIN book ON book.accession_no=reservation.accession_no LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID WHERE borrower.IDNumber='$idnumber' AND showstatus=1 GROUP BY book.accession_no";
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
<script>
$(document).ready(function(){
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