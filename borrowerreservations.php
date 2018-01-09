<?php
require "dbconnect.php";
if(!isset($_SESSION['borrower'])) {
	header("Location:index.php");
} else {
	$borrower=$_SESSION['borrower'];
	$reservationSQL = "SELECT reservationID, book.accession_no, borrower.IDNumber, borrower.firstname, callnumber, booktitle, reservationdate, expdate,  author, publisher, publishingyear, showstatus FROM reservation JOIN borrower ON borrower.IDNumber=reservation.IDNumber JOIN book ON book.accession_no=reservation.accession_no LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID WHERE firstname='$borrower' AND showstatus=1";
	$reservationQuery = mysqli_query($dbconnect, $reservationSQL);
	$reservation = mysqli_fetch_assoc($reservationQuery);
	$checkDB = mysqli_num_rows($reservationQuery);
?>
<title>Reservations - <?php echo $borrower;?></title>
<h4>Current Reservations</h4>
<div class="reservations">
	<table class='table table-hover'>
		<tr>
			<th>Title</th>
			<th>Author</th>
			<th>Publication Details</th>
			<th>Reservation Date</th>
			<th>Expiration Date</th>
			<th> </th>
		</tr>
		<?php
			if($checkDB==0) {
				echo "<tr><td colspan='7'><center><h4>You currently have no reservations.</h4></center></td></tr>";
			} else if($checkDB>=1) {
		?>
		<?php
			do {?>
				<tr>
					<td><?php echo $reservation['booktitle'];?></td>
					<td><?php echo $reservation['author'];?></td>
					<td><?php echo $reservation['publisher']." c".$reservation['publishingyear'];?></td>
					<td><?php echo $reservation['reservationdate'];?></td>
					<td><?php echo $reservation['expdate'];?></td>
					<td><button type="button" data-id="<?php echo $reservation['reservationID'];?>" class="btn btn-danger btn-sm cancelreserve" data-toggle="modal" data-target="#confirmborrowercancelreserve">Cancel Reservation</button></td>
				</tr>
			<?php
			} while($reservation = mysqli_fetch_assoc($reservationQuery));
		}
			?>
	</table>
</div>
<?php
}
?>
<script>
$(document).ready(function() {
	$(document).on("click",".cancelreserve", function(){
		var reservationID = $(this).data("id");
		$(".confirmcancelreserve").data("id", reservationID);
	});

	$(".confirmcancelreserve").click(function(){
		var reservationID = $(this).data("id");
		$.ajax({
			url:"borrowercancelreserve.php",
			method:"POST", 
			data:{reservationID:reservationID},
			success:function(data) {
				$("#confirmborrowercancelreserve").modal("hide");
				$(".reservations").html(data);
			}
		});
	});

	$("#selectallchk").click(function(){
			$("input:checkbox").not(this).prop("checked", this.checked);
	});
});


</script>