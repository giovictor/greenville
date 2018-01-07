<title>Borrow Book</title>
<div class="admincontainer">
	<div class="borrowform">
		<h4>Borrow Book</h4>
		<form id="borrowform" class="form-inline">
			<div class="form-group">
				<label for="idnumber">ID Number:</label>
				<input type="text" name="idnumber" class="form-control" id="idnumber">
			</div>
				<button id="button" class="btn btn-success btn-sm">Submit</button>
		</form>
	</div>
	<div id="generateborrow"> 
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
		$("#borrowform").submit(function(e){
			e.preventDefault();
			var idnumber = $("#idnumber").val();
				if(idnumber=="") {
					$("#emptyidnum").modal("show");
				} else {
					$.ajax({
						url:"generateborrow.php",
						method:"POST",
						data:{idnumber:idnumber},
						beforeSend:function() {
							$("#button").html("Getting Data...");
						},
						success:function(data) {
							if(data=="Weekends") {
								$("#weekends").modal("show");
								$("#button").html("Submit");
								$("#idnumber").val("");
							} else if(data=="Invalid") {
								$("#invalididnum").modal("show");
								$("#button").html("Submit");
								$("#idnumber").val("");
							} else if(data=="Limit") {
								$("#notallowedborrow").modal("show");
								$("#button").html("Submit");
								$("#idnumber").val("");
							} else if(data=="Not Allowed") {
								$("#notallowedborrower").modal("show");
								$("#button").html("Submit");
								$("#idnumber").val("");
							} else { 
								$("#button").html("Submit");
								$("#generateborrow").html(data);
							}
						}
					});
				}
		});

		$("#invalididnum").on("hide.bs.modal",function(){
			$("#idnumber").focus();
		});

		$("#emptyidnum").on("hide.bs.modal",function(){
			$("#idnumber").focus();
		});

		$("#notallowedborrow").on("hide.bs.modal",function(){
			$("#idnumber").focus();
		});

		$("#notallowedborrower").on("hide.bs.modal",function(){
			$("#idnumber").focus();
		});

		$("#weekends").on("hide.bs.modal",function(){
			$("#idnumber").focus();
		});
	});
	</script>
</div>