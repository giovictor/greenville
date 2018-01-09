<?php
require "dbconnect.php";
if(!isset($_SESSION['borrower'])) {
	header("Location:index.php");
} 
	$borrower=$_SESSION['borrower'];
	$booklogsSQL = "SELECT borrower.IDNumber, borrower.firstname, book.accession_no, callnumber, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, dateborrowed, duedate, datereturned, penalty FROM booklog  JOIN borrower ON borrower.IDNumber=booklog.IDNumber JOIN book ON book.accession_no=booklog.accession_no LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID WHERE firstname='$borrower' GROUP BY booktitle ORDER BY dateborrowed DESC";
	$booklogsQuery = mysqli_query($dbconnect, $booklogsSQL);
	$booklogsDisplay = mysqli_fetch_assoc($booklogsQuery);
	$checkDB = mysqli_num_rows($booklogsQuery);
?>
<title>Book Logs - <?php echo $borrower;?></title>
<div class="panel panel-success borrowerlogssearchform" id="borrowerbooklogspanel">
	<div class="panel-heading">
		<h3>Book Logs</h3>
	</div>
	<div class="panel-body">
		<form method="GET" id="borrowerbooklogssearch">
			<table>
				<tr>
					<td>
						<label>Date Borrowed:</label>
					</td>
					<td>
						<input type="date" size="10" name="dateborrowed" class="form-control" id="dateborrowed">
					</td>
					<td>
						<label>Due Date:</label>
					</td>
					<td>
						<input type="date" size="15" name="duedate" class="form-control" id="duedate">
					</td>
					<td>
						<label>Date Returned:</label>
					</td>
					<td>
						<input type="date" size="15" name="datereturned" class="form-control" id="datereturned">
					</td>
				</tr>
				<tr>
					<td>
						<label>Book:</label>
					</td>
					<td>
						<input type="text" size="10" name="book" class="form-control" id="book" placeholder="Search for a book">
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="borrowerbooklogssearchbutton" class="btn btn-success btn-sm button" value="Search">
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
<div class="booklogs">
	<table class='table table-hover'>
			<tr>
				<th>Title</th>
				<th>Date Borrowed</th>
				<th>Due Date</th>
				<th>Date Returned</th>
				<th>Penalty</th>
			</tr>
			<?php
				if($checkDB==0) {
					echo "<tr><td colspan='7'><center><h4>You have no book logs in the library.</h4></center></td></tr>";
				} else {	
			?>
	<?php
		do {?>
		 	<tr>
		 		<td><?php echo $booklogsDisplay['booktitle'];?></td>
		 		<td><?php echo $booklogsDisplay['dateborrowed'];?></td>
		 		<td><?php echo $booklogsDisplay['duedate'];?></td>
		 		<td><?php echo $booklogsDisplay['datereturned'];?></td>
		 		<td><?php echo $booklogsDisplay['penalty'];?></td>
		 	</tr>
		 <?php
		 } while($booklogsDisplay = mysqli_fetch_assoc($booklogsQuery));

		}

?>
	</table>
</div>
<script>
$(document).ready(function(){
	$("#borrowerbooklogssearch").submit(function(e){
		var book = $("#book").val();
		var dateborrowed = $("#dateborrowed").val();
		var duedate = $("#duedate").val();
		var datereturned = $("#datereturned").val();

		if(book=="" && dateborrowed=="" && duedate=="" && datereturned=="") {
			e.preventDefault();
			$("#emptysearch").modal("show");
		} else if(dateborrowed > duedate) {
			e.preventDefault();
			$("#invaliddateborrowed1").modal("show");
		} else if(duedate > datereturned) {
			e.preventDefault();
			$("#invalidduedate").modal("show");
		} else if(dateborrowed > datereturned) {
			e.preventDefault();
			$("#invaliddateborrowed2").modal("show");
		}
	});
});
</script>
