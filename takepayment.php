<?php
require "dbconnect.php";
if(isset($_POST['idnumber']) && isset($_POST['payment'])) {
	$idnumber = $_POST['idnumber'];
	$payment = $_POST['payment'];

	$getborrowerSQL = "SELECT * FROM borrower WHERE IDNumber='$idnumber'";
	$getborrowerQuery = mysqli_query($dbconnect, $getborrowerSQL);
	$getborrower = mysqli_fetch_assoc($getborrowerQuery);
	$accountbalance = $getborrower['accountbalance'];

	if(!is_numeric($payment)) {
		echo "Invalid";
	} else if($accountbalance==0.00) {
		echo "No Balance";
	} else {

		if($payment > $accountbalance) {
			$change = $payment - $accountbalance;
			$newbalance = 0.00;
		} else {
			$change = 0.00;
			$newbalance = $accountbalance - $payment;
		}

		$updatebalanceSQL = "UPDATE borrower SET accountbalance='$newbalance' WHERE IDNumber='$idnumber'";
		$updatebalance = mysqli_query($dbconnect, $updatebalanceSQL);

		$borrowerSQL = "SELECT * FROM borrower WHERE IDNumber='$idnumber'";
		$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
		$borrower = mysqli_fetch_assoc($borrowerQuery);
		$accountbalance = $borrower['accountbalance'];
?>
<form id="takepaymentform">
	<label>Account Balance:</label>
	<input type="text" name="balance" id="balance" class="form-control" value="<?php echo $borrower['accountbalance'];?>" disabled>
	<label>Payment:</label>
	<input type="text" name="payment" id="payment" class="form-control">
	<button class="btn btn-success btn-sm button" id="paymentsubmit">Take Payment</button>
	<input type="hidden" name="idnumber" id="idnumber" value="<?php echo $idnumber;?>">
</form>
<h4>Change: &#x20B1; <?php echo $change.".00";?></h4>
<script>
$(document).ready(function(){
	$("#takepaymentform").submit(function(e){
		e.preventDefault();
		var idnumber = $("#idnumber").val();
		var payment = $("#payment").val();
		if(payment=="") {
			$("#takepaymentalert").html("<h3>Please provide a payment.</h3>");
		} else {
			$.ajax({
				url:"takepayment.php",
				method:"POST",
				data:{idnumber:idnumber,payment:payment},
				success:function(data) {
					if(data=="Invalid") {
						$("#takepaymentalert").html("<h3>Please input a numeric value.</h3>");
					} else {
						$(".takepayment").html(data);
					}
				}
			});
		}
	});
});
</script>
<?php
	}
}
?>