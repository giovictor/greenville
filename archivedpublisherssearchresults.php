<div class="admincontainer">
	<div class="publishersearch">
		<form method="GET" class="form-inline" id="archivedpsearchform">
			<div class="form-group">
				<label>Search: </label>
				<input type="text" name="archivedpsearch" id="archivedpsearchbox" class="form-control">
			</div>
			<button id="archivedsearchpublisher" class="btn btn-success btn-sm">
				<span class="glyphicon glyphicon-search"> </span>
			</button>
		</form>
	</div>
	<h4>Archived Publishers</h4>
	<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
		if(isset($_GET['archivedpsearch'])) {
			$archivedpsearch = $_GET['archivedpsearch'];
			$archivedPublishersSQL = "SELECT * FROM publisher WHERE status=0 AND publisher LIKE '%$archivedpsearch%' ORDER BY publisherID DESC";
			$archivedPublishersQuery = mysqli_query($dbconnect,$archivedPublishersSQL);
			$archivedPublishers = mysqli_fetch_assoc($archivedPublishersQuery);
			$rows = mysqli_num_rows($archivedPublishersQuery);
	?>
	<div class="publishers">
		<table class="table table-hover table-bordered">
			<tr>
				<th width="30%">Publisher ID</th>
				<th width="60%">Publisher</th>
				<th width="10%"> </th>
			</tr>
			<?php
				if($rows==0) {
					echo "<tr><td colspan='3'><center><h4>No results found.</h4></center></td></tr>";
				} else if($rows>=1) {
					do {
			?>
					<tr>
						<td><?php echo $archivedPublishers['publisherID'];?></td>
						<td><?php echo $archivedPublishers['publisher'];?></td>
						<td>
							<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedPublishers['publisherID'];?>" data-toggle="modal" data-target="#restorepublisher">
								<span class="glyphicon glyphicon-refresh"> </span>
							</button>
							<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedPublishers['publisherID'];?>" data-toggle="modal" data-target="#permanentdeletepublisher">
								<span class="glyphicon glyphicon-trash"> </span>
							</button>
						</td>
					</tr>
			<?php
					} while($archivedPublishers = mysqli_fetch_assoc($archivedPublishersQuery));
				}
			?>
		</table>
		<form method="POST" action="pdfarchivedpublishers.php" target="_blank" class="form-inline">
			<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
			<input type="hidden" name="query" value="<?php echo $archivedPublishersSQL;?>">
		</form>
	</div>
</div>
<script>
$(document).ready(function(){
	$("#archivedpsearchform").submit(function(e){
		var searchbox = $("#archivedpsearchbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		}
	});

	$(document).on("click",".restorebutton",function(){
		var publisherID = $(this).data("id");
		$(".confirmrestorepublisher").data("id",publisherID);
	});

	$(".confirmrestorepublisher").click(function(){
		var publisherID = $(this).data("id");
		$.ajax({
			url:"restorepublisher.php",
			method:"POST",
			data:{publisherID:publisherID},
			success:function(data) {
				$("#restorepublisher").modal("hide");
				$(".publishers").html(data);
			}
		});
	});


	$("#permanentdeletepublisher").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});

});
</script>
<?php
}
?>