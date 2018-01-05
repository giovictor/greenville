<title>Manage Publishers</title>
<div class="admincontainer">
	<div class="publisherform">
	<h4>Manage publishers</h4>
		<form id="pform" class="form-inline">
			<div class="form-group">
				<label for="publisher">Publisher: </label>
				<input type="text" name="publisher" id="publisher" class="form-control">
			</div>
			<button id="addpublisher" class="btn btn-success btn-sm">Add Publisher</button>
		</form>
	</div>
	<div class="publishersearch">
		<form method="GET" class="form-inline" id="psearchform">
			<div class="form-group">
				<label>Search: </label>
				<input type="text" name="psearch" id="psearchbox" class="form-control">
			</div>
			<button id="searchpublisher" class="btn btn-success btn-sm">
				<span class="glyphicon glyphicon-search"> </span>
			</button>
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
	</div>
	<form method="POST" action="pdfpublishers.php" target="_blank" class="form-inline">
		<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
		<input type="hidden" name="query" value="<?php echo $publisherSQL;?>">
	</form>
</div>
<script>
$(document).ready(function(){
	$("#pform").submit(function(e){
		e.preventDefault();
		var publisher = $("#publisher").val();
		if(publisher=="") {
			$("#emptypublisher").modal("show");
		} else {
			$.ajax({
				url:"addpublisher.php",
				method:"POST",
				data:{publisher:publisher},
				beforeSend:function() {
					$("#addpublisher").html("Adding...");
				},
				success:function(data) {
					$("#addpublisher").html("Add Publisher");
					$("#pform")[0].reset();
					$("#addmsg5").modal("show");
					$(".publishers").html(data);
				}
			});
		}
	});

	$("#psearchform").submit(function(e){
		var searchbox = $("#psearchbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		}
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