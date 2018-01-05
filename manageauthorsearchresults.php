<div class="admincontainer">	
	<a href="?page=authors" style="margin-top:20px;"  class="btn btn-success btn-md button">
		Back To Authors
		<span class="glyphicon glyphicon-menu-hamburger"></span>
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

		$authorSQL = "SELECT * FROM author WHERE status=1 AND author LIKE '%$keyword%' ORDER BY authorID DESC";
		$authorQuery = mysqli_query($dbconnect, $authorSQL);
		$author = mysqli_fetch_assoc($authorQuery);
		$rows = mysqli_num_rows($authorQuery)

	?>
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
	<form method="POST" action="pdfauthors.php" target="_blank" class="form-inline">
		<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
		<input type="hidden" name="query" value="<?php echo $authorSQL;?>">
	</form>
</div>
<script>
$(document).ready(function(){
	$(document).on("click",".deletebutton",function(){
		var authorID = $(this).data("id");
		$(".confirmdeleteauthor").data("id",authorID);
	});

	$(".confirmdeleteauthor").click(function(){
		var authorID = $(this).data("id");
		$.ajax({
			url:"deleteauthor.php",
			method:"POST",
			data:{authorID:authorID},
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