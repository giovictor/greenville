<div class="admincontainer">
	<div class="panel panel-success" id="archivedsearchform">
		<div class="panel-heading">
			<a href="?page=archvsbooks" style="float:right;" class="btn btn-success btn-sm button">View All Archived Books</a>
			<h3>Archived Books</h3>
		</div>
		<div class="panel-body">
			<form method="GET" class="form-inline" id="archivedbooksearchform">
				<div class="form-group">
					Filter by keyword: 
					<select name="archivedbooksearchtype" class="form-control">
						<option value="booktitle"
						<?php
							if(isset($_GET['archivedbooksearchtype']) && $_GET['archivedbooksearchtype']=="booktitle") {
								echo "selected='selected'";
							}
						?>
						>Title</option>
						<option value="author"
						<?php
							if(isset($_GET['archivedbooksearchtype']) && $_GET['archivedbooksearchtype']=="author") {
								echo "selected='selected'";
							}
						?>
						>Author</option>
						<option value="publisher"
						<?php
							if(isset($_GET['archivedbooksearchtype']) && $_GET['archivedbooksearchtype']=="publisher") {
								echo "selected='selected'";
							}
						?>
						>Publisher</option>
						<option value="publishingyear"
						<?php
							if(isset($_GET['archivedbooksearchtype']) && $_GET['archivedbooksearchtype']=="publishingyear") {
								echo "selected='selected'";
							}
						?>
						>Year</option>
						<option value="accession_no"
						<?php
							if(isset($_GET['archivedbooksearchtype']) && $_GET['archivedbooksearchtype']=="accession_no") {
								echo "selected='selected'";
							}
						?>
						>Accession Number</option>
					</select>
				</div>
				<div class="form-group">
					<input class="form-control archivedbooksearchbox" type="text" name="archivedbooksearch" size="20" placeholder="Search by keyword">
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
							<option value="<?php echo $classification['classificationID'];?>"<?php
								if(isset($_GET['archivedbookclassification']) && $_GET['archivedbookclassification']==$classification['classificationID']) {
									echo "selected='selected'";
								}
							 ?>	
							><?php echo $classification['classification'];?></option>
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
				if($archivedbooksearchtype=="accession_no") {
					$totalarchivedbookSQL = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE accession_no='$archivedbooksearch' AND book.status='Archived' GROUP BY book.accession_no ORDER BY book.accession_no DESC";
				} else {
					$totalarchivedbookSQL = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $archivedbooksearchtype LIKE '%$archivedbooksearch%' AND book.status='Archived' GROUP BY book.accession_no ORDER BY book.accession_no DESC";
				}

				$booksperpages = 10;
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
				
				if($archivedbooksearchtype=="accession_no") {
					$archivedbookSQL = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE accession_no='$archivedbooksearch' AND book.status='Archived' GROUP BY book.accession_no ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
				} else {
					$archivedbookSQL = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $archivedbooksearchtype LIKE '%$archivedbooksearch%' AND book.status='Archived' GROUP BY book.accession_no ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";
				}
			} else if(isset($_GET['archivedbookclassification'])) {
				$archivedbookclassification = $_GET['archivedbookclassification'];
				$totalarchivedbookSQL = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$archivedbookclassification' AND book.status='Archived' GROUP BY book.accession_no ORDER BY book.accession_no DESC";
				
				$booksperpages = 10;
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

				$archivedbookSQL = "SELECT book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$archivedbookclassification' AND book.status='Archived' GROUP BY book.accession_no ORDER BY book.accession_no DESC LIMIT $firstresult, $booksperpages";

			}

		$archivedbookQuery = mysqli_query($dbconnect, $archivedbookSQL);
		$archivedbook = mysqli_fetch_assoc($archivedbookQuery);
		$rows = mysqli_num_rows($archivedbookQuery);
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
				<th>Authors</th>
				<th>Publication Details</th>
				<th>Remarks</th>
				<th> </th>
				
			</tr>
		<?php
			if($rows==0) {
				echo "<tr><td colspan='9'><center><h4>No results found.</h4></center></td></tr>";
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
	</div>
	<?php
		if($numberofpages> 1) {
			$pagination = '';
			if(isset($_GET['archivedbooksearch']) && isset($_GET['archivedbooksearchtype'])) {
	?>
				<p style="margin-top:20px;">Showing <?php echo $totalarchivedbooks;?> results</p>
				<p>Page: <?php echo $page; ?> of <?php echo $numberofpages;?></p>
				<?php
					if($page > 1) {
						$previous = $page - 1;
						$pagination .= '<a href="index.php?archivedbooksearchtype='.$archivedbooksearchtype.'&archivedbooksearch='.$archivedbooksearch.'&archivedbookbutton=Search&bookpage='.$previous.'">Previous</a>&nbsp;';
		
						for($i = $page - 3; $i < $page; $i++) {
							if($i > 0) {
								$pagination .= '<a href="index.php?archivedbooksearchtype='.$archivedbooksearchtype.'&archivedbooksearch='.$archivedbooksearch.'&archivedbookbutton=Search&bookpage='.$i.'">'.$i.'</a>&nbsp;';
							}
						}
					}
		
					$pagination .= ''.$page.'&nbsp;';
		
					for($i = $page + 1; $i <= $numberofpages; $i++) {
						$pagination .= '<a href="index.php?archivedbooksearchtype='.$archivedbooksearchtype.'&archivedbooksearch='.$archivedbooksearch.'&archivedbookbutton=Search&bookpage='.$i.'">'.$i.'</a>&nbsp;';
						if($i >= $page + 3) {
							break;
						}
					}
		
					if($page != $numberofpages) {
						$next = $page + 1;
						$pagination .= '<a href="index.php?archivedbooksearchtype='.$archivedbooksearchtype.'&archivedbooksearch='.$archivedbooksearch.'&archivedbookbutton=Search&bookpage='.$next.'">Next</a>&nbsp;';	
					}
				?>
		<?php
			} else if(isset($_GET['archivedbookclassification'])) {
		?>
				<p style="margin-top:20px;">Showing <?php echo $totalarchivedbooks;?> results</p>
				<p>Page: <?php echo $page; ?> of <?php echo $numberofpages;?></p>
				<?php
					if($page > 1) {
						$previous = $page - 1;
						$pagination .= '<a href="index.php?archivedbookclassification='.$archivedbookclassification.'&archivedbookbutton=Search&bookpage='.$previous.'">Previous</a>&nbsp;';
		
						for($i = $page - 3; $i < $page; $i++) {
							if($i > 0) {
								$pagination .= '<a href="index.php?archivedbookclassification='.$archivedbookclassification.'&archivedbookbutton=Search&bookpage='.$i.'">'.$i.'</a>&nbsp;';
							}
						}
					}
		
					$pagination .= ''.$page.'&nbsp;';
		
					for($i = $page + 1; $i <= $numberofpages; $i++) {
						$pagination .= '<a href="index.php?archivedbookclassification='.$archivedbookclassification.'&archivedbookbutton=Search&bookpage='.$i.'">'.$i.'</a>&nbsp;';
						if($i >= $page + 3) {
							break;
						}
					}
		
					if($page != $numberofpages) {
						$next = $page + 1;
						$pagination .= '<a href="index.php?archivedbookclassification='.$archivedbookclassification.'&archivedbookbutton=Search&bookpage='.$next.'">Next</a>&nbsp;';	
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
			<input type="hidden" name="keyword"  id="keyword" value="<?php echo $archivedbooksearch;?>">
			<input type="hidden" name="searchtype" id="searchtype" value="<?php echo $archivedbooksearchtype;?>">
			<input type="hidden" name="classification" id="classification" value="<?php echo $archivedbookclassification;?>">
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
		var bookid = $(this).data("id");
		$(".confirmrestorebook").data("id",bookid);
	});

	<?php
		if(isset($_GET['archivedbooksearch']) && isset($_GET['archivedbooksearchtype'])) {
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
		} else if(isset($_GET['archivedbookclassification'])) {
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
		}
	?>
});
</script>
<?php
}
?>