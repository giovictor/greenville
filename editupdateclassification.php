<?php
require "dbconnect.php";
if(isset($_GET['classificationID'])) {
	$classificationID = $_GET['classificationID'];
	$classificationeditSQL = "SELECT * FROM classification WHERE classificationID='$classificationID'";
	$classificationeditQuery = mysqli_query($dbconnect, $classificationeditSQL);
	$classificationedit = mysqli_fetch_assoc($classificationeditQuery);
}
?>
<div class="admincontainer">
	<div class="classifications">
		<div class="classificationform">
			<h4>Update Classification</h4>
			<a href="?page=classifications" style="float:right;" class="btn btn-success btn-sm button">
				View All Classifications 
			</a>
			<form id="cform" class="form-inline">
				<div class="form-group">
					<label for="classification">Classification: </label>
					<input type="text" name="classification" id="classification" class="form-control" value="<?php echo $classificationedit['classification'];?>">
					<input type="hidden" value="<?php echo $classificationID;?>" name="classificationID" id="classificationID">
				</div>
				<button id="editclassification" class="btn btn-success btn-sm">Update Classification</button>
			</form>
		</div>
	<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
		$classificationSQL = "SELECT * FROM classification WHERE status=1 AND classificationID='$classificationID' ORDER BY classificationID DESC";
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
					<button class="btn btn-danger btn-sm deletebutton"  title="This classification cannot be deleted due to foreign key constraint." disabled>
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
	</div>
</div>
<script>
$(document).ready(function(){
	$("#cform").submit(function(e){
		e.preventDefault();
		var classification = $("#classification").val();
		var classificationID = $("#classificationID").val();
		$.ajax({
			url:"editclassification.php",
			method:"POST",
			data:{classification:classification, classificationID:classificationID},
			beforeSend:function() {
				$("#editclassification").html("Updating...");
			},
			success:function(data) {
				$("#editclassification").html("Update Classification");
				$("#editmsg").modal("show");
				$("#classification").val("");
				$(".classifications").html(data);
			}
		});
	});

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