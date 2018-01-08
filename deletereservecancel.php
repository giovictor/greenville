<?php
require "dbconnect.php";
if(isset($_POST['reservationID']) && isset($_POST['reserveperpages']) && isset($_POST['firstresult'])) {
	$reservationID = $_POST['reservationID'];

	$getaccnumSQL = "SELECT accession_no FROM reservation WHERE reservationID='$reservationID'";
	$getaccnumQuery = mysqli_query($dbconnect, $getaccnumSQL);
	$getaccnum = mysqli_fetch_assoc($getaccnumQuery);
	$accession_no = $getaccnum['accession_no'];

	$updatebookstatusSQL = "UPDATE book SET status='On Shelf' WHERE accession_no='$accession_no'";
	$updatebookstatusQuery = mysqli_query($dbconnect, $updatebookstatusSQL);

	$deleteReserveSQL = "UPDATE reservation SET showstatus=0 WHERE reservationID='$reservationID'";
	$deleteReserveQuery = mysqli_query($dbconnect, $deleteReserveSQL);

	$reserveperpages = $_POST['reserveperpages'];
	$firstresult = $_POST['firstresult'];
	
	$reservedate = $_POST['reservedate'];
	$expdate = $_POST['expdate'];
	$borrower = $_POST['borrower'];
	$book = $_POST['book'];

	if(!empty($reservedate) && empty($expdate) && empty($borrower) && empty($book)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($expdate) && empty($reservedate) && empty($borrower) && empty($book)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$expdate' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($borrower) && empty($reservedate) && empty($expdate) && empty($book)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($book) && empty($reservedate) && empty($expdate) && empty($borrower)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($reservedate) && !empty($expdate) && empty($borrower) && empty($book)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND expdate='$expdate' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	}  else if(!empty($reservedate) && !empty($borrower) && empty($expdate) && empty($book)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($reservedate) && !empty($book) && empty($expdate) && empty($borrower)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($expdate) && !empty($borrower) && empty($reservedate) && empty($book)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$expdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($expdate) && !empty($book) && empty($reservedate) && empty($borrower)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$expdate' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC";
	} else if(!empty($borrower) && !empty($book) && empty($reservedate) && empty($expdate)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($reservedate) && !empty($expdate) && !empty($borrower) && empty($book)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND expdate='$expdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($reservedate) && !empty($expdate) && !empty($book) && empty($borrower)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND expdate='$expdate' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($reservedate) && !empty($borrower) && !empty($book) && empty($expdate)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	}  else if(!empty($expdate) && !empty($borrower) && !empty($book) && empty($reservedate)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$expdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	} else if(!empty($expdate) && !empty($borrower) && !empty($book) && !empty($reservedate)) {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND expdate='$expdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	}  else {
		$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE showstatus=1 ORDER BY reservationID DESC LIMIT $firstresult, $reserveperpages";
	}
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
				echo "<tr><td style='text-align:center;' colspan='6'><h4>No reservations were made.</h4></td></tr>";
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

	<?php
		if(isset($_POST['reservedate']) && isset($_POST['expdate']) && isset($_POST['borrower']) && isset($_POST['book'])) {
	?>
			$(".confirmcancelreserve").click(function(){
				var reservationID = $(this).data("id");
				var reservedate = $("#reservedate").val();
				var expdate = $("#expdate").val();
				var borrower = $("#borrower").val();
				var book = $("#book").val();
				var reserveperpages = $("#reserveperpages").val();
				var firstresult = $("#firstresult").val();
				$.ajax({
					url:"deletereservecancel.php",
					method:"POST",
					data:{reservationID:reservationID,  reserveperpages:reserveperpages, firstresult:firstresult, reservedate:reservedate, expdate:expdate, borrower:borrower, book:book},
					success:function(data) {
						$("#confirmadmincancelreserve").modal("hide");
						$(".reservations").html(data);
					}
				});
			});
	<?php
		} else {
	?>
			$(".confirmcancelreserve").click(function(){
				var reservationID = $(this).data("id");
				var reserveperpages = $("#reserveperpages").val();
				var firstresult = $("#firstresult").val();
				$.ajax({
					url:"deletereservecancel.php",
					method:"POST",
					data:{reservationID:reservationID, reserveperpages:reserveperpages, firstresult:firstresult},
					success:function(data) {
						$("#confirmadmincancelreserve").modal("hide");
						$(".reservations").html(data);
					}
				});
			});
	<?php
		}
	?>

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