<?php
require "dbconnect.php";
if(isset($_POST['addbutton'])) {
	$booktitle = ucwords($_POST['title']);
	$author = trim($_POST['author']);
	$publisher = trim($_POST['publisher']);
	$year = $_POST['year'];
	$classificationID = $_POST['classification'];
	$callnumber = $_POST['callnumber'];
	$ISBN = $_POST['ISBN'];
	$pages = $_POST['pages'];
	$price = $_POST['price'];
	$copies = $_POST['copies'];
	
	$getmaxaccessionnoSQL = "SELECT MAX(accession_no) AS max FROM book";
	$getmaxaccessionnoQuery = mysqli_query($dbconnect, $getmaxaccessionnoSQL);
	$getmaxaccessionno = mysqli_fetch_assoc($getmaxaccessionnoQuery);
	$max = $getmaxaccessionno['max'] + 1;

	for($i=1; $i<=$copies; $i++) {
		$accession_no = $max++;

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
			$year = $_POST['year'];
			$yearcode = $_POST['year'];
		}


		if(empty($publisher)) {
			$addBookQuery = "INSERT INTO book(accession_no, booktitle, classificationID, publisherID, publishingyear, callnumber, ISBN, pages, price) VALUES('$accession_no','$booktitle','$classificationID',NULL,'$year','$callnumber','$ISBN','$pages','$price')";
			$addBook = mysqli_query($dbconnect, $addBookQuery);
		} else if(empty($year)) {
			$addBookQuery = "INSERT INTO book(accession_no, booktitle, classificationID, publisherID, publishingyear, callnumber, ISBN, pages, price) VALUES('$accession_no','$booktitle','$classificationID','$publisherID',NULL,'$callnumber','$ISBN','$pages','$price')";
			$addBook = mysqli_query($dbconnect, $addBookQuery);
		} else if(empty($year) && empty($publisher)) {
			$addBookQuery = "INSERT INTO book(accession_no, booktitle, classificationID, publisherID, publishingyear, callnumber, ISBN, pages, price) VALUES('$accession_no','$booktitle','$classificationID',NULL,NULL,'$callnumber','$ISBN','$pages','$price')";
			$addBook = mysqli_query($dbconnect, $addBookQuery);
		} else {
			$addBookQuery = "INSERT INTO book(accession_no, booktitle, classificationID, publisherID, publishingyear, callnumber, ISBN, pages, price) VALUES('$accession_no','$booktitle','$classificationID','$publisherID','$year','$callnumber','$ISBN','$pages','$price')";
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
		
		header("Location:index.php?page=updatebook&bookID=$bookID");
			
	}
}	
?>
