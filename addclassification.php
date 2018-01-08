<?php
require "dbconnect.php";
if(isset($_POST['classification']) && isset($_POST['classificationperpages']) && isset($_POST['firstresult'])) {
	$classification = $_POST['classification'];
	$addclassificationSQL = "INSERT INTO classification(classification) VALUES('$classification')";
	$addclassificationQuery = mysqli_query($dbconnect, $addclassificationSQL);
	
	$classificationperpages = $_POST['classificationperpages'];
	$firstresult = $_POST['firstresult'];

	$classificationSQL = "SELECT * FROM classification WHERE status=1 ORDER BY classificationID DESC LIMIT $firstresult, $classificationperpages";
	$classificationQuery = mysqli_query($dbconnect, $classificationSQL);
	$classification = mysqli_fetch_assoc($classificationQuery);
?>
<table class="table table-hover table-bordered">
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
		var classificationperpages = $("#classificationperpages").val();
		var firstresult = $("#firstresult").val();
		$.ajax({
			url:"deleteclassification.php",
			method:"POST",
			data:{classificationID:classificationID, classificationperpages:classificationperpages, firstresult:firstresult},
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
