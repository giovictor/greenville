<div class="admincontainer">
	<a href="?page=archvsc" class="btn btn-success btn-sm button">
		View All Archived Classifications
	</a>
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
		if(isset($_GET['archivedcsearch'])) {
			$archivedcsearch = $_GET['archivedcsearch'];
			$classificationperpages = 10;
			$totalarchivedclassificationSQL = "SELECT * FROM classification WHERE status=0 AND classification LIKE '%$archivedcsearch%' ORDER BY classificationID DESC";
			$totalarchivedvclassificationQuery = mysqli_query($dbconnect, $totalarchivedclassificationSQL);
			$rows = mysqli_num_rows($totalarchivedvclassificationQuery);

			$numberofpages = ceil($rows/$classificationperpages);
			if(!isset($_GET['cpage'])) {
				$page = 1;
			} else {
				$page = $_GET['cpage'];
				if($page < 1) {
					$page = 1;
				} else if($page > $numberofpages) {
					$page = $numberofpages;
				} else if(!is_numeric($page)) {
					$page = 1;
				} else {
					$page = $_GET['cpage'];
				}
			}

			$firstresult = ($page - 1) * $classificationperpages;

			$archivedclassificationSQL = "SELECT * FROM classification WHERE status=0 AND classification LIKE '%$archivedcsearch%' ORDER BY classificationID DESC LIMIT $firstresult, $classificationperpages";
			$archivedclassificationQuery = mysqli_query($dbconnect, $archivedclassificationSQL);
			$archivedclassification = mysqli_fetch_assoc($archivedclassificationQuery);
	?>
	<div class="reportpdf">
		<form id="printpdf" target="_blank" action="pdfarchivedclassifications.php" method="POST">
			<input type="hidden" name="query" value="<?php echo $totalarchivedclassificationSQL;?>">
			<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
		</form>
	</div>
	<div class="classifications">
		<table class="table table-hover table-bordered">
			<tr>
				<th width="30%">Classification ID</th>
				<th width="60%">Classification</th>
				<th width="10%"> </th>
			</tr>
			<?php
				if($rows==0) {
					echo "<tr><td colspan='3'><center><h4>No results found.</h4></center></td></tr>";
				} else if($rows>=1) {
					do {
			?>
					<tr>
						<td><?php echo $archivedclassification['classificationID'];?></td>
						<td><?php echo $archivedclassification['classification'];?></td>
						<td>
							<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedclassification['classificationID'];?>" data-toggle="modal" data-target="#restoreclassification">
								<span class="glyphicon glyphicon-refresh"> </span>
							</button>
							<!--<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedclassification['classificationID'];?>" data-toggle="modal" data-target="#permanentdeleteclassification">
								<span class="glyphicon glyphicon-trash"> </span>
							</button>-->
						</td>
					</tr>
			<?php
					} while($archivedclassification = mysqli_fetch_assoc($archivedclassificationQuery));
				}
			?>
		</table>
	</div>
	<form id="pagination-data">
		<input type="hidden" name="classificationperpages" id="classificationperpages" value="<?php echo $classificationperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
	<form id="data">
		<input type="hidden" name="keyword" id="keyword" value="<?php echo $archivedcsearch;?>">
	</form>
	<?php
		$pagination = '';
		if($numberofpages > 1) {
	?>
			<p style='margin-top:20px;'>Showing <?php echo $rows;?> results</p>
			<p>Page: <?php echo $page;?> of <?php echo $numberofpages;?></p>
	<?php
			if($page > 1) {
				$previous = $page - 1;
				$pagination .= '<a href="index.php?archivedcsearch='.$archivedcsearch.'&cpage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="index.php?archivedcsearch='.$archivedcsearch.'&cpage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}
			
			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="index.php?archivedcsearch='.$archivedcsearch.'&cpage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="index.php?archivedcsearch='.$archivedcsearch.'&cpage='.$next.'">Next</a>&nbsp;';	
			}
	?>
			<div class="pagination"><?php echo $pagination;?></div>
	<?php
		}
	?>
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
});
</script>
<?php
} 
?>