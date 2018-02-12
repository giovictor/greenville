<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
?>
<div class="admincontainer">
	<a href="?page=addbook" class="btn btn-success btn-sm button">Add Book <span class="glyphicon glyphicon-plus"></span></a>
	<div class="panel panel-success" id="aedsearchform">
		<div class="panel-heading">
			<a class="btn btn-success btn-sm button viewlinks" href="?page=books">View All Books</a>
			<h4>Search Books <span class="glyphicon glyphicon-search"></span></h4>
		</div>
		<div class="panel-body">
			<form method="GET" class="form-inline" id="booktablesearchform">
					Filter by keyword:
					<div class="form-group">
						<select name="aedsearchtype" class="form-control">
							<option value="booktitle"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="booktitle") {
									echo "selected='selected'";
								}
							?>
							>Title</option>
							<option value="author"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="author") {
									echo "selected='selected'";
								}
							?>
							>Author</option>
							<option value="publisher"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="publisher") {
									echo "selected='selected'";
								}
							?>
							>Publisher</option>
							<option value="publishingyear"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="publishingyear") {
									echo "selected='selected'";
								}
							?>
							>Year</option>
							<option value="accession_no"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="accession_no") {
									echo "selected='selected'";
								}
							?>
							>Accession Number</option>
						</select>
					</div>
					<div class="form-group">
						<input class="form-control aedsearchbox" type="text" name="mngbooksearch" size="20" placeholder="Search by keyword">
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
							<option value="<?php echo $classification['classificationID'];?>"<?php
								if(isset($_GET['classification']) && $_GET['classification']==$classification['classificationID']) {
									echo "selected='selected'";
								}
							 ?>	
							><?php echo $classification['classification'];?></option>
						<?php
							} while($classification = mysqli_fetch_assoc($classificationQuery));
						?>
					</select>
				</div>
				<input class="btn btn-success btn-sm form-control" id="aedsearchbutton" type="submit" name="mngbookbutton" value="Search">
			</form>
		</div>
	</div>
	<?php
	require "dbconnect.php";
		if(isset($_GET['mngbookbutton'])) {
			if(isset($_GET['mngbooksearch']) && isset($_GET['aedsearchtype'])) {
				$keyword = mysqli_real_escape_string($dbconnect, htmlspecialchars($_GET['mngbooksearch']));
				$searchtype = mysqli_real_escape_string($dbconnect, htmlspecialchars($_GET['aedsearchtype']));
				if($searchtype=="accession_no") {
					$totalbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no='$keyword' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				} else {
					$totalbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $searchtype LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				}

				$booksperpages = 10;
				$totalbookQuery = mysqli_query($dbconnect, $totalbookSQL);
				$totalbookresults = mysqli_num_rows($totalbookQuery);
				$numberofpages = ceil($totalbookresults/$booksperpages);

				if(!isset($_GET['bookpage'])) {
					$page = 1;
				} else {
					$page = $_GET['bookpage'];
				}

				if($page < 1) {
					$page = 1;
				} else if($page > $numberofpages) {
					$page = $numberofpages;
				}

				$firstresult = ($page - 1) * $booksperpages;

				if($searchtype=="accession_no") {
					$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no='$keyword' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
				} else {
					$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $searchtype LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
				}
			} else if(isset($_GET['classification'])) {
				$classification = $_GET['classification'];
				$totalbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classification' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				$booksperpages = 10;
				$totalbookQuery = mysqli_query($dbconnect, $totalbookSQL);
				$totalbookresults = mysqli_num_rows($totalbookQuery);
				$numberofpages = ceil($totalbookresults/$booksperpages);

				if(!isset($_GET['bookpage'])) {
					$page = 1;
				} else {
					$page = $_GET['bookpage'];
				}

				if($page < 1) {
					$page = 1;
				} else if($page > $numberofpages) {
					$page = $numberofpages;
				}

				$firstresult = ($page - 1) * $booksperpages;

				$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classification' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
			}

			$bookQuery = mysqli_query($dbconnect, $bookSQL);
			$book = mysqli_fetch_assoc($bookQuery);
			$rows = mysqli_num_rows($bookQuery);
	?>
	<div class="booktblfilter">
		<form class="form-inline">
			<span>Group by:</span>
			<select class="form-control" id="bookgroupby" style="width:165px;">
				<option value="bookID">Title</option>
				<option value="accession_no">Accession Number</option>
			</select>
		</form>
	</div>
	<div class="table-responsive" id="bookdisplay">
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
			if($rows==0) {
				echo "<tr><td colspan='7'><center><h4>No results found. Try searching again.</h4></center></td></tr>";
			} else if($rows>=1) {
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
						<a href="?page=updatebook&bookID=<?php echo $book['bookID'];?>" class="btn btn-success btn-sm">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
						<button data-id="<?php echo $book['bookID'];?>" class="btn btn-danger btn-sm" id="deletebook" data-toggle="modal" data-target="#deleteconfirm">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
					</button>
					</td>
				</tr>
		<?php
				} while($book = mysqli_fetch_assoc($bookQuery));
			}
		?>
		</table>
	<?php
		}
	?>
	</div>
	<?php
		if($numberofpages> 1) {
			$pagination = '';
			if(isset($_GET['aedsearchtype']) && isset($_GET['mngbooksearch'])) {
	?>
				<p style="margin-top:20px;">Showing <?php echo $totalbookresults;?> results</p>
				<p>Page: <?php echo $page; ?> of <?php echo $numberofpages;?></p>
				<?php
					if($page > 1) {
						$previous = $page - 1;
						$pagination .= '<a href="index.php?aedsearchtype='.$searchtype.'&mngbooksearch='.$keyword.'&mngbookbutton=Search&bookpage='.$previous.'">Previous</a>&nbsp;';
		
						for($i = $page - 3; $i < $page; $i++) {
							if($i > 0) {
								$pagination .= '<a href="index.php?aedsearchtype='.$searchtype.'&mngbooksearch='.$keyword.'&mngbookbutton=Search&bookpage='.$i.'">'.$i.'</a>&nbsp;';
							}
						}
					}
		
					$pagination .= ''.$page.'&nbsp;';
		
					for($i = $page + 1; $i <= $numberofpages; $i++) {
						$pagination .= '<a href="index.php?aedsearchtype='.$searchtype.'&mngbooksearch='.$keyword.'&mngbookbutton=Search&bookpage='.$i.'">'.$i.'</a>&nbsp;';
						if($i >= $page + 3) {
							break;
						}
					}
		
					if($page != $numberofpages) {
						$next = $page + 1;
						$pagination .= '<a href="index.php?aedsearchtype='.$searchtype.'&mngbooksearch='.$keyword.'&mngbookbutton=Searchs&bookpage='.$next.'">Next</a>&nbsp;';	
					}
				?>
	<?php
			} else if(isset($_GET['classification'])) {
	?>
				<p style="margin-top:20px;">Showing <?php echo $totalbookresults;?> results</p>
				<p>Page: <?php echo $page; ?> of <?php echo $numberofpages;?></p>
				<?php
					if($page > 1) {
						$previous = $page - 1;
						$pagination .= '<a href="index.php?classification='.$classification.'&mngbookbutton=Search&bookpage='.$previous.'">Previous</a>&nbsp;';
		
						for($i = $page - 3; $i < $page; $i++) {
							if($i > 0) {
								$pagination .= '<a href="index.php?classification='.$classification.'&mngbookbutton=Search&bookpage='.$i.'">'.$i.'</a>&nbsp;';
							}
						}
					}
		
					$pagination .= ''.$page.'&nbsp;';
		
					for($i = $page + 1; $i <= $numberofpages; $i++) {
						$pagination .= '<a href="index.php?classification='.$classification.'&mngbookbutton=Search&bookpage='.$i.'">'.$i.'</a>&nbsp;';
						if($i >= $page + 3) {
							break;
						}
					}
		
					if($page != $numberofpages) {
						$next = $page + 1;
						$pagination .= '<a href="index.php?classification='.$classification.'&mngbookbutton=Search&bookpage='.$next.'">Next</a>&nbsp;';	
					}
				?>
	<?php
			}
	?>
		<div class="pagination"><?php echo $pagination;?></div>
	<?php
		}
	?>
	<form id="pagination-data">
		<input type="hidden" name="booksperpages" id="booksperpages" value="<?php echo $booksperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
	<form id="datas">
		<input type="hidden" name="keyword"  id="keyword" value="<?php echo $keyword;?>">
		<input type="hidden" name="searchtype" id="searchtype" value="<?php echo $searchtype;?>">
		<input type="hidden" name="classification" id="classification" value="<?php echo $classification;?>">
	</form>
	<script>
		$(document).ready(function(){
			$(document).on("click", "#deletebook", function(){
				var bookid = $(this).data("id");
				$("#confirmdelete").data("id", bookid);
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
			<?php
				if(isset($_GET['mngbooksearch']) && isset($_GET['aedsearchtype'])) {
			?>

				$("#bookgroupby").change(function(){
					var option = $(this).val();
					var keyword = $("#keyword").val();
					var searchtype = $("#searchtype").val();
					var booksperpages = $("#booksperpages").val();
					var firstresult = $("#firstresult").val();
					$.ajax({
						url:"booktblfilter.php",
						method:"POST",
						data:{option:option, keyword:keyword, searchtype:searchtype, booksperpages:booksperpages, firstresult:firstresult},
						success:function(data) {
							$("#bookdisplay").html(data);
						}
					});
				});

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
				} else if(isset($_GET['classification'])) {
			?>
				$("#bookgroupby").change(function(){
					var option = $(this).val();
					var classification = $("#classification").val();
					var booksperpages = $("#booksperpages").val();
					var firstresult = $("#firstresult").val();
					$.ajax({
						url:"booktblfilter.php",
						method:"POST",
						data:{option:option, classification:classification, booksperpages:booksperpages, firstresult:firstresult},
						success:function(data) {
							$("#bookdisplay").html(data);
						}
					});
				});

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
				}
			?>
		});
	</script>
</div>
