<div class="admincontainer">
	<a href="?page=classifications" class="btn btn-success btn-sm button">
		View All Classifications
	</a>
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

	if(isset($_GET['csearch'])) {
		$keyword = $_GET['csearch'];
		$classificationperpages = 10;
		$totalclassificationSQL = "SELECT * FROM classification WHERE status=1 AND classification LIKE '%$keyword%' ORDER BY classificationID DESC";
		$totalclassificationQuery = mysqli_query($dbconnect, $totalclassificationSQL);
		$rows = mysqli_num_rows($totalclassificationQuery);

		$numberofpages = ceil($rows/$classificationperpages);
		if(!isset($_GET['cpage'])) {
			$page = 1;
		} else {
			$page = $_GET['cpage'];
		}

		$firstresult = ($page - 1) * $classificationperpages;

		$classificationSQL = "SELECT * FROM classification WHERE status=1 AND classification LIKE '%$keyword%' ORDER BY classificationID DESC LIMIT $firstresult, $classificationperpages";
		$classificationQuery = mysqli_query($dbconnect, $classificationSQL);
		$classification = mysqli_fetch_assoc($classificationQuery);

	?>
	<div class="reportpdf">
		<form id="printpdf" target="_blank" action="pdfclassifications.php" method="POST">
			<input type="hidden" name="query" value="<?php echo $totalclassificationSQL;?>">
			<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
		</form>
	</div>
	<div class="classifications">
		<table class="table table-hover table-bordered" id="ctable">
			<tr>
				<th width="30%">Classification ID</th>
				<th width="62%">Classification</th>
				<th width="8%"> </th>
			</tr>
		<?php
		if($rows==0) {
			echo "<tr><td colspan='3'><center><h4>No results found.</h4></center></td></tr>";
		} else if($rows>=1) {
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
					<button class="btn btn-danger btn-sm deletebutton"  title="This classification cannot be deleted due to foreign key constraint." disabled>
							<span class="glyphicon glyphicon-trash"></span>
					</button>
				<?php
					}
				?>
				</td>
			</tr>
		<?php	
			} while($classification = mysqli_fetch_assoc($classificationQuery));
		}
		?>
		</table>
	</div>
	<?php
		if($numberofpages > 1) {
			$pagination = '';
	?>
			<p style='margin-top:20px;'>Showing <?php echo $rows;?> results</p>
			<p>Page: <?php echo $page;?> of <?php echo $numberofpages;?></p>
	<?php
		if($page > 1) {
			$previous = $page - 1;
			$pagination .= '<a href="index.php?csearch='.$keyword.'&cpage='.$previous.'">Previous</a>&nbsp;';

			for($i = $page - 3; $i < $page; $i++) {
				if($i > 0) {
					$pagination .= '<a href="index.php?csearch='.$keyword.'&cpage='.$i.'">'.$i.'</a>&nbsp;';
				}
			}
		}
		
		$pagination .= ''.$page.'&nbsp;';

		for($i = $page + 1; $i <= $numberofpages; $i++) {
			$pagination .= '<a href="index.php?csearch='.$keyword.'&cpage='.$i.'">'.$i.'</a>&nbsp;';
			if($i >= $page + 3) {
				break;
			}
		}

		if($page != $numberofpages) {
			$next = $page + 1;
			$pagination .= '<a href="index.php?csearch='.$keyword.'&cpage='.$next.'">Next</a>&nbsp;';	
		}
	?>
		<div class="pagination"><?php echo $pagination;?></div>
	<?php
		}
	?>
	<form id="pagination_data">
		<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword; ?>">
		<input type="hidden" name="classificationperpages" id="classificationperpages" value="<?php echo $classificationperpages; ?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult; ?>">
	</form>
</div>
<script>
$(document).ready(function(){
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
		var classificationperpages = $("#classificationperpages").val();
		var firstresult = $("#firstresult").val();
		var keyword = $("#keyword").val();
		$.ajax({
			url:"deleteclassification.php",
			method:"POST",
			data:{classificationID:classificationID, classificationperpages:classificationperpages, firstresult:firstresult, keyword:keyword},
			success:function(data) {
				$("#confirmdeleteclassification").modal("hide");
				$(".classifications").html(data);
			}
		});
	});

});
</script>
<?php
}
?>