<title>Archived Publishers</title>
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
		$publishersperpages = 10;
		$totalarchivedpublisherSQL = "SELECT * FROM publisher WHERE status=0 ORDER BY publisherID DESC";
		$totalarchivedpublisherQuery = mysqli_query($dbconnect, $totalarchivedpublisherSQL);
		$rows = mysqli_num_rows($totalarchivedpublisherQuery);

		$numberofpages = ceil($rows/$publishersperpages);

		if(!isset($_GET['ppage'])) {
			$page = 1;
		} else {
			$page = $_GET['ppage'];
		}

		$firstresult = ($page - 1) * $publishersperpages;

		$archivedpublisherSQL = "SELECT * FROM publisher WHERE status=0 ORDER BY publisherID DESC LIMIT $firstresult, $publishersperpages";
		$archivedpublisherQuery = mysqli_query($dbconnect, $archivedpublisherSQL);
		$archivedpublisher = mysqli_fetch_assoc($archivedpublisherQuery);
	?>
	<div class="reportpdf">
		<form id="printpdf" target="_blank" action="pdfarchivedpublishers.php" method="POST">
			<input type="hidden" name="query" value="<?php echo $totalarchivedpublisherSQL;?>">
			<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
		</form>
	</div>
	<div class="publishers">
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
	</div>
	<?php
		$pagination = '';
		if($numberofpages > 1) {
	?>
			<p>Page: <?php echo $page; ?> of <?php echo $numberofpages;?></p>
	<?php
			if($page > 1) {
				$previous = $page - 1;
				$pagination .= '<a href="?page=archvsp&ppage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="?page=archvsp&ppage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}
			
			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="?page=archvsp&ppage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="?page=archvsp&ppage='.$next.'">Next</a>&nbsp;';	
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


	$("#permanentdeletepublisher").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});

});
</script>