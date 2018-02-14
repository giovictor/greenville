<?php
require "dbconnect.php";
if(isset($_POST['booksearchtype']) && isset($_POST['booksearch'])) {
	$booksearchtype = $_POST['booksearchtype'];
	$booksearch = $_POST['booksearch'];

	if($booksearchtype=="booktitle") {
		$bookSQL = "SELECT * FROM book WHERE booktitle LIKE '%$booksearch%' LIMIT 0,10";
	} else if($booksearchtype=="accession_no") {
		$bookSQL = "SELECT * FROM book WHERE accession_no LIKE '%$booksearch%' LIMIT 0,10";
	} else if($booksearchtype=="barcode") {
		$bookSQL = "SELECT * FROM book WHERE barcode LIKE '%$booksearch%' LIMIT 0,10";
	} else if($booksearchtype=="callnumber") {
		$bookSQL = "SELECT * FROM book WHERE callnumber LIKE '%$booksearch%' LIMIT 0,10";
	} 

	$bookQuery = mysqli_query($dbconnect, $bookSQL);
	$book = mysqli_fetch_assoc($bookQuery);
	$rows = mysqli_num_rows($bookQuery);

	if($rows==0) {
		echo "Invalid";
	} else {
?>
<table class="table table-hover">
	<tr>	
		<th>Accession Number</th>
		<th>Barcode</th>
		<th>Title</th>
		<th>Status</th>
	</tr>
	<?php
			do {
	?>
			<tr>
				<td><?php echo $book['accession_no'];?></td>
				<td><?php echo $book['barcode'];?></td>
				<td><?php echo $book['booktitle'];?></td>
				<td><?php echo $book['status'];?></td>
			</tr>
	<?php
			} while($book = mysqli_fetch_assoc($bookQuery));
	?>
</table>
<?php
	}
}
?>