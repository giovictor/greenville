<title>Archived Authors</title>
<div class="admincontainer">
	<div class="authorsearch">
		<form method="GET" class="form-inline" id="archivedasearchform">
			<div class="form-group">
				<label>Search:</label>
				<input type="text" name="archivedasearch" id="archivedasearchbox" class="form-control">
			</div>
			<button id="archivedsearchauthor" class="btn btn-success btn-sm">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</form>
	</div>
	<h4>Archived Authors</h4>
	<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
		require "dbconnect.php";
		$authorsperpages = 10;
		$totalarchivedauthorSQL = "SELECT * FROM author WHERE status=0 ORDER BY authorID DESC";
		$totalarchivedauthorQuery = mysqli_query($dbconnect, $totalarchivedauthorSQL);
		$rows = mysqli_num_rows($totalarchivedauthorQuery);

		$numberofpages = ceil($rows/$authorsperpages);

		if(!isset($_GET['apage'])) {
			$page = 1;
		} else {
			$page = $_GET['apage'];
			if($page < 1) {
				$page = 1;
			} else if($page > $numberofpages) {
				$page = $numberofpages;
			} else if(!is_numeric($page)) {
				$page = 1;
			} else {
				$page = $_GET['apage'];
			}
		}

		$firstresult = ($page - 1) * $authorsperpages;

		$archivedauthorSQL = "SELECT * FROM author WHERE status=0 ORDER BY authorID DESC LIMIT $firstresult, $authorsperpages";
		$archivedauthorQuery = mysqli_query($dbconnect, $archivedauthorSQL);
		$archivedauthor = mysqli_fetch_assoc($archivedauthorQuery);
	?>
	<div class="reportpdf">
		<form id="printpdf" target="_blank" action="pdfarchivedauthors.php" method="POST">
			<input type="hidden" name="query" value="<?php echo $totalarchivedauthorSQL;?>">
			<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
		</form>
	</div>
	<div class="authors">
		<table class="table table-hover table-bordered">
			<tr>
				<th width="30%">Author ID</th>
				<th width="60%">Author</th>
				<th width="10%"> </th>
			</tr>
			<?php
				if($rows==0) {
					echo "<tr><td colspan='3'><center><h4>There were no archived authors.</h4></center></td></tr>";
				} else if($rows>=1) {
					do {
			?>
					<tr>
						<td><?php echo $archivedauthor['authorID'];?></td>
						<td><?php echo $archivedauthor['author'];?></td>
						<td>
							<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedauthor['authorID'];?>" data-toggle="modal" data-target="#restoreauthor">
								<span class="glyphicon glyphicon-refresh"> </span>
							</button>
							<!--<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedauthor['authorID'];?>" data-toggle="modal" data-target="#permanentdeleteauthor">
								<span class="glyphicon glyphicon-trash"> </span>
							</button>-->
						</td>
					</tr>
			<?php
					} while($archivedauthor = mysqli_fetch_assoc($archivedauthorQuery));
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
				$pagination .= '<a href="?page=archvsa&apage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="?page=archvsa&apage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}
			
			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="?page=archvsa&apage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="?page=archvsa&apage='.$next.'">Next</a>&nbsp;';	
			}
	?>
			<div class="pagination"><?php echo $pagination;?></div>
	<?php
		}
	?>
	<form id="pagination_data">
		<input type="hidden" name="authorsperpages" id="authorsperpages" value="<?php echo $authorsperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
</div>
<script>
$(document).ready(function(){
	$("#archivedasearchform").submit(function(e){
		var searchbox = $("#archivedasearchbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		}
	});

	$(document).on("click",".restorebutton",function(){
		var authorID = $(this).data("id");
		$(".confirmrestoreauthor").data("id",authorID);
	});

	$(".confirmrestoreauthor").click(function(){
		var authorID = $(this).data("id");
		var authorsperpages = $("#authorsperpages").val();
		var firstresult = $("#firstresult").val();
		$.ajax({
			url:"restoreauthor.php",
			method:"POST",
			data:{authorID:authorID,authorsperpages:authorsperpages, firstresult:firstresult},
			success:function(data) {
				$("#restoreauthor").modal("hide");
				$(".authors").html(data);
			}
		});
	});
});
</script>