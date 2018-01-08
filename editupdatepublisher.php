<?php 
require "dbconnect.php";
if(isset($_GET['publisherID'])) {
	$publisherID = $_GET['publisherID'];
	$publishereditSQL = "SELECT * FROM publisher WHERE publisherID='$publisherID'";
	$publishereditQuery = mysqli_query($dbconnect, $publishereditSQL);
	$publisheredit = mysqli_fetch_assoc($publishereditQuery);
}
?>
<div class="admincontainer">
	<div class="publishers">
		<div class="publisherform">
			<h4>Manage publishers</h4>
			<a href="?page=publishers" style="float:right;" class="btn btn-success btn-sm button">
				Back To Publishers
			</a>
			<form id="pform" class="form-inline">
				<div class="form-group">
					<label for="publisher">Publisher: </label>
					<input type="text" name="publisher" id="publisher" class="form-control" value="<?php echo $publisheredit['publisher'];?>">
					<input type="hidden" name="publisherID" id="publisherID" value="<?php echo $publisherID;?>">
				</div>
				<button id="editpublisher" class="btn btn-success btn-sm">Update Publisher</button>
			</form>
		</div>
	<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
		$publisherSQL = "SELECT * FROM publisher WHERE status=1 AND publisherID='$publisherID' ORDER BY publisherID DESC";
		$publisherQuery = mysqli_query($dbconnect, $publisherSQL);
		$publisher = mysqli_fetch_assoc($publisherQuery);

	?>
		<table class="table table-hover table-bordered">
			<tr>
				<th width="30%">Publisher ID</th>
				<th width="62%">Publisher</th>
			</tr>
		<?php
		do {
		?>
			<tr>
				<td><?php echo $publisherID = $publisher['publisherID'];?></td>
				<td><?php echo $publisher['publisher'];?></td>
			</tr>
		<?php	
		} while($publisher = mysqli_fetch_assoc($publisherQuery));
		?>
		</table>
	</div>
</div>
<script>
$(document).ready(function(){
	$("#pform").submit(function(e){
		e.preventDefault();
		var publisher = $("#publisher").val();
		var publisherID = $("#publisherID").val();
		$.ajax({
			url:"editpublisher.php",
			method:"POST",
			data:{publisher:publisher, publisherID:publisherID},
			beforeSend:function() {
				$("#editpublisher").html("Updating...");
			},
			success:function(data) {
				$("#editpublisher").html("Update Publisher");
				$("#publisher").val("");
				$("#editmsg3").modal("show");
				$(".publishers").html(data);
			}
		});
	});
});
</script>