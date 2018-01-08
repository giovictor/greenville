<?php
require "dbconnect.php";
if(isset($_POST['authorID']) && isset($_POST['authorsperpages']) && isset($_POST['firstresult'])) {
	$authorID = $_POST['authorID'];
	$archiveauthorSQL = "UPDATE author SET status=0 WHERE authorID='$authorID'";
	$archiveauthor = mysqli_query($dbconnect, $archiveauthorSQL);

	$authorsperpages = $_POST['authorsperpages'];
	$firstresult = $_POST['firstresult'];
	if(isset($_POST['keyword'])) {
		$keyword = $_POST['keyword'];
		$authorSQL = "SELECT * FROM author WHERE status=1 AND author LIKE '%$keyword%' ORDER BY authorID DESC LIMIT $firstresult, $authorsperpages";
	} else {
		$authorSQL = "SELECT * FROM author WHERE status=1 ORDER BY authorID DESC LIMIT $firstresult, $authorsperpages";
	}
	$authorQuery = mysqli_query($dbconnect, $authorSQL);
	$author = mysqli_fetch_assoc($authorQuery);
	$rows = mysqli_num_rows($authorQuery);
?>
<table class="table table-hover table-bordered" id="atable">
		<tr>
			<th width="30%">Author ID</th>
			<th width="62%">Author</th>
			<th width="8%"> </th>
		</tr>
	<?php
		if($rows==0) {
			if(isset($_POST['keyword'])) {
				echo "<tr><td colspan='3'><center><h4>No authors available for search keyword '$keyword'.</h4></center></td></tr>";
			} else {
				echo "<tr><td colspan='3'><center><h4>No authors available.</h4></center></td></tr>";
			}
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
<script>
$(document).ready(function() {
	$(document).on("click",".deletebutton", function(){
		var authorID = $(this).data("id");
		$(".confirmdeleteauthor").data("id", authorID);
	});

	<?php
		if(isset($_POST['keyword'])) {
	?>
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
	<?php
		} else {
	?>
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
	<?php
		}
	?>
});
</script>
<?php
}
?>