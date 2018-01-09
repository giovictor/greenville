<?php
require "dbconnect.php";
if(isset($_POST['editbutton'])) {
	$bookID = $_POST['bookID'];
	$title = ucwords($_POST['title']);
	$author = trim($_POST['author']);
	$publisher = trim($_POST['publisher']);
	$year = $_POST['year'];
	$classificationID = $_POST['classification'];
	$callnumber = $_POST['callnumber'];
	$ISBN = $_POST['ISBN'];
	$pages = $_POST['pages'];
	$price = $_POST['price'];


	if(empty($publisher)) {
		$publisherID = NULL;
		$publishercode = "***";
	} else {
		$publisherSQL = "SELECT * FROM publisher WHERE publisher='$publisher' AND status=1 LIMIT 1";
		$publisherQuery = mysqli_query($dbconnect, $publisherSQL);
		$checkpublisher = mysqli_num_rows($publisherQuery);

		if($checkpublisher==0) {
			$insertpublisherSQL = "INSERT INTO publisher(publisher) VALUES('$publisher')";
			$insertpublisher = mysqli_query($dbconnect, $insertpublisherSQL);
			$publisherID = mysqli_insert_id($dbconnect);
			
			$newpublisherSQL = "SELECT * FROM publisher WHERE publisherID='$publisherID'";
			$newpublisherQuery = mysqli_query($dbconnect, $newpublisherSQL);
			$newpublisherGet = mysqli_fetch_assoc($newpublisherQuery);
			$publishercode = strtoupper(substr($newpublisherGet['publisher'], 0, 3));
		} else {
			$publisher = mysqli_fetch_assoc($publisherQuery);
			$publisherID = $publisher['publisherID'];
			$publishercode = strtoupper(substr($publisher['publisher'], 0, 3));
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
		$updatebookSQL = "UPDATE book SET booktitle='$title', callnumber='$callnumber', classificationID='$classificationID', publisherID=NULL, publishingyear='$year', ISBN='$ISBN',pages='$pages', price='$price' WHERE bookID='$bookID'";	
		$updatebook = mysqli_query($dbconnect, $updatebookSQL);	
	} else if(empty($year)) {
		$updatebookSQL = "UPDATE book SET booktitle='$title', callnumber='$callnumber', classificationID='$classificationID', publisherID='$publisherID', publishingyear=NULL, ISBN='$ISBN',pages='$pages', price='$price' WHERE bookID='$bookID'";
		$updatebook = mysqli_query($dbconnect, $updatebookSQL);	
	} else if(empty($publisher) && empty($year)) {
		$updatebookSQL = "UPDATE book SET booktitle='$title', callnumber='$callnumber', classificationID='$classificationID', publisherID=NULL, publishingyear=NULL, ISBN='$ISBN',pages='$pages', price='$price' WHERE bookID='$bookID'";	
		$updatebook = mysqli_query($dbconnect, $updatebookSQL);	
	} else {
		$updatebookSQL = "UPDATE book SET booktitle='$title', callnumber='$callnumber', classificationID='$classificationID', publisherID='$publisherID', publishingyear='$year', ISBN='$ISBN',pages='$pages', price='$price' WHERE bookID='$bookID'";	
		$updatebook = mysqli_query($dbconnect, $updatebookSQL);	
	}

	if(empty($author)) {
		$authorID = NULL;
		$authorcode = "***";

		$updatebookauthorSQL = "UPDATE bookauthor SET authorID=NULL WHERE accession_no IN (SELECT accession_no FROM book WHERE bookID='$bookID')";
		$updatebookauthor = mysqli_query($dbconnect, $updatebookauthorSQL);
	} else {
		$authorSQL = "SELECT * FROM author WHERE author='$author' AND status=1 LIMIT 1";
		$authorQuery = mysqli_query($dbconnect, $authorSQL);
		$checkauthor = mysqli_num_rows($authorQuery);

		if($checkauthor==0) {
			$insertauthorSQL = "INSERT INTO author(author) VALUES('$author')";
			$insertauthorQuery = mysqli_query($dbconnect, $insertauthorSQL);
			$authorID = mysqli_insert_id($dbconnect);

			$newauthorSQL = "SELECT * FROM author WHERE authorID='$authorID'";
			$newauthorQuery = mysqli_query($dbconnect, $newauthorSQL);
			$newauthorGet = mysqli_fetch_assoc($newauthorQuery);
			$authorcode = strtoupper(substr($newauthorGet['author'], 0, 3));
		} else {
			$author = mysqli_fetch_assoc($authorQuery);
			$authorID = $author['authorID'];
			$authorcode = strtoupper(substr($author['author'], 0, 3));
		}

		$updatebookauthorSQL = "UPDATE bookauthor SET authorID='$authorID' WHERE accession_no IN (SELECT accession_no FROM book WHERE bookID='$bookID')";
		$updatebookauthor = mysqli_query($dbconnect, $updatebookauthorSQL);
	}


	$titlecode = strtoupper(md5(str_replace(' ','',$title)));
	$newbookID = $titlecode.$authorcode.$publishercode.$yearcode;
	$updatebookIDSQL = "UPDATE book SET bookID='$newbookID' WHERE bookID='$bookID'";
	$updatebookID = mysqli_query($dbconnect, $updatebookIDSQL);

	header("Location:index.php?page=updatebook&bookID=$newbookID");
}
?>