<title>Archived Books</title>
<div class="admincontainer">
	<div class="panel panel-success" id="archivedsearchform">
		<div class="panel-heading">
			<h3>Archived Books</h3>
		</div>
		<div class="panel-body">
			<form method="GET" class="form-inline" id="archivedbooksearchform">
					<div class="form-group">
						Filter by keyword: 
						<select name="archivedbooksearchtype" class="aedsearchtype form-control">
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
						<input class="form-control archivedbooksearchbox" type="text" name="archivedbooksearch" placeholder="Search by keyword">
					</div>
					<input class="btn btn-success btn-sm form-control button" id="archivedbooksearchbutton" type="submit" name="archivedbookbutton" value="Search">
			</form>
			<form style="margin-top:10px;" method="GET" class="form-inline" id="archivedbooktablesearchform">
				Filter by classification:
				<div class="form-group">
					<select name="archivedbookclassificationselect" id="archivedbookclassification" class="form-control">
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
				<input class="btn btn-success btn-sm form-control button" id="archivedbooksearchbutton" type="submit" name="archivedbookbutton" value="Search">
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
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
		$archivedbookSQL = "SELECT bookID, book.accession_no, callnumber, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher.publisher, publishingyear, classification.classification, COUNT(DISTINCT book.accession_no) AS copies, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status='Archived' GROUP BY bookID ORDER BY accession_no DESC";
		$archivedbookQuery = mysqli_query($dbconnect, $archivedbookSQL);
		$archivedbook = mysqli_fetch_assoc($archivedbookQuery);
		$rows = mysqli_num_rows($archivedbookQuery);
	?>
	<div id='bookdisplay'>
		<table class='table table-hover table-bordered table-striped' id='booktable'>
			<tr>
				<th>Title</th>
				<th>Authors</th>
				<th>Publication Details</th>
				<th>Copies</th>
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
					<td><?php echo $archivedbook['booktitle'];?></td>
					<td><?php echo $archivedbook['authors'];?></td>
					<td><?php echo $archivedbook['publisher']." c".$archivedbook['publishingyear'];?></td>
					<td><?php echo $archivedbook['copies'];?></td>
					<td><?php echo $archivedbook['bookcondition'];?></td>
					<td>
						<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedbook['bookID'];?>" data-toggle="modal" data-target="#restorebook">
							<span class="glyphicon glyphicon-refresh"> </span>
						</button>
						<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedbook['bookID'];?>" data-toggle="modal" data-target="#permanentdeletebook">
							<span class="glyphicon glyphicon-trash"> </span>
						</button>
					</td>
				</tr>
		<?php
				} while($archivedbook = mysqli_fetch_assoc($archivedbookQuery));
			}
		?>
	</table>
	<form id="printpdf" target="_blank" action="pdfarchivedbookbytitle.php" method="POST" class="form-inline">
		<input class="btn btn-success btn-sm" id="button" type="submit" name="createpdf" value="Print PDF">
		<input type="hidden" name="query" value="<?php echo $archivedbookSQL;?>">
	</form>
	</div>
</div>
<script>
$(document).ready(function(){
	$("#archivedbooksearchform").submit(function(e){
		var searchbox = $(".archivedbooksearchbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		}
	});

	$("#bookgroupby").change(function(){
		var option = $(this).val();
		$.ajax({
			url:"archivedbooktblfilter.php",
			method:"POST",
			data:{option:option},
			success:function(data) {
				$("#bookdisplay").html(data);
			}
		});
	});

	$(document).on("click",".restorebutton",function(){
		var bookid = $(this).data("id");
		$(".confirmrestorebook").data("id",bookid);
	});

	$(".confirmrestorebook").click(function(){
		var bookid = $(this).data("id");
		var option = $("#bookgroupby").val();
		$.ajax({
			url:"restorebook.php",
			method:"POST",
			data:{bookid:bookid, option:option},
			success:function(data) {
				$("#restorebook").modal("hide");
				$("#bookdisplay").html(data);
			}
		});
	});


	$("#permanentdeletebook").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>