<div class="admincontainer">	
	<div class="panel panel-success reservationssearchform">
		<div class="panel-heading">
			<a href="?page=vrs" class="btn btn-success btn-sm button" style="float:right;">View All Reservations</a>
			<h3>Reserved Books</h3>
		</div>
		<div class="panel-body">
			<form method="GET" id="reservationssearchform">
				<table>
					<tr>	
						<td>
							<label>Reservation Date:</label>
						</td>
						<td>
							<input type="date" size="15" name="reservedate" id="reservedate" class="form-control">
						</td>
						<td>
							<label>Expiration Date:</label>
						</td>
						<td>
							<input type="date" size="15" name="expdate"  id="expdate" class="form-control">
						</td>
					</tr>
					<tr>	
						<td>
							<label>Borrower:</label>
						</td>
						<td>
							<input type="text" size="15" name="borrower"  id="borrower" class="form-control" placeholder="Search for borrower">
						</td>
						<td>
							<label>Book:</label>
						</td>
						<td>
							<input type="text" size="15" name="book" class="form-control"  id="book" placeholder="Search for book">
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" class="btn btn-success btn-sm button" value="Search" name="reservesearchbutton">
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
	if(isset($_GET['reservesearchbutton'])) {
		$reservedate = $_GET['reservedate'];
		$expdate = $_GET['expdate'];
		$borrower = $_GET['borrower'];
		$book = $_GET['book'];

		if(!empty($reservedate) && empty($expdate) && empty($borrower) && empty($book)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($expdate) && empty($reservedate) && empty($borrower) && empty($book)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$expdate' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($borrower) && empty($reservedate) && empty($expdate) && empty($book)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($book) && empty($reservedate) && empty($expdate) && empty($borrower)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($reservedate) && !empty($expdate) && empty($borrower) && empty($book)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND expdate='$expdate' AND showstatus=1 ORDER BY reservationID DESC";
		}  else if(!empty($reservedate) && !empty($borrower) && empty($expdate) && empty($book)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($reservedate) && !empty($book) && empty($expdate) && empty($borrower)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($expdate) && !empty($borrower) && empty($reservedate) && empty($book)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$expdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($expdate) && !empty($book) && empty($reservedate) && empty($borrower)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$expdate' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($borrower) && !empty($book) && empty($reservedate) && empty($expdate)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($reservedate) && !empty($expdate) && !empty($borrower) && empty($book)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND expdate='$expdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($reservedate) && !empty($expdate) && !empty($book) && empty($borrower)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND expdate='$expdate' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($reservedate) && !empty($borrower) && !empty($book) && empty($expdate)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC";
		}  else if(!empty($expdate) && !empty($borrower) && !empty($book) && empty($reservedate)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE expdate='$expdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC";
		} else if(!empty($expdate) && !empty($borrower) && !empty($book) && !empty($reservedate)) {
			$reservationsSQL = "SELECT reservationID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, reservationdate, expdate, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE reservationdate='$reservedate' AND expdate='$expdate' AND CONCAT(borrower.IDNumber, lastname, firstname, mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND showstatus=1 ORDER BY reservationID DESC";
		} 

		$reservationsQuery = mysqli_query($dbconnect, $reservationsSQL);
		$reservations = mysqli_fetch_assoc($reservationsQuery);
		$rows = mysqli_num_rows($reservationsQuery);

	?>
	<div class="reservations">
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
					echo "<tr><td style='text-align:center;' colspan='6'><h4>No results found.</h4></td></tr>";
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
	</div>
	<?php
		if($rows>=1) {
	?>
		<form method="POST" action="pdfreservations.php" target="_blank" class="form-inline">
			<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
			<input type="hidden" name="query" value="<?php echo $reservationsSQL;?>">
		</form>
	<?php
		} 
	?>
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

		$("#reservationssearchform").submit(function(e){
			var reservedate = $("#reservedate").val();
			var expdate = $("#expdate").val();
			var borrower = $("#borrower").val();
			var book = $("#book").val();
			if(reservedate=="" && expdate=="" && borrower=="" && book=="") {
				e.preventDefault();
				$("#emptysearch").modal("show");
			} else if(reservedate > expdate) {
				$("#invalidreservedate").modal("show");
				e.preventDefault();
			}
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
</div>