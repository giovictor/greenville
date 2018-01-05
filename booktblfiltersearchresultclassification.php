<?php
require "dbconnect.php";
if(isset($_POST['option']) && isset($_POST['classification'])) {
	$option = $_POST['option'];
	$classification = $_POST['classification'];
	$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classification' AND book.status!='Archived' GROUP BY $option ORDER BY book.accession_no DESC";


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
	<input type="hidden" name="classification" id="classification" value="<?php echo $classification;?>">
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
		var option = $("#classificationbookgroupby").val();
		var classification = $("#classification").val();
		$.ajax({
			url:"deletebookclassificationsearchresults.php",
			method:"POST",
			data:{bookid:bookid, option:option, classification:classification},
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
		$.ajax({
			url:"addbookcopyinfoclassification.php",
			method:"POST",
			data:{bookID:bookID,classification:classification},
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