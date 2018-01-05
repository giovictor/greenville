<?php
session_start();
require "dbconnect.php";
if(isset($_POST['reservationID'])) {
	$reservationID = $_POST['reservationID'];
		$deletereservationSQL = "DELETE FROM reservation WHERE reservationID='$reservationID'";
		$deletereservation = mysqli_query($dbconnect, $deletereservationSQL);

		$archivedreservationsSQL = "SELECT reservationID,borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE showstatus=0 ORDER BY reservationID DESC";
		$archivedreservationsQuery = mysqli_query($dbconnect, $archivedreservationsSQL);
		$archivedreservations = mysqli_fetch_assoc($archivedreservationsQuery);
		$rows = mysqli_num_rows($archivedreservationsQuery);
?>
<table class="table table-hover table-bordered">
		<tr>
			<th>ID Number</th>
			<th>Borrower</th>
			<th>Accession Number</th>
			<th>Title</th>
			<th>Reservation Date</th>
			<th>Expiration Date</th>
			<?php
				if($rows>=1) {
			?>
					<th> </th>
			<?php
				}
			?>
		</tr>
		<?php
			if($rows==0) {
				echo "<tr><td style='text-align:center;' colspan='6'><h4>There were no archived reservations.</h4></td></tr>";
			} else if($rows>=1) {
				do {
		?>
			<tr>
				<td><?php echo $archivedreservations['IDNumber'];?></td>
				<td><?php echo $archivedreservations['lastname'].", ".$archivedreservations['firstname']." ".$archivedreservations['mi']; ?></td>
				<td><?php echo $archivedreservations['accession_no'];?></td>
				<td><?php echo $archivedreservations['booktitle'];?></td>
				<td><?php echo $archivedreservations['reservationdate'];?></td>
				<td><?php echo $archivedreservations['expdate'];?></td>
				<td>
					<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedreservations['reservationID'];?>" data-toggle="modal" data-target="#permanentdeletereservation">
						<span class="glyphicon glyphicon-trash"> </span>
					</button>
				</td>
			</tr>
		<?php
			} while($archivedreservations = mysqli_fetch_assoc($archivedreservationsQuery));
		}
		?>
	</table>
	<form method="POST" action="pdfarchivedreservations.php" target="_blank" class="form-inline">
		<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
		<input type="hidden" name="query" value="<?php echo $archivedreservationsSQL;?>">
	</form>
<script>
$(document).ready(function(){
	$(document).on("click",".permanentdeletebutton",function(){
		var reservationID = $(this).data("id");
		$(".confirmpermanentdeletereservation").data("id",reservationID);
	});

	$(".confirmpermanentdeletereservation").click(function(){
		var reservationID = $(this).data("id");
		$.ajax({
			url:"permanentdeletereservations.php",
			method:"POST",
			data:{reservationID:reservationID},
			success:function(data) {
				$("#permanentdeletereservation").modal("hide");
				$(".reservations").html(data);
			}
		});
	});
});
</script>
<?php
}
?>