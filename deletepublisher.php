<?php
require "dbconnect.php";
if(isset($_POST['publisherID'])) {
	$publisherID = $_POST['publisherID'];
	$archivepublisherSQL = "UPDATE publisher SET status=0 WHERE publisherID='$publisherID'";
	$archivepublisher = mysqli_query($dbconnect, $archivepublisherSQL);

	$publisherSQL = "SELECT * FROM publisher WHERE status=1 ORDER BY publisherID DESC";
	$publisherQuery = mysqli_query($dbconnect, $publisherSQL);
	$publisher = mysqli_fetch_assoc($publisherQuery);
?>
<table class="table table-hover table-bordered" id="ptable">
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
<script>
$(document).ready(function(){
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
<?php
}
?>