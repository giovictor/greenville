<?php
require "dbconnect.php";
if(isset($_POST['option'])) {
	$option = $_POST['option'];

	$archivedbookSQL = "SELECT bookID, book.accession_no, callnumber, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher.publisher, publishingyear, classification.classification, COUNT(DISTINCT book.accession_no) AS copies, book.status, bookcondition FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status='Archived' GROUP BY $option ORDER BY accession_no DESC";
	$archivedbookQuery = mysqli_query($dbconnect, $archivedbookSQL);
	$archivedbook = mysqli_fetch_assoc($archivedbookQuery);

?>
<table class='table table-hover table-bordered table-striped' id='booktable'>
	<tr>
		<?php
			if($option=="bookID") {
		?>
				<th>Title</th>
				<th>Authors</th>
				<th>Publication Details</th>
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
		do {
	?>		
			<tr>
				<?php
					if($option=="bookID") {
				?>
						<td><?php echo $archivedbook['booktitle'];?></td>
						<td><?php echo $archivedbook['authors'];?></td>
						<td><?php echo $archivedbook['publisher']." c".$archivedbook['publishingyear'];?></td>
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
		$.ajax({
			url:"restorebook.php",
			method:"POST",
			data:{bookid:bookid, option:option},
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