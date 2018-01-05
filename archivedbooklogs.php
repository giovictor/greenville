<title>Archived Book Logs</title>
<div class="admincontainer">
	<div class="panel panel-success booklogssearchform">
		<div class="panel-heading">
			<h3>Archived Book Logs</h3>
		</div>
		<div class="panel-body">
			<form method="GET" id="archivedbooklogssearch">
				<table>
					<tr>
						<td>
							<label>Date Borrowed:</label>
						</td>
						<td>
							<input type="date" size="10" name="archiveddateborrowed" class="form-control" id="archiveddateborrowed">
						</td>
						<td>
							<label>Date Returned:</label>
						</td>
						<td>
							<input type="date" size="10" name="archiveddatereturned" class="form-control" id="archiveddatereturned">
						</td>
					</tr>
					<tr>
						<td>
							<label>Borrower:</label>
						</td>
						<td>
							<input type="text" size="15" name="archivedborrower" class="form-control" id="archivedborrower" placeholder="Search for a borrower">
						</td>
						<td>
							<label>Book:</label>
						</td>
						<td>
							<input type="text" size="15" name="archivedbook" class="form-control" id="archivedbook" placeholder="Search for a book">
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="archivedbooklogssearchbutton" class="btn btn-success btn-sm button" value="Search">
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
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC";
		$archivedbooklogsQuery = mysqli_query($dbconnect, $archivedbooklogsSQL);
		$archivedbooklogs = mysqli_fetch_assoc($archivedbooklogsQuery);
		$rows = mysqli_num_rows($archivedbooklogsQuery);

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
	?>
	<div class="booklogs">
		<table class="table table-hover table-bordered">
			<tr>
				<th>ID Number</th>
				<th>Borrower</th>
				<th width="3%">Accession Number</th>
				<th>Title</th>
				<th>Date Borrowed</th>
				<th>Due Date</th>
				<th>Date Returned</th>
				<th>Days Overdue</th>
				<th>Penalty</th>
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
					echo "<tr><td style='text-align:center;' colspan='9'><h4>There were no archived book logs.</h4></td></tr>";
				} else if($rows>=1) {
					do {
			?>
					<tr>
						<td><?php echo $archivedbooklogs['IDNumber'];?></td>
						<td><?php echo $archivedbooklogs['lastname'].", ".$archivedbooklogs['firstname']." ".$archivedbooklogs['mi'];?></td>
						<td><?php echo $archivedbooklogs['accession_no'];?></td>
						<td><?php echo $archivedbooklogs['booktitle'];?></td>
						<td><?php echo $archivedbooklogs['dateborrowed'];?></td>
						<td><?php echo $duedate = $archivedbooklogs['duedate'];?></td>
						<td><?php echo $datereturned = $archivedbooklogs['datereturned'];?></td>
						<td>	
						<?php
							if(strtotime($datereturned) > strtotime($duedate)) {
								$duedatetime = new Datetime($duedate);
								$currentdatetime = new Datetime($datereturned);
								$datediff = $currentdatetime->diff($duedatetime);
								$daysoverdue = $datediff->days;

								$betweendays = new DatePeriod($duedatetime, new DateInterval("P1D"), $currentdatetime);
								foreach($betweendays AS $days) {
									$day = $days->format("D");
									if($day=="Sat" || $day=="Sun") {
										$daysoverdue--;
									} else if(in_array($days->format("Y-m-d"), $holidayarray)) {
										$daysoverdue--;
									}
								}
								echo "$daysoverdue days(s)";
							} else {
								echo "0 day(s)";
							}
						?>
						</td>
						<td><?php echo $archivedbooklogs['penalty'];?></td>
						<td>
							<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedbooklogs['booklogID'];?>" data-toggle="modal" data-target="#restorebooklog">
								<span class="glyphicon glyphicon-refresh"> </span>
							</button>
							<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedbooklogs['booklogID'];?>" data-toggle="modal" data-target="#permanentdeletebooklog">
								<span class="glyphicon glyphicon-trash"> </span>
							</button>
						</td>
					</tr>
				<?php
					} while($archivedbooklogs = mysqli_fetch_assoc($archivedbooklogsQuery));
				}
				?>
		</table>
		<form method="POST" action="pdfarchivedbooklogs.php" target="_blank" class="form-inline">
			<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
			<input type="hidden" name="query" value="<?php echo $archivedbooklogsSQL;?>">
		</form>
	</div>
</div>
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton",function(){
		var booklogID = $(this).data("id");
		$(".confirmrestorebooklog").data("id", booklogID);
	});

	$(".confirmrestorebooklog").click(function(){
		var booklogID = $(this).data("id");
		$.ajax({
			url:"restorebooklog.php",
			method:"POST",
			data:{booklogID:booklogID},
			success:function(data) {
				$("#restorebooklog").modal("hide");
				$(".booklogs").html(data);
			}
		});
	});

	$("#archivedbooklogssearch").submit(function(e){
		var dateborrowed = $("#archiveddateborrowed").val();
		var datereturned = $("#archiveddatereturned").val();
		var borrower = $("#archivedborrower").val();
		var book = $("#archivedbook").val();
		if(dateborrowed=="" && datereturned=="" && borrower=="" && book=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		} else if(dateborrowed > datereturned) {
			$("#invaliddateborrowed2").modal("show");
			e.preventDefault();
		}
	});


	$("#permanentdeletebooklog").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>