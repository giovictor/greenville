<div class="admincontainer">
	<div class="panel panel-success" style="margin-top:20px;height:140px;">
		<div class="panel-heading">
			<h3>Archived Borrowers</h3>
		</div>
		<div class="panel-body">
			<form method="GET" class="form-inline" id="archivedborrowersearchform">
					Search by:
					<div class="form-group">
						<select name="archivedborrowersearchtype" class="form-control">
							<option value="idnumber">ID Number</option>
							<option value="name">Name</option>
						</select>
					</div>
					<div class="form-group">
						<input class="form-control" id="archivedborrowersearchbox" type="text" name="archivedborrowersearch" size="20">
					</div>
					<input id="button" class="btn btn-success btn-sm" type="submit" name="archivedborrowerbutton" value="Search">
			</form>
		</div>
	</div>
	<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
	require "dbconnect.php";
		if(isset($_GET['archivedborrowerbutton'])) {
			$archivedborrowersearchtype = $_GET['archivedborrowersearchtype'];
			$archivedborrowersearch = $_GET['archivedborrowersearch'];

			if($archivedborrowersearchtype=="idnumber") {
				$archivedborrowerSQL = "SELECT * FROM borrower WHERE status='Inactive' AND IDNumber LIKE '%$archivedborrowersearch%'";
			} else if($archivedborrowersearchtype=="name") {
					$archivedborrowerSQL = "SELECT * FROM borrower WHERE status='Inactive' AND CONCAT(lastname, firstname, mi) LIKE '%$archivedborrowersearch%'";
			}


			$archivedborrowerQuery = mysqli_query($dbconnect,$archivedborrowerSQL);
			$archivedborrower = mysqli_fetch_assoc($archivedborrowerQuery);
			$rows = mysqli_num_rows($archivedborrowerQuery);
	?>
	<div class="borrowerdisplay">
		<table class='table table-hover table-striped table-bordered' id='borrowertable'>
					<tr>
						<th>ID Number</th>
						<th>Name</th>
						<th>Contact Number</th>
						<th>Course</th>
						<th>Date Registered</th>
						<th width="8%">Account Type</th>
						<th width="5%">Account Balance</th>
						<th> </th>
					</tr>
			<?php
				if($rows==0) {
					echo "<tr><td colspan='9'><center><h4>No results found.</h4></center></td></tr>";
				} else if($rows>=1){
					do {
			?>
					<tr>
						<td><?php echo $archivedborrower['IDNumber']; ?></td>
						<td><?php echo $archivedborrower['lastname'].", ".$archivedborrower['firstname']." ".$archivedborrower['mi']; ?></td>
						<td><?php echo $archivedborrower['contactnumber']; ?></td>
						<td><?php echo $archivedborrower['course']; ?></td>
						<td><?php echo $archivedborrower['dateregistered']; ?></td>
						<td><?php echo $archivedborrower['accounttype']; ?></td>
						<td><?php echo $archivedborrower['accountbalance']; ?></td>
						<td>
							<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedborrower['IDNumber'];?>" data-toggle="modal" data-target="#restoreborrower">
								<span class="glyphicon glyphicon-refresh"> </span>
							</button>
							<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedborrower['IDNumber'];?>" data-toggle="modal" data-target="#permanentdeleteborrower">
								<span class="glyphicon glyphicon-trash"> </span>
							</button>
						</td>
					</tr>
			<?php
					} while($archivedborrower = mysqli_fetch_assoc($archivedborrowerQuery));
				}
			?>
	</table>
	<form id="printpdf" target="_blank" action="pdfarchivedborrower.php" method="POST">
		<input type="hidden" name="query" value="<?php echo $archivedborrowerSQL;?>">
		<input type="submit" name="createpdf" value="Print PDF" id="button" class="btn btn-success btn-sm">
	</form>
	</div>
</div>
<script>
$(document).ready(function(){
	$("#archivedborrowersearchform").submit(function(e){
		var searchbox = $("#archivedborrowersearchbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		}
	});

	$(document).on("click",".restorebutton",function(){
		var idnumber = $(this).data("id");
		$(".confirmrestoreborrower").data("id",idnumber);
	});

	$(".confirmrestoreborrower").click(function(){
		var idnumber = $(this).data("id");
		$.ajax({
			url:"restoreborrower.php",
			method:"POST",
			data:{idnumber:idnumber},
			success:function(data) {
				$("#restoreborrower").modal("hide");
				$(".borrowerdisplay").html(data);
			}
		});
	});


	$("#permanentdeleteborrower").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>
<?php
}
?>