<?php
require "dbconnect.php";
include "modals.php";
	if(isset($_GET['classification'])) {
		$classification = $_GET['classification'];
			$sql = "SELECT * FROM classification WHERE classificationID='$classification'";
			$query = mysqli_query($dbconnect, $sql);
			$classification = mysqli_fetch_assoc($query); 
			$classificationID = $classification['classificationID'];
?>
<div class="collectionsearch">
<h2><?php echo strtoupper($classification['classification']);?></h2>
<h4>Search for Greenville College's <?php echo $classification['classification']; ?> collections</h4>
		<form action="?page=collectionssearchresults&classification=<?php echo $classification['classificationID'];?>" method="POST" class="form-inline" id="collectionssearchform">
			<div class="form-group">
			Limit to: <select name="selectsearchtype" class="form-control selectsearchtype">
				<option value="Title"
					<?php
						if($_POST['selectsearchtype']=="Title") {
							echo "selected='selected'";
						}
					?>
				>Title</option>
				<option value="Author"
					<?php
						if($_POST['selectsearchtype']=="Author") {
							echo "selected='selected'";
						}
					?>
				>Author</option>
				<option value="Publisher"
					<?php
						if($_POST['selectsearchtype']=="Publisher") {
							echo "selected='selected'";
						}
					?>
				>Publisher</option>
				<option value="Year"
					<?php
						if($_POST['selectsearchtype']=="Year") {
							echo "selected='selected'";
						}
					?>
				>Year</option>
				<option value="Call Number"
					<?php
						if($_POST['selectsearchtype']=="Call Number") {
							echo "selected='selected'";
						}
					?>
				>Call Number</option>
				<option value="Accession Number"
					<?php
						if($_POST['selectsearchtype']=="Accession Number") {
							echo "selected='selected'";
						}
					?>
				>Accession Number</option>
			</select>
			</div>
			<div class="form-group">
				<input class="form-control collectionssearchbox" type="text" name="collectionssearch">
				<input id="button" class="btn btn-success btn-sm" type="submit" name="searchbutton" value="Search">
			</div>
		</form>
</div>
<?php
}
?>
<div class="table-responsive" id="bookscollection">
<table class="table table-hover" id="collectionstable">
	<tr>
	<?php 
		if(isset($_SESSION['borrower'])) {
	?>
		<th colspan='6'>
			<h3><center><?php echo strtoupper($classification['classification']);?></center></h3>
		</th>
	<?php
		} else {
	?>
		<th colspan='4'>
			<h3><center><?php echo strtoupper($classification['classification']);?></center></h3>
		</th>
	<?php
		}
	?>
	</tr>
	<tr>
		<th>Title</th>
		<th>Authors</th>
		<th>Publication Details</th>
		<th>Available</th>
		<?php
			if(isset($_SESSION['borrower'])) {
		?>
			<th> </th>
		<?php
			}
		?>
	</tr>
<?php
	if(isset($_POST['searchbutton'])) {
		$keyword = $_POST['collectionssearch'];
		$searchtype = $_POST['selectsearchtype'];
			if($searchtype=="Title") {
				$searchresultsSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher, publishingyear, classification.classificationID, classification.classification, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON book.publisherID=publisher.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classificationID' AND booktitle LIKE '%$keyword%' AND book.status!='Archived' GROUP BY booktitle";
			} else if($searchtype=="Author") {
				$searchresultsSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher, publishingyear, classification.classificationID, classification.classification, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON book.publisherID=publisher.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classificationID' AND author.author LIKE '%$keyword%' AND book.status!='Archived' GROUP BY booktitle";
			} else if($searchtype=="Publisher") {
				$searchresultsSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher, publishingyear, classification.classificationID, classification.classification, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON book.publisherID=publisher.publisherID JOIN classification ON classification.classificationID=book.classificationID JOIN publisher ON publisher.publisherID=book.publisherID WHERE classification.classificationID='$classificationID' AND publisher.publisher LIKE '%$keyword%' AND book.status!='Archived' GROUP BY booktitle";
			} else if($searchtype=="Year") {
				$searchresultsSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher, publishingyear, classification.classificationID, classification.classification, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON book.publisherID=publisher.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classificationID' AND publishingyear LIKE '%$keyword%' AND book.status!='Archived' GROUP BY booktitle";
			} else if($searchtype=="Call Number") {
				$searchresultsSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher, publishingyear, classification.classificationID, classification.classification, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON book.publisherID=publisher.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classificationID' AND callnumber LIKE '%$keyword%' AND book.status!='Archived' GROUP BY booktitle";
			} else if($searchtype=="Accession Number") {
				$searchresultsSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher, publishingyear, classification.classificationID, classification.classification, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON book.publisherID=publisher.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classificationID' AND book.accession_no LIKE '%$keyword%' AND book.status!='Archived' GROUP BY booktitle";
			}

			$searchresultsQuery = mysqli_query($dbconnect, $searchresultsSQL);
			$searchresults = mysqli_fetch_assoc($searchresultsQuery);
			$checkDB = mysqli_num_rows($searchresultsQuery);
			if($checkDB==0) {
				echo "<tr><td colspan='6'>No results found.</td></tr>";
			} else {
				do {?>
				<tr>
					<?php
						$bookID = $searchresults['bookID'];
						$getaccNumSQL = "SELECT MAX(accession_no) AS accession_no FROM book WHERE bookID='$bookID' AND status='On Shelf'";
						$getaccNumQuery = mysqli_query($dbconnect, $getaccNumSQL);
						$getaccNum = mysqli_fetch_assoc($getaccNumQuery);
					?>
					<td>
						<button type="button" style="color:#1C8A43" class="btn btn-link modalShow" id="<?php echo $searchresults['accession_no'];?>">
							<b><?php echo $searchresults['booktitle'];?></b>
						</button>
					</td>
					<td><?php echo $searchresults['authors'];?></td>
					<td><?php echo $searchresults['publisher']." c".$searchresults['publishingyear'];?></td>
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
							} else if($day=="Sat" || $day=="Sun") {
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
			} while($searchresults = mysqli_fetch_assoc($searchresultsQuery));
		?>
		<input type="hidden" name="keyword" class="keyword" id="<?php echo $keyword;?>">
		<input type="hidden" name="searchtype" class="searchtype" id="<?php echo $searchtype;?>">
		<input type="hidden" name="classification" class="classification" id="<?php echo $classificationID;?>">
<?php
	}
}
?>

</table>
</div>
<title><?php echo $keyword;?> - Search Results</title>

<script>
$(document).ready(function() {
	$(".reservebutton").click(function() {
		$(this).attr("disabled", true);
		$(this).css("opacity", "0.7");
		var accession_no = $(this).attr("id");
		var keyword = $(".keyword").attr("id");
		var searchtype = $(".searchtype").attr("id");
		var classification = $(".classification").attr("id");
			$.ajax({
				url:"collectionssearchresultreserve.php",
				method:"GET",
				data:{accession_no:accession_no, keyword:keyword, searchtype:searchtype, classification:classification},
				success:function(data) {
					$("#bookscollection").html(data);
				}
			});
	});

	$(".modalShow").click(function() {
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
	$("#collectionssearchform").submit(function(e){
		var searchbox = $(".collectionssearchbox").val();
			if(searchbox=="") {
				$("#emptysearch").modal("show");
				e.preventDefault();
			}
	});
});
</script>