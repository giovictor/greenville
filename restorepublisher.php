<?php
require "dbconnect.php";
if(isset($_POST['publisherID']) && isset($_POST['publishersperpages']) && isset($_POST['firstresult'])) {
	$publisherID = $_POST['publisherID'];
	$restorepublisherSQL = "UPDATE publisher SET status=1 WHERE publisherID='$publisherID'";
	$restorepublisher = mysqli_query($dbconnect, $restorepublisherSQL);

	$publishersperpages = $_POST['publishersperpages'];
	$firstresult = $_POST['firstresult'];

	if(isset($_POST['keyword'])) {
		$keyword = $_POST['keyword'];
		$archivedpublisherSQL = "SELECT * FROM publisher WHERE status=0 AND publisher LIKE '%$keyword%' ORDER BY publisherID DESC LIMIT $firstresult, $publishersperpages";
	} else {
		$archivedpublisherSQL = "SELECT * FROM publisher WHERE status=0 ORDER BY publisherID DESC LIMIT $firstresult, $publishersperpages";
	}

	$archivedpublisherQuery = mysqli_query($dbconnect, $archivedpublisherSQL);
	$archivedpublisher = mysqli_fetch_assoc($archivedpublisherQuery);
	$rows = mysqli_num_rows($archivedpublisherQuery);
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
					<td><?php echo $archivedpublisher['publisherID'];?></td>
					<td><?php echo $archivedpublisher['publisher'];?></td>
					<td>
						<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedpublisher['publisherID'];?>" data-toggle="modal" data-target="#restorepublisher">
							<span class="glyphicon glyphicon-refresh"> </span>
						</button>
						<!--<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedpublisher['publisherID'];?>" data-toggle="modal" data-target="#permanentdeletepublisher">
							<span class="glyphicon glyphicon-trash"> </span>
						</button>-->
					</td>
				</tr>
		<?php
				} while($archivedpublisher = mysqli_fetch_assoc($archivedpublisherQuery));
			}
		?>
</table>
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton",function(){
		var publisherID = $(this).data("id");
		$(".confirmrestorepublisher").data("id",publisherID);
	});

	<?php
		if(isset($_POST['keyword'])) {
	?>	
				$(".confirmrestorepublisher").click(function(){
					var publisherID = $(this).data("id");
					var publishersperpages = $("#publishersperpages").val();
					var firstresult = $("#firstresult").val();
					var keyword = $("#keyword").val();
					$.ajax({
						url:"restorepublisher.php",
						method:"POST",
						data:{publisherID:publisherID, publishersperpages:publishersperpages, firstresult:firstresult, keyword:keyword},
						success:function(data) {
							$("#restorepublisher").modal("hide");
							$(".publishers").html(data);
						}
					});
				});
	<?php
		} else {
	?>
				$(".confirmrestorepublisher").click(function(){
					var publisherID = $(this).data("id");
					var publishersperpages = $("#publishersperpages").val();
					var firstresult = $("#firstresult").val();
					$.ajax({
						url:"restorepublisher.php",
						method:"POST",
						data:{publisherID:publisherID, publishersperpages:publishersperpages, firstresult:firstresult},
						success:function(data) {
							$("#restorepublisher").modal("hide");
							$(".publishers").html(data);
						}
					});
				});
	<?php
		}
	?>
});
</script>
<?php
}
?>