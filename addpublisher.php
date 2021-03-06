<?php
require "dbconnect.php";
if(isset($_POST['publisher'])  && isset($_POST['publishersperpages']) && isset($_POST['firstresult'])) {
	$publisher = $_POST['publisher'];
	$addpublisherSQL = "INSERT INTO publisher(publisher) VALUES('$publisher')";
	$addpublisherQuery = mysqli_query($dbconnect, $addpublisherSQL);

	$publishersperpages = $_POST['publishersperpages'];
	$firstresult = $_POST['firstresult'];

	$publisherSQL = "SELECT * FROM publisher WHERE status=1 ORDER BY publisherID DESC LIMIT $firstresult, $publishersperpages";
	$publisherQuery = mysqli_query($dbconnect, $publisherSQL);
	$publisher = mysqli_fetch_assoc($publisherQuery);
?>
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
<script>
$(document).ready(function(){
	$(document).on("click",".deletebutton", function(){
		var publisherID = $(this).data("id");
		$(".confirmdeletepublisher").data("id",publisherID);
	});

	$(".confirmdeletepublisher").click(function(){
		var publisherID = $(this).data("id");
		var publishersperpages = $("#publishersperpages").val();
		var firstresult = $("#firstresult").val();
		$.ajax({
			url:"deletepublisher.php",
			method:"POST",
			data:{publisherID:publisherID, publishersperpages:publishersperpages, firstresult:firstresult},
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