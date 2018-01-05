<?php
require "dbconnect.php";
if(isset($_POST['attendanceID'])) {
	$attendanceID = $_POST['attendanceID'];
	$restoreborrowerlogSQL = "UPDATE attendance SET showstatus=1 WHERE attendanceID='$attendanceID'";
	$restoreborrowerlog = mysqli_query($dbconnect, $restoreborrowerlogSQL);

	$archivedborrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=0 ORDER BY attendanceID DESC";
	$archivedborrowerLogsQuery = mysqli_query($dbconnect, $archivedborrowerLogsSQL);
	$archivedborrowerLogs = mysqli_fetch_assoc($archivedborrowerLogsQuery);
	$rows = mysqli_num_rows($archivedborrowerLogsQuery);
?>
<table class='table table-hover table-bordered'>
			<tr>
				<th>ID Number</th>
				<th>Borrower</th>
				<th>Login Datetime</th>
				<th>Logout Datetime</th>
				<?php
					if($rows>=1) {
				?>
						<th> </th>
				<?php
					}
				?>	
			</tr>
		<?php
			if($rows==0) {
				echo "<tr><td colspan='7'><center><h4>There were no borrower logs.</h4></center></td></tr>";
			} else if($rows>=1) {
				do {
		?>
				<tr>
					<td><?php echo $archivedborrowerLogs['IDNumber']; ?></td>
					<td><?php echo $archivedborrowerLogs['lastname'].", ".$archivedborrowerLogs['firstname']." ".$archivedborrowerLogs['mi']; ?></td>
					<td><?php echo $archivedborrowerLogs['logindatetime']; ?></td>
					<td><?php echo $archivedborrowerLogs['logoutdatetime']; ?></td>
					<td>
						<button class="btn btn-success btn-sm restorebutton" data-id="<?php echo $archivedborrowerLogs['attendanceID'];?>" data-toggle="modal" data-target="#restoreborrowerlog">
							<span class="glyphicon glyphicon-refresh"> </span>
						</button>
						<button class="btn btn-danger btn-sm permanentdeletebutton" data-id="<?php echo $archivedborrowerLogs['attendanceID'];?>" data-toggle="modal" data-target="#permanentdeleteborrowerlog">
							<span class="glyphicon glyphicon-trash"> </span>
						</button>
					</td>
				</tr>
		<?php	
				} while($archivedborrowerLogs = mysqli_fetch_assoc($archivedborrowerLogsQuery));
			}
		?>
</table>
<form id="printpdf" target="_blank" action="pdfarchivedborrowerlogs.php" method="POST" class="form-inline">
	<input class="btn btn-success btn-sm" id="button" type="submit" name="createpdf" value="Print PDF">
	<input type="hidden" name="query" value="<?php echo $archivedborrowerLogsSQL;?>">
</form>
<script>
$(document).ready(function(){
	$(document).on("click",".restorebutton",function(){
		var attendanceID = $(this).data("id");
		$(".confirmrestoreborrowerlog").data("id",attendanceID);
	});

	$(".confirmrestoreborrowerlog").click(function(){
		var attendanceID = $(this).data("id");
		$.ajax({
			url:"restoreborrowerlog.php",
			method:"POST",
			data:{attendanceID:attendanceID},
			success:function(data) {
				$("#restoreborrowerlog").modal("hide");
				$(".logs").html(data);
			}
		});
	});

	
	$("#permanentdeleteborrowerlog").on("hide.bs.modal", function(){
		$(this).find("#password").val("").end();
	});
});
</script>
<?php
}
?>