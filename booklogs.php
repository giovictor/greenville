<title>Book Logs</title>
<div class="admincontainer">
	<div class="panel panel-success booklogssearchform">
		<div class="panel-heading">
			<h3>Book Logs</h3>
		</div>
		<div class="panel-body">
			<form method="GET" id="booklogssearch">
				<table>
					<tr>
						<td>
							<label>Date Borrowed:</label>
						</td>
						<td>
							<input type="date" size="10" name="dateborrowed" class="form-control" id="dateborrowed">
						</td>
						<td>
							<label>Date Returned:</label>
						</td>
						<td>
							<input type="date" size="10" name="datereturned" class="form-control" id="datereturned">
						</td>
					</tr>
					<tr>
						<td>
							<label>Borrower:</label>
						</td>
						<td>
							<input type="text" size="15" name="borrower" class="form-control" id="borrower" placeholder="Search for a borrower">
						</td>
						<td>
							<label>Book:</label>
						</td>
						<td>
							<input type="text" size="15" name="book" class="form-control" id="book" placeholder="Search for a book">
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="booklogssearchbutton" class="btn btn-success btn-sm button" value="Search">
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
		$totalbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE datereturned IS NOT NULL AND showstatus=1 ORDER BY booklogID DESC";
		$totalbooklogsQuery = mysqli_query($dbconnect, $totalbooklogsSQL);
		$rows = mysqli_num_rows($totalbooklogsQuery);

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
		$booklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE datereturned IS NOT NULL AND showstatus=1 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
		$booklogsQuery = mysqli_query($dbconnect, $booklogsSQL);
		$booklogs = mysqli_fetch_assoc($booklogsQuery);

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
		<form id="printpdf" target="_blank" action="pdfbooklogs.php" method="POST">
			<input type="hidden" name="query" value="<?php echo $totalbooklogsSQL;?>">
			<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
		</form>
	</div>
	<div class="booklogs">
		<table class="table table-hover">
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
				<th> </th>
			</tr>
			<?php
				if($rows==0) {
					echo "<tr><td style='text-align:center;' colspan='8'><h4>No book logs as of now.</h4></td></tr>";
				} else if($rows>=1) {
					do {
			?>
					<tr>
						<td>
							<button type="button" style="color:#1CA843;" class="btn btn-link borrowerinfo" id="<?php echo $booklogs['IDNumber'];?>">
								<b><?php echo $booklogs['IDNumber'];?></b>
							</button>
						</td>
						<td><?php echo $booklogs['lastname'].", ".$booklogs['firstname']." ".$booklogs['mi'];?></td>
						<td><?php echo $booklogs['accession_no'];?></td>
						<td><?php echo $booklogs['booktitle'];?></td>
						<td><?php echo $booklogs['dateborrowed'];?></td>
						<td><?php echo $duedate = $booklogs['duedate'];?></td>
						<td><?php echo $datereturned = $booklogs['datereturned'];?></td>
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
									if($day=="Sun") {
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
						<td><?php echo $booklogs['penalty'];?></td>
						<td>
							<button class="btn btn-warning btn-sm archivebutton" title="Archive this record." data-id="<?php echo $booklogs['booklogID'];?>" data-toggle="modal" data-target="#confirmarchivebooklog">
								<span class="glyphicon glyphicon-briefcase"></span>
							</button>
						</td>
					</tr>
				<?php
					} while($booklogs = mysqli_fetch_assoc($booklogsQuery));
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
				$pagination .= '<a href="?page=bklogs&booklogspage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="?page=bklogs&booklogspage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}

			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="?page=bklogs&booklogspage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="?page=bklogs&booklogspage='.$next.'">Next</a>&nbsp;';	
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
	<script>
	$(document).ready(function(){
		$(document).on("click",".archivebutton",function(){
			var booklogID = $(this).data("id");
			$(".confirmarchiverecord").data("id",booklogID);
		});

		$(".confirmarchiverecord").click(function(){
			var booklogID = $(this).data("id");
			var booklogsperpages = $("#booklogsperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"archivebooklogs.php",
				method:"POST",
				data:{booklogID:booklogID, booklogsperpages:booklogsperpages, firstresult:firstresult},
				success:function(data) {
					$("#confirmarchivebooklog").modal("hide");
					$(".booklogs").html(data);
				}
			});
		});

		$("#booklogssearch").submit(function(e){
			var dateborrowed = $("#dateborrowed").val();
			var datereturned = $("#datereturned").val();
			var borrower = $("#borrower").val();
			var book = $("#book").val();
			if(dateborrowed=="" && datereturned=="" && borrower=="" && book=="") {
				$("#emptysearch").modal("show");
				e.preventDefault();
			} else if(dateborrowed > datereturned) {
				$("#invaliddateborrowed2").modal("show");
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
</div>