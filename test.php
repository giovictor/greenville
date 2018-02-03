<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Ubuntu" rel="stylesheet">
	<style>
		#table1 , #table2 {
			float:left;
			width:50%;
		}
	</style>
</head>
<body>
	<?php
		require "dbconnect.php";
		$bookSQL = "SELECT * FROM book WHERE accession_no IN (SELECT accession_no FROM duplicate_tables) ORDER BY accession_no";
		$bookQuery = mysqli_query($dbconnect, $bookSQL);
		$book = mysqli_fetch_assoc($bookQuery);

		$duplicateSQL = "SELECT * FROM duplicate_tables WHERE accession_no IN (SELECT accession_no FROM book) ORDER BY accession_no";
		$duplicateQuery = mysqli_query($dbconnect, $duplicateSQL);
		$duplicate = mysqli_fetch_assoc($duplicateQuery);
		$booknumber = 1;
		$duplicatenumber = 1;
	?>
	<table id="table1">
		<tr>
			<th></th>
			<th>Accession Number</th>
			<th>Book Title</th>
		</tr>
		<?php
			do {
		?>
				<tr>
					<td><?php echo $booknumber++;?>
					<td><?php echo $book['accession_no'];?></td>
					<td><?php echo $book['booktitle'];?></td>
				</tr>
		<?php
			} while($book = mysqli_fetch_assoc($bookQuery));
		?>
	</table>

	<table id="table2">
		<tr>
			<th></th>
			<th>Accession Number</th>
			<th>Duplicate Title</th>
		</tr>
		<?php
			do {
		?>
				<tr>
					<td><?php echo $duplicatenumber++;?>
					<td><?php echo $duplicate['accession_no'];?></td>
					<td><?php echo $duplicate['booktitle'];?></td>
				</tr>
		<?php
			} while($duplicate = mysqli_fetch_assoc($duplicateQuery));
		?>
	</table>
	<script src="jquery-3.2.0.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>  
</body>
</html>