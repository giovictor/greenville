<?php
require "dbconnect.php";
if(isset($_POST['option']) && isset($_POST['keyword']) && isset($_POST['searchtype']) ) {
	$option = $_POST['option'];
	$keyword = $_POST['keyword'];
	$searchtype = $_POST['searchtype'];
	if($searchtype=="All") {
		$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE booktitle LIKE '%$keyword%' OR author.author LIKE '%$keyword%' OR publisher.publisher LIKE '%$keyword%' OR publishingyear LIKE '%$keyword%' OR classification LIKE '%$keyword%' AND book.status!='Archived' GROUP BY $option ORDER BY book.accession_no DESC";
	} else if($searchtype=="Title") {
			$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE booktitle LIKE '%$keyword%' AND book.status!='Archived' GROUP BY $option ORDER BY book.accession_no DESC";
	} else if($searchtype=="Author") {
			$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE author.author LIKE '%$keyword%' AND book.status!='Archived' GROUP BY $option ORDER BY book.accession_no DESC";
	} else if($searchtype=="Publisher") {
		$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE publisher.publisher LIKE '%$keyword%' AND book.status!='Archived' GROUP BY $option ORDER BY book.accession_no DESC";
	} else if($searchtype=="Year") {
		$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE publishingyear LIKE '%$keyword%' AND book.status!='Archived' GROUP BY $option ORDER BY book.accession_no DESC";
	} else if($searchtype=="Call Number") {
		$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE callnumber LIKE '%$keyword%' AND book.status!='Archived' GROUP BY $option ORDER BY book.accession_no DESC";
	} else if($searchtype=="ISBN") {
		$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE ISBN LIKE '%$keyword%' AND book.status!='Archived' GROUP BY $option ORDER BY book.accession_no DESC";
	} else if($searchtype=="Accession Number") {
		$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no LIKE '%$keyword%' AND book.status!='Archived' GROUP BY $option ORDER BY book.accession_no DESC";
	} 

			$bookQuery = mysqli_query($dbconnect, $bookSQL);
			$book = mysqli_fetch_assoc($bookQuery);
			$checkDB = mysqli_num_rows($bookQuery);
?>
<table class='table table-hover table-bordered table-striped' id='booktable'>
	<tr>
		<?php
			if($option=="bookID") {
		?>
				<th>Title</th>
				<th>Authors</th>
				<th>Publication Details</th>
				<th>Copies</th>
				<th> </th>
		<?php
			} else if($option=="accession_no") {
		?>
				<th>Accession No.</th>
				<th>Title</th>
				<th>Authors</th>
				<th>Publication Details</th>
				<th> </th>
		<?php
			}
		?>
	</tr>
	<?php
		do {
	?>		
			<tr>
				<?php
					if($option=="bookID") {
				?>
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
							<a href="?page=updatebook&bookID=<?php echo $book['bookID'];?>" class="btn btn-success btn-sm">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>
							<button data-id="<?php echo $book['bookID'];?>" class="btn btn-danger btn-sm" id="deletebook" data-toggle="modal" data-target="#deleteconfirm">
								<span class="glyphicon glyphicon-trash"></span>
							</button>
						</td>
				<?php
					} else if($option=="accession_no") {
				?>
						<td><?php echo $book['accession_no'];?></td>
						<td>
							<button class="btn btn-link btn-sm viewbookinfo" style="color:#1CA843;" id="<?php echo $book['accession_no'];?>">
								<b><?php echo $book['booktitle'];?></b>
							</button>
						</td>
						<td><?php echo $book['authors'];?></td>
						<td><?php echo $book['publisher']." c".$book['publishingyear'];?></td>
						<td>
							<a href="?page=updatebook&acc=<?php echo $book['accession_no'];?>" class="btn btn-success btn-sm">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>
							<button data-id="<?php echo $book['accession_no'];?>" class="btn btn-danger btn-sm" id="deletebook" data-toggle="modal" data-target="#deleteconfirm">
								<span class="glyphicon glyphicon-trash"></span>
							</button>
						</td>

				<?php
					}
				?>
			</tr>
	<?php
		} while($book = mysqli_fetch_assoc($bookQuery));
	?>
</table>
<form id="datas">
	<input type="hidden" name="keyword"  id="keyword" value="<?php echo $keyword;?>">
	<input type="hidden" name="searchtype" id="searchtype" value="<?php echo $searchtype;?>">
	<input type="hidden" name="classification" id="classification" value="<?php echo $classificationselect;?>">
</form>
<?php
	if($option=="bookID") {
?>
<form id="printpdf" target="_blank" action="pdfbookbytitle.php" method="POST" class="form-inline">
	<input class="btn btn-success btn-sm" id="button" type="submit" name="createpdf" value="Print PDF">
	<input type="hidden" name="query" value="<?php echo $bookSQL;?>">
</form>
<?php
} else if($option=="accession_no") {
?>
<form id="printpdf" target="_blank" action="pdfbookbycopy.php" method="POST" class="form-inline">
	<input class="btn btn-success btn-sm" id="button" type="submit" name="createpdf" value="Print PDF">
	<input type="hidden" name="query" value="<?php echo $bookSQL;?>">
</form>
<?php
}
?>
<script>
$(document).ready(function(){
	$(document).on("click", "#deletebook", function(){
		var bookid = $(this).data("id");
		$("#confirmdelete").data("id", bookid);
	});

	$("#confirmdelete").click(function(){
		var bookid = $(this).data("id");
		var option = $("#keywordbookgroupby").val();
		var keyword = $("#keyword").val();
		var searchtype = $("#searchtype").val();
		$.ajax({
			url:"deletebook.php",
			method:"POST",
			data:{bookid:bookid, option:option, keyword:keyword, searchtype:searchtype},
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
		$.ajax({
			url:"addbookcopyinfosearchresult.php",
			method:"POST",
			data:{bookID:bookID, keyword:keyword, searchtype:searchtype},
			success:function(data) {
				$("#addcopybookdata").html(data);
				$("#addbookcopy").modal("show");
			}
		});
	});

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
?>