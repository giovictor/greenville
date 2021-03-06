<?php
require "dbconnect.php";
if(isset($_POST['bookID']) && isset($_POST['newcopies']) && isset($_POST['booksperpages']) && isset($_POST['firstresult'])) {
	$bookID = $_POST['bookID'];
	$newcopies = $_POST['newcopies'];
	$booksperpages = $_POST['booksperpages'];
	$firstresult = $_POST['firstresult'];

	if(!is_numeric($newcopies)) {
		echo "Invalid";
	} else {
		$getbookdataSQL = "SELECT bookID, book.accession_no, callnumber, booktitle, barcode, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher.publisherID, publisher.publisher, publishingyear, classification.classificationID, classification.classification, ISBN,book.status, acquisitiondate, pages, borrowcounter, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status!='Archived' AND bookID='$bookID' GROUP BY bookID ORDER BY accession_no DESC";
		$getbookdataQuery = mysqli_query($dbconnect, $getbookdataSQL);
		$getbookdata = mysqli_fetch_assoc($getbookdataQuery);

		$bookauthorSQL = "SELECT authorID FROM bookauthor WHERE accession_no IN (SELECT accession_no FROM book WHERE bookID='$bookID')";
		$bookauthorQuery = mysqli_query($dbconnect, $bookauthorSQL);
		$bookauthor = mysqli_fetch_assoc($bookauthorQuery);
		$authorID = $bookauthor['authorID'];

		$bookID = $getbookdata['bookID'];
		$title = $getbookdata['booktitle'];
		$barcode = $getbookdata['barcode'];
		$publisherID = $getbookdata['publisherID'];
		$classificationID = $getbookdata['classificationID'];
		$year = $getbookdata['publishingyear'];
		$callnumber = $getbookdata['callnumber'];
		$pages = $getbookdata['pages'];
		$price = $getbookdata['price'];
		$ISBN = $getbookdata['ISBN'];

		$getmaxaccessionnoSQL = "SELECT MAX(accession_no) AS max FROM book";
		$getmaxaccessionnoQuery = mysqli_query($dbconnect, $getmaxaccessionnoSQL);
		$getmaxaccessionno = mysqli_fetch_assoc($getmaxaccessionnoQuery);
		$max = $getmaxaccessionno['max'] + 1;

		for($i=1;$i<=$newcopies; $i++) {
			$accession_no = $max++;
			$addbookSQL = "INSERT INTO book(accession_no, bookID, booktitle, barcode, classificationID, publisherID, publishingyear,callnumber, ISBN, pages, price) VALUES('$accession_no','$bookID','$title','$barcode','$classificationID','$publisherID','$year','$callnumber','$ISBN','$pages','$price')";
			$addbookQuery = mysqli_query($dbconnect, $addbookSQL);

			$addbookauthorSQL = "INSERT INTO bookauthor(accession_no, authorID) VALUES('$accession_no', '$authorID')";
			$addbookauthor = mysqli_query($dbconnect, $addbookauthorSQL);
		}

		if(isset($_POST['keyword']) && isset($_POST['searchtype'])) {
		$keyword = $_POST['keyword'];
		$searchtype = $_POST['searchtype'];
		if($searchtype=="accession_no") {
			$totalbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no='$keyword' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no='$keyword' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
		} else {
			$totalbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $searchtype LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $searchtype LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
		}
	} else if(isset($_POST['classification'])) {
		$classification = $_POST['classification'];
		$totalbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classification' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
		$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classification' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
	} else if(isset($_POST['startyear']) && isset($_POST['endyear'])) {
		$startyear = $_POST['startyear'];
		$endyear = $_POST['endyear'];
		$totalbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE publishingyear BETWEEN $startyear AND $endyear AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
		$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE publishingyear BETWEEN $startyear AND $endyear AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
	} else {
		$totalbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR',') AS authors, publisher, publishingyear, classification, callnumber, ISBN, pages, price, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
		$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR',') AS authors, publisher, publishingyear, classification, callnumber, ISBN, pages, price, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
	}
			$bookQuery = mysqli_query($dbconnect, $bookSQL);
			$book = mysqli_fetch_assoc($bookQuery);
?>

<div class="reportbtn">
	<form id="printpdf" target="_blank" action="pdfbookbytitle.php" method="POST" class="form-inline">
		<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
		<input type="hidden" name="query" value="<?php echo $totalbookSQL;?>">
	</form>
</div>
<table class='table table-hover table-bordered table-striped' id='booktable'>
		<tr>
			<th>Title</th>
			<th>Authors</th>
			<th>Publication Details</th>
			<th>Copies</th>
			<th> </th>
			
		</tr>
	<?php
		do {
	?>
			<tr>
				<td>
					<button class="btn btn-link btn-sm viewbookinfo" style="color:#1CA843;" id="<?php echo $book['accession_no'];?>">
						<b><?php echo $book['booktitle'];?></b>
					</button>
				</td>
				<td><?php echo $book['authors'];?></td>
				<td><?php echo $book['publisher']." c".$book['publishingyear'];?></td>
				<td><?php echo $book['copies'];?></td>
				<td>
					<button class="btn btn-primary btn-sm addbookcopy" id="<?php echo $book['bookID'];?>" data-toggle="modal" data-target="#addbookcopy" title="Add copies of book.">
						<span class="glyphicon glyphicon-plus"></span>
					</button>
					<a href="?page=updatebook&bookID=<?php echo $book['bookID'];?>" class="btn btn-success btn-sm" title="Edit book.">
						<span class="glyphicon glyphicon-pencil"></span>
					</a>
					<button data-id="<?php echo $book['bookID'];?>" class="btn btn-danger btn-sm" id="deletebook" data-toggle="modal" data-target="#deleteconfirm" title="Delete book.">
						<span class="glyphicon glyphicon-trash"></span>
					</button>
				</td>
			</tr>
	<?php
		} while($book = mysqli_fetch_assoc($bookQuery));
	?>
</table>
<script>
$(document).ready(function(){
	$(document).on("click", "#deletebook", function(){
		var bookid = $(this).data("id");
		$("#confirmdelete").data("id", bookid);
	});

	<?php
		if(isset($_POST['keyword']) && isset($_POST['searchtype'])) {
	?>
		$("#confirmdelete").click(function(){
			var bookid = $(this).data("id");
			var option = $("#bookgroupby").val();
			var keyword = $("#keyword").val();
			var searchtype = $("#searchtype").val();
			var booksperpages = $("#booksperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"deletebook.php",
				method:"POST",
				data:{bookid:bookid, option:option, keyword:keyword, searchtype:searchtype, booksperpages:booksperpages, firstresult:firstresult},
				beforeSend:function() {
					$("#confirmdelete").html("Deleting Book...");
				},
				success:function(data) {
					$("#deleteconfirm").modal("hide");
					$("#confirmdelete").html("Confirm");
					$("#bookdisplay").html(data);
				}
			});
		});

		$(".addbookcopy").click(function(){
			var bookID = $(this).attr("id");
			var keyword = $("#keyword").val();
			var searchtype = $("#searchtype").val();
			var booksperpages = $("#booksperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"addbookcopyinfo.php",
				method:"POST",
				data:{bookID:bookID, keyword:keyword, searchtype:searchtype, booksperpages:booksperpages, firstresult:firstresult},
				success:function(data) {
					$("#addcopybookdata").html(data);
					$("#addbookcopy").modal("show");
				}
			});
		});
	<?php
	} else if(isset($_POST['classification'])) {
	?>
		$("#confirmdelete").click(function(){
			var bookid = $(this).data("id");
			var option = $("#bookgroupby").val();
			var classification = $("#classification").val();
			var booksperpages = $("#booksperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"deletebook.php",
				method:"POST",
				data:{bookid:bookid, option:option, classification:classification, booksperpages:booksperpages, firstresult:firstresult},
				beforeSend:function() {
					$("#confirmdelete").html("Deleting Book...");
				},
				success:function(data) {
					$("#deleteconfirm").modal("hide");
					$("#confirmdelete").html("Confirm");
					$("#bookdisplay").html(data);
				}
			});
		});

		$(".addbookcopy").click(function(){
			var bookID = $(this).attr("id");
			var classification = $("#classification").val();
			var booksperpages = $("#booksperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"addbookcopyinfo.php",
				method:"POST",
				data:{bookID:bookID,classification:classification, booksperpages:booksperpages, firstresult:firstresult},
				success:function(data) {
					$("#addcopybookdata").html(data);
					$("#addbookcopy").modal("show");
				}
			});
		});
	<?php
		} else if(isset($_POST['startyear']) && isset($_POST['endyear'])) {
	?>
		$("#confirmdelete").click(function(){
			var bookid = $(this).data("id");
			var option = $("#bookgroupby").val();
			var booksperpages = $("#booksperpages").val();
			var firstresult = $("#firstresult").val();
			var startyear = $("#startyear").val();
			var endyear = $("#endyear").val();
			$.ajax({
				url:"deletebook.php",
				method:"POST",
				data:{bookid:bookid, option:option, booksperpages:booksperpages, firstresult:firstresult, startyear:startyear, endyear:endyear},
				beforeSend:function() {
					$("#confirmdelete").html("Deleting Book...");
				},
				success:function(data) {
					$("#deleteconfirm").modal("hide");
					$("#confirmdelete").html("Confirm");
					$("#bookdisplay").html(data);
				}
			});
		});

		$(".addbookcopy").click(function(){
			var bookID = $(this).attr("id");
			var booksperpages = $("#booksperpages").val();
			var firstresult = $("#firstresult").val();
			var startyear = $("#startyear").val();
			var endyear = $("#endyear").val();
			$.ajax({
				url:"addbookcopyinfo.php",
				method:"POST",
				data:{bookID:bookID, booksperpages:booksperpages, firstresult:firstresult,  startyear:startyear, endyear:endyear},
				success:function(data) {
					$("#addcopybookdata").html(data);
					$("#addbookcopy").modal("show");
				}
			});
		});
	<?php
		} else {
	?>
		$("#confirmdelete").click(function(){
			var bookid = $(this).data("id");
			var option = $("#bookgroupby").val();
			var booksperpages = $("#booksperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"deletebook.php",
				method:"POST",
				data:{bookid:bookid, option:option, booksperpages:booksperpages, firstresult:firstresult},
				beforeSend:function() {
					$("#confirmdelete").html("Deleting Book...");
				},
				success:function(data) {
					$("#deleteconfirm").modal("hide");
					$("#confirmdelete").html("Confirm");
					$("#bookdisplay").html(data);
				}
			});
		});

		$(".addbookcopy").click(function(){
			var bookID = $(this).attr("id");
			var booksperpages = $("#booksperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"addbookcopyinfo.php",
				method:"POST",
				data:{bookID:bookID, booksperpages:booksperpages, firstresult:firstresult},
				success:function(data) {
					$("#addcopybookdata").html(data);
					$("#addbookcopy").modal("show");
				}
			});
		});
	<?php
	}
	?>

	$(".viewbookinfo").click(function(){
		var accession_no = $(this).attr("id");
		$.ajax({
			url:"bookmodalinfo.php",
			method:"POST",
			data:{accession_no, accession_no},
			success:function(data) {
				$("#content").html(data);
				$("#bookInfo").modal("show");
			}
		});
	});
});
</script>
<?php
	}
}
?>