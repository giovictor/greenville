<?php
session_start();
require "dbconnect.php";
if(isset($_POST['accession_no'])) {
	$accession_no = $_POST['accession_no'];
	$borrowerID = $_SESSION['borrowerID'];
	$reservedate = date("Y-m-d");
	$addDays = strtotime($reservedate."+ 2 days");
	$expdate = date("Y-m-d", $addDays);

	$reservebookSQL = "INSERT INTO reservation(IDNumber,accession_no,reservationdate, expdate) VALUES('$borrowerID','$accession_no','$reservedate','$expdate')";
	$reservebook = mysqli_query($dbconnect, $reservebookSQL);

	$updatebookstatusSQL = "UPDATE book SET status='Reserved' WHERE accession_no='$accession_no'";
	$updatebookstatus = mysqli_query($dbconnect, $updatebookstatusSQL);

	$bookSQL = "SELECT bookID, book.accession_no, booktitle, callnumber, GROUP_CONCAT(DISTINCT author SEPARATOR',') AS authors, publisher, publishingyear, classification FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON bookauthor.authorID=author.authorID LEFT JOIN publisher ON book.publisherID=publisher.publisherID JOIN classification ON book.classificationID=classification.classificationID WHERE book.status!='Archived' GROUP BY bookID ORDER BY publishingyear DESC LIMIT 10";
	$bookQuery = mysqli_query($dbconnect, $bookSQL);
	$book = mysqli_fetch_assoc($bookQuery);
	$rank = 1;
?>
<table id="newcollectionstable" class="table table-hover">
	<tr>
		<th> </th>
		<th>Book Title</th>
		<th>Author</th>
		<th>Publisher</th>
		<th>Year</th>
		<th>Classification</th>
		<th>Available</th>
		<?php
			if(isset($_SESSION['borrower'])) {
		?>
			<th></th>
		<?php
			}
		?>
	</tr>
<?php
	do {
		$bookID = $book['bookID'];
		$noofcopiesSQL = "SELECT COUNT(*) AS noofcopies FROM book WHERE bookID='$bookID'";
		$noofcopiesQuery = mysqli_query($dbconnect, $noofcopiesSQL);
		$noofcopies = mysqli_fetch_assoc($noofcopiesQuery);
?>
	<tr>
		<td><p class="rank"><?php echo $rank++;?></p></td>
		<td>
			<button class="btn btn-link btn-md bookinfo" id="<?php echo $book['accession_no'];?>" style="color:#1CA843;"><b><?php echo $book['booktitle'];?></b></button>
		</td>
		<td><?php echo $book['authors'];?></td>
		<td><?php echo $book['publisher'];?></td>
		<td><?php echo $book['publishingyear'];?></td>
		<td><?php echo $book['classification'];?></td>
		<td>
			<?php
				$availablebookSQL = "SELECT * FROM book WHERE status='On Shelf' AND bookID='$bookID'";
				$availablebookQuery = mysqli_query($dbconnect, $availablebookSQL);
				$availablebook = mysqli_num_rows($availablebookQuery);
				echo $availablebook."/".$noofcopies['noofcopies'];
			?>
		</td>
		<?php
			if(isset($_SESSION['borrower'])) {
		?>
		<td>
			<?php
				if($availablebook==0) {
					echo "Not Available";
				} else {
					$borrowerID = $_SESSION['borrowerID'];
					$checkbalanceSQL = "SELECT * FROM borrower WHERE borrower.IDNumber='$borrowerID'";
					$checkbalanceQuery = mysqli_query($dbconnect, $checkbalanceSQL);
					$checkbalance = mysqli_fetch_assoc($checkbalanceQuery);

					$noofreservedbookSQL = "SELECT COUNT(*) AS noofreservedbooks FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE borrower.IDNumber='$borrowerID' AND showstatus=1";
					$noofreservedbookQuery = mysqli_query($dbconnect, $noofreservedbookSQL);
					$noofreservedbooks = mysqli_fetch_assoc($noofreservedbookQuery);

					$noofborrowedbookSQL = "SELECT COUNT(*) AS noofborrowedbooks FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE borrower.IDNumber='$borrowerID' AND showstatus=1";
					$noofborrowedbookQuery = mysqli_query($dbconnect, $noofborrowedbookSQL);
					$noofborrowedbooks = mysqli_fetch_assoc($noofborrowedbookQuery);

					$checknooftitlesSQL = "SELECT COUNT(*) AS nooftitles FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE borrower.IDNumber='$borrowerID' AND bookID='$bookID' AND showstatus=1";
					$checknooftitlesQuery = mysqli_query($dbconnect, $checknooftitlesSQL);
					$checknooftitles = mysqli_fetch_assoc($checknooftitlesQuery);

					$settingsSQL = "SELECT * FROM settings";
					$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
					$settings = mysqli_fetch_assoc($settingsQuery);

					$getaccnumSQL = "SELECT MAX(accession_no) AS accession_no FROM book WHERE status='On Shelf' AND bookID='$bookID'";
					$getaccnumQuery = mysqli_query($dbconnect, $getaccnumSQL);
					$getaccnum = mysqli_fetch_assoc($getaccnumQuery);

					$holidaySQL = "SELECT * FROM holiday";
					$holidayQuery = mysqli_query($dbconnect, $holidaySQL);
					$holiday = mysqli_fetch_assoc($holidayQuery);
					$holidayrows = mysqli_num_rows($holidayQuery);
					$holidayarray = array();
					if($holidayrows > 0) {
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
					} 

					$day = date("D");
					$date = date("Y-m-d");

					if($checkbalance['accountbalance'] > 0) {
				?>
						<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#notallowedborrower2">Reserve</button>
				<?php
					} else if($noofreservedbooks['noofreservedbooks']>=$settings['reservelimit']) {
				?>	
						<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#notallowreserve">Reserve</button>
				<?php
					} else if($noofborrowedbooks['noofborrowedbooks']>=$settings['borrowlimit']) {
				?>
						<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#notallowborrow">Reserve</button>
				<?php
					} else if($day=="Sat" || $day=="Sun") {
				?>
						<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#weekends3">Reserve</button>
				<?php
					} else if(in_array($date, $holidayarray)) {
				?>
						<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#weekends3">Reserve</button>
				<?php
					} else if($checknooftitles['nooftitles']==1) {
				?>
						<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#onlyonetitle">Reserve</button>
				<?php
					} else {
				?>
						<button type="button" id="<?php echo $getaccnum['accession_no'];?>" class="reservebutton">Reserve</button>
				<?php
					}
				}
			?>
		</td>
		<?php
			}
		?>
	</tr>
<?php
	} while($book = mysqli_fetch_assoc($bookQuery));
?>
</table>
<script>
	$(document).ready(function(){
		$(".bookinfo").click(function(){
			var accession_no = $(this).attr("id");
			$.ajax({
				url:"bookmodalinfo.php",
				method:"POST",
				data:{accession_no:accession_no},
				success:function(data) {
					$("#content").html(data);
					$("#bookInfo").modal("show");
				}
			});
		});

		$(".reservebutton").click(function(){
			$(this).attr("disabled", true);
			$(this).css("opacity", "0.7");
			var accession_no = $(this).attr("id");
			$.ajax({
				url:"newcollectionsreserve.php",
				method:"POST",
				data:{accession_no:accession_no},
				success:function(data) {
					$(".newcollectionstable").html(data);
				}
			});
		});
	});
</script>
<?php
}
?>