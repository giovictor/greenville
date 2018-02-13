<?php
require "dbconnect.php";
if(isset($_POST['booklogID']) && isset($_POST['booklogsperpages']) && isset($_POST['firstresult'])) {
	$booklogID = $_POST['booklogID'];
	$restorebooklogSQL = "UPDATE booklog SET showstatus=1 WHERE booklogID='$booklogID'";
	$restorebooklog = mysqli_query($dbconnect, $restorebooklogSQL);

	$booklogsperpages = $_POST['booklogsperpages'];
	$firstresult = $_POST['firstresult'];

	if(isset($_POST['dateborrowed']) && isset($_POST['datereturned']) && isset($_POST['borrower']) && isset($_POST['book'])) {
		$dateborrowed = $_POST['dateborrowed']; 
		$datereturned = $_POST['datereturned']; 
		$borrower = $_POST['borrower'];
		$book = $_POST['book'];
	}

	if(!empty($dateborrowed) && empty($datereturned) && empty($book) && empty($borrower)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($datereturned) && empty($dateborrowed) && empty($book) && empty($borrower)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE datereturned='$datereturned' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($book) && empty($dateborrowed) && empty($datereturned) && empty($borrower)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE booktitle LIKE '%$book%' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($borrower) && empty($dateborrowed) && empty($datereturned) && empty($book)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($dateborrowed) && !empty($datereturned) && empty($book) && empty($borrower)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND datereturned='$datereturned' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($dateborrowed) && !empty($borrower) && empty($datereturned) && empty($book)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%'  AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($dateborrowed) && !empty($book) && empty($datereturned) &&  empty($borrower)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND booktitle LIKE '%$book%' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($datereturned) && !empty($borrower) && empty($dateborrowed) && empty($book)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE datereturned='$datereturned' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($datereturned) && !empty($book) && empty($dateborrowed) && empty($borrower)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE datereturned='$datereturned' AND booktitle LIKE '%$book%' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($book) && !empty($borrower) && empty($dateborrowed) && empty($datereturned)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($dateborrowed) && !empty($datereturned) && !empty($borrower) && empty($book)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND datereturned='$datereturned' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($dateborrowed) && !empty($datereturned) && !empty($book) && empty($borrower)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND datereturned='$datereturned' AND booktitle LIKE '%$book%' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($dateborrowed) && !empty($book) && !empty($borrower) && empty($datereturned)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND booktitle LIKE '%$book%' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($datereturned) && !empty($book) && !empty($borrower) && empty($dateborrowed)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus,  borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE datereturned='$datereturned' AND booktitle LIKE '%$book%' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NOT NULL  AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else if(!empty($dateborrowed) && !empty($datereturned) && !empty($borrower) && !empty($book)) {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND datereturned='$datereturned' AND booktitle LIKE '%$book%' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	} else {
		$archivedbooklogsSQL = "SELECT booklogID, showstatus, borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, datereturned, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE datereturned IS NOT NULL AND showstatus=0 ORDER BY booklogID DESC LIMIT $firstresult, $booklogsperpages";
	}

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
				echo "<tr><td style='text-align:center;' colspan='8'><h4>There were no archived book logs.</h4></td></tr>";
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
						<!--<button class="btn btn-danger btn-sm permanentdeletebutton"  data-id="<?php echo $archivedbooklogs['booklogID'];?>" data-toggle="modal" data-target="#permanentdeletebooklog">
							<span class="glyphicon glyphicon-trash"> </span>
						</button>-->
					</td>
				</tr>
			<?php
				} while($archivedbooklogs = mysqli_fetch_assoc($archivedbooklogsQuery));
			}
			?>
</table>
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton",function(){
		var booklogID = $(this).data("id");
		$(".confirmrestorebooklog").data("id", booklogID);
	});

	<?php
		if(isset($_POST['dateborrowed']) && isset($_POST['datereturned']) && isset($_POST['borrower']) && isset($_POST['book'])) {
	?>
			$(".confirmrestorebooklog").click(function(){
				var booklogID = $(this).data("id");
				var dateborrowed = $(".dateborrowed").val();
				var datereturned = $(".datereturned").val();
				var borrower = $(".borrower").val();
				var book = $(".book").val();
				var booklogsperpages = $("#booklogsperpages").val();
				var firstresult = $("#firstresult").val();
				$.ajax({
					url:"restorebooklog.php",
					method:"POST",
					data:{booklogID:booklogID, booklogsperpages:booklogsperpages, firstresult:firstresult, dateborrowed:dateborrowed, datereturned:datereturned, borrower:borrower, book:book},
					success:function(data) {
						$("#restorebooklog").modal("hide");
						$(".booklogs").html(data);
					}
				});
	<?php
		} else {
	?>
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
	<?php
		}
	?>
});
</script>
<?php
}
?>