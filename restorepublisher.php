<?php
require "dbconnect.php";
if(isset($_POST['publisherID'])) {
	$publisherID = $_POST['publisherID'];
	$restorepublisherSQL = "UPDATE publisher SET status=1 WHERE publisherID='$publisherID'";
	$restorepublisher = mysqli_query($dbconnect, $restorepublisherSQL);

	$archivedPublishersSQL = "SELECT * FROM publisher WHERE status=0 ORDER BY publisherID DESC";
	$archivedPublishersQuery = mysqli_query($dbconnect,$archivedPublishersSQL);
	$archivedPublishers = mysqli_fetch_assoc($archivedPublishersQuery);
	$rows = mysqli_num_rows($archivedPublishersQuery);
?>
<table class="table table-hover table-bordered">
		<tr>
			<th width="30%">Publisher ID</th>
			<th width="60%">Publisher</th>
			<th width="10%"> </th>
		</tr>
		<?php
			if($rows==0) {
				echo "<tr><td colspan='3'><center><h4>There were no archived publishers.</h4></center></td></tr>";
			} else if($rows>=1) {
				do {
		?>
				<tr>
					<td><?php echo $archivedPublishers['publisherID'];?></td>
					<td><?php echo $archivedPublishers['publisher'];?></td>
					<td>
						<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedPublishers['publisherID'];?>" data-toggle="modal" data-target="#restorepublisher">
							<span class="glyphicon glyphicon-refresh"> </span>
						</button>
						<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedPublishers['publisherID'];?>" data-toggle="modal" data-target="#permanentdeletepublisher">
							<span class="glyphicon glyphicon-trash"> </span>
						</button>
					</td>
				</tr>
		<?php
				} while($archivedPublishers = mysqli_fetch_assoc($archivedPublishersQuery));
			}
		?>
</table>
<form method="POST" action="pdfarchivedpublishers.php" target="_blank" class="form-inline">
	<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
	<input type="hidden" name="query" value="<?php echo $archivedPublishersSQL;?>">
</form>
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton",function(){
		var publisherID = $(this).data("id");
		$(".confirmrestorepublisher").data("id",publisherID);
	});

	$(".confirmrestorepublisher").click(function(){
		var publisherID = $(this).data("id");
		$.ajax({
			url:"restorepublisher.php",
			method:"POST",
			data:{publisherID:publisherID},
			success:function(data) {
				$("#restorepublisher").modal("hide");
				$(".publishers").html(data);
			}
		});
	});
	
	$("#permanentdeletepublisher").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>
<?php
}
?>