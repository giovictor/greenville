<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Ubuntu" rel="stylesheet">
	
</head>
<body>
	<?php
		require "dbconnect.php";
		$bookSQL = "SELECT * FROM book";
		$bookQuery = mysqli_query($dbconnect, $bookSQL);
		$book = mysqli_fetch_assoc($bookQuery);
		$rows = mysqli_num_rows($bookQuery);
	?>
	<table class="table table-striped">
		<tr>
			<th>Accession Number</th>
			<th>Book Title</th>
		</tr>
		<?php
			if($rows % 5==0) {}
			do {
		?>
					<tr>
						<td><?php echo $book['accession_no'];?></td>
						<td><?php echo $book['booktitle'];?></td>
					</tr>
		<?php
			} while($book = mysqli_fetch_assoc($bookQuery));
		?>
	</table>
	<script src="jquery-3.2.0.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>  
</body>
</html>