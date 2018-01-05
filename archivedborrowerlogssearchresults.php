<div class="admincontainer">
	<div class="panel panel-success borrowerlogssearchform">
		<div class="panel-heading">
			<h3>Archived Borrower Logs</h3>
		</div>
		<div class="panel-body">
			<form method="GET" id="archivedborrowerlogssearchform">
				<table>
					<tr>
						<td><label>Borrower:</label></td>
						<td>
							<input type="text" name="archivedborrower" id="archivedborrower" class="form-control" placeholder="Search for a borrower">
						</td>
						<td><label>Login Datetime:</label></td>
						<td>
							<input type="date" name="archivedlogindate" id="archivedlogindate" class="form-control">
						</td>
						<td>
							<input type="time" name="archivedlogintime" id="archivedlogintime" class="form-control">
						</td>
						<td><label>Logout Datetime:</label></td>
						<td>
							<input type="date" name="archivedlogoutdate" id="archivedlogoutdate" class="form-control">
						</td>
						<td>
							<input type="time" name="archivedlogouttime" id="archivedlogouttime" class="form-control">
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" class="btn btn-success btn-sm button" name="archivedborrowerlogssearch" value="Search">
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<?php
	require "dbconnect.php";
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	} 
		if(isset($_GET['archivedborrowerlogssearch'])) {
			$archivedborrower = $_GET['archivedborrower'];
			$archivedlogindate = $_GET['archivedlogindate'];
			$archivedlogintime = $_GET['archivedlogintime'];
			$archivedlogoutdate = $_GET['archivedlogoutdate'];
			$archivedlogouttime = $_GET['archivedlogouttime'];
			$archivedlogindatetime = $archivedlogindate." ".$archivedlogintime;
			$archivedlogoutdatetime = $archivedlogoutdate." ".$archivedlogouttime;

			if(!empty($archivedborrower) && empty($archivedlogindate) && empty($archivedlogintime) && empty($archivedlogoutdate) && empty($archivedlogouttime)) {
				$archivedborrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=0 AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$archivedborrower%' ORDER BY attendanceID DESC";
			} else if(!empty($archivedlogindate) && !empty($archivedlogintime) && empty($archivedborrower) && empty($archivedlogoutdate) && empty($archivedlogouttime)) {
				$archivedborrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=0 AND logindatetime='$archivedlogindatetime' ORDER BY attendanceID DESC";
			} else if(!empty($archivedlogoutdate) && !empty($archivedlogouttime) && empty($archivedborrower) && empty($archivedlogindate) && empty($archivedlogintime)) {
					$archivedborrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=0 AND logoutdatetime='$archivedlogoutdatetime' ORDER BY attendanceID DESC";
			} else if(!empty($archivedborrower) && !empty($archivedlogindate) && !empty($archivedlogintime) && empty($archivedlogoutdate) && empty($archivedlogouttime)) {
					$archivedborrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=0 AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$archivedborrower%' AND  logindatetime='$archivedlogindatetime' ORDER BY attendanceID DESC";
			} else if(!empty($archivedborrower) && !empty($archivedlogoutdate) && !empty($archivedlogouttime) && empty($archivedlogindate) && empty($archivedlogintime)) {
					$archivedborrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=0 AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$archivedborrower%' AND logoutdatetime='$archivedlogoutdatetime' ORDER BY attendanceID DESC";
			} else if(!empty($archivedlogindate) && !empty($archivedlogintime) && !empty($archivedlogoutdate) && !empty($archivedlogouttime) && empty($archivedborrower)) {
					$archivedborrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=0 AND logindatetime='$archivedlogindatetime' AND logoutdatetime='$archivedlogoutdatetime' ORDER BY attendanceID DESC";
			} else if(!empty($archivedlogindate) && !empty($archivedlogintime) && !empty($archivedlogoutdate) && !empty($archivedlogouttime) && !empty($archivedborrower)) {
				$archivedborrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=0 AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$archivedborrower%' AND  logindatetime='$archivedlogindatetime' AND  logoutdatetime='$archivedlogoutdatetime' ORDER BY attendanceID DESC";
			}
		$archivedborrowerLogsQuery = mysqli_query($dbconnect, $archivedborrowerLogsSQL);
		$archivedborrowerLogs = mysqli_fetch_assoc($archivedborrowerLogsQuery);
		$rows = mysqli_num_rows($archivedborrowerLogsQuery);
	?>
	<div class="logs">
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
					echo "<tr><td colspan='7'><center><h4>No results found.</h4></center></td></tr>";
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
	</div>
</div>
<script>
$(document).ready(function(){
	$("#archivedborrowerlogssearchform").submit(function(e){
		var borrower = $("#archivedborrower").val();
		var logindate = $("#archivedlogindate").val();
		var logoutdate = $("#archivedlogoutdate").val();
		var logintime = $("#archivedlogintime").val();
		var logouttime = $("#archivedlogouttime").val();
		if(borrower=="" && logindate=="" && logoutdate=="" && logintime=="" && logouttime=="") {
			e.preventDefault();
			$("#emptysearch").modal("show");
		} else if(logindate!="" && logintime=="") {
			e.preventDefault();
			$("#providedateandtime").modal("show");
			$("#logintime").focus();
		} else if(logintime!="" && logindate=="") {
			e.preventDefault();
			$("#providedateandtime").modal("show");
			$("#logindate").focus();
		} else if(logoutdate!="" && logouttime=="") {
			e.preventDefault();
			$("#providedateandtime").modal("show");
			$("#logouttime").focus();
		} else if(logouttime!="" && logoutdate=="") {
			e.preventDefault();
			$("#providedateandtime").modal("show");
			$("#logoutdate").focus();
		} else if(logindate > logoutdate) {
			$("#invalidlogindate").modal("show");
			e.preventDefault();
		}
	});

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
