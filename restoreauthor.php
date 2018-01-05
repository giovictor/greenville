<?php
require "dbconnect.php";
if(isset($_POST['authorID'])) {
	$authorID = $_POST['authorID'];
	$restoreauthorSQL = "UPDATE author SET status=1 WHERE authorID='$authorID'";
	$restoreauthor = mysqli_query($dbconnect, $restoreauthorSQL);

	$archivedAuthorsSQL = "SELECT * FROM author WHERE status=0 ORDER BY authorID DESC";
	$archivedAuthorsQuery = mysqli_query($dbconnect,$archivedAuthorsSQL);
	$archivedAuthors = mysqli_fetch_assoc($archivedAuthorsQuery);
	$rows = mysqli_num_rows($archivedAuthorsQuery);
?>
<table class="table table-hover table-bordered">
		<tr>
			<th width="30%">Author ID</th>
			<th width="60%">Author</th>
			<th width="10%"> </th>
		</tr>
		<?php
			if($rows==0) {
				echo "<tr><td colspan='3'><center><h4>There were no archived authors.</h4></center></td></tr>";
			} else if($rows>=1) {
				do {
		?>
				<tr>
					<td><?php echo $archivedAuthors['authorID'];?></td>
					<td><?php echo $archivedAuthors['author'];?></td>
					<td>
						<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedAuthors['authorID'];?>" data-toggle="modal" data-target="#restoreauthor">
							<span class="glyphicon glyphicon-refresh"> </span>
						</button>
						<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedAuthors['authorID'];?>" data-toggle="modal" data-target="#permanentdeleteauthor">
							<span class="glyphicon glyphicon-trash"> </span>
						</button>
					</td>
				</tr>
		<?php
				} while($archivedAuthors = mysqli_fetch_assoc($archivedAuthorsQuery));
			}
		?>
</table>
<form method="POST" action="pdfarchivedauthors.php" target="_blank" class="form-inline">
	<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
	<input type="hidden" name="query" value="<?php echo $archivedAuthorsSQL;?>">
</form>
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton",function(){
		var authorID = $(this).data("id");
		$(".confirmrestoreauthor").data("id",authorID);
	});

	$(".confirmrestoreauthor").click(function(){
		var authorID = $(this).data("id");
		$.ajax({
			url:"restoreauthor.php",
			method:"POST",
			data:{authorID:authorID},
			success:function(data) {
				$("#restoreauthor").modal("hide");
				$(".authors").html(data);
			}
		});
	});

	$("#permanentdeleteauthor").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>
<?php
}
?>