<?php
require "dbconnect.php";
if(isset($_POST['author'])) {
	$author = $_POST['author'];
	$addAuthorSQL = "INSERT INTO author(author) VALUES('$author')";
	$addAuthorQuery = mysqli_query($dbconnect, $addAuthorSQL);

	$authorSQL = "SELECT * FROM author WHERE status=1 ORDER BY authorID DESC";
	$authorQuery = mysqli_query($dbconnect, $authorSQL);
	$author = mysqli_fetch_assoc($authorQuery);
?>
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
<script>
$(document).ready(function(){
	$(document).on("click",".deletebutton",function(){
		var authorID = $(this).data("id");
		$(".confirmdeleteauthor").data("id",authorID);
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
<?php
}
?>