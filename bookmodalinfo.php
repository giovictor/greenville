<?php
require "dbconnect.php";	
if(isset($_POST['accession_no'])) {		
	$accession_no = $_POST['accession_no'];
	$sql = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, pages, book.status AS quantity FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no='$accession_no' GROUP BY booktitle";
	$query = mysqli_query($dbconnect, $sql);
	$modalresults = mysqli_fetch_assoc($query);
?>
<div id="bookinfo">
	<p style="font-size:1.5em;"><?php echo $modalresults['booktitle']; ?></p>
	<p>Author: <?php echo $modalresults['authors']; ?></p>
	<p>Publisher: <?php echo $modalresults['publisher']; ?></p>
	<p>Year: <?php echo $modalresults['publishingyear']; ?></p>
	<p>Classification: <?php echo $modalresults['classification']; ?></p>
</div>
<?php
}
?>