<?php
require "dbconnect.php";
if(isset($_POST['returncartID'])) {
	$returncartID = $_POST['returncartID'];
	$deletereturncartSQL = "DELETE FROM returncart WHERE returncartID='$returncartID'";
	$deletereturncart = mysqli_query($dbconnect, $deletereturncartSQL);

	$returncartSQL = "SELECT returncartID, borrower.IDNumber, lastname, firstname, mi, book.accession_no, booktitle, barcode, callnumber FROM returncart JOIN book ON book.accession_no=returncart.accession_no JOIN borrower ON borrower.IDNumber=returncart.IDNumber";
	$returncartQuery = mysqli_query($dbconnect, $returncartSQL);
	$returncart = mysqli_fetch_assoc($returncartQuery);
	$rows = mysqli_num_rows($returncartQuery);

	if($rows>=1) {
?>
<table class="table table-hover" id="booktable">
		<tr>
			<th>ID Number</th>
			<th>Borrower</th>
			<th>Accession Number</th>
			<th>Title</th>
			<th>Barcode</th>
			<th>Call Number</th>
			<th> </th>
		</tr>
		<?php
			do {
		?>
			<tr>
				<td class="idnumcol"><?php echo $returncart['IDNumber'];?></td>
				<td><?php echo $returncart['lastname'].", ".$returncart['firstname']." ".$returncart['mi'];;?></td>
				<td class="accnumcol"><?php echo $returncart['accession_no'];?></td>
				<td><?php echo $returncart['booktitle'];?></td>
				<td><?php echo $returncart['barcode'];?></td>
				<td><?php echo $returncart['callnumber'];?></td>
				<td><button class="btn btn-danger btn-sm deletereturncart" data-id="<?php echo $returncart['returncartID'];?>" data-toggle="modal" data-target="#deletefromreturncart"><span class="glyphicon glyphicon-remove"></span></button></td>
			</tr>
		<?php
			} while($returncart = mysqli_fetch_assoc($returncartQuery));
		?>
	</table>
	<button class="btn btn-success btn-md" id="returnbutton">Return <span class="glyphicon glyphicon-check"></span></button>
<script>
$(document).ready(function(){
	$(document).on("click",".deletereturncart",function(){
		var returncartID = $(this).data("id");
		$(".confirmdeletefromreturncart").data("id",returncartID);
	});

	$(".confirmdeletefromreturncart").click(function(){
		var returncartID = $(this).data("id");
		$.ajax({
			url:"deletereturncart.php",
			method:"POST",
			data:{returncartID:returncartID},
			success:function(data) {
				$("#deletefromreturncart").modal("hide");
				$(".cart").html(data);
			}
		});
	});

	$("#returnbutton").click(function(){
		$(".accnumcol").each(function(){
			var bookid = $(this).html();
			var idnumber = $(".idnumcol").html();
			$.ajax({
				url:"returnbook.php",
				method:"POST",
				data:{bookid:bookid, idnumber:idnumber},
				beforeSend:function() {
					$("#returnbutton").html("Returning...");
				},
				success:function(data) {
					$(".cart").html(data);
				}
			});
		});
	});
});
</script>

<?php
	}
}
?>