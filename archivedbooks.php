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
						<option value="booktitle">Title</option>
						<option value="author">Author</option>
						<option value="publisher">Publisher</option>
						<option value="publishingyear">Year</option>
						<option value="accession_no">Accession Number</option>
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
					<select name="archivedbookclassification" id="archivedbookclassification" class="form-control">
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
	<?php 
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
		$booksperpages = 10;
		$totalarchivedbookSQL = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher.publisher, publishingyear, classification.classification, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status='Archived' GROUP BY book.accession_no ORDER BY accession_no DESC";
		$totalarchivedbookQuery = mysqli_query($dbconnect, $totalarchivedbookSQL);
		$totalarchivedbooks = mysqli_num_rows($totalarchivedbookQuery);

		$numberofpages = ceil($totalarchivedbooks/$booksperpages);

		if(!isset($_GET['bookpage'])) {
			$page = 1;
		} else {
			$page = $_GET['bookpage'];
			if($page < 1) {
				$page = 1;
			} else if($page > $numberofpages) {
				$page = $numberofpages;
			} else if(!is_numeric($page)) {
				$page = 1;
			} else {
				$page = $_GET['bookpage'];
			}
		}

		$firstresult = ($page - 1) * $booksperpages;

		$archivedbookSQL = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher.publisher, publishingyear, classification.classification, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status='Archived' GROUP BY book.accession_no ORDER BY accession_no DESC LIMIT $firstresult, $booksperpages";
		$archivedbookQuery = mysqli_query($dbconnect, $archivedbookSQL);
		$archivedbook = mysqli_fetch_assoc($archivedbookQuery);
		
	?>
	<div class="reportbtn">
		<form id="printpdf" target="_blank" action="pdfarchivedbook.php" method="POST" class="form-inline">
			<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
			<input type="hidden" name="query" value="<?php echo $totalarchivedbookSQL;?>">
		</form>
	</div>
	<div id='bookdisplay'>
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
			if($totalarchivedbooks==0) {
				echo "<tr><td colspan='9'><center><h4>There were no archived books.</h4></center></td></tr>";
			} else if($totalarchivedbooks>=1) {
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
	</div>
	<?php
		$pagination = '';
		if($numberofpages > 1) {
	?>
			<p style='margin-top:20px;'>Page: <?php echo $page;?> of <?php echo $numberofpages;?></p>
	<?php
			if($page > 1) {
				$previous = $page - 1;
				$pagination .= '<a href="?page=archvsbooks&bookpage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="?page=archvsbooks&bookpage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}

			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="?page=archvsbooks&bookpage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="?page=archvsbooks&bookpage='.$next.'">Next</a>&nbsp;';	
			}
		}
	?>
	<div class="pagination"><?php echo $pagination;?></div>
	<form id="pagination-data">
		<input type="hidden" name="booksperpages" id="booksperpages" value="<?php echo $booksperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
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

	$(document).on("click",".restorebutton",function(){
		var accession_no = $(this).data("id");
		$(".confirmrestorebook").data("id",accession_no);
	});

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
});
</script>