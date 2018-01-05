<title>Archived Classifications</title>
<div class="admincontainer">
	<div class="classificationsearch">
		<form method="GET" class="form-inline" id="archivedcsearchform">
			<div class="form-group">
				<label>Search: </label>
				<input type="text" name="archivedcsearch" id="archivedcsearchbox" class="form-control">
			</div>
			<button id="archivedsearchclassification" class="btn btn-success btn-sm">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</form>
	</div>
	<h4>Archived Classifications</h4>
	<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
		$archivedClassificationsSQL = "SELECT * FROM classification WHERE status=0 ORDER BY classificationID DESC";
		$archivedClassificationsQuery = mysqli_query($dbconnect,$archivedClassificationsSQL);
		$archivedClassifications = mysqli_fetch_assoc($archivedClassificationsQuery);
		$rows = mysqli_num_rows($archivedClassificationsQuery);
	?>
	<div class="classifications">
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
						<td><?php echo $archivedClassifications['classificationID'];?></td>
						<td><?php echo $archivedClassifications['classification'];?></td>
						<td>
							<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedClassifications['classificationID'];?>" data-toggle="modal" data-target="#restoreclassification">
								<span class="glyphicon glyphicon-refresh"> </span>
							</button>
							<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedClassifications['classificationID'];?>" data-toggle="modal" data-target="#permanentdeleteclassification">
								<span class="glyphicon glyphicon-trash"> </span>
							</button>
						</td>
					</tr>
			<?php
					} while($archivedClassifications = mysqli_fetch_assoc($archivedClassificationsQuery));
				}
			?>
		</table>
		<form method="POST" action="pdfarchivedclassifications.php" target="_blank" class="form-inline">
			<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
			<input type="hidden" name="query" value="<?php echo $archivedClassificationsSQL;?>">
		</form>
	</div>
</div>
<script>
$(document).ready(function(){
	$("#archivedcsearchform").submit(function(e){
		var searchbox = $("#archivedcsearchbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		}
	});

	$(document).on("click",".restorebutton", function(){
		var classificationID = $(this).data("id");
		$(".confirmrestoreclassification").data("id", classificationID);
	});

	$(".confirmrestoreclassification").click(function(){
		var classificationID = $(this).data("id");
		$.ajax({
			url:"restoreclassification.php",
			method:"POST",
			data:{classificationID:classificationID},
			success:function(data) {
				$("#restoreclassification").modal("hide");
				$(".classifications").html(data);
			}
		});
	});


	$("#permanentdeleteclassification").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>