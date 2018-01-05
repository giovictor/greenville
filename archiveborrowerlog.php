<?php
require "dbconnect.php";
if(isset($_POST['attendanceID'])) {
	$attendanceID = $_POST['attendanceID'];
	$archiveborrowerlogSQL = "UPDATE attendance SET showstatus=0 WHERE attendanceID='$attendanceID'";
	$archiveborrowerlog = mysqli_query($dbconnect, $archiveborrowerlogSQL);

	$borrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime,showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=1 ORDER BY attendanceID DESC";
	$borrowerLogsQuery = mysqli_query($dbconnect, $borrowerLogsSQL);
	$borrowerLogs = mysqli_fetch_assoc($borrowerLogsQuery);
	$rows = mysqli_num_rows($borrowerLogsQuery);
?>
<table class='table table-hover'>
		<tr>
			<th>ID Number</th>
			<th>Borrower</th>
			<th>Login Datetime</th>
			<th>Logout Datetime</th>
			<th> </th>
		</tr>
	<?php
		if($rows==0) {
			echo "<tr><td colspan='7'><center><h4>There were no borrower logs.</h4></center></td></tr>";
		} else if($rows>=1) {
			do {
	?>
			<tr>
				<td>
					<button type="button" style="color:#1CA843;" class="btn btn-link borrowerinfo" id="<?php echo $borrowerLogs['IDNumber'];?>">
						<b><?php echo $borrowerLogs['IDNumber'];?></b>
					</button>
				</td>
				<td><?php echo $borrowerLogs['lastname'].", ".$borrowerLogs['firstname']." ".$borrowerLogs['mi'];?></td>
				<td><?php echo $borrowerLogs['logindatetime']; ?></td>
				<td><?php echo $borrowerLogs['logoutdatetime']; ?></td>
				<td>
					<button class="btn btn-warning btn-sm archivebutton" data-id="<?php echo $borrowerLogs['attendanceID'];?>" data-toggle="modal" data-target="#confirmarchiveborrowerlog">
						<span class="glyphicon glyphicon-briefcase"> </span>
					</button>
				</td>
			</tr>
	<?php	
			} while($borrowerLogs = mysqli_fetch_assoc($borrowerLogsQuery));
		}
	?>
</table>
<form id="printpdf" target="_blank" action="pdfborrowerlogs.php" method="POST" class="form-inline">
	<input class="btn btn-success btn-sm" id="button" type="submit" name="createpdf" value="Print PDF">
	<input type="hidden" name="query" value="<?php echo $borrowerLogsSQL;?>">
</form>
<script>
$(document).ready(function(){
	$(document).on("click",".archivebutton",function(){
		var attendanceID = $(this).data("id");
		$(".archiveborrowerlog").data("id",attendanceID);
	});

	$(".archiveborrowerlog").click(function(){
		var attendanceID = $(this).data("id");
		$.ajax({
			url:"archiveborrowerlog.php",
			method:"POST",
			data:{attendanceID:attendanceID},
			success:function(data) {
				$("#confirmarchiveborrowerlog").modal("hide");
				$(".logs").html(data);
			}	
		});
	});

	$(".borrowerinfo").click(function(){
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