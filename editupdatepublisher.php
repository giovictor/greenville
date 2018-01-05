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
	<a href="?page=publishers" style="margin-top:20px;" class="btn btn-success btn-md button">
		Back To Publishers
		<span class="glyphicon glyphicon-menu-hamburger"> </span>
	</a>
	<div class="publisherform">
	<h4>Manage publishers</h4>
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
		$publisherSQL = "SELECT * FROM publisher WHERE status=1 ORDER BY publisherID DESC";
		$publisherQuery = mysqli_query($dbconnect, $publisherSQL);
		$publisher = mysqli_fetch_assoc($publisherQuery);

	?>
	<div class="publishers">
		<table class="table table-hover table-bordered">
			<tr>
				<th width="30%">Publisher ID</th>
				<th width="62%">Publisher</th>
				<th width="8%"> </th>
			</tr>
		<?php
		do {
		?>
			<tr>
				<td><?php echo $publisherID = $publisher['publisherID'];?></td>
				<td><?php echo $publisher['publisher'];?></td>
				<td> 
					<a href="?page=editpublisher&publisherID=<?php echo $publisher['publisherID'];?>" class="btn btn-success btn-sm" title="Edit publisher.">
						<span class="glyphicon glyphicon-pencil"></span>
					</a>
				<?php
					$checkpublisherSQL = "SELECT COUNT(*) AS existing FROM book WHERE publisherID='$publisherID'";
					$checkpublisherQuery = mysqli_query($dbconnect, $checkpublisherSQL);
					$checkpublisher = mysqli_fetch_assoc($checkpublisherQuery);

					if($checkpublisher['existing']==0) {
				?>
						<button class="btn btn-danger btn-sm deletebutton" data-id="<?php echo $publisher['publisherID'];?>" title="Delete publisher." data-toggle="modal" data-target="#confirmdeletepublisher">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
				<?php
					} else if($checkpublisher['existing']>=1)  {
				?>
						<button class="btn btn-danger btn-sm deletebutton" title="This publisher cannot be deleted due to foreign key constraint." disabled>
							<span class="glyphicon glyphicon-trash"></span>
						</button>
				<?php
					}
				?>
				</td>
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

	$(document).on("click",".deletebutton", function(){
		var publisherID = $(this).data("id");
		$(".confirmdeletepublisher").data("id",publisherID);
	});

	$(".confirmdeletepublisher").click(function(){
		var publisherID = $(this).data("id");
		$.ajax({
			url:"deletepublisher.php",
			method:"POST",
			data:{publisherID:publisherID},
			success:function(data) {
				$("#confirmdeletepublisher").modal("hide");
				$(".publishers").html(data);
			}
		});
	});
});
</script>