<title>Publishers</title>
<div class="admincontainer">
	<div class="publisherform">
	<h4>Publishers</h4>
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
		$publishersperpages = 10;
		$totalpublisherSQL = "SELECT * FROM publisher WHERE status=1 ORDER BY publisherID DESC";
		$totalpublisherQuery = mysqli_query($dbconnect, $totalpublisherSQL);
		$rows = mysqli_num_rows($totalpublisherQuery);

		$numberofpages = ceil($rows/$publishersperpages);

		if(!isset($_GET['ppage'])) {
			$page = 1;
		} else {
			$page = $_GET['ppage'];
		}

		$firstresult = ($page - 1) * $publishersperpages;

		$publisherSQL = "SELECT * FROM publisher WHERE status=1 ORDER BY publisherID DESC LIMIT $firstresult, $publishersperpages";
		$publisherQuery = mysqli_query($dbconnect, $publisherSQL);
		$publisher = mysqli_fetch_assoc($publisherQuery);

	?>
	<div class="reportpdf">
		<form id="printpdf" target="_blank" action="pdfpublishers.php" method="POST">
			<input type="hidden" name="query" value="<?php echo $totalpublisherSQL;?>">
			<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
		</form>
	</div>
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
	<p>Page: <?php echo $page; ?> of <?php echo $numberofpages;?></p>
	<?php
		$pagination = '';
		if($numberofpages > 1) {
			if($page > 1) {
				$previous = $page - 1;
				$pagination .= '<a href="?page=publishers&ppage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="?page=publishers&ppage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}
			
			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="?page=publishers&ppage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="?page=publishers&ppage='.$next.'">Next</a>&nbsp;';	
			}
	?>
			<div class="pagination"><?php echo $pagination;?></div>
	<?php
		}
	?>

	<form id="pagination_data">
		<input type="hidden" name="publishersperpages" id="publishersperpages" value="<?php echo $publishersperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
</div>
<script>
$(document).ready(function(){
	$("#pform").submit(function(e){
		e.preventDefault();
		var publisher = $("#publisher").val();
		var publishersperpages = $("#publishersperpages").val();
		var firstresult = $("#firstresult").val();
		if(publisher=="") {
			$("#emptypublisher").modal("show");
		} else {
			$.ajax({
				url:"addpublisher.php",
				method:"POST",
				data:{publisher:publisher, publishersperpages:publishersperpages, firstresult:firstresult},
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