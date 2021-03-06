<title>Books</title>
<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
?>
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
							<option value="booktitle">Title</option>
							<option value="author">Author</option>
							<option value="publisher">Publisher</option>
							<option value="publishingyear">Year</option>
							<option value="accession_no">Accession Number</option>
						</select>
					</div>
					<div class="form-group">
						<input class="form-control aedsearchbox" type="text" name="mngbooksearch" placeholder="Search by keyword">
					</div>
					<input class="btn btn-success btn-sm form-control" id="aedsearchbutton" type="submit" name="mngbookbutton" value="Search">
			</form>
			<form style="margin-top:10px;" method="GET" class="form-inline" id="booktableyearsearchform">
				<div class="form-group">
					Filter by year range: 
					<input class="form-control aedsearchbox startyear" type="text" name="startyear" placeholder="Start Year">
					<input class="form-control aedsearchbox endyear" type="text" name="endyear" placeholder="End Year">
				</div>
				<input class="btn btn-success btn-sm form-control" id="aedsearchbutton" type="submit" name="mngbookbutton" value="Search">
			</form>
			<form style="margin-top:10px;" method="GET" class="form-inline" id="booktablesearchform">
				Filter by classification:
				<div class="form-group">
					<select name="classification" id="classification" class="form-control">
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
				<option value="accession_no">Accession Number</option>
				<option value="bookID">Title</option>
			</select>
		</form>
	</div>
	<?php 
	require "dbconnect.php";
		$booksperpages = 10;
		$totalbookSQL = "SELECT bookID, book.accession_no, callnumber, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher.publisher, publishingyear, classification.classification, COUNT(DISTINCT book.accession_no) AS copies, book.status, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status!='Archived' GROUP BY bookID ORDER BY accession_no DESC";
		$totalbookQuery = mysqli_query($dbconnect, $totalbookSQL);
		$totalbookresults = mysqli_num_rows($totalbookQuery);

		$numberofpages = ceil($totalbookresults/$booksperpages);
						
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
		

		$bookSQL = "SELECT bookID, book.accession_no, callnumber, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher.publisher, publishingyear, classification.classification, COUNT(DISTINCT book.accession_no) AS copies, book.status, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status!='Archived' GROUP BY book.accession_no ORDER BY accession_no DESC LIMIT $firstresult, $booksperpages";
		$bookQuery = mysqli_query($dbconnect, $bookSQL);
		$book = mysqli_fetch_assoc($bookQuery);
	?>
	<div class="table-responsive" id='bookdisplay'>
		<div class="reportbtn">
			<form id="printpdf" target="_blank" action="pdfbookbycopy.php" method="POST" class="form-inline">
				<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
				<input type="hidden" name="query" value="<?php echo $totalbookSQL;?>">
			</form>
		</div>
		<table class='table table-hover table-bordered table-striped' id='booktable'>
			<tr>
				<th>Accession No.</th>
				<th>Title</th>
				<th>Authors</th>
				<th>Publication Details</th>
				<th> </th>
			</tr>
		<?php
			do {
		?>
				<tr>
					<td><?php echo $book['accession_no'];?></td>
					<td>
						<button class="btn btn-link btn-sm viewbookinfo" style="color:#1CA843;" id="<?php echo $book['accession_no'];?>">
							<b><?php echo $book['booktitle'];?></b>
						</button>
					</td>
					<td><?php echo $book['authors'];?></td>
					<td><?php echo $book['publisher']." c".$book['publishingyear'];?></td>
					<td>
						<a href="?page=updatebook&bookID=<?php echo $book['accession_no'];?>" class="btn btn-success btn-sm" title="Edit book.">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
						<button data-id="<?php echo $book['accession_no'];?>" class="btn btn-danger btn-sm" id="deletebook" data-toggle="modal" data-target="#deleteconfirm" title="Delete book.">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
					</td>
				</tr>
		<?php
			} while($book = mysqli_fetch_assoc($bookQuery));
		?>
		</table>
	</div>
	<p style='margin-top:20px;'>Page: <?php echo $page;?> of <?php echo $numberofpages;?></p>
	<?php
		$pagination = '';
		if($numberofpages > 1) {
			if($page > 1) {
				$previous = $page - 1;
				$pagination .= '<a href="?page=books&bookpage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="?page=books&bookpage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}

			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="?page=books&bookpage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="?page=books&bookpage='.$next.'">Next</a>&nbsp;';	
			}
		}
	?>
	<div class="pagination"><?php echo $pagination;?></div>
	<form id="pagination-data">
		<input type="hidden" name="booksperpages" id="booksperpages" value="<?php echo $booksperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
	<script>
		$(document).ready(function(){
			$("#booktablesearchform").submit(function(e) {
				var keyword = $(".aedsearchbox").val();
				var startyear = $(".startyear").val();
				var endyear = $(".endyear").val();

				if(keyword=="") {
					$("#emptysearch").modal("show");
					e.preventDefault();
				} 
			});

			$("#booktableyearsearchform").submit(function(e) {
				var startyear = $(".startyear").val();
				var endyear = $(".endyear").val();

				if(startyear=="" || endyear=="") {
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
			
			$("#bookgroupby").change(function(){
				var option = $(this).val();
				var booksperpages = $("#booksperpages").val();
				var firstresult = $("#firstresult").val();
				$.ajax({
					url:"booktblfilter.php",
					method:"POST",
					data:{option:option, booksperpages:booksperpages,firstresult:firstresult},
					success:function(data) {
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