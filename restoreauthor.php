<?php
require "dbconnect.php";
if(isset($_POST['authorID']) && isset($_POST['firstresult']) && isset($_POST['authorsperpages'])) {
	$authorID = $_POST['authorID'];
	$restoreauthorSQL = "UPDATE author SET status=1 WHERE authorID='$authorID'";
	$restoreauthor = mysqli_query($dbconnect, $restoreauthorSQL);

	$authorsperpages = $_POST['authorsperpages'];
	$firstresult = $_POST['firstresult'];

	if(isset($_POST['keyword'])) {
		$keyword = $_POST['keyword'];
		$archivedauthorSQL = "SELECT * FROM author WHERE status=0 AND author LIKE '%$keyword%' ORDER BY authorID DESC LIMIT $firstresult, $authorsperpages";
	} else {
		$archivedauthorSQL = "SELECT * FROM author WHERE status=0 ORDER BY authorID DESC LIMIT $firstresult, $authorsperpages";
	}
	$archivedauthorQuery = mysqli_query($dbconnect,$archivedauthorSQL);
	$archivedauthor = mysqli_fetch_assoc($archivedauthorQuery);
	$rows = mysqli_num_rows($archivedauthorQuery);
?>
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
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton",function(){
		var authorID = $(this).data("id");
		$(".confirmrestoreauthor").data("id",authorID);
	});

	<?php
		if(isset($_POST['keyword'])) {
	?>
			$(".confirmrestoreauthor").click(function(){
				var authorID = $(this).data("id");
				var authorsperpages = $("#authorsperpages").val();
				var firstresult = $("#firstresult").val();
				var keyword = $("#keyword").val();
				$.ajax({
					url:"restoreauthor.php",
					method:"POST",
					data:{authorID:authorID, authorsperpages:authorsperpages, firstresult:firstresult, keyword:keyword},
					success:function(data) {
						$("#restoreauthor").modal("hide");
						$(".authors").html(data);
					}
				});
			});
	<?php
		} else {
	?>
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
	<?php
		}
	?>

	
});
</script>
<?php
}
?>