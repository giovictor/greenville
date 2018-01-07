<?php
require "dbconnect.php";
if(isset($_POST['option']) && isset($_POST['classification'])) {
	$option = $_POST['option'];
	$classification = $_POST['classification'];
	$archivedbookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classificationID, classification.classification, publishingyear, ISBN, book.status, COUNT(DISTINCT book.accession_no) AS copies, price, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE classification.classificationID='$classification' AND book.status='Archived' GROUP BY $option ORDER BY book.accession_no DESC";


	$archivedbookQuery = mysqli_query($dbconnect, $archivedbookSQL);
	$archivedbook = mysqli_fetch_assoc($archivedbookQuery);
	$rows = mysqli_num_rows($archivedbookQuery);
?>
<table class='table table-hover table-bordered table-striped' id='booktable'>
	<tr>
		<?php
			if($option=="bookID") {
		?>
				<th>Title</th>
				<th>Authors</th>
				<th>Publication Details</th>
				<th>Price</th>
				<th>Copies</th>
				<th>Remarks</th>
				<th> </th>
		<?php
			} else if($option=="accession_no") {
		?>
				<th>Accession No.</th>
				<th>Title</th>
				<th>Authors</th>
				<th>Publication Details</th>
				<th>Remarks</th>
				<th> </th>
		<?php
			}
		?>
	</tr>
	<?php
		if($rows==0) {
			echo "<tr><td colspan='9'><center><h4>No results found.</h4></center></td></tr>";
		} else if($rows>=1) {
			do {
	?>		
				<tr>
					<?php
						if($option=="bookID") {
					?>
							<td><?php echo $archivedbook['booktitle'];?></td>
							<td><?php echo $archivedbook['authors'];?></td>
							<td><?php echo $archivedbook['publisher']." c".$archivedbook['publishingyear'];?></td>
							<td><?php echo $archivedbook['price'];?></td>
							<td><?php echo $archivedbook['copies'];?></td>
							<td><?php echo $archivedbook['bookcondition'];?></td>
							<td>
								<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedbook['bookID'];?>" data-toggle="modal" data-target="#restorebook">
									<span class="glyphicon glyphicon-refresh"> </span>
								</button>
								<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedbook['bookID'];?>" data-toggle="modal" data-target="#permanentdeletebook">
									<span class="glyphicon glyphicon-trash"> </span>
								</button>
							</td>
					<?php
						} else if($option=="accession_no") {
					?>
							<td><?php echo $archivedbook['accession_no'];?></td>
							<td><?php echo $archivedbook['booktitle'];?></td>
							<td><?php echo $archivedbook['authors'];?></td>
							<td><?php echo $archivedbook['publisher']." c".$archivedbook['publishingyear'];?></td>
							<td><?php echo $archivedbook['bookcondition'];?></td>
							<td>
								<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedbook['accession_no'];?>" data-toggle="modal" data-target="#restorebook">
									<span class="glyphicon glyphicon-refresh"> </span>
								</button>
								<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedbook['accession_no'];?>" data-toggle="modal" data-target="#permanentdeletebook">
									<span class="glyphicon glyphicon-trash"> </span>
								</button>
							</td>

					<?php
						}
					?>
				</tr>
	<?php
			} while($archivedbook = mysqli_fetch_assoc($archivedbookQuery));
		}
	?>
</table>
<?php
	if($option=="bookID") {
?>
<form id="printpdf" target="_blank" action="pdfarchivedbookbytitle.php" method="POST" class="form-inline">
	<input class="btn btn-success btn-sm" id="button" type="submit" name="createpdf" value="Print PDF">
	<input type="hidden" name="query" value="<?php echo $archivedbookSQL;?>">
</form>
<?php
} else if($option=="accession_no") {
?>
<form id="printpdf" target="_blank" action="pdfarchivedbookbycopy.php" method="POST" class="form-inline">
	<input class="btn btn-success btn-sm" id="button" type="submit" name="createpdf" value="Print PDF">
	<input type="hidden" name="query" value="<?php echo $archivedbookSQL;?>">
</form>
<?php
}
?>
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton",function(){
		var bookid = $(this).data("id");
		$(".confirmrestorebook").data("id",bookid);
	});

	$(".confirmrestorebook").click(function(){
		var bookid = $(this).data("id");
		var option = $("#bookgroupby").val();
		var classification = $("#classification").val();
		$.ajax({
			url:"restorebook.php",
			method:"POST",
			data:{bookid:bookid, option:option, classification:classification},
			success:function(data) {
				$("#restorebook").modal("hide");
				$("#bookdisplay").html(data);
			}
		});
	});


	$("#permanentdeletebook").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>
<?php
}
?>