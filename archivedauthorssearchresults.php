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
		if(isset($_GET['archivedasearch'])) {
			$archivedasearch = $_GET['archivedasearch'];
			$archivedAuthorsSQL = "SELECT * FROM author WHERE status=0 AND author LIKE '%$archivedasearch%' ORDER BY authorID DESC";
			$archivedAuthorsQuery = mysqli_query($dbconnect,$archivedAuthorsSQL);
			$archivedAuthors = mysqli_fetch_assoc($archivedAuthorsQuery);
			$rows = mysqli_num_rows($archivedAuthorsQuery);
	?>
	<div class="authors">
		<table class="table table-hover table-bordered">
			<tr>
				<th width="30%">Author ID</th>
				<th width="60%">Author</th>
				<th width="10%"> </th>
			</tr>
			<?php
				if($rows==0) {
					echo "<tr><td colspan='3'><center><h4>No results found.</h4></center></td></tr>";
				} else if($rows>=1) {
					do {
			?>
					<tr>
						<td><?php echo $archivedAuthors['authorID'];?></td>
						<td><?php echo $archivedAuthors['author'];?></td>
						<td>
							<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedAuthors['authorID'];?>" data-toggle="modal" data-target="#restoreauthor">
								<span class="glyphicon glyphicon-refresh"> </span>
							</button>
							<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedAuthors['authorID'];?>" data-toggle="modal" data-target="#permanentdeleteauthor">
								<span class="glyphicon glyphicon-trash"> </span>
							</button>
						</td>
					</tr>
			<?php
					} while($archivedAuthors = mysqli_fetch_assoc($archivedAuthorsQuery));
				}
			?>
		</table>
		<form method="POST" action="pdfarchivedauthors.php" target="_blank" class="form-inline">
			<input type="submit" name="createpdf" class="btn btn-success btn-sm" id="button" value="Print PDF">
			<input type="hidden" name="query" value="<?php echo $archivedAuthorsSQL;?>">
		</form>
	</div>
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
		$.ajax({
			url:"restoreauthor.php",
			method:"POST",
			data:{authorID:authorID},
			success:function(data) {
				$("#restoreauthor").modal("hide");
				$(".authors").html(data);
			}
		});
	});

	$("#permanentdeleteauthor").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>
<?php
}
?>