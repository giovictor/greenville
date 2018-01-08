<div class="admincontainer">
	<a href="?page=publishers" class="btn btn-success btn-sm button">
		View All Publishers		
	</a>
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
	if(isset($_GET['psearch'])) {
		$keyword = $_GET['psearch'];
		$publishersperpages = 10;
		$totalpublisherSQL = "SELECT * FROM publisher WHERE status=1 AND publisher LIKE '%$keyword%' ORDER BY publisherID DESC";
		$totalpublisherQuery = mysqli_query($dbconnect, $totalpublisherSQL);
		$rows = mysqli_num_rows($totalpublisherQuery);

		$numberofpages = ceil($rows/$publishersperpages);

		if(!isset($_GET['ppage'])) {
			$page = 1;
		} else {
			$page = $_GET['ppage'];
		}

		$firstresult = ($page - 1) * $publishersperpages;

		$publisherSQL = "SELECT * FROM publisher WHERE status=1 AND publisher LIKE '%$keyword%' ORDER BY publisherID DESC LIMIT $firstresult, $publishersperpages";
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
		if($rows==0) {
			echo "<tr><td colspan='3'><center><h4>No results found.</h4></center></td></tr>";
		} else if($rows>=1) {
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
		}
		?>
		</table>
	</div>
	<form method="POST" action="pdfpublishers.php" target="_blank" class="form-inline">
		<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
		<input type="hidden" name="query" value="<?php echo $publisherSQL;?>">
	</form>
	<?php
		if($numberofpages > 1) {
	?>
			<ul class="pagination">
				<?php
					for($i=1;$i<=$numberofpages;$i++) {
				?>
						<li><a href="index.php?psearch=<?php echo $keyword;?>&apage=<?php echo $i;?>"><?php echo $i;?></a></li>
				<?php
					}
				?>
			</ul>
	<?php
		}
	?>
	<form id="pagination_data">
		<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword;?>">
		<input type="hidden" name="publishersperpages" id="publishersperpages" value="<?php echo $publishersperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
</div>
<script>
$(document).ready(function(){
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
		var keyword = $("#keyword").val();
		$.ajax({
			url:"deletepublisher.php",
			method:"POST",
			data:{publisherID:publisherID, publishersperpages:publishersperpages, firstresult:firstresult, keyword:keyword},
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