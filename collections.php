<?php
require "dbconnect.php";
include "modals.php";
	if(isset($_GET['classificationID'])) {
		$classification = mysqli_real_escape_string($dbconnect,htmlspecialchars($_GET['classificationID']));
		$sql = "SELECT * FROM classification WHERE classificationID='$classification'";
		$query = mysqli_query($dbconnect, $sql);
		$classification = mysqli_fetch_assoc($query); 
		$classificationID = $classification['classificationID'];
		
		$maxIDSQL = "SELECT MAX(classificationID) AS maxID FROM classification";
		$maxIDQuery = mysqli_query($dbconnect, $maxIDSQL);
		$maxID = mysqli_fetch_assoc($maxIDQuery);
		$max = $maxID['maxID'];

		$minIDSQL = "SELECT MIN(classificationID) AS minID FROM classification";
		$minIDQuery = mysqli_query($dbconnect, $minIDSQL);
		$minID = mysqli_fetch_assoc($minIDQuery);
		$min = $minID['minID'];


		if(!is_numeric($classificationID)) {
			header("Location:index.php");
		} else if($classificationID < $minID['minID']) {
			header("Location:index.php?page=collections&classificationID=$min");
		} else if($classificationID > $maxID['maxID']) {
			header("Location:index.php?page=collections&classificationID=$max");
		}

?>
<title><?php echo $classification['classification'];?></title>
<?php
	$booksperpages = 10;
	$totalbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher, publishingyear, classification.classificationID, classification.classification, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON book.publisherID=publisher.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classificationID'  AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
	$totalbookQuery = mysqli_query($dbconnect, $totalbookSQL);
	$rows = mysqli_num_rows($totalbookQuery);
	$numberofpages = ceil($rows/$booksperpages);

	if(!isset($_GET['bookpage'])) {
		$page = 1;
	} else {
		$page = $_GET['bookpage'];
		if($page < 1) {
			$page = 1;
		} else if($page > $numberofpages) {
			$page = $numberofpages;
		} else if(!is_numeric($page)) {
			$page = 1;
		} else {
			$page = $_GET['bookpage'];
		}
	}



	$firstresult = ($page - 1) * $booksperpages;

	$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher, publishingyear, classification.classificationID, classification.classification, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON book.publisherID=publisher.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classificationID'  AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
	if($rows >= 1) {
		$bookQuery = mysqli_query($dbconnect, $bookSQL);
		$book = mysqli_fetch_assoc($bookQuery);
	}
?>
<div class="table-responsive" id="bookscollection">
	<table class="table table-hover" id="collectionstable">
		<tr>
			<th>Title</th>
			<th>Authors</th>
			<th>Publication Details</th>
			<th>Available</th>
		<?php
			if(isset($_SESSION['borrower']) && !empty($_SESSION['borrower'])) {
		?>
			<th></th>
		<?php
			}
		?>
		</tr>
			<?php
				if($rows==0) {
					echo "<tr><td colspan='6'><center><h4>No books in this classification is available.</h4></center></td></tr>";
				} else if($rows>=1) {
					do {
			?>
				<tr>
					<?php
						$bookID = $book['bookID'];
						$getaccNumSQL = "SELECT MAX(accession_no) AS accession_no FROM book WHERE bookID='$bookID' AND status='On Shelf'";
						$getaccNumQuery = mysqli_query($dbconnect, $getaccNumSQL);
						$getaccNum = mysqli_fetch_assoc($getaccNumQuery);
					?>
					<td>
						<button type="button" style="color:#1C8A43" class="btn btn-link modalShow" id="<?php echo $book['accession_no'];?>">
							<b><?php echo $book['booktitle'];?></b>
						</button>
					</td>
					<td><?php echo $book['authors'];?></td>
					<td><?php echo $book['publisher']." c".$book['publishingyear'];?></td>
					<td>
						<?php
						$checkQuantitySQL = "SELECT COUNT(accession_no) AS quantity FROM book WHERE status='On Shelf' AND bookID='$bookID'";
						$checkQuantityQuery = mysqli_query($dbconnect, $checkQuantitySQL);
						$quantity = mysqli_fetch_assoc($checkQuantityQuery);

						$checkallcopiesSQL = "SELECT COUNT(accession_no) AS noofcopies FROM book WHERE status!='Archived' AND bookID='$bookID'";
						$checkallcopiesQuery = mysqli_query($dbconnect, $checkallcopiesSQL);
						$allcopies = mysqli_fetch_assoc($checkallcopiesQuery);
							echo $quantity['quantity']."/".$allcopies['noofcopies']; 
						?>
					</td>
					<?php
						if(isset($_SESSION['borrower']) && !empty($_SESSION['borrower'])) {
					?>
						<td>
					<?php
							$borrower = $_SESSION['borrower'];
							if($quantity['quantity']==0) {
								echo "Not Available";
							} else {
								
							$checknoofreservedbooksSQL = "SELECT * FROM reservation JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE borrower.firstname='$borrower' AND showstatus=1";
							$checknoofreservedbooksQuery = mysqli_query($dbconnect, $checknoofreservedbooksSQL);
							$noOfReservedBooks = mysqli_num_rows($checknoofreservedbooksQuery);

							$checknoofborrowedbooksSQL = "SELECT * FROM booklog JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE borrower.firstname='$borrower' AND showstatus=1 AND datereturned IS NULL";
							$checknoofborrowedbooksQuery = mysqli_query($dbconnect, $checknoofborrowedbooksSQL);
							$noOfborrowedBooks = mysqli_num_rows($checknoofborrowedbooksQuery);

							$checktitleSQL ="SELECT booktitle, borrower.IDNumber, borrower.firstname, showstatus FROM reservation JOIN book ON book.accession_no=reservation.accession_no JOIN borrower ON borrower.IDNumber=reservation.IDNumber WHERE bookID='$bookID' AND borrower.firstname='$borrower' AND showstatus=1";
							$checktitleQuery = mysqli_query($dbconnect, $checktitleSQL);
							$checknooftitles = mysqli_num_rows($checktitleQuery);

							$settingsSQL = "SELECT * FROM settings";
							$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
							$settings = mysqli_fetch_assoc($settingsQuery);

							$borrowerSQL = "SELECT * FROM borrower WHERE firstname='$borrower'";
							$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
							$borrower = mysqli_fetch_assoc($borrowerQuery);

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

							if($borrower['accountbalance'] > 0) {
					?>
							<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#notallowedborrower2">Reserve</button>
					<?php
							} else if($noOfReservedBooks>=$settings['reservelimit']) {
					?>
							<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#notallowreserve">Reserve</button>
					<?php
							} else if($noOfborrowedBooks>=$settings['borrowlimit']) {
					?>
							<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#notallowborrow">Reserve</button>
					<?php
							} else if($day=="Sun") {
					?>
							<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#weekends3">Reserve</button>
					<?php
							} else if(in_array($date, $holidayarray)) {
					?>
							<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#weekends3">Reserve</button>
					<?php
							} else {
								if($checknooftitles==1) {
					?>
							<button type="button" id="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#onlyonetitle">Reserve</button>
					<?php
								} else {
					?>
							<button type="button" id="<?php echo $getaccNum['accession_no'];?>" class="reservebutton">Reserve</button>
					<?php 
									}
								} 
							}
						}
					?>
					</td>
				</tr>
			<?php
					} while($book = mysqli_fetch_assoc($bookQuery));
			?>
					<input type="hidden" name="classification" class="classification" id="<?php echo $classificationID;?>">
			<?php
				}
			?>
		</table>
	</div>
	<p style="margin-top:20px;">Page: <?php echo $page;?> of <?php echo $numberofpages;?></p>
	<?php
		$pagination = '';
		if($numberofpages > 1) {
			if($page > 1) {
				$previous = $page - 1;
				$pagination .= '<a href="?page=collections&classificationID='.$classificationID.'&bookpage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="?page=collections&classificationID='.$classificationID.'&bookpage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}
			
			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="?page=collections&classificationID='.$classificationID.'&bookpage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="?page=collections&classificationID='.$classificationID.'&bookpage='.$next.'">Next</a>&nbsp;';	
			}
	?>
			<div class="pagination"><?php echo $pagination;?></div>
	<?php
		}
	?>
	
	<input type="hidden" name="booksperpages" id="booksperpages" value="<?php echo $booksperpages;?>">
	<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
<?php
	}
?>

<script>
	$(document).ready(function() {
		$(".reservebutton").click(function() {
			$(this).attr("disabled", true);
			$(this).css("opacity", "0.7");
			var accession_no = $(this).attr("id");
			var classification = $(".classification").attr("id");
			var booksperpages = $("#booksperpages").val();
			var firstresult = $("#firstresult").val();
				$.ajax({
					url:"collectionsreserve.php",
					method:"GET",
					data:{accession_no:accession_no, classification:classification, booksperpages:booksperpages, firstresult:firstresult },
					success:function(data) {
						$("#bookscollection").html(data);
					}
				});
		});

		$(".modalShow").click(function(){
			var accession_no = $(this).attr("id");
				$.ajax({
					url:"bookmodalinfo.php",
					method:"post",
					data:{accession_no:accession_no},
					success:function(data) {
						$("#content").html(data);
						$("#bookInfo").modal("show");
					}
				});
		});

		$("#selectallchk").click(function(){
			$("input:checkbox").not(this).prop("checked", this.checked);
		});

	});
</script>