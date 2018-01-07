<?php
require "dbconnect.php";
if(isset($_POST['idnumber']) && isset($_POST['borrowersperpages']) && isset($_POST['firstresult'])) {
	$idnumber = $_POST['idnumber'];

	$deactivateborrowerstatusSQL = "UPDATE borrower SET status='Inactive' WHERE IDNumber='$idnumber'";
	$deactivateborrowerstatus = mysqli_query($dbconnect, $deactivateborrowerstatusSQL);

	$borrowersperpages = $_POST['borrowersperpages'];
	$firstresult = $_POST['firstresult'];

	if(isset($_POST['keyword']) && isset($_POST['searchtype'])) {
		$keyword = $_POST['keyword'];
		$searchtype = $_POST['searchtype'];
		if($searchtype=="idnumber") {
			$borrowerSQL = "SELECT * FROM borrower WHERE IDNumber LIKE '%$keyword%' ORDER BY dateregistered DESC LIMIT $firstresult, $borrowersperpages";
 		} else if($searchtype=="name") {
			$borrowerSQL = "SELECT * FROM borrower WHERE CONCAT(lastname, firstname, mi) LIKE '%$keyword%' ORDER BY dateregistered DESC $firstresult, $borrowersperpages";
		}
	} else {
		$borrowerSQL = "SELECT * FROM borrower ORDER BY dateregistered DESC LIMIT $firstresult, $borrowersperpages";
	}
	$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
	$borrower = mysqli_fetch_assoc($borrowerQuery);
?>
<table class='table table-hover table-striped table-bordered' id='borrowertable'>
				<tr>
					<th>ID Number</th>
					<th>Name</th>
					<th>Contact Number</th>
					<th>Course</th>
					<th>Date Registered</th>
					<th>Account Type</th>
					<th>Account Balance</th>
					<th>Status</th>
					<th> </th>
				</tr>
	<?php		
		do {?>
				<tr>
					<td>
						<button class="btn btn-link btn-sm viewlinks viewborrowerinfo" style="color:#1CA843;" id="<?php echo $borrower['IDNumber'];?>">
							<b><?php echo $borrower['IDNumber']; ?></b>
						</button>
					</td>
					<td><?php echo $borrower['lastname'].", ".$borrower['firstname']." ".$borrower['mi']; ?></td>
					<td><?php echo $borrower['contactnumber']; ?></td>
					<td><?php echo $borrower['course']; ?></td>
					<td><?php echo $borrower['dateregistered']; ?></td>
					<td><?php echo $borrower['accounttype']; ?></td>
					<td><?php echo $borrower['accountbalance']; ?></td>
					<td><?php echo $borrower['status']; ?></td>
					<td>
						<?php
							if($borrower['accountbalance']==0.00) {
						?>
							<button class="btn btn-warning btn-sm paymentbutton" id="<?php echo $borrower['IDNumber'];?>" data-toggle="modal" data-target="#paymentmodal" disabled>
								<span class="glyphicon glyphicon-credit-card"></span>
							</button>
						<?php
							} else {
						?>
							<button class="btn btn-warning btn-sm paymentbutton" id="<?php echo $borrower['IDNumber'];?>" data-toggle="modal" data-target="#paymentmodal">
								<span class="glyphicon glyphicon-credit-card"></span>
							</button>
						<?php
							}
						?>
						<a class="btn btn-success btn-sm button" href="?page=editupdateborrower&idNum=<?php echo $borrower['IDNumber']; ?>">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
						<?php
							if($borrower['status']=="Active") {
								if($borrower['accountbalance'] > 0.00) {
						?>
									<button class="btn btn-danger btn-sm deactivatebutton" data-id="<?php echo $borrower['IDNumber']; ?>" data-toggle="modal" data-target="#deactivateborrower" title="Deactivate borrower." disabled>
										<span class="glyphicon glyphicon-ban-circle">
									</button>
						<?php
								} else {
						?>
									<button class="btn btn-danger btn-sm deactivatebutton" data-id="<?php echo $borrower['IDNumber']; ?>" data-toggle="modal" data-target="#deactivateborrower" title="Deactivate borrower.">
										<span class="glyphicon glyphicon-ban-circle">
									</button>
						<?php
								}
							} else if($borrower['status']=="Inactive") {
								if($borrower['accountbalance'] > 0.00) {
						?>
									<button class="btn btn-success btn-sm activatebutton" data-id="<?php echo $borrower['IDNumber']; ?>" data-toggle="modal" data-target="#activateborrower" title="Activate borrower." disabled>
										<span class="glyphicon glyphicon-ok-circle">
									</button>
						<?php
								} else {
						?>
									<button class="btn btn-success btn-sm activatebutton" data-id="<?php echo $borrower['IDNumber']; ?>" data-toggle="modal" data-target="#activateborrower" title="Activate borrower.">
										<span class="glyphicon glyphicon-ok-circle">
									</button>
						<?php
								}
							}
						?>
					</td>
				</tr>
<?php
		} while($borrower = mysqli_fetch_assoc($borrowerQuery));
?>
</table>
<script>
$(document).ready(function(){
	$(document).on("click",".deactivatebutton", function(){
		var idnumber = $(this).data("id");
		$(".confirmdeactivateborrower").data("id",idnumber);
	});
	<?php
		if(isset($_POST['keyword']) && isset($_POST['searchtype'])) {
	?>	
			$(".confirmdeactivateborrower").click(function(){
				var idnumber = $(this).data("id");
				var borrowersperpages = $("#borrowersperpages").val();
				var firstresult = $("#firstresult").val();
				var keyword = $("#keyword").val();
				var searchtype = $("#searchtype").val();
				$.ajax({
					url:"deactivateborrower.php",
					method:"POST",
					data:{idnumber:idnumber,  borrowersperpages:borrowersperpages, firstresult:firstresult, keyword:keyword, searchtype:searchtype},
					success:function(data) {
						$("#deactivateborrower").modal("hide");
						$(".borrowerdisplay").html(data);
					}
				});
			});
	<?php
		} else {
	?>
			$(".confirmdeactivateborrower").click(function(){
				var idnumber = $(this).data("id");
				var borrowersperpages = $("#borrowersperpages").val();
				var firstresult = $("#firstresult").val();
				$.ajax({
					url:"deactivateborrower.php",
					method:"POST",
					data:{idnumber:idnumber, borrowersperpages:borrowersperpages, firstresult:firstresult},
					success:function(data) {
						$("#deactivateborrower").modal("hide");
						$(".borrowerdisplay").html(data);
					}
				});
			});
	<?php
		}
	?>


	$(document).on("click",".activatebutton", function(){
		var idnumber = $(this).data("id");
		$(".confirmactivateborrower").data("id",idnumber);
	});

	<?php
		if(isset($_POST['keyword']) && isset($_POST['searchtype'])) {
	?>	
			$(".confirmactivateborrower").click(function(){
				var idnumber = $(this).data("id");
				var borrowersperpages = $("#borrowersperpages").val();
				var firstresult = $("#firstresult").val();
				var keyword = $("#keyword").val();
				var searchtype = $("#searchtype").val();
				$.ajax({
					url:"activateborrower.php",
					method:"POST",
					data:{idnumber:idnumber,  borrowersperpages:borrowersperpages, firstresult:firstresult, keyword:keyword, searchtype:searchtype},
					success:function(data) {
						$("#deactivateborrower").modal("hide");
						$(".borrowerdisplay").html(data);
					}
				});
			});
	<?php
		} else {
	?>
			$(".confirmactivateborrower").click(function(){
				var idnumber = $(this).data("id");
				var borrowersperpages = $("#borrowersperpages").val();
				var firstresult = $("#firstresult").val();
				$.ajax({
					url:"activateborrower.php",
					method:"POST",
					data:{idnumber:idnumber, borrowersperpages:borrowersperpages, firstresult:firstresult},
					success:function(data) {
						$("#deactivateborrower").modal("hide");
						$(".borrowerdisplay").html(data);
					}
				});
			});
	<?php
		}
	?>


	<?php
	if(isset($_POST['keyword']) && isset($_POST['searchtype'])) {
	?>
		$(".paymentbutton").click(function(){
			var idnumber = $(this).attr("id");
			var keyword = $("#keyword").val();
			var searchtype = $("#searchtype").val();
			var borrowersperpages = $("#borrowersperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"paymentmodal.php",
				method:"POST",
				data:{idnumber:idnumber, borrowersperpages:borrowersperpages, firstresult:firstresult, keyword:keyword, searchtype:searchtype},
				success:function(data) {
					$("#takepaymentdata").html(data);
					$("#paymentmodal").modal("show");
				}
			});
		});
	<?php
	} else {
	?>
		$(".paymentbutton").click(function(){
			var idnumber = $(this).attr("id");
			var borrowersperpages = $("#borrowersperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"paymentmodal.php",
				method:"POST",
				data:{idnumber:idnumber, borrowersperpages:borrowersperpages, firstresult:firstresult},
				success:function(data) {
					$("#takepaymentdata").html(data);
					$("#paymentmodal").modal("show");
				}
			});
		});
	<?php
	}
	?>

	$(".viewborrowerinfo").click(function(){
		var idnumber = $(this).attr("id");
		$.ajax({
			url:"borrowermodalinfo.php",
			method:"POST",
			data:{idnumber:idnumber},
			success:function(data) {
				$("#borrowerinfodata").html(data);
				$("#borrowerinfomodal").modal("show");
			}
		});
	});
});
</script>
<?php
}
?>