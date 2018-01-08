<?php
require "dbconnect.php";
if(isset($_GET['authorID'])) {
	$authorID = $_GET['authorID'];
	$authoreditSQL = "SELECT * FROM author WHERE authorID='$authorID'";
	$authoreditQuery = mysqli_query($dbconnect, $authoreditSQL);
	$authoredit = mysqli_fetch_assoc($authoreditQuery);
}
?>
<div class="admincontainer">
	<div class="authors">
		<div class="authorform">
			<h4>Update Author</h4>
			<a href="?page=authors" style="float:right;"  class="btn btn-success btn-sm button">
				View All Authors
			</a>
			<form id="aform" class="form-inline">
				<div class="form-group">
					<label for="author">Author: </label>
					<input type="text" name="author" id="author" class="form-control" value="<?php echo $authoredit['author'];?>">
					<input type="hidden" name="authorID" value="<?php echo $authorID; ?>" id="authorID">
				</div>
				<button id="editauthor" class="btn btn-success btn-sm">Update Author</button>
			</form>
		</div>
	<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
		$authorSQL = "SELECT * FROM author WHERE status=1 AND authorID='$authorID' ORDER BY authorID DESC";
		$authorQuery = mysqli_query($dbconnect, $authorSQL);
		$author = mysqli_fetch_assoc($authorQuery);

	?>
		<table class="table table-hover table-bordered">
			<tr>
				<th width="30%">Author ID</th>
				<th width="62%">Author</th>
			</tr>
		<?php
		do {
		?>
			<tr>
				<td><?php echo $authorID = $author['authorID'];?></td>
				<td><?php echo $author['author'];?></td>
			</tr>
		<?php	
		} while($author = mysqli_fetch_assoc($authorQuery));
		?>
		</table>
	</div>
</div>
<script>
$(document).ready(function(){
	$("#aform").submit(function(e){
		e.preventDefault();
		var author = $("#author").val();
		var authorID = $("#authorID").val();
		$.ajax({
			url:"editauthor.php",
			method:"POST",
			data:{author:author, authorID:authorID},
			beforeSend:function() {
				$("#editauthor").html("Updating...");
			},
			success:function(data){
				$("#editauthor").html("Update Author");
				$("#author").val("");
				$("#editmsg2").modal("show");
				$(".authors").html(data);
			}
		});
	});
});
</script>