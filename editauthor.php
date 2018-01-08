<?php
require "dbconnect.php";
if(isset($_POST['author']) && isset($_POST['authorID'])) {
	$author = $_POST['author'];
	$authorID = $_POST['authorID'];

	$updateauthorSQL = "UPDATE author SET author='$author' WHERE authorID='$authorID'";
	$updateauthorQuery = mysqli_query($dbconnect, $updateauthorSQL);

	$authorSQL = "SELECT * FROM author WHERE status=1 AND authorID='$authorID' ORDER BY authorID DESC";
	$authorQuery = mysqli_query($dbconnect, $authorSQL);
	$author = mysqli_fetch_assoc($authorQuery);
?>
<div class="authorform">
	<h4>Update Author</h4>
	<a href="?page=authors" style="float:right;"  class="btn btn-success btn-sm button">
		View All Authors
	</a>
	<form id="aform" class="form-inline">
		<div class="form-group">
			<label for="author">Author: </label>
			<input type="text" name="author" id="author" class="form-control" value="<?php echo $author['author'];?>">
			<input type="hidden" name="authorID" value="<?php echo $authorID; ?>" id="authorID">
		</div>
		<button id="editauthor" class="btn btn-success btn-sm">Update Author</button>
	</form>
</div>
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
<?php
}
?>