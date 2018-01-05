<?php
require "dbconnect.php";
include "editborrower.php";
if(!isset($_SESSION['librarian'])) {
	header("Location:greenvillecollegelibrary.php");
}

if(isset($_GET['idNum'])) {
	$getidnumber = $_GET['idNum'];

	$borrowerSQL = "SELECT * FROM borrower WHERE IDNumber='$getidnumber'";
	$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
	$borrower = mysqli_fetch_assoc($borrowerQuery);
}
?>
<div class="admincontainer">
	<div id="edit">
		<div class="panel panel-success">
			<div class="panel-heading">
				<a class="btn btn-success btn-sm button viewlinks" href="?page=borrowers">View All Borrowers</a>
				<a class="btn btn-success btn-sm button viewlinks viewborrowerinfo" href="#" id="<?php echo $borrower['IDNumber'];?>">View Borrower Info</a>
				<h4>EDIT BORROWER</h4>
			</div>
			<div class="panel-body">
				<form id="editborrower">
					<table cellpadding="10">
					<tr>
						<td>ID Number:</td>
						<td><input type="text" name="idnumber" class="form-control" value="<?php echo $borrower['IDNumber']; ?>" style="width:400px;"></td>
					</tr>
					<tr>
						<td>Contact No: </td>
						<td><input type="text" name="contactnumber" class="form-control" value="<?php echo $borrower['contactnumber']; ?>" style="width:400px;"></td>
					</tr>
					<tr>
						<td>Last Name:</td> 
						<td><input type="text" name="lastname" class="form-control" value="<?php echo $borrower['lastname']; ?>" style="width:400px;"></td>
					</tr>
					<tr>
						<td>First Name:</td> 
						<td><input type="text" name="firstname" class="form-control" value="<?php echo $borrower['firstname']; ?>" style="width:400px;"></td>
					</tr>
					<tr>
						<td>MI:</td> 
						<td><input type="text" name="mi" class="form-control" value="<?php echo $borrower['mi']; ?>" size="3" style="width:400px;"></td>
					</tr>
					<tr>
						<td>Course:</td> 
						<td>
							<select name="course" class="form-control" style="width:400px;">
								<option value="AB Public Administration""
									<?php
										if($borrower['course']=="AB Public Administration") {
											echo 'selected="selected"';
										}

									 ?>
								>AB Public Administration</option>
								<option value="AB English"
									<?php
										if($borrower['course']=="AB English") {
											echo 'selected="selected"';
										}

									 ?>

								>AB English</option>
								<option value="BS Psychology"
									<?php
										if($borrower['course']=="BS Psychology") {
											echo 'selected="selected"';
										}

									 ?>
								>BS Psychology</option>
								<option value="BEED"
									<?php
										if($borrower['course']=="BEED") {
											echo 'selected="selected"';
										}

									 ?>
								>BEED</option>
								<option value="BSED"
									<?php
										if($borrower['course']=="BSED") {
											echo 'selected="selected"';
										}

									 ?>

								>BSED</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Account Type: </td> 
						<td>
							<select name="accounttype" class="form-control" style="width:400px;">
								<option value="Student"
									<?php
										if($borrower['accounttype']=="Student") {
											echo 'selected="selected"';
										}
									?>
								>Student</option>
								<option value="Faculty"
									<?php
										if($borrower['accounttype']=="Faculty") {
											echo 'selected="selected"';
										}
									?>

								>Faculty</option>
							</select>
						</td>	 
						
					</tr>
					<tr>
						<td><input id="editbutton" class="btn btn-success btn-md button" type="submit" value="Edit Borrower" name="editbutton"></td>
					</tr>
					</table>
					<input type="hidden" name="getidnumber" id="getidnumber" value="<?php echo $getidnumber;?>">
				</form>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
	$("#editborrower").submit(function(e){
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
					url:"editborrower.php",
					method:"POST",
					data:$("#editborrower").serialize(),
					beforeSend:function() {
						$("#editbutton").val("Updating...");
					},
					success:function(data) {
						if(data=="Need Numeric Values") {
							$("#invalidborrowerinput").modal("show");
							$("#editbutton").val("Edit Borrower");
						} else {
							$("#editborrower")[0].reset();
							$("#editmsg6").modal("show");
							$("#editbutton").val("Edit Borrower");
							$("#edit").html(data);
						}
					}
				});
			}
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
		


