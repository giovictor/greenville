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
	<a href="?page=authors" style="margin-top:20px;"  class="btn btn-success btn-md button">
		Back To Authors
		<span class="glyphicon glyphicon-menu-hamburger"></span>
	</a>
	<div class="authorform">
	<h4>Manage authors</h4>
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
		$authorSQL = "SELECT * FROM author WHERE status=1 ORDER BY authorID DESC";
		$authorQuery = mysqli_query($dbconnect, $authorSQL);
		$author = mysqli_fetch_assoc($authorQuery);

	?>
	<div class="authors">
		<table class="table table-hover table-bordered">
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