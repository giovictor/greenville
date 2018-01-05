<?php
require "dbconnect.php";
if(isset($_POST['classificationID'])) {
	$classificationID = $_POST['classificationID'];
	$restoreclassificationSQL = "UPDATE classification SET status=1 WHERE classificationID='$classificationID'";
	$restoreclassification = mysqli_query($dbconnect, $restoreclassificationSQL);

	$archivedClassificationsSQL = "SELECT * FROM classification WHERE status=0 ORDER BY classificationID DESC";
	$archivedClassificationsQuery = mysqli_query($dbconnect,$archivedClassificationsSQL);
	$archivedClassifications = mysqli_fetch_assoc($archivedClassificationsQuery);
	$rows = mysqli_num_rows($archivedClassificationsQuery);
?>
<table class="table table-hover table-bordered">
		<tr>
			<th width="30%">Classification ID</th>
			<th width="60%">Classification</th>
			<th width="10%"> </th>
		</tr>
		<?php
			if($rows==0) {
				echo "<tr><td colspan='3'><center><h4>There were no archived classifications.</h4></center></td></tr>";
			} else if($rows>=1) {
				do {
		?>
				<tr>
					<td><?php echo $archivedClassifications['classificationID'];?></td>
					<td><?php echo $archivedClassifications['classification'];?></td>
					<td>
						<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedClassifications['classificationID'];?>" data-toggle="modal" data-target="#restoreclassification">
							<span class="glyphicon glyphicon-refresh"> </span>
						</button>
						<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedClassifications['classificationID'];?>" data-toggle="modal" data-target="#permanentdeleteclassification">
							<span class="glyphicon glyphicon-trash"> </span>
						</button>
					</td>
				</tr>
		<?php
				} while($archivedClassifications = mysqli_fetch_assoc($archivedClassificationsQuery));
			}
		?>
</table>
<form method="POST" action="pdfarchivedclassifications.php" target="_blank" class="form-inline">
	<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
	<input type="hidden" name="query" value="<?php echo $archivedClassificationsSQL;?>">
</form>
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton", function(){
		var classificationID = $(this).data("id");
		$(".confirmrestoreclassification").data("id", classificationID);
	});

	$(".confirmrestoreclassification").click(function(){
		var classificationID = $(this).data("id");
		$.ajax({
			url:"restoreclassification.php",
			method:"POST",
			data:{classificationID:classificationID},
			success:function(data) {
				$("#restoreclassification").modal("hide");
				$(".classifications").html(data);
			}
		});
	});

	$("#permanentdeleteclassification").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>
<?php
}
?>