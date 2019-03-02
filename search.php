<!-- <div class="basicsearch">
	<h3>Greenville College Library</h3>
	<h4>Search for library's materials and collections</>
<?php
require "dbconnect.php";

if(isset($_GET['q'])) {
	$keyword = mysqli_real_escape_string($dbconnect, htmlspecialchars($_GET['q']));
	$type = mysqli_real_escape_string($dbconnect, htmlspecialchars($_GET['type']));
?>
		<title><?php echo $keyword;?> - Search Results</title>
		<form method="GET" id="basicsearchform">
			<div class="form-group">
				<input id="basicsearchbox" type="text" name="q" class="form-control" size="50" value="<?php echo $keyword;?>">
			</div>
			<div class="form-group">
			Limit to: <select name="type" class="searchtype form-control">
				<option value="any"
					<?php
						if($_GET['type']=="any") {
							echo "selected='selected'";
						}

					?>
				>Any Field</option>
				<option value="booktitle"
					<?php
						if($_GET['type']=="booktitle") {
							echo "selected='selected'";
						}

					?>
				>Title</option>
				<option value="author"
					<?php
						if($_GET['type']=="author") {
							echo "selected='selected'";
						}

					?>
				>Author</option>
				<option value="publisher"
					<?php
						if($_GET['type']=="publisher") {
							echo "selected='selected'";
						}

					?>
				>Publisher</option>
				<option value="publishingyear"
					<?php
						if($_GET['type']=="publishingyear") {
							echo "selected='selected'";
						}

					?>
				>Year</option>
				<option value="accession_no"
					<?php
						if($_GET['type']=="accession_no") {
							echo "selected='selected'";
						}

					?>
				>Accession Number</option>
			</select>
			</div>
			<button type="submit" id="button" class="btn btn-success btn-sm">Search</button>
		</form>
		</div>
<?php
	if(empty($keyword)) {
		echo "Please type a keyword.";	
	} else {
		$booksperpages = 10;
		if($type=="any") {
			$totalsearchresultsSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE booktitle LIKE '%$keyword%' OR author.author LIKE '%$keyword%' OR publisher.publisher LIKE '%$keyword%' OR publishingyear LIKE '%$keyword%' OR classification LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID";
		} else if ($type=="accession_no") {
			$totalsearchresultsSQL = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no='$keyword' AND book.status!='Archived' GROUP BY bookID";
		} else {
			$totalsearchresultsSQL = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $type LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID";
		}

		$totalsearchresultsQuery = mysqli_query($dbconnect, $totalsearchresultsSQL);
		$rows = mysqli_num_rows($totalsearchresultsQuery);
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
		
		if($type=="any") {
			$searchresultsSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE booktitle LIKE '%$keyword%' OR author.author LIKE '%$keyword%' OR publisher.publisher LIKE '%$keyword%' OR publishingyear LIKE '%$keyword%' OR classification LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID LIMIT $firstresult, $booksperpages";
		} else if ($type=="accession_no") {
			$searchresultsSQL = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no='$keyword' AND book.status!='Archived' GROUP BY bookID LIMIT $firstresult, $booksperpages";
		} else {
			$searchresultsSQL = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $type LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID LIMIT $firstresult, $booksperpages";
		}
			if($rows==0) {
				echo "<center><h4 style='margin-top:20px;'>No results found. Try searching again.</h4</center>";
			} else if($rows>=1) {
				$searchresultsQuery = mysqli_query($dbconnect, $searchresultsSQL);
				$searchresults = mysqli_fetch_assoc($searchresultsQuery);
				echo "<hr style='border-color:#00b359;'>";
				$booktotalsql = "SELECT * FROM book GROUP BY bookID";
				$booktotalquery = mysqli_query($dbconnect, $booktotalsql);
				$booktotal = mysqli_num_rows($booktotalquery);
			?>
			<div class="resultsbar">
				<p>Results: 1 - <?php echo $rows; ?> of <?php echo $booktotal;?></p>
			</div>
			<div class="table-responsive searchresults">
				<table class="table table-hover">
					<tr>
						<th>Title</th>
						<th>Author</th>
						<th>Publication Details</th>
						<th>Classification</th>
						<th>Available</th>
					<?php
						if(isset($_SESSION['borrower']) && !empty($_SESSION['borrower'])) {
					?>	
						<th> </th>
					<?php
						}
					?>
					</tr>
			<?php
				do {
			?>

					<tr>
					<?php 
						$bookID = $searchresults['bookID'];
						$getaccNumSQL = "SELECT MAX(accession_no) AS accession_no FROM book WHERE status='On Shelf' AND bookID='$bookID'";
						$getaccNumQuery = mysqli_query($dbconnect, $getaccNumSQL);
						$getaccNum = mysqli_fetch_assoc($getaccNumQuery);
					?>
						<td>
							<button type="button" class="btn btn-link modalShow" style="color:#1C8A43;" id="<?php echo $searchresults['accession_no']; ?>">
								<b>
								<?php echo $searchresults['booktitle']; ?>
								</b>
							</button>
						</td>
						<td><?php echo $searchresults['authors']; ?></td>
						<td><?php echo $searchresults['publisher']." c".$searchresults['publishingyear']; ?></td>
						<td><?php echo $searchresults['classification']; ?></td>
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
			} while ($searchresults = mysqli_fetch_assoc($searchresultsQuery));
		}				
	?>
			</table>
		</div>
		<input type="hidden" name="keyword" class="keyword" id="<?php echo $keyword;?>">
		<input type="hidden" name="type" class="type" id="<?php echo $type;?>">
		<input type="hidden" name="booksperpages" id="booksperpages" value="<?php echo $booksperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
		<?php
			if($numberofpages > 1) {
				$pagination = '';
		?>
			<p style='margin-top:20px;'>Showing <?php echo $rows;?> results</p>
			<p>Page: <?php echo $page;?> of <?php echo $numberofpages;?></p>
		<?php
			if($page > 1) {
				$previous = $page - 1;
				$pagination .= '<a href="index.php?q='.$keyword.'&type='.$type.'&bookpage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="index.php?q='.$keyword.'&type='.$type.'&bookpage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}

			//CURRENT PAGE
			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="index.php?q='.$keyword.'&type='.$type.'&bookpage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="index.php?q='.$keyword.'&type='.$type.'&bookpage='.$next.'">Next</a>&nbsp;';	
			}
?>
			<div class="pagination"><?php echo $pagination;?></div>
<?php
		}
	}
}
?>
<script>
$(document).ready(function() {
	$(".reservebutton").click(function() {
		$(this).attr("disabled", true);
		$(this).css("opacity", "0.7");
		var accession_no = $(this).attr("id");
		var keyword = $(".keyword").attr("id");
		var type = $(".type").attr("id");
		var booksperpages = $("#booksperpages").val();
		var firstresult = $("#firstresult").val();
			$.ajax({
				url:"reservebook.php",
				method:"GET",
				data:{accession_no:accession_no, keyword:keyword, type:type, booksperpages:booksperpages, firstresult:firstresult},
				success:function(data) {
					$(".searchresults").html(data);
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

	$("#qform").submit(function(e) {
	var searchbox = $("#qbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		}
	});
});

</script>

-->

<?php
	include_once 'config/DatabaseConnection.php';
	include 'models/Book.php';

	$database = new Database();
	$dbconnect = $database->connect();

	if(isset($_GET['q']) && isset($_GET['type'])) {
		$keyword = $_GET['q'];
		$type = $_GET['type'];

		$book = new Book($dbconnect);
		$books = $book->searchBookWithTotalResults($keyword, $type);

		foreach($books as $book) {
			echo $book['booktitle']."<br>";
		}
	}
?>

