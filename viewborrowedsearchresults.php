<div class="admincontainer">	
	<div class="panel panel-success borrowedbookssearchform">
		<div class="panel-heading">
			<h3>Borrowed Books</h3>
		</div>
		<div class="panel-body">
			<form method="GET" id="borrowedbookssearchform">
				<table>	
					<tr>
						<td>
							<label>Date Borrowed: </label>
						</td>
						<td>
							<input type="date"  size="15" name="dateborrowed" id="dateborrowed" class="form-control">
						</td>
						<td>
							<label>Due Date: </label>
						</td>
						<td>
							<input type="date" size="15" name="duedate" id="duedate" class="form-control">
						</td>
					</tr>
					<tr>
						<td>
							<label>Borrower: </label>
						</td>
						<td>
							<input type="text"  size="15" name="borrower" id="borrower" class="form-control" placeholder="Search for a borrower">
						</td>
						<td>
							<label>Book: </label>
						</td>
						<td>
							<input type="text" size="15" name="book" class="form-control" id="book" placeholder="Search for a book">
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="borrowedbookssearchbutton" value="Search" class="btn btn-success btn-sm button">
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<?php
	require "dbconnect.php";

	if(isset($_GET['borrowedbookssearchbutton'])) {
		$dateborrowed = $_GET['dateborrowed']; 
		$duedate = $_GET['duedate']; 
		$borrowerget = $_GET['borrower'];
		$book = $_GET['book'];
		if(!empty($dateborrowed) && empty($duedate) && empty($book) && empty($borrower)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($duedate) && empty($dateborrowed) && empty($book) && empty($borrower)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE duedate='$duedate' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($book) && empty($dateborrowed) && empty($duedate) && empty($borrower)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE booktitle LIKE '%$book%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($borrower) && empty($dateborrowed) && empty($duedate) && empty($book)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($dateborrowed) && !empty($duedate) && empty($book) && empty($borrower)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND duedate='$duedate' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($dateborrowed) && !empty($borrower) && empty($duedate) && empty($book)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($dateborrowed) && !empty($book) && empty($duedate) &&  empty($borrower)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND booktitle LIKE '%$book%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($duedate) && !empty($borrower) && empty($dateborrowed) && empty($book)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE duedate='$duedate' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($duedate) && !empty($book) && empty($dateborrowed) && empty($borrower)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE duedate='$duedate' AND booktitle LIKE '%$book%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($book) && !empty($borrower) && empty($dateborrowed) && empty($duedate)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($dateborrowed) && !empty($duedate) && !empty($borrower) && empty($book)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND duedate='$duedate' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($dateborrowed) && !empty($duedate) && !empty($book) && empty($borrower)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND duedate='$duedate' AND booktitle LIKE '%$book%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($dateborrowed) && !empty($book) && !empty($borrower) && empty($duedate)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND booktitle LIKE '%$book%' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($duedate) && !empty($book) && !empty($borrower) && empty($dateborrowed)) {
			$totaltotalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE duedate='$duedate' AND booktitle LIKE '%$book%' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC";
		} else if(!empty($dateborrowed) && !empty($duedate) && !empty($borrower) && !empty($book)) {
			$totalborrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND duedate='$duedate' AND booktitle LIKE '%$book%' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC";
		}
		 
		$totalborrowedbooksQuery = mysqli_query($dbconnect, $totalborrowedbooksSQL);
		$rows = mysqli_num_rows($totalborrowedbooksQuery);

		$borrowedperpages = 10;
		$numberofpages = ceil($rows/$borrowedperpages);

		if(!isset($_GET['borrowedpage'])) {
			$page = 1;
		} else {
			$page = $_GET['borrowedpage'];
		}

		$firstresult = ($page - 1) * $borrowedperpages;
		
		if(!empty($dateborrowed) && empty($duedate) && empty($book) && empty($borrower)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($duedate) && empty($dateborrowed) && empty($book) && empty($borrower)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE duedate='$duedate' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($book) && empty($dateborrowed) && empty($duedate) && empty($borrower)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE booktitle LIKE '%$book%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($borrower) && empty($dateborrowed) && empty($duedate) && empty($book)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($dateborrowed) && !empty($duedate) && empty($book) && empty($borrower)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND duedate='$duedate' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($dateborrowed) && !empty($borrower) && empty($duedate) && empty($book)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($dateborrowed) && !empty($book) && empty($duedate) &&  empty($borrower)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND booktitle LIKE '%$book%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($duedate) && !empty($borrower) && empty($dateborrowed) && empty($book)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE duedate='$duedate' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($duedate) && !empty($book) && empty($dateborrowed) && empty($borrower)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE duedate='$duedate' AND booktitle LIKE '%$book%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($book) && !empty($borrower) && empty($dateborrowed) && empty($duedate)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND booktitle LIKE '%$book%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($dateborrowed) && !empty($duedate) && !empty($borrower) && empty($book)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND duedate='$duedate' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($dateborrowed) && !empty($duedate) && !empty($book) && empty($borrower)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND duedate='$duedate' AND booktitle LIKE '%$book%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($dateborrowed) && !empty($book) && !empty($borrower) && empty($duedate)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND booktitle LIKE '%$book%' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($duedate) && !empty($book) && !empty($borrower) && empty($dateborrowed)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE duedate='$duedate' AND booktitle LIKE '%$book%' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		} else if(!empty($dateborrowed) && !empty($duedate) && !empty($borrower) && !empty($book)) {
			$borrowedbooksSQL = "SELECT borrower.IDNumber, lastname, firstname,mi, book.accession_no, booktitle, dateborrowed, duedate, penalty FROM booklog JOIN book ON book.accession_no=booklog.accession_no JOIN borrower ON borrower.IDNumber=booklog.IDNumber WHERE dateborrowed='$dateborrowed' AND duedate='$duedate' AND booktitle LIKE '%$book%' AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND datereturned IS NULL ORDER BY booklogID DESC LIMIT $firstresult, $borrowedperpages";
		}

		$borrowedbooksQuery = mysqli_query($dbconnect, $borrowedbooksSQL);
		$borrowedbooks = mysqli_fetch_assoc($borrowedbooksQuery);

		$currentdate = date("Y-m-d");

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
	<div class="borrowedbooks">
		<table class="table table-hover">
			<tr>
				<th>ID Number</th>
				<th>Borrower</th>
				<th width="10%">Acc. No.</th>
				<th>Title</th>
				<th>Date Borrowed</th>
				<th>Due Date</th>
				<th>Days Overdue</th>
				<th>Pending Penalty</th>
			</tr>
			<?php
			if($rows==0) {
				echo "<tr><td colspan='8' style='text-align:center;'><h4>No results found.</h4></td></tr>";
			} else if($rows>=1) {
				do {
			?>
				<tr>
					<td>
						<button type="button" style="color:#1CA843;" class="btn btn-link borrowerinfo" id="<?php echo $borrowedbooks['IDNumber'];?>">
							<b><?php echo $borrowedbooks['IDNumber'];?></b>
						</button>
					</td>
					<td><?php echo $borrower = $borrowedbooks['lastname'].", ".$borrowedbooks['firstname']." ".$borrowedbooks['mi'];;?></td>
					<td><?php echo $borrowedbooks['accession_no'];?></td>
					<td><?php echo $borrowedbooks['booktitle'];?></td>
					<td><?php echo $borrowedbooks['dateborrowed'];?></td>
					<td><?php echo $duedate = $borrowedbooks['duedate'];?></td>
					<td>	
						<?php
							if(strtotime($currentdate) > strtotime($duedate)) {
								$duedatetime = new Datetime($duedate);
								$currentdatetime = new Datetime($currentdate);
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
						<td>	
						<?php
							if(strtotime($currentdate) > strtotime($duedate)) {
								$duedatetime = new Datetime($duedate);
								$currentdatetime = new Datetime($currentdate);
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
								echo $daysoverdue * $settings['penalty'].".00";
							} else {
								echo "0.00";
							}
						?>
					</td>
				</tr>
			<?php
				} while($borrowedbooks = mysqli_fetch_assoc($borrowedbooksQuery));
			}
			?>
		</table>
	</div>
	<?php
		if($rows>=1) {
	?>
		<form method="POST" action="pdfborrowedbooks.php" target="_blank" class="form-inline">
			<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
			<input type="hidden" name="query" value="<?php echo $borrowedbooksSQL;?>">
		</form>
	<?php
		}

		if($numberofpages > 1) {
	?>
			<ul class="pagination">
				<?php
					for($i=1;$i<=$numberofpages;$i++) {
				?>
						<li><a href="index.php?dateborrowed=<?php echo $dateborrowed;?>&duedate=<?php echo $duedate;?>&borrower=<?php echo $borrowerget;?>&book=<?php echo $book;?>&borrowedbookssearchbutton=Search&borrowedpage=<?php echo $i;?>"><?php echo $i;?></a></li>
				<?php
					}
				?>
			</ul>
	<?php
		}
	?>
	<script>
	$(document).ready(function(){
		$("#borrowedbookssearchform").submit(function(e){
			var dateborrowed = $("#dateborrowed").val();
			var duedate = $("#duedate").val();
			var borrower = $("#borrower").val();
			var book = $("#book").val();
			if(dateborrowed=="" && duedate=="" && borrower=="" && book=="") {
				$("#emptysearch").modal("show");
				e.preventDefault();
			} else if(dateborrowed > duedate) {
				$("#invaliddateborrowed1").modal("show");
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