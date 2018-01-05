<div class="admincontainer">
	<div class="panel panel-success reservationssearchform">
		<div class="panel-heading">
			<h3>Archived Reserved Books</h3>
		</div>
		<div class="panel-body">
			<form method="GET" id="archivedreservationssearchform">
				<table>
					<tr>	
						<td>
							<label>Reservation Date:</label>
						</td>
						<td>
							<input type="date" size="15" name="archivedreservedate" id="archivedreservedate" class="form-control">
						</td>
						<td>
							<label>Expiration Date:</label>
						</td>
						<td>
							<input type="date" size="15" name="archivedexpdate"  id="archivedexpdate" class="form-control">
						</td>
					</tr>
					<tr>	
						<td>
							<label>Borrower:</label>
						</td>
						<td>
							<input type="text" size="15" name="archivedborrower"  id="archivedborrower" class="form-control" placeholder="Search for borrower">
						</td>
						<td>
							<label>Book:</label>
						</td>
						<td>
							<input type="text" size="15" name="archivedbook" class="form-control"  id="archivedbook" placeholder="Search for book">
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" class="btn btn-success btn-sm button" value="Search" name="archivedreservesearchbutton">
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
		if(isset($_GET['archivedreservesearchbutton'])) {
			$archivedreservedate = $_GET['archivedreservedate'];
			$archivedexpdate = $_GET['archivedexpdate'];
			$archivedborrower = $_GET['archivedborrower'];
			$archivedbook = $_GET['archivedbook'];

		if(!empty($archivedreservedate) && empty($archivedexpdate) && empty($archivedborrower) && empty($archivedbook)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$archivedreservedate' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedexpdate) && empty($archivedreservedate) && empty($archivedborrower) && empty($archivedbook)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$archivedexpdate' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedborrower) && empty($archivedreservedate) && empty($archivedexpdate) && empty($archivedbook)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$archivedborrower%' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedbook) && empty($archivedreservedate) && empty($archivedexpdate) && empty($archivedborrower)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE booktitle LIKE '%$archivedbook%' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedreservedate) && !empty($archivedexpdate) && empty($archivedborrower) && empty($archivedbook)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$archivedreservedate' AND expdate='$archivedexpdate' AND showstatus=0 ORDER BY reservationID DESC";
		}  else if(!empty($archivedreservedate) && !empty($archivedborrower) && empty($archivedexpdate) && empty($archivedbook)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$archivedreservedate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$archivedborrower%' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedreservedate) && !empty($archivedbook) && empty($archivedexpdate) && empty($archivedborrower)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$archivedreservedate' AND booktitle LIKE '%$archivedbook%' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedexpdate) && !empty($archivedborrower) && empty($archivedreservedate) && empty($archivedbook)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$archivedexpdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$archivedborrower%' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedexpdate) && !empty($archivedbook) && empty($archivedreservedate) && empty($archivedborrower)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$archivedexpdate' AND booktitle LIKE '%$archivedbook%' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedborrower) && !empty($archivedbook) && empty($archivedreservedate) && empty($archivedexpdate)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$archivedborrower%' AND booktitle LIKE '%$archivedbook%' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedreservedate) && !empty($archivedexpdate) && !empty($archivedborrower) && empty($archivedbook)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$archivedreservedate' AND expdate='$archivedexpdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$archivedborrower%' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedreservedate) && !empty($archivedexpdate) && !empty($archivedbook) && empty($archivedborrower)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$archivedreservedate' AND expdate='$archivedexpdate' AND booktitle LIKE '%$archivedbook%' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedreservedate) && !empty($archivedborrower) && !empty($archivedbook) && empty($archivedexpdate)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$archivedreservedate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$archivedborrower%' AND booktitle LIKE '%$archivedbook%' AND showstatus=0 ORDER BY reservationID DESC";
		}  else if(!empty($archivedexpdate) && !empty($archivedborrower) && !empty($archivedbook) && empty($archivedreservedate)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$archivedexpdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$archivedborrower%' AND booktitle LIKE '%$archivedbook%' AND showstatus=0 ORDER BY reservationID DESC";
		} else if(!empty($archivedexpdate) && !empty($archivedborrower) && !empty($archivedbook) && !empty($archivedreservedate)) {
			$archivedreservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$archivedreservedate' AND expdate='$archivedexpdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$archivedborrower%' AND booktitle LIKE '%$archivedbook%' AND showstatus=0 ORDER BY reservationID DESC";
		} 
			$archivedreservationsQuery = mysqli_query($dbconnect, $archivedreservationsSQL);
			$archivedreservations = mysqli_fetch_assoc($archivedreservationsQuery);
			$rows = mysqli_num_rows($archivedreservationsQuery);
	?>
	<div class="reservations">
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
					echo "<tr><td style='text-align:center;' colspan='6'><h4>No results found.</h4></td></tr>";
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
	</div>
</div>
<script>
$(document).ready(function(){
	$("#archivedreservationssearchform").submit(function(e){
		var reservedate = $("#archivedreservedate").val();
		var expdate = $("#archivedexpdate").val();
		var borrower = $("#archivedborrower").val();
		var book = $("#archivedbook").val();

		if(reservedate=="" && expdate=="" && borrower=="" && book=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		} else if(reservedate > expdate) {
			$("#invalidreservedate").modal("show");
			e.preventDefault();
		}
	});

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