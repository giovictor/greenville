<title>Manage Classifications</title>
<div class="admincontainer">
	<div class="classificationform">
	<h4>Manage Classifications</h4>
		<form id="cform" class="form-inline">
			<div class="form-group">
				<label for="classification">Classification: </label>
				<input type="text" name="classification" id="classification" class="form-control">
			</div>
			<button id="addclassification" class="btn btn-success btn-sm">Add Classification</button>
		</form>
	</div>
	<div class="classificationsearch">
		<form method="GET" class="form-inline" id="csearchform">
			<div class="form-group">
				<label>Search: </label>
				<input type="text" name="csearch" id="csearchbox" class="form-control">
			</div>
			<button id="searchclassification" class="btn btn-success btn-sm">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</form>
	</div>
	<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
		$classificationSQL = "SELECT * FROM classification WHERE status=1 ORDER BY classificationID DESC";
		$classificationQuery = mysqli_query($dbconnect, $classificationSQL);
		$classification = mysqli_fetch_assoc($classificationQuery);
	?>
	<div class="classifications">
		<table class="table table-hover table-bordered" id="ctable">
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
					<button class="btn btn-danger btn-sm deletebutton" title="This classification cannot be deleted due to foreign key constraint." disabled>
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
	<form method="POST" action="pdfclassifications.php" target="_blank" class="form-inline">
		<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
		<input type="hidden" name="query" value="<?php echo $classificationSQL;?>">
	</form>
</div>
<script>
$(document).ready(function(){
	$("#cform").submit(function(e){
		e.preventDefault();
		var classification = $("#classification").val();
		if(classification=="") {
			$("#emptyclassification").modal("show");
		} else {
			$.ajax({
				url:"addclassification.php",
				method:"POST",
				data:{classification:classification},
				beforeSend:function() {
					$("#addclassification").html("Adding...");
				},
				success:function(data) {
					$("#addclassification").html("Add Classification");
					$("#addmsg3").modal("show");
					$("#cform")[0].reset();
					$(".classifications").html(data);
				}
			});
		}
	});

	$("#csearchform").submit(function(e){
		var searchbox = $("#csearchbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		}
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