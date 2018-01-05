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
<?php 
if(!isset($_SESSION['librarian'])) {
	header("Location:index.php");
}
require "dbconnect.php";
	if(isset($_GET['archivedbookbutton'])) {
		if(isset($_GET['archivedbooksearch']) && isset($_GET['archivedbooksearchtype'])) {
			$archivedbooksearch = $_GET['archivedbooksearch'];
			$archivedbooksearchtype = $_GET['archivedbooksearchtype'];
			if($_GET['archivedbooksearchtype']=="All") {
				$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, bookcondition, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE booktitle LIKE '%$archivedbooksearch%' OR author.author LIKE '%$archivedbooksearch%' OR publisher.publisher LIKE '%$archivedbooksearch%' OR publishingyear LIKE '%$archivedbooksearch%' OR classification LIKE '%$archivedbooksearch%' AND book.status='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			} else if($_GET['archivedbooksearchtype']=="Title") {
				$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, bookcondition, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE booktitle LIKE '%$archivedbooksearch%' AND book.status='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			} else if($_GET['archivedbooksearchtype']=="Author") {
				$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, bookcondition, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE author.author LIKE '%$archivedbooksearch%' AND book.status='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			} else if($_GET['archivedbooksearchtype']=="Publisher") {
				$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, bookcondition, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE publisher.publisher LIKE '%$archivedbooksearch%' AND book.status='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			} else if($_GET['archivedbooksearchtype']=="Year") {
				$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, bookcondition, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE publishingyear LIKE '%$archivedbooksearch%' AND book.status='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			} else if($_GET['archivedbooksearchtype']=="Call Number") {
				$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, bookcondition, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE callnumber LIKE '%$archivedbooksearch%' AND book.status='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			} else if($_GET['archivedbooksearchtype']=="ISBN") {
				$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, bookcondition, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE ISBN LIKE '%$archivedbooksearch%' AND book.status='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			} else if($_GET['archivedbooksearchtype']=="Accession Number") {
				$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status, bookcondition, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no LIKE '%$archivedbooksearch%' AND book.status='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
			} 
		} else if(isset($_GET['archivedbookclassificationselect'])) {
			$archivedbookclassificationselect = $_GET['archivedbookclassificationselect'];
			$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, bookcondition, COUNT(DISTINCT book.accession_no) AS copies FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$archivedbookclassificationselect' AND book.status='Archived' GROUP BY bookID ORDER BY book.accession_no DESC";
		}

	$archivedbookQuery = mysqli_query($dbconnect, $archivedbookSQL);
	$archivedbook = mysqli_fetch_assoc($archivedbookQuery);
?>
<div class="booktblfilter">
<?php
	if(isset($_GET['archivedbooksearch']) && isset($_GET['archivedbooksearchtype'])) {
?>
		<form class="form-inline">
			<span>Group by:</span>
			<select class="form-control" id="keywordbookgroupby" style="width:165px;">
				<option value="bookID">Title</option>
				<option value="accession_no">Accession Number</option>
			</select>
		</form>
<?php
	} else if(isset($_GET['archivedbookclassificationselect'])) {
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
	?>
</table>
<form id="printpdf" target="_blank" action="pdfarchivedbookbytitle.php" method="POST" class="form-inline">
	<input class="btn btn-success btn-sm" id="button" type="submit" name="createpdf" value="Print PDF">
	<input type="hidden" name="query" value="<?php echo $archivedbookSQL;?>">
</form>
</div>
<form id="datas">
	<input type="hidden" name="keyword"  id="keyword" value="<?php echo $archivedbooksearch;?>">
	<input type="hidden" name="searchtype" id="searchtype" value="<?php echo $archivedbooksearchtype;?>">
	<input type="hidden" name="classification" id="classification" value="<?php echo $archivedbookclassificationselect;?>">
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

	$("#keywordbookgroupby").change(function(){
		var option = $(this).val();
		var keyword = $("#keyword").val();
		var searchtype = $("#searchtype").val();
		$.ajax({
			url:"archivedbooktblfiltersearchresultkeyword.php",
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
			url:"archivedbooktblfiltersearchresultclassification.php",
			method:"POST",
			data:{option:option, classification:classification},
			success:function(data) {
				$("#bookdisplay").html(data);
			}
		});
	});

	$(document).on("click",".restorebutton",function(){
		var bookid = $(this).data("id");
		$(".confirmrestorebook").data("id",bookid);
	});

<?php
	if(isset($_GET['archivedbooksearch']) && isset($_GET['archivedbooksearchtype'])) {
?>
		$(".confirmrestorebook").click(function(){
			var bookid = $(this).data("id");
			var option = $("#keywordbookgroupby").val();
			var keyword = $("#keyword").val();
			var searchtype = $("#searchtype").val();
			$.ajax({
				url:"restorebook.php",
				method:"POST",
				data:{bookid:bookid, option:option, keyword:keyword, searchtype:searchtype},
				success:function(data) {
					$("#restorebook").modal("hide");
					$("#bookdisplay").html(data);
				}
			});
		});
<?php
	} else if(isset($_GET['archivedbookclassificationselect'])) {
?>
		$(".confirmrestorebook").click(function(){
			var bookid = $(this).data("id");
			var option = $("#classificationbookgroupby").val();
			var classification = $("#classification").val();
			$.ajax({
				url:"restorebook.php",
				method:"POST",
				data:{bookid:bookid, option:option, classification:classification},
				success:function(data) {
					$("#restorebook").modal("hide");
					$("#bookdisplay").html(data);
				}
			});
		});
<?php
	}
?>
	$("#permanentdeletebook").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>
<?php
}
?>