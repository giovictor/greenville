<div class="admincontainer">	
	<a href="?page=authors" class="btn btn-success btn-sm button">
		View All Authors
	</a>
	<div class="authorsearch">
		<form method="GET" class="form-inline" id="asearchform">
			<div class="form-group">
				<label>Search:</label>
				<input type="text" name="asearch" id="asearchbox" class="form-control">
			</div>
			<button id="searchauthor" class="btn btn-success btn-sm">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</form>
	</div>
	<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
	if(isset($_GET['asearch'])) {
		$keyword = $_GET['asearch'];
		$authorsperpages = 10;
		$totalauthorSQL = "SELECT * FROM author WHERE status=1 AND author LIKE '%$keyword%' ORDER BY authorID DESC";
		$totalauthorQuery = mysqli_query($dbconnect, $totalauthorSQL);
		$rows = mysqli_num_rows($totalauthorQuery);

		$numberofpages = ceil($rows/$authorsperpages);

		if(!isset($_GET['apage'])) {
			$page = 1;
		} else {
			$page = $_GET['apage'];
		}

		$firstresult = ($page - 1) * $authorsperpages;

		$authorSQL = "SELECT * FROM author WHERE status=1 AND author LIKE '%$keyword%' ORDER BY authorID DESC LIMIT $firstresult, $authorsperpages";
		$authorQuery = mysqli_query($dbconnect, $authorSQL);
		$author = mysqli_fetch_assoc($authorQuery);
	?>
	<div class="reportpdf">
		<form id="printpdf" target="_blank" action="pdfauthors.php" method="POST">
			<input type="hidden" name="query" value="<?php echo $totalauthorSQL;?>">
			<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
		</form>
	</div>
	<div class="authors">
		<table class="table table-hover table-bordered" id="atable">
			<tr>
				<th width="30%">Author ID</th>
				<th width="62%">Author</th>
				<th width="8%"> </th>
			</tr>
		<?php
		if($rows==0) {
			echo "<tr><td colspan='3'><center><h4>No results found.</h4></center></td></tr>";
		} else if($rows>=1) {
			do {
		?>
			<tr>
				<td><?php echo $authorID = $author['authorID'];?></td>
				<td><?php echo $author['author'];?></td>
				<td> 
					<a href="?page=editauthor&authorID=<?php echo $author['authorID'];?>" class="btn btn-success btn-sm" title="Edit author.">
						<span class="glyphicon glyphicon-pencil"></span>
					</a>
				<?php
					$checkauthorSQL = "SELECT COUNT(*) AS existing FROM bookauthor WHERE authorID='$authorID'";
					$checkauthorQuery = mysqli_query($dbconnect, $checkauthorSQL);
					$checkauthor = mysqli_fetch_assoc($checkauthorQuery);

					if($checkauthor['existing']==0) {
				?>
						<button class="btn btn-danger btn-sm deletebutton" data-id="<?php echo $author['authorID'];?>" data-toggle="modal" data-target="#confirmdeleteauthor" title="Delete author.">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
				<?php
					} else if($checkauthor['existing']>=1) {
				?>
						<button class="btn btn-danger btn-sm deletebutton" title="This author cannot be deleted due to foreign key constraint." disabled>
							<span class="glyphicon glyphicon-trash"></span>
						</button>
				<?php	
					}
				?>
				</td>
			</tr>
		<?php	
			} while($author = mysqli_fetch_assoc($authorQuery));
		}
		?>
		</table>
	</div>
	<?php
		if($numberofpages > 1) {
			$pagination = '';
	?>
			<p style='margin-top:20px;'>Showing <?php echo $rows;?> results</p>
			<p>Page: <?php echo $page;?> of <?php echo $numberofpages;?></p>
	<?php
		if($page > 1) {
			$previous = $page - 1;
			$pagination .= '<a href="index.php?asearch='.$keyword.'&apage='.$previous.'">Previous</a>&nbsp;';

			for($i = $page - 3; $i < $page; $i++) {
				if($i > 0) {
					$pagination .= '<a href="index.php?asearch='.$keyword.'&apage='.$i.'">'.$i.'</a>&nbsp;';
				}
			}
		}
		
		$pagination .= ''.$page.'&nbsp;';

		for($i = $page + 1; $i <= $numberofpages; $i++) {
			$pagination .= '<a href="index.php?asearch='.$keyword.'&apage='.$i.'">'.$i.'</a>&nbsp;';
			if($i >= $page + 3) {
				break;
			}
		}

		if($page != $numberofpages) {
			$next = $page + 1;
			$pagination .= '<a href="index.php?asearch='.$keyword.'&apage='.$next.'">Next</a>&nbsp;';	
		}
	?>
		<div class="pagination"><?php echo $pagination;?></div>
	<?php
		}
	?>
	<form id="pagination_data">
		<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword;?>">
		<input type="hidden" name="authorsperpages" id="authorsperpages" value="<?php echo $authorsperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
</div>
<script>
$(document).ready(function(){
	$("#asearchform").submit(function(e){
		var searchbox = $("#asearchbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		} 
	});

	$(document).on("click",".deletebutton",function(){
		var authorID = $(this).data("id");
		$(".confirmdeleteauthor").data("id",authorID);
	});

	$(".confirmdeleteauthor").click(function(){
		var authorID = $(this).data("id");
		var authorsperpages = $("#authorsperpages").val();
		var firstresult = $("#firstresult").val();
		var keyword = $("#keyword").val();
		$.ajax({
			url:"deleteauthor.php",
			method:"POST",
			data:{authorID:authorID, authorsperpages:authorsperpages, firstresult:firstresult, keyword:keyword},
			success:function(data) {
				$("#confirmdeleteauthor").modal("hide");
				$(".authors").html(data);
			}
		});
	});

});
</script>
<?php
}
?>