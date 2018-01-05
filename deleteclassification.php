<?php
require "dbconnect.php";
if(isset($_POST['classificationID'])) {
	$classificationID = $_POST['classificationID'];
	$archiveclassificationSQL = "UPDATE classification SET status=0 WHERE classificationID='$classificationID'";
	$archiveclassification = mysqli_query($dbconnect, $archiveclassificationSQL);

	$classificationSQL = "SELECT * FROM classification WHERE status=1 ORDER BY classificationID DESC";
	$classificationQuery = mysqli_query($dbconnect, $classificationSQL);
	$classification = mysqli_fetch_assoc($classificationQuery);
?>
<table class="table table-hover table-bordered" id="ctable">
		<tr>
			<th width="30%">Classification ID</th>
			<th width="62%">Classification</th>
			<th width="8%"> </th>
		</tr>
	<?php
	do {
	?>
		<tr>
			<td><?php echo $classificationID = $classification['classificationID'];?></td>
			<td><?php echo $classification['classification'];?></td>
			<td> 
				<a href="?page=editclassification&classificationID=<?php echo $classification['classificationID'];?>"  class="btn btn-success btn-sm" title="Edit classification.">
					<span class="glyphicon glyphicon-pencil"></span>
				</a>
			<?php
				$checkclassificationSQL = "SELECT COUNT(*) AS existing FROM book WHERE classificationID='$classificationID'";
				$checkclassificationQuery = mysqli_query($dbconnect, $checkclassificationSQL);
				$checkclassification = mysqli_fetch_assoc($checkclassificationQuery);
				if($checkclassification['existing']==0) {
			?>
					<button class="btn btn-danger btn-sm deletebutton" data-id="<?php echo $classification['classificationID'];?>" title="Delete classification." data-toggle="modal" data-target="#confirmdeleteclassification">
						<span class="glyphicon glyphicon-trash"></span>
					</button>
			<?php
				} else if($checkclassification['existing']>=1) {
			?>
				<button class="btn btn-danger btn-sm deletebutton" title="This classification cannot be deleted due to foreign key constraint." disabled>
						<span class="glyphicon glyphicon-trash"></span>
				</button>
			<?php
				}
			?>
			</td>
		</tr>
	<?php	
	} while($classification = mysqli_fetch_assoc($classificationQuery));
	?>
</table>
<script>
$(document).ready(function(){
	$(document).on("click", ".deletebutton", function(){
		var classificationID = $(this).data("id");
		$(".confirmdeleteclassification").data("id", classificationID);
	});

	$(".confirmdeleteclassification").click(function(){
		var classificationID = $(this).data("id");
		$.ajax({
			url:"deleteclassification.php",
			method:"POST",
			data:{classificationID:classificationID},
			success:function(data) {
				$("#confirmdeleteclassification").modal("hide");
				$(".classifications").html(data);
			}
		});
	});
});
</script>
<?php
}
?>