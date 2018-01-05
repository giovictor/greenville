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
							<option value="All"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="All") {
									echo "selected";
								}
							?>
							>Any Field</option>
							<option value="Title"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="Title") {
									echo "selected='selected'";
								}
							?>
							>Title</option>
							<option value="Author"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="Author") {
									echo "selected='selected'";
								}
							?>
							>Author</option>
							<option value="Publisher"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="Publisher") {
									echo "selected='selected'";
								}
							?>
							>Publisher</option>
							<option value="Year"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="Year") {
									echo "selected='selected'";
								}
							?>
							>Year</option>
							<option value="Call Number"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="Call Number") {
									echo "selected='selected'";
								}
							?>
							>Call Number</option>
							<option value="ISBN"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="ISBN") {
									echo "selected='selected'";
								}
							?>
							>ISBN</option>
							<option value="Accession Number"
							<?php
								if(isset($_GET['aedsearchtype']) && $_GET['aedsearchtype']=="Accession Number") {
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
					<select name="classificationselect" id="classificationselect" class="form-control">
						<?php
							require "dbconnect.php";
							$classificationSQL = "SELECT * FROM classification WHERE status=1";
							$classificationQuery = mysqli_query($dbconnect, $classificationSQL);
							$classification = mysqli_fetch_assoc($classificationQuery);
							do {
						?>
							<option value="<?php echo $classification['classificationID'];?>"<?php
								if(isset($_GET['classificationselect']) && $_GET['classificationselect']==$classification['classificationID']) {
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
				$keyword = $_GET['mngbooksearch'];
				$searchtype = $_GET['aedsearchtype'];
				if($_GET['aedsearchtype']=="All") {
					$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE booktitle LIKE '%$keyword%' OR author.author LIKE '%$keyword%' OR publisher.publisher LIKE '%$keyword%' OR publishingyear LIKE '%$keyword%' OR classification LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				} else if($_GET['aedsearchtype']=="Title") {
						$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE booktitle LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				} else if($_GET['aedsearchtype']=="Author") {
						$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE author.author LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				} else if($_GET['aedsearchtype']=="Publisher") {
					$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE publisher.publisher LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				} else if($_GET['aedsearchtype']=="Year") {
					$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE publishingyear LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				} else if($_GET['aedsearchtype']=="Call Number") {
					$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE callnumber LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				} else if($_GET['aedsearchtype']=="ISBN") {
					$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE ISBN LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				} else if($_GET['aedsearchtype']=="Accession Number") {
					$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
				} 
			} else if(isset($_GET['classificationselect'])) {
				$classificationselect = $_GET['classificationselect'];
				$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classificationselect' AND book.status!='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			}

				$bookQuery = mysqli_query($dbconnect, $bookSQL);
				$book = mysqli_fetch_assoc($bookQuery);
				$checkDB = mysqli_num_rows($bookQuery);
	?>
	<div class="booktblfilter">
	<?php
		if(isset($_GET['mngbooksearch']) && isset($_GET['aedsearchtype'])) {
	?>
			<form class="form-inline">
				<span>Group by:</span>
				<select class="form-control" id="keywordbookgroupby" style="width:165px;">
					<option value="bookID">Title</option>
					<option value="accession_no">Accession Number</option>
				</select>
			</form>
	<?php
		} else if(isset($_GET['classificationselect'])) {
	?>
			<form class="form-inline">
				<span>Group by:</span>
				<select class="form-control" id="classificationbookgroupby" style="width:165px;">
					<option value="bookID">Title</option>
					<option value="accession_no">Accession Number</option>
				</select>
			</form>
	<?php
		}
	?>
	</div>
	<div class="table-responsive" id="bookdisplay">
			<table class='table table-hover table-bordered table-striped' id='booktable'>
					<tr>
						<th>Title</th>
						<th>Authors</th>
						<th>Publication Details</th>
						<th>Copies</th>
						<th> </th>
						
					</tr>
			<?php
				if($checkDB==0) {
					echo "<tr><td colspan='7'><center><h4>No results found. Try searching again.</h4></center></td></tr>";
				} else if($checkDB>=1) {
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
	<form id="printpdf" target="_blank" action="pdfbookbytitle.php" method="POST" class="form-inline">
		<input class="btn btn-success btn-sm" id="button" type="submit" name="createpdf" value="Print PDF">
		<input type="hidden" name="query" value="<?php echo $bookSQL;?>">
	</form>
	</div>
	<form id="datas">
		<input type="hidden" name="keyword"  id="keyword" value="<?php echo $keyword;?>">
		<input type="hidden" name="searchtype" id="searchtype" value="<?php echo $searchtype;?>">
		<input type="hidden" name="classification" id="classification" value="<?php echo $classificationselect;?>">
	</form>
	<script>
	$(document).ready(function(){
		$(document).on("click", "#deletebook", function(){
			var bookid = $(this).data("id");
			$("#confirmdelete").data("id", bookid);
		});

		$(document).on("click","#archivebook",function(){
			var bookid = $(this).data("id");
			$(".confirmarchivebook").data("id", bookid);
		});

		$("#keywordbookgroupby").change(function(){
			var option = $(this).val();
			var keyword = $("#keyword").val();
			var searchtype = $("#searchtype").val();
			$.ajax({
				url:"booktblfiltersearchresultkeyword.php",
				method:"POST",
				data:{option:option, keyword:keyword, searchtype:searchtype},
				success:function(data) {
					$("#bookdisplay").html(data);
				}
			});
		});

		$("#classificationbookgroupby").change(function(){
			var option = $(this).val();
			var classification = $("#classification").val();
			$.ajax({
				url:"booktblfiltersearchresultclassification.php",
				method:"POST",
				data:{option:option, classification:classification},
				success:function(data) {
					$("#bookdisplay").html(data);
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
	<?php
		if(isset($_GET['mngbooksearch']) && isset($_GET['aedsearchtype'])) {
	?>
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

	<?php
		} else if(isset($_GET['classificationselect'])) {
	?>
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
	<?php
		}
	?>
	</script>
</div>
