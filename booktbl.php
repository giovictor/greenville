<title>Books</title>
<div class="admincontainer">	
	<a href="?page=addbook" class="btn btn-success btn-sm button">Add Book <span class="glyphicon glyphicon-plus"></span></a>
	<div class="panel panel-success" id="aedsearchform">
		<div class="panel-heading">
			<h4>Search Books <span class="glyphicon glyphicon-search"></span></h4>
		</div>
		<div class="panel-body">
			<form method="GET" class="form-inline" id="booktablesearchform">
					<div class="form-group">
						Filter by keyword: 
						<select name="aedsearchtype" class="aedsearchtype form-control">
							<option value="All">Any Field</option>
							<option value="Title">Title</option>
							<option value="Author">Author</option>
							<option value="Publisher">Publisher</option>
							<option value="Year">Year</option>
							<option value="Call Number">Call Number</option>
							<option value="ISBN">ISBN</option>
							<option value="Accession Number">Accession Number</option>
						</select>
					</div>
					<div class="form-group">
						<input class="form-control aedsearchbox" type="text" name="mngbooksearch" placeholder="Search by keyword">
					</div>
					<input class="btn btn-success btn-sm form-control" id="aedsearchbutton" type="submit" name="mngbookbutton" value="Search">
			</form>
			<form style="margin-top:10px;" method="GET" class="form-inline" id="booktablesearchform">
				Filter by classification:
				<div class="form-group">
					<select name="classificationselect" id="classificationselect" class="form-control">
						<?php
							require "dbconnect.php";
							$classificationSQL = "SELECT * FROM classification WHERE status=1";
							$classificationQuery = mysqli_query($dbconnect, $classificationSQL);
							$classification = mysqli_fetch_assoc($classificationQuery);
							do {
						?>
							<option value="<?php echo $classification['classificationID'];?>"><?php echo $classification['classification'];?></option>
						<?php
							} while($classification = mysqli_fetch_assoc($classificationQuery));
						?>
					</select>
				</div>
				<input class="btn btn-success btn-sm form-control" id="aedsearchbutton" type="submit" name="mngbookbutton" value="Search">
			</form>
		</div>
	</div>
	<div class="booktblfilter">
		<form class="form-inline">
			<span>Group by:</span>
			<select class="form-control" id="bookgroupby" style="width:165px;">
				<option value="bookID">Title</option>
				<option value="accession_no">Accession Number</option>
			</select>
		</form>
	</div>
	<?php 
	require "dbconnect.php";
		$bookSQL = "SELECT bookID, book.accession_no, callnumber, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher.publisher, publishingyear, classification.classification, COUNT(DISTINCT book.accession_no) AS copies, book.status, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status!='Archived' GROUP BY bookID ORDER BY accession_no DESC";
		$bookQuery = mysqli_query($dbconnect, $bookSQL);
		$book = mysqli_fetch_assoc($bookQuery);
	?>
	<div id='bookdisplay'>
		<table class='table table-hover table-bordered table-striped' id='booktable'>
			<tr>
				<th>Call No.</th>
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
					<td><?php echo $book['callnumber'];?></td>
					<td>
						<button class="btn btn-link btn-sm viewbookinfo" style="color:#1CA843;" id="<?php echo $book['accession_no'];?>">
							<b><?php echo $book['booktitle'];?></b>
						</button>
					</td>
					<td><?php echo $book['authors'];?></td>
					<td><?php echo $book['publisher']." c".$book['publishingyear'];?></td>
					<td><?php echo $book['copies'];?></td>
					<td>
						<!--<button class="btn btn-primary btn-sm addbookcopy" id="<?php echo $book['bookID'];?>" data-toggle="modal" data-target="#addbookcopy" title="Add copies of book.">
							<span class="glyphicon glyphicon-plus"></span>
						</button>-->
						<a href="?page=editupdatebook&bookID=<?php echo $book['bookID'];?>" class="btn btn-success btn-sm" title="Edit book.">
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
	<form id="printpdf" target="_blank" action="pdfbookbytitle.php" method="POST" class="form-inline">
		<input class="btn btn-success btn-sm" id="button" type="submit" name="createpdf" value="Print PDF">
		<input type="hidden" name="query" value="<?php echo $bookSQL;?>">
	</form>
	</div>
	<script>
	$(document).ready(function(){
		$("#booktablesearchform").submit(function(e) {
			var keyword = $(".aedsearchbox").val();
			if(keyword=="") {
				$("#emptysearch").modal("show");
				e.preventDefault();
			}
		});

		$(document).on("click", "#deletebook", function(){
			var bookid = $(this).data("id");
			$("#confirmdelete").data("id", bookid);
		});

		$("#confirmdelete").click(function(){
			var bookid = $(this).data("id");
			var option = $("#bookgroupby").val();
			$.ajax({
				url:"deletebook.php",
				method:"POST",
				data:{bookid:bookid, option:option},
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

		
		$("#bookgroupby").change(function(){
			var option = $(this).val();
			$.ajax({
				url:"booktblfilter.php",
				method:"POST",
				data:{option:option},
				success:function(data) {
					$("#bookdisplay").html(data);
				}
			});
		});

		$(".addbookcopy").click(function(){
			var bookID = $(this).attr("id");
			$.ajax({
				url:"addbookcopyinfo.php",
				method:"POST",
				data:{bookID:bookID},
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
</div>