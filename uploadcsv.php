<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
	<script src="jquery-3.2.0.js"> </script>
	<script src="bootstrap/js/bootstrap.min.js"></script>  
	<div class="container">  
		<form action="uploadcsv.php" method="POST" enctype="multipart/form-data" class="form-inline">
			<label>Import CSV File:</label>
			<input type="file" name="csvtoupload" class="form-control">
			<input type="submit" name="csvupload" value="Upload CSV" class="btn btn-primary btn-md">
		</form>
	</div>
	<?php
		session_start();
		if(!isset($_SESSION['librarian'])) {
			header("Location:index.php");
		}
		set_time_limit(0);
		require "dbconnect.php";
		if(isset($_POST['csvupload'])) {
			if($_FILES['csvtoupload']['name']) {
			$csvuploadfile = fopen($_FILES['csvtoupload']['tmp_name'],"r");
			while($data = fgetcsv($csvuploadfile)) {
				$accession_no = mysqli_real_escape_string($dbconnect,$data[0]);
				$booktitle = mysqli_real_escape_string($dbconnect,ucwords($data[1]));
				$author = mysqli_real_escape_string($dbconnect,ucwords($data[2]));
				$publisher = mysqli_real_escape_string($dbconnect,ucwords($data[3]));
				$year = mysqli_real_escape_string($dbconnect,$data[4]);
				$classificationID = mysqli_real_escape_string($dbconnect, $data[5]);
				$acqdate = date("Y-m-d");

				if(empty($publisher)) {
					$publisherID = NULL;
					$publishercode = "***";
				} else {
					$publisherSQL = "SELECT * FROM publisher WHERE publisher='$publisher' AND status=1 LIMIT 1";
					$publisherQuery = mysqli_query($dbconnect, $publisherSQL);
					$checkPublisher = mysqli_num_rows($publisherQuery);
						if($checkPublisher==0) {
							$addPublisherQuery = "INSERT INTO publisher(publisher) VALUES('$publisher')";
							$addPublisher = mysqli_query($dbconnect, $addPublisherQuery);
							$publisherID = mysqli_insert_id($dbconnect);

							$newpublisherSQL = "SELECT * FROM publisher WHERE publisherID='$publisherID'";
							$newpublisherQuery = mysqli_query($dbconnect, $newpublisherSQL);
							$newpublisherGet = mysqli_fetch_assoc($newpublisherQuery);
							$publishercode = strtoupper(substr($newpublisherGet['publisher'], 0, 3));
						} else {
							$publisherGet = mysqli_fetch_assoc($publisherQuery);
							$publisherID = $publisherGet['publisherID'];
							$publishercode = strtoupper(substr($publisherGet['publisher'], 0, 3));
						}
				}


				if(empty($year)) {
					$year = NULL;
					$yearcode = "***";
				} else {
					$year = mysqli_real_escape_string($dbconnect,$data[4]);
					$yearcode = mysqli_real_escape_string($dbconnect,$data[4]);
				}

				if(empty($publisher)) {
					$addBookQuery = "INSERT INTO book(accession_no, booktitle, classificationID, publisherID, publishingyear) VALUES('$accession_no','$booktitle','$classificationID', NULL ,'$year')";
					$addBook = mysqli_query($dbconnect, $addBookQuery);
				} else if(empty($year)) {
					$addBookQuery = "INSERT INTO book(accession_no, booktitle, classificationID, publisherID, publishingyear) VALUES('$accession_no','$booktitle','$classificationID','$publisherID',NULL)";
					$addBook = mysqli_query($dbconnect, $addBookQuery);
				} else if(empty($publisher) && empty($year)){
					$addBookQuery = "INSERT INTO book(accession_no, booktitle, classificationID, publisherID, publishingyear) VALUES('$accession_no','$booktitle','$classificationID',NULL,NULL)";
					$addBook = mysqli_query($dbconnect, $addBookQuery);
				} else {
					$addBookQuery = "INSERT INTO book(accession_no, booktitle, classificationID, publisherID, publishingyear) VALUES('$accession_no','$booktitle','$classificationID','$publisherID','$year')";
					$addBook = mysqli_query($dbconnect, $addBookQuery);
				}

				$newbookSQL = "SELECT * FROM book WHERE accession_no='$accession_no'";
				$newbookQuery = mysqli_query($dbconnect, $newbookSQL);
				$newbook = mysqli_fetch_assoc($newbookQuery);
				$titlecode = strtoupper(md5(str_replace(' ','',$newbook['booktitle'])));

				if(empty($author)) {
					$authorID = NULL;
					$authorcode = "***";

					$bookauthorQuery = "INSERT INTO bookauthor(accession_no, authorID) VALUES('$accession_no', NULL)";
					$bookauthor = mysqli_query($dbconnect, $bookauthorQuery);
				} else {
					$authorSQL = "SELECT * FROM author WHERE author='$author' AND status=1 LIMIT 1";
					$authorQuery = mysqli_query($dbconnect, $authorSQL);
					$checkAuthor = mysqli_num_rows($authorQuery);	
						if($checkAuthor==0) {
							$addAuthorQuery = "INSERT INTO author(author) VALUES('$author')";
							$addAuthor = mysqli_query($dbconnect, $addAuthorQuery);
							$authorID = mysqli_insert_id($dbconnect);

							$newauthorSQL = "SELECT * FROM author WHERE authorID='$authorID'";
							$newauthorQuery = mysqli_query($dbconnect, $newauthorSQL);
							$newauthorGet = mysqli_fetch_assoc($newauthorQuery);
							$authorcode = strtoupper(substr($newauthorGet['author'], 0, 3));
						} else {
							$authorGet = mysqli_fetch_assoc($authorQuery);
							$authorID = $authorGet['authorID'];
							$authorcode = strtoupper(substr($authorGet['author'], 0, 3));
						}

					$bookauthorQuery = "INSERT INTO bookauthor(accession_no, authorID) VALUES('$accession_no','$authorID')";
					$bookauthor = mysqli_query($dbconnect, $bookauthorQuery);
				}

				

				$bookID = $titlecode.$authorcode.$publishercode.$yearcode;
				$updatebookIDSQL = "UPDATE book SET bookID='$bookID' WHERE accession_no='$accession_no'";
				$updatebookID = mysqli_query($dbconnect, $updatebookIDSQL);

				$rand = rand(1000000, 9999999);
				$barcode = $accession_no.$rand;
				$updatebarcodeSQL = "UPDATE book SET barcode='$barcode' WHERE accession_no='$accession_no'";
				$updatebarcode = mysqli_query($dbconnect, $updatebarcodeSQL);
	
			}
			fclose($csvuploadfile);
			}
		}
	?>
</body>
</html>






 