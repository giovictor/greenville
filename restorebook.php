<?php
require "dbconnect.php";
if(isset($_POST['accession_no']) && isset($_POST['firstresult']) && isset($_POST['booksperpages'])) {
	$accession_no = $_POST['accession_no'];
	$booksperpages = $_POST['booksperpages'];
	$firstresult = $_POST['firstresult'];

	$restorebookSQL = "UPDATE book SET status='On Shelf',bookcondition='On Shelf' WHERE accession_no='$accession_no'";
	$restorebook = mysqli_query($dbconnect, $restorebookSQL);

	if(isset($_POST['keyword']) && isset($_POST['searchtype'])) {
		$keyword = $_POST['keyword'];
		$searchtype = $_POST['searchtype'];
		if($searchtype=="accession_no") {
			$archivedbookSQL = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE accession_no='$keyword' AND book.status='Archived' GROUP BY book.accession_no ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
		} else {
			$archivedbookSQL = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $searchtype LIKE '%$keyword%' AND book.status='Archived' GROUP BY book.accession_no ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
		}
	} else if(isset($_POST['classification'])) {
		$classification = $_POST['classification'];
		$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, price, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classification' AND book.status='Archived' GROUP BY book.accession_no ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
	} else {
		$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, price, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status='Archived' GROUP BY book.accession_no ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
	}

	$archivedbookQuery = mysqli_query($dbconnect, $archivedbookSQL);
	$archivedbook = mysqli_fetch_assoc($archivedbookQuery);
	$rows = mysqli_num_rows($archivedbookQuery);
?>
<table class='table table-hover table-bordered table-striped' id='booktable'>
	<tr>
		<th>Accession Number</th>
		<th>Title</th>
		<th>Author</th>
		<th>Publication Details</th>
		<th>Remarks</th>
		<th> </th>
		
	</tr>
	<?php
		if($rows==0) {
			echo "<tr><td colspan='9'><center><h4>There were no archived books.</h4></center></td></tr>";
		} else if($rows>=1) {
			do {
	?>
			<tr>
				<td><?php echo $archivedbook['accession_no'];?></td>
				<td><?php echo $archivedbook['booktitle'];?></td>
				<td><?php echo $archivedbook['authors'];?></td>
				<td><?php echo $archivedbook['publisher']." c".$archivedbook['publishingyear'];?></td>
				<td><?php echo $archivedbook['bookcondition'];?></td>
				<td>
					<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedbook['accession_no'];?>" data-toggle="modal" data-target="#restorebook">
						<span class="glyphicon glyphicon-refresh"> </span>
					</button>
					<!--<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedbook['accession_no'];?>" data-toggle="modal" data-target="#permanentdeletebook">
						<span class="glyphicon glyphicon-trash"> </span>
					</button>-->
				</td>
			</tr>
	<?php
			} while($archivedbook = mysqli_fetch_assoc($archivedbookQuery));
		}
	?>
</table>
<script>
$(document).ready(function() {
	$(document).on("click",".restorebutton",function(){
		var accession_no = $(this).data("id");
		$(".confirmrestorebook").data("id",accession_no);
	});

	<?php
		if(isset($_POST['keyword']) && isset($_POST['searchtype'])) {
	?>
			$(".confirmrestorebook").click(function(){
				var accession_no = $(this).data("id");
				var keyword = $("#keyword").val();
				var searchtype = $("#searchtype").val();
				var booksperpages = $("#booksperpages").val();
				var firstresult = $("#firstresult").val();
				$.ajax({
					url:"restorebook.php",
					method:"POST",
					data:{accession_no:accession_no,keyword:keyword, searchtype:searchtype, booksperpages:booksperpages, firstresult:firstresult},
					success:function(data) {
						$("#restorebook").modal("hide");
						$("#bookdisplay").html(data);
					}
				});
			});
	<?php
		} else if(isset($_POST['classification'])) {
	?>
			$(".confirmrestorebook").click(function(){
				var accession_no = $(this).data("id");
				var classification = $("#classification").val();
				var booksperpages = $("#booksperpages").val();
				var firstresult = $("#firstresult").val();
				$.ajax({
					url:"restorebook.php",
					method:"POST",
					data:{accession_no:accession_no,classification:classification, booksperpages:booksperpages, firstresult:firstresult},
					success:function(data) {
						$("#restorebook").modal("hide");
						$("#bookdisplay").html(data);
					}
				});
			});
	<?php
		} else {
	?>
			$(".confirmrestorebook").click(function(){
				var accession_no = $(this).data("id");
				var booksperpages = $("#booksperpages").val();
				var firstresult = $("#firstresult").val();
				$.ajax({
					url:"restorebook.php",
					method:"POST",
					data:{accession_no:accession_no, booksperpages:booksperpages, firstresult:firstresult},
					success:function(data) {
						$("#restorebook").modal("hide");
						$("#bookdisplay").html(data);
					}
				});
			});
	<?php
		}
	?>
});
</script>
<?php
}
?>