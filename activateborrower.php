<?php
require "dbconnect.php";
if(isset($_POST['idnumber'])) {
	$idnumber = $_POST['idnumber'];

	$activateborrowerstatusSQL = "UPDATE borrower SET status='Active' WHERE IDNumber='$idnumber'";
	$activateborrowerstatus = mysqli_query($dbconnect, $activateborrowerstatusSQL);

	$borrowerSQL = "SELECT * FROM borrower ORDER BY dateregistered DESC";
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
					<th width="8%">Account Type</th>
					<th width="5%">Account Balance</th>
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

	$(".confirmdeactivateborrower").click(function(){
		var idnumber = $(this).data("id");
		$.ajax({
			url:"deactivateborrower.php",
			method:"POST",
			data:{idnumber:idnumber},
			success:function(data) {
				$("#deactivateborrower").modal("hide");
				$(".borrowerdisplay").html(data);
			}
		});
	});


	$(document).on("click",".activatebutton", function(){
		var idnumber = $(this).data("id");
		$(".confirmactivateborrower").data("id",idnumber);
	});

	$(".confirmactivateborrower").click(function(){
		var idnumber = $(this).data("id");
		$.ajax({
			url:"activateborrower.php",
			method:"POST",
			data:{idnumber:idnumber},
			success:function(data) {
				$("#activateborrower").modal("hide");
				$(".borrowerdisplay").html(data);
			}
		});
	});

	$(".paymentbutton").click(function(){
		var idnumber = $(this).attr("id");
		$.ajax({
			url:"paymentmodal.php",
			method:"POST",
			data:{idnumber:idnumber},
			success:function(data) {
				$("#takepaymentdata").html(data);
				$("#paymentmodal").modal("show");
			}
		});
	});

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