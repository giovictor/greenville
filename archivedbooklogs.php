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
		$totalarchivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC";
		$totalarchivedbooklogsQuery = mysqli_query($dbconnect, $totalarchivedbooklogsSQL);
		$rows = mysqli_num_rows($totalarchivedbooklogsQuery);

		$booklogsperpages = 10;
		$numberofpages = ceil($rows/$booklogsperpages);

		if(!isset($_GET['booklogspage'])) {
			$page = 1;
		} else {
			$page = $_GET['booklogspage'];
			if($page < 1) {
				$page = 1;
			} else if($page > $numberofpages) {
				$page = $numberofpages;
			} else if(!is_numeric($page)) {
				$page = 1;
			} else {
				$page = $_GET['booklogspage'];
			}
		}


		$firstresult = ($page - 1) * $booklogsperpages;
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
		$archivedbooklogsQuery = mysqli_query($dbconnect, $archivedbooklogsSQL);
		$archivedbooklogs = mysqli_fetch_assoc($archivedbooklogsQuery);

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
	<div class="reportpdf">
		<form id="printpdf" target="_blank" action="pdfarchivedbooklogs.php" method="POST">
			<input type="hidden" name="query" value="<?php echo $totalarchivedbooklogsSQL;?>">
			<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
		</form>
	</div>
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
							<!--<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedbooklogs['booklogID'];?>" data-toggle="modal" data-target="#permanentdeletebooklog">
								<span class="glyphicon glyphicon-trash"> </span>
							</button>-->
						</td>
					</tr>
				<?php
					} while($archivedbooklogs = mysqli_fetch_assoc($archivedbooklogsQuery));
				}
				?>
		</table>
	</div>
	<?php
		$pagination = '';
		if($numberofpages > 1) {
	?>
			<p style="margin-top:20px;">Page: <?php echo $page;?> of <?php echo $numberofpages;?></p>
	<?php
			if($page > 1) {
				$previous = $page - 1;
				$pagination .= '<a href="?page=archvsbklogs&booklogspage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="?page=archvsbklogs&booklogspage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}

			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="?page=archvsbklogs&booklogspage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="?page=archvsbklogs&booklogspage='.$next.'">Next</a>&nbsp;';	
			}
	?>
			<div class="pagination"><?php echo $pagination;?></div>	
	<?php
		}
	?>
	
	<form id="pagination_data">
		<input type="hidden" name="booklogsperpages" id="booklogsperpages" value="<?php echo $booklogsperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
</div>
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton",function(){
		var booklogID = $(this).data("id");
		$(".confirmrestorebooklog").data("id", booklogID);
	});

	$(".confirmrestorebooklog").click(function(){
		var booklogID = $(this).data("id");
		var booklogsperpages = $("#booklogsperpages").val();
		var firstresult = $("#firstresult").val();
		$.ajax({
			url:"restorebooklog.php",
			method:"POST",
			data:{booklogID:booklogID,booklogsperpages:booklogsperpages, firstresult:firstresult},
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

});
</script>