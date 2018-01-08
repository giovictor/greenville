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
			</tr>
		<?php
		do {
		?>
			<tr>
				<td><?php echo $classificationID = $classification['classificationID'];?></td>
				<td><?php echo $classification['classification'];?></td>
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
});
</script>