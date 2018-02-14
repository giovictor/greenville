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
		$sql = "SELECT book.accession_no, bookID, booktitle, author.author, publisher.publisher, publishingyear FROM book LEFT JOIN bookauthor ON bookauthor.accession_no=book.accession_no LEFT JOIN author ON bookauthor.authorID=author.authorID LEFT JOIN publisher ON book.publisherID=publisher.publisherID WHERE book.accession_no IN  (3855, 4798, 6420, 7297, 7339, 7340, 7341, 7342, 7343, 7345, 7346)";
		$query = mysqli_query($dbconnect, $sql);
		$book = mysqli_fetch_assoc($query);

		do {
			$accession_no = $book['accession_no'];
			$titlecode = strtoupper(md5(str_replace(' ','',$book['booktitle'])));
			$publishercode = strtoupper(str_replace("'","\"",substr($book['publisher'], 0, 3)));
			$authorcode = strtoupper(str_replace("'","\"",substr($book['author'], 0, 3)));
			$yearcode = $book['publishingyear'];
			$bookID = $titlecode.$authorcode.$publishercode.$yearcode;

			$updatebookID = "UPDATE book SET bookID='$bookID' WHERE accession_no='$accession_no'";
			$update = mysqli_query($dbconnect, $updatebookID);
		  
		} while($book = mysqli_fetch_assoc($query));
	?>
	<script src="jquery-3.2.0.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>  
</body>
</html>