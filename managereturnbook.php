<title>Return Book</title>
<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
?>
<div class="admincontainer">
	<h4>Return Book</h4>
	<form id="returnform" class="form-inline">
		<div class="form-group">
			<label for="bookid">ID Number: </label>
			<input type="text" name="idnumber" id="idnumber" class="form-control">
		</div>
		<button class="btn btn-success btn-sm button" id="idnumbutton">Submit</button>
	</form>
	<div class="generatereturn">
	<form id="borrowerinfo" class="form-inline">
		<div class="form-group">
			<label for="borrower">Borrower: </label>
			<input type="text" id="borrower" class="form-control" size="20" disabled>
		</div>
		<div class="form-group">
			<label for="course">Course: </label>
			<input type="text" id="course" class="form-control" size="20" disabled>
		</div>
	</form>
	</div>
	<script>
	$(document).ready(function(){
		$("#returnform").submit(function(e){
			e.preventDefault();
			var idnumber = $("#idnumber").val();
			if(idnumber=="") {
				$("#emptyidnum").modal("show");
			} else {
				$.ajax({
					url:"generatereturn.php",
					method:"POST",
					data:{idnumber:idnumber},
					beforeSend:function() {
						$("#idnumbutton").html("Getting Data...");
					},
					success:function(data) {
						if(data=="Weekends") {
							$("#idnumbutton").html("Submit");
							$("#idnumber").val("");
							$("#weekends2").modal("show");
						} else if(data=="Invalid") {
							$("#idnumbutton").html("Submit");
							$("#idnumber").val("");
							$("#invalididnum").modal("show");
						} else if(data=="Did Not Borrow") {
							$("#idnumbutton").html("Submit");
							$("#idnumber").val("");
							$("#didnotborrow").modal("show");
						} else {
							$("#idnumbutton").html("Submit");
							$(".generatereturn").html(data);
						}
					}
				});
			}
		});

		$("#emptyidnum").on("hide.bs.modal",function(){
			$("#idnumber").focus();
		});

		$("#invalididnum").on("hide.bs.modal",function(){
			$("#idnumber").focus();
		});

		$("#didnotborrow").on("hide.bs.modal",function(){
			$("#idnumber").focus();
		});


		$("#weekends2").on("hide.bs.modal",function(){
			$("#idnumber").focus();
		});
	});
	</script>
</div>