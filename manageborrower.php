<div class="admincontainer">	
	<?php
		require "dbconnect.php";
		if(!isset($_SESSION['librarian'])) {
			header("Location:index.php");
		}
	?>
	<title>Add Borrower</title>
	<div class="panel panel-success">
		<div class="panel-heading">
			<a href="?page=borrowers" class="btn btn-success btn-sm button viewlinks">View All Borrowers</a>
			<h4><span class="glyphicon glyphicon-plus"></span> ADD BORROWER </h4>
		</div>
		<div class="panel-body">
			<form id="addborrower">
				<table cellpadding="10">
					<tr>
						<td>ID Number:</td>
						<td><input type="text" name="idnumber" style="width:400px;" class="form-control" id="idnumber"></td>
					</tr>
					<tr>
						<td>Contact No: </td>
						<td><input type="text" name="contact" style="width:400px;" class="form-control" id="contact"></td>
					</tr>
					<tr>
						<td>Last Name:</td> 
						<td><input type="text" name="lastname" style="width:400px;" class="form-control" id="lastname"></td>
					</tr>
					<tr>
						<td>First Name:</td> 
						<td><input type="text" name="firstname" style="width:400px;" class="form-control" id="firstname"></td>
					</tr>
					<tr>
						<td>MI:</td> 
						<td><input type="text" name="mi" style="width:400px;" class="form-control" id="mi"></td>
					</tr>
					<tr>
						<td>Course:</td> 
						<td>
							<select name="course" class="form-control" style="width:400px;">
								<option value="AB Public Administration">AB Public Administration</option>
								<option value="AB English">AB English</option>
								<option value="BS Psychology">BS Psychology</option>
								<option value="BEED">BEED</option>
								<option value="BSED">BSED</option>
								<option value="MAed">MAed</option>
								<option value="MPA">MPA</option>
								<option value="DPA">DPA</option>
								<option value="MBA">MBA</option>
								<option value="EdD">EdD</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Account Type: </td> 
						<td>
							<select name="accounttype" class="form-control" style="width:400px;">
								<option value="Student">Student</option>
								<option value="Faculty">Faculty</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><input id="addbutton" class="btn btn-success btn-md" type="submit" value="Add Borrower" name="addbutton"></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<script>
	$(document).ready(function(){
		$("#addborrower").submit(function(e){
			e.preventDefault();
			var idnumber = $("#idnumber").val();
			var contact = $("#contact").val();
			var lastname = $("#lastname").val();
			var firstname = $("#firstname").val();
			var mi = $("#mi").val();
				if(idnumber=="") {
					$("#emptyinput").modal("show");
				} else {
					$.ajax({
						url:"addborrower.php",
						method:"POST",
						data:$("#addborrower").serialize(),
						beforeSend:function() {
							$("#addbutton").val("Adding...");
						},
						success:function(data) {
							if(data=="Need Numeric Values") {
								$("#invalidborrowerinput").modal("show");
								$("#addbutton").val("Add Borrower");
							} else {
								$("#addborrower")[0].reset();
								$("#addmsg2").modal("show");
								$("#addbutton").val("Add Borrower");
							}
						}
					});
				}
		});	
	});
	</script>
</div>
			


