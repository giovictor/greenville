<?php
require "dbconnect.php";
if(isset($_POST['idnumber'])) {
	$idnumber = $_POST['idnumber'];
	$borrowerSQL = "SELECT * FROM borrower WHERE IDNumber='$idnumber'";
	$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
	$borrower = mysqli_fetch_assoc($borrowerQuery);
?>
<h4><?php echo $borrower['lastname'].", ".$borrower['firstname']." ".$borrower['mi'];?></h4>
<div class="takepayment">
	<form id="takepaymentform">
		<label>Account Balance:</label>
		<input type="text" name="balance" id="balance" class="form-control" value="<?php echo $borrower['accountbalance'];?>" disabled>
		<label>Payment:</label>
		<input type="text" name="payment" id="payment" class="form-control">
		<button class="btn btn-success btn-sm button" id="paymentsubmit">Take Payment</button>
		<input type="hidden" name="idnumber" id="idnumber" value="<?php echo $idnumber;?>">
	</form>
</div>
<script>
$(document).ready(function(){
	$("#takepaymentform").submit(function(e){
		e.preventDefault();
		var idnumber = $("#idnumber").val();
		var payment = $("#payment").val();
		if(payment=="") {
			$("#takepaymentalert").html("<h3>Please provide a payment.</h3>");
			$("#payment").focus();
		} else {
			$.ajax({
				url:"takepayment.php",
				method:"POST",
				data:{idnumber:idnumber,payment:payment},
				success:function(data) {
					if(data=="Invalid") {
						$("#takepaymentalert").html("<h3>Please input a numeric value.</h3>");
						$("#payment").focus();
					} else if(data=="No Balance") {
						$("#takepaymentalert").html("<h3>This borrower has no balance.</h3>");
						$("#payment").focus();
					} else {
						$(".takepayment").html(data);
					}
				}
			});
		}
	});

	$("#payment").keypress(function(){
		$("#takepaymentalert").html("");
	});

	$("#paymentmodal").on("hide.bs.modal", function(){
		$("#payment").val("");
		$("#takepaymentalert").html("").end();
		$.ajax({
			url:"takepaymenttable.php",
			success:function(data) {
				$(".borrowerdisplay").html(data);
			}
		});
	});
});
</script>
<?php
}
?>