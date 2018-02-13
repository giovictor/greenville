<?php
require "dbconnect.php";
if(isset($_POST['classificationID']) && isset($_POST['classificationperpages']) && isset($_POST['firstresult'])) {
	$classificationID = $_POST['classificationID'];
	$restoreclassificationSQL = "UPDATE classification SET status=1 WHERE classificationID='$classificationID'";
	$restoreclassification = mysqli_query($dbconnect, $restoreclassificationSQL);

	$firstresult = $_POST['firstresult'];
	$classificationperpages = $_POST['classificationperpages'];

	if(isset($_POST['keyword'])) {
		$keyword = $_POST['keyword'];
		$archivedclassificationsSQL = "SELECT * FROM classification WHERE status=0 AND classification LIKE '%$keyword%' ORDER BY classificationID DESC LIMIT $firstresult, $classificationperpages";
	} else {
		$archivedclassificationsSQL = "SELECT * FROM classification WHERE status=0 ORDER BY classificationID DESC LIMIT $firstresult, $classificationperpages";
	}
	
	$archivedclassificationsQuery = mysqli_query($dbconnect,$archivedclassificationsSQL);
	$archivedclassifications = mysqli_fetch_assoc($archivedclassificationsQuery);
	$rows = mysqli_num_rows($archivedclassificationsQuery);
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
					<td><?php echo $archivedclassifications['classificationID'];?></td>
					<td><?php echo $archivedclassifications['classification'];?></td>
					<td>
						<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedclassifications['classificationID'];?>" data-toggle="modal" data-target="#restoreclassification">
							<span class="glyphicon glyphicon-refresh"> </span>
						</button>
						<!--<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedclassifications['classificationID'];?>" data-toggle="modal" data-target="#permanentdeleteclassification">
							<span class="glyphicon glyphicon-trash"> </span>
						</button>-->
					</td>
				</tr>
		<?php
				} while($archivedclassifications = mysqli_fetch_assoc($archivedclassificationsQuery));
			}
		?>
</table>
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton", function(){
		var classificationID = $(this).data("id");
		$(".confirmrestoreclassification").data("id", classificationID);
	});

	<?php
		if(isset($_POST['keyword'])) {
	?>
			$(".confirmrestoreclassification").click(function(){
				var classificationID = $(this).data("id");
				var classificationperpages = $("#classificationperpages").val();
				var firstresult = $("#firstresult").val();
				var keyword = $("#keyword").val();
				$.ajax({
					url:"restoreclassification.php",
					method:"POST",
					data:{classificationID:classificationID, classificationperpages:classificationperpages, firstresult:firstresult, keyword:keyword},
					success:function(data) {
						$("#restoreclassification").modal("hide");
						$(".classifications").html(data);
					}
				});
			});

	<?php
		} else {
	?>
			$(".confirmrestoreclassification").click(function(){
				var classificationID = $(this).data("id");
				var classificationperpages = $("#classificationperpages").val();
				var firstresult = $("#firstresult").val();
				$.ajax({
					url:"restoreclassification.php",
					method:"POST",
					data:{classificationID:classificationID, classificationperpages:classificationperpages, firstresult:firstresult},
					success:function(data) {
						$("#restoreclassification").modal("hide");
						$(".classifications").html(data);
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