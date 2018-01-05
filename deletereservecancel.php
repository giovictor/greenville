<?php
require "dbconnect.php";
if(isset($_POST['reservationID'])) {
	$reservationID = $_POST['reservationID'];

	$getaccnumSQL = "SELECT accession_no FROM reservation WHERE reservationID='$reservationID'";
	$getaccnumQuery = mysqli_query($dbconnect, $getaccnumSQL);
	$getaccnum = mysqli_fetch_assoc($getaccnumQuery);
	$accession_no = $getaccnum['accession_no'];

	$updatebookstatusSQL = "UPDATE book SET status='On Shelf' WHERE accession_no='$accession_no'";
	$updatebookstatusQuery = mysqli_query($dbconnect, $updatebookstatusSQL);

	$deleteReserveSQL = "UPDATE reservation SET showstatus=0 WHERE reservationID='$reservationID'";
	$deleteReserveQuery = mysqli_query($dbconnect, $deleteReserveSQL);

	$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE showstatus=1 ORDER BY reservationID DESC";
	$reservationsQuery = mysqli_query($dbconnect, $reservationsSQL);
	$reservations = mysqli_fetch_assoc($reservationsQuery);
	$rows = mysqli_num_rows($reservationsQuery);
?>
	<table class="table table-hover">
		<tr>
			<th>ID Number</th>
			<th>Borrower</th>
			<th>Accession Number</th>
			<th>Title</th>
			<th>Reservation Date</th>
			<th>Expiration Date</th>
			<th> </th>
		</tr>
		<?php
			if($rows==0) {
				echo "<tr><td style='text-align:center;' colspan='6'><h4>No reservations were made for this day.</h4></td></tr>";
			} else if($rows>=1) {
				do {
		?>
			<tr>
				<td>
					<button type="button" style="color:#1CA843;" class="btn btn-link borrowerinfo" id="<?php echo $reservations['IDNumber'];?>">
						<b><?php echo $reservations['IDNumber'];?></b>
					</button>
				</td>
				<td><?php echo $reservations['lastname'].", ".$reservations['firstname']." ".$reservations['mi']; ?></td>
				<td><?php echo $reservations['accession_no'];?></td>
				<td><?php echo $reservations['booktitle'];?></td>
				<td><?php echo $reservations['reservationdate'];?></td>
				<td><?php echo $reservations['expdate'];?></td>
				<td>
					<button class="btn btn-danger btn-sm deletereserve" data-id="<?php echo $reservations['reservationID'];?>" data-toggle="modal" data-target="#confirmadmincancelreserve">Cancel Reservation</button>
				</td>
			</tr>
		<?php
			} while($reservations = mysqli_fetch_assoc($reservationsQuery));
		}
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
			url:"deletereservecancel.php",
			method:"POST",
			data:{reservationID:reservationID},
			success:function(data) {
				$("#confirmadmincancelreserve").modal("hide");
				$(".reservations").html(data);
			}
		});
	});

	$(".borrowerinfo").click(function(){
		var idnumber = $(this).attr("id");
		$.ajax({
			url:"borrowermodalinfo.php",
			method:"POST",
			data:{idnumber:idnumber},
			success:function(data) {
				$("#borrowerinfodata").html(data);
				$("#borrowerinfomodal").modal("show");
			}
		});
	});
});
</script>
<?php
}
?>