<?php
require "dbconnect.php";
if(isset($_POST['bookid']) && isset($_POST['idnumber'])) {
	$bookid = $_POST['bookid'];
	$idnumber = $_POST['idnumber'];

		$bookSQL = "SELECT bookID, COUNT(*) AS rows, book.accession_no, booktitle, callnumber, book.status, barcode FROM book WHERE barcode='$bookid' OR book.accession_no='$bookid' AND status!='Archived'";
		$bookQuery = mysqli_query($dbconnect, $bookSQL);
		$book = mysqli_fetch_assoc($bookQuery);
		$rows = $book['rows'];
		$bookID = $book['bookID'];
		$status = $book['status'];

		$checkinputSQL = "SELECT * FROM book WHERE barcode='$bookid' OR accession_no='$bookid'";
		$checkinputQuery = mysqli_query($dbconnect, $checkinputSQL);
		$checkinput = mysqli_fetch_assoc($checkinputQuery);

		if($bookid==$checkinput['barcode']) {
			$bookid = $checkinput['accession_no'];
		} else {
			$bookid = $_POST['bookid'];
		}

		$date = date("Y-m-d");
		$rand = rand(1000000, 9999999);
		$borrowsessionID = md5($rand.$idnumber.$date);

		$checkreserveSQL = "SELECT * FROM reservation WHERE accession_no='$bookid' AND IDNumber='$idnumber' AND showstatus=1";
		$checkreserveQuery = mysqli_query($dbconnect, $checkreserveSQL);
		$checkreserve = mysqli_num_rows($checkreserveQuery);
	
		$checkborrowSQL = "SELECT COUNT(*) AS noofborrowedbooks FROM booklog WHERE IDNumber='$idnumber' AND datereturned IS NULL";
		$checkborrowQuery = mysqli_query($dbconnect, $checkborrowSQL);
		$checkborrow = mysqli_fetch_assoc($checkborrowQuery);

		$checkbookIDSQL = "SELECT COUNT(bookID) AS nooftitle FROM booklog JOIN book ON book.accession_no=booklog.accession_no WHERE bookID='$bookID' AND datereturned IS NULL AND IDNumber='$idnumber'";
		$checkbookIDQuery = mysqli_query($dbconnect, $checkbookIDSQL);
		$checkbookID = mysqli_fetch_assoc($checkbookIDQuery);

		$checkbookIDborrowcartSQL = "SELECT COUNT(bookID) AS nooftitle FROM borrowcart JOIN book ON book.accession_no=borrowcart.accession_no WHERE bookID='$bookID' AND IDNumber='$idnumber'";
		$checkbookIDborrowcartQuery = mysqli_query($dbconnect, $checkbookIDborrowcartSQL);
		$checkbookIDborrowcart = mysqli_fetch_assoc($checkbookIDborrowcartQuery);

		$checkborrowcartSQL = "SELECT COUNT(*) AS alreadyincart FROM borrowcart WHERE accession_no='$bookid'";
		$checkborrowcartQuery = mysqli_query($dbconnect, $checkborrowcartSQL);
		$checkborrowcart = mysqli_fetch_assoc($checkborrowcartQuery);

		$checkrowsSQL = "SELECT * FROM borrowcart WHERE IDNumber='$idnumber'";
		$checkrowsQuery = mysqli_query($dbconnect, $checkrowsSQL);
		$borrowcartrows = mysqli_num_rows($checkrowsQuery);

		$settingsSQL = "SELECT * FROM settings";
		$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
		$settings = mysqli_fetch_assoc($settingsQuery);

		if($rows==0) {
			echo "Invalid";
		} else if($status=="Borrowed") {
			echo "Unavailable";
		} else if($checkborrow['noofborrowedbooks']==$settings['borrowlimit'] || $borrowcartrows==$settings['borrowlimit']) {
			echo "Limit";
		} else if($checkbookID['nooftitle']==1 || $checkbookIDborrowcart['nooftitle']==1) {
			echo "No Duplicate";
		} else if($checkborrowcart['alreadyincart']==1) {
			echo "Already In Cart";
		} else if($status=="Reserved" && $checkreserve==0) {
			echo "Unavailable";
		} else {
				$addtoborrowcartSQL = "INSERT INTO borrowcart(accession_no, IDNumber) VALUES('$bookid', '$idnumber')";
				$addtoborrowcart = mysqli_query($dbconnect, $addtoborrowcartSQL);

				$borrowcartSQL = "SELECT borrowcartID, book.accession_no, booktitle, barcode, callnumber FROM borrowcart JOIN book ON book.accession_no=borrowcart.accession_no WHERE IDNumber='$idnumber'";
				$borrowcartQuery = mysqli_query($dbconnect, $borrowcartSQL);
				$borrowcart = mysqli_fetch_assoc($borrowcartQuery);
?>
<div class="cart">
<table class="table table-hover" id="booktable">
	<tr>
		<th>Accession Number</th>
		<th>Barcode</th>
		<th>Title</th>
		<th> </th>
	</tr>
	<?php
		do {
	?>
		<tr>
			<td class="accnumcol"><?php echo $borrowcart['accession_no'];?></td>
			<td><?php echo $borrowcart['barcode'];?></td>
			<td><?php echo $borrowcart['booktitle'];?></td>
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
	<input type="hidden" name="borrowsessionID" id="borrowsessionID" value="<?php echo $borrowsessionID; ?>">
</form>
</div>
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
			var borrowsessionID = $("#borrowsessionID").val();
			$.ajax({
				url:"borrowbook.php",
				method:"POST",
				data:{bookid:bookid, idnumber:idnumber,borrowsessionID:borrowsessionID},
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