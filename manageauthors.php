<title>Authors</title>
<div class="admincontainer">
	<div class="authorform">
		<h4>Authors</h4>
		<form id="aform" class="form-inline">
			<div class="form-group">
				<label for="author">Author: </label>
				<input type="text" name="author" id="author" class="form-control">
			</div>
			<button id="addauthor" class="btn btn-success btn-sm">Add Author</button>
		</form>
	</div>
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
		$authorsperpages = 10;
		$totalauthorSQL = "SELECT * FROM author WHERE status=1 ORDER BY authorID DESC";
		$totalauthorQuery = mysqli_query($dbconnect, $totalauthorSQL);
		$rows = mysqli_num_rows($totalauthorQuery);

		$numberofpages = ceil($rows/$authorsperpages);

		if(!isset($_GET['apage'])) {
			$page = 1;
		} else {
			$page = $_GET['apage'];
		}

		$firstresult = ($page - 1) * $authorsperpages;

		$authorSQL = "SELECT * FROM author WHERE status=1 ORDER BY authorID DESC LIMIT $firstresult, $authorsperpages";
		$authorQuery = mysqli_query($dbconnect, $authorSQL);
		$author = mysqli_fetch_assoc($authorQuery);

	?>
	<div class="authors">
		<table class="table table-hover table-bordered" id="atable">
			<tr>
				<th width="30%">Author ID</th>
				<th width="62%">Author</th>
				<th width="8%"> </th>
			</tr>
		<?php
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
		?>
		</table>
	</div>
	<form method="POST" action="pdfauthors.php" target="_blank" class="form-inline">
		<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
		<input type="hidden" name="query" value="<?php echo $authorSQL;?>">
	</form>
	<?php
		if($numberofpages > 1) {
	?>
			<ul class="pagination">
				<?php
					for($i=1;$i<=$numberofpages;$i++) {
				?>
						<li><a href="index.php?page=authors&apage=<?php echo $i;?>"><?php echo $i;?></a></li>
				<?php
					}
				?>
			</ul>
	<?php
		}
	?>
	<form id="pagination_data">
		<input type="hidden" name="authorsperpages" id="authorsperpages" value="<?php echo $authorsperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
</div>
<script>
$(document).ready(function(){
	$("#aform").submit(function(e) {
		e.preventDefault();
		var author = $("#author").val();
		if(author=="") {
			$("#emptyauthor").modal("show");
		} else {
			$.ajax({
				url:"addauthor.php",
				method:"POST",
				data:{author:author},
				beforeSend:function() {
					$("#addauthor").html("Adding...");
				},
				success:function(data){
					$("#addauthor").html("Add Author");
					$("#aform")[0].reset();
					$("#addmsg4").modal("show");
					$(".authors").html(data);
				}
			});
		}
	});

	$("#asearchform").submit(function(e){
		var searchbox = $("#asearchbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		} 
	});

	$(document).on("click",".deletebutton", function(){
		var authorID = $(this).data("id");
		$(".confirmdeleteauthor").data("id", authorID);
	});

	$(".confirmdeleteauthor").click(function(){
		var authorID = $(this).data("id");
		var authorsperpages = $("#authorsperpages").val();
		var firstresult = $("#firstresult").val();
		$.ajax({
			url:"deleteauthor.php",
			method:"POST",
			data:{authorID:authorID, authorsperpages:authorsperpages, firstresult:firstresult},
			success:function(data) {
				$("#confirmdeleteauthor").modal("hide");
				$(".authors").html(data);
			}
		});
	});
});
</script>