<?php
require "dbconnect.php";
if(isset($_POST['borrowcartID']) && isset($_POST['idnumber'])) {
	$borrowcartID = $_POST['borrowcartID'];
	$idnumber = $_POST['idnumber'];
	$deletefromborrowcartSQL = "DELETE FROM borrowcart WHERE borrowcartID='$borrowcartID'";
	$deletefromborrowcart = mysqli_query($dbconnect, $deletefromborrowcartSQL);

		$borrowcartSQL = "SELECT borrowcartID, book.accession_no, booktitle, barcode, callnumber FROM borrowcart JOIN book ON book.accession_no=borrowcart.accession_no WHERE IDNumber='$idnumber'";
		$borrowcartQuery = mysqli_query($dbconnect, $borrowcartSQL);
		$borrowcart = mysqli_fetch_assoc($borrowcartQuery);
		$rows = mysqli_num_rows($borrowcartQuery);

		if($rows>=1) {
?>
<table class="table table-hover" id="booktable">
	<tr>
		<th>Accession Number</th>
		<th>Barcode</th>
		<th>Title</th>
		<th>Call Number</th>
		<th> </th>
	</tr>
	<?php
		do {
	?>
		<tr>
			<td class="accnumcol"><?php echo $borrowcart['accession_no'];?></td>
			<td><?php echo $borrowcart['barcode'];?></td>
			<td><?php echo $borrowcart['booktitle'];?></td>
			<td><?php echo $borrowcart['callnumber'];?></td>
			<td>
				<button data-id="<?php echo $borrowcart['borrowcartID']; ?>" class="btn btn-danger btn-sm deleteborrowcart" data-toggle="modal" data-target="#deletefromborrowcart"><span class="glyphicon glyphicon-remove"></span></button>
			</td>
		</tr>
	<?php
		} while($borrowcart = mysqli_fetch_assoc($borrowcartQuery));
	?>
</table>
<button id="borrowbutton" class="btn btn-success btn-md">Borrow <span class="glyphicon glyphicon-check"></span></button>
<form id="data">
	<input type="hidden" name="idnumber" id="idnumber" value="<?php echo $idnumber; ?>">
</form>
<script>
$(document).ready(function(){
	$(document).on("click",".deleteborrowcart",function(){
		var borrowcartID = $(this).data("id");
		$(".confirmdeletefromborrowcart").data("id",borrowcartID);
	});

	$(".confirmdeletefromborrowcart").click(function(){
		var borrowcartID = $(this).data("id");
		var idnumber = $("#idnumber").val();
		$.ajax({
			url:"deleteborrowcart.php",
			method:"POST",
			data:{borrowcartID:borrowcartID, idnumber:idnumber},
			success:function(data) {
				$("#deletefromborrowcart").modal("hide");
				$(".cart").html(data);
			}
		});
	});

	$("#borrowbutton").click(function(){
		$(".accnumcol").each(function(){
			var bookid = $(this).html();
			var idnumber = $("#idnumber").val();
			$.ajax({
				url:"borrowbook.php",
				method:"POST",
				data:{bookid:bookid, idnumber:idnumber},
				beforeSend:function() {
					$("#borrowbutton").html("Borrowing...");
				},
				success:function(data) {
					if(data=="Limit") {
						$("#limitborrow").modal("show");
						$("#borrowbutton").html("Borrow <span class='glyphicon glyphicon-check'></span>");
					} else {
						$(".cart").html(data);
						$("#borrowbutton").html("Borrow <span class='glyphicon glyphicon-check'></span>");
						$("#showreservations").hide();
					}
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