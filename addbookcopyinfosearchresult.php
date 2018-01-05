<?php
require "dbconnect.php";
if(isset($_POST['bookID']) && isset($_POST['keyword']) && isset($_POST['searchtype'])) {
	$bookID = $_POST['bookID'];
	$bookSQL = "SELECT bookID, book.accession_no, callnumber, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher.publisher, publishingyear, classification.classification, COUNT(DISTINCT book.accession_no) AS copies, book.status, acquisitiondate, pages, borrowcounter FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status!='Archived' AND bookID='$bookID' GROUP BY bookID ORDER BY accession_no DESC";
	$bookQuery = mysqli_query($dbconnect, $bookSQL);
	$book = mysqli_fetch_assoc($bookQuery);

	$keyword = $_POST['keyword'];
	$searchtype = $_POST['searchtype'];
?>
		<p>Title: <?php echo $book['booktitle'];?></p>
		<p>Author/s: <?php echo $book['authors'];?></p>
		<p>Publication Details: <?php echo $book['publisher']." c".$book['publishingyear'];?></p>
		<p>Classification: <?php echo $book['classification'];?></p>

<form id="addcopyform" class="form-inline">
	<label>Copies To Add:</label>
	<input type="text" name="newcopies" id="newcopies" class="form-control">
	<button id="addcopybutton"  class="btn btn-success btn-sm button">Add Copies</button>
	<input type="hidden" name="bookID" id="bookID" value="<?php echo $bookID;?>">
	<input type="hidden" name="keyword"  id="keyword" value="<?php echo $keyword;?>">
	<input type="hidden" name="searchtype" id="searchtype" value="<?php echo $searchtype;?>">
	<input type="hidden" name="classification" id="classification" value="<?php echo $classification;?>">
</form>
<script>
$(document).ready(function(){
	$("#addcopyform").submit(function(e){
		e.preventDefault();
		var newcopies = $("#newcopies").val();
		var bookID = $("#bookID").val();
		var keyword = $("#keyword").val();
		var searchtype = $("#searchtype").val();
		var classification = $("#classification").val();
		if(newcopies=="") {
			$("#addbookcopyalert").html("<h4>Please input the number of copies.</h4>");
		} else if(newcopies==0) {
			
		} else {
			$.ajax({
				url:"addbookcopies.php",
				method:"POST",
				data:{bookID:bookID, newcopies:newcopies, keyword:keyword, searchtype:searchtype},
				beforeSend:function() {
					$("#addcopybutton").html("Adding books...");
					$("#addcopybutton").attr("disabled", true);
				},
				success:function(data) {
					if(data=="Invalid") {
						$("#addcopybutton").html("Add copies");
						$("#addbookcopyalert").html("<h4>Please input a numeric value.</h4>");
						$("#addcopybutton").attr("disabled", false);
					} else {
						$("#addcopybutton").html("Add copies");
						$("#addcopybutton").attr("disabled", false);
						$("#addbookcopy").modal("hide");
						$("#bookdisplay").html(data);
					}
				}
			});
		}
	});

	$("#newcopies").keypress(function(){
		$("#addbookcopyalert").html("");
	});

	$("#addbookcopy").on("hide.bs.modal", function(){
		$("#newcopies").val("").end();
		$("#addbookcopyalert").html("").end();
	});
});
</script>
<?php
}
?>