<div class="admincontainer">
	<div class="panel panel-success borrowerlogssearchform">
		<div class="panel-heading">
			<h3>Borrower Logs</h3>
		</div>
		<div class="panel-body">
			<form method="GET" id="borrowerlogssearchform">
				<table>
					<tr>
						<td><label>Borrower:</label></td>
						<td>
							<input type="text" name="borrower" id="borrower" class="form-control" placeholder="Search for a borrower">
						</td>
						<td><label>Login Datetime:</label></td>
						<td>
							<input type="date" name="logindate" id="logindate" class="form-control">
						</td>
						<td>
							<input type="time" name="logintime" id="logintime" class="form-control">
						</td>
						<td><label>Logout Datetime:</label></td>
						<td>
							<input type="date" name="logoutdate" id="logoutdate" class="form-control">
						</td>
						<td>
							<input type="time" name="logouttime" id="logouttime" class="form-control">
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" class="btn btn-success btn-sm button" name="borrowerlogssearch" value="Search">
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
		if(isset($_GET['borrowerlogssearch'])) {
			$borrower = $_GET['borrower'];
			$logindate = $_GET['logindate'];
			$logintime = $_GET['logintime'];
			$logoutdate = $_GET['logoutdate'];
			$logouttime = $_GET['logouttime'];
			$logindatetime = $logindate." ".$logintime;
			$logoutdatetime = $logoutdate." ".$logouttime;

			if(!empty($borrower) && empty($logindate) && empty($logintime) && empty($logoutdate) && empty($logouttime)) {
				$borrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=1 AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' ORDER BY attendanceID DESC";
			} else if(!empty($logindate) && !empty($logintime) && empty($borrower) && empty($logoutdate) && empty($logouttime)) {
				$borrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=1 AND logindatetime='$logindatetime' ORDER BY attendanceID DESC";
			} else if(!empty($logoutdate) && !empty($logouttime) && empty($borrower) && empty($logindate) && empty($logintime)) {
					$borrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=1 AND logoutdatetime='$logoutdatetime' ORDER BY attendanceID DESC";
			} else if(!empty($borrower) && !empty($logindate) && !empty($logintime) && empty($logoutdate) && empty($logouttime)) {
					$borrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=1 AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND  logindatetime='$logindatetime' ORDER BY attendanceID DESC";
			} else if(!empty($borrower) && !empty($logoutdate) && !empty($logouttime) && empty($logindate) && empty($logintime)) {
					$borrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=1 AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND logoutdatetime='$logoutdatetime' ORDER BY attendanceID DESC";
			} else if(!empty($logindate) && !empty($logintime) && !empty($logoutdate) && !empty($logouttime) && empty($borrower)) {
					$borrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=1 AND logindatetime='$logindatetime' AND logoutdatetime='$logoutdatetime' ORDER BY attendanceID DESC";
			} else if(!empty($logindate) && !empty($logintime) && !empty($logoutdate) && !empty($logouttime) && !empty($borrower)) {
				$borrowerLogsSQL = "SELECT attendanceID, borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi, logindatetime, logoutdatetime, showstatus FROM attendance JOIN borrower ON borrower.IDNumber=attendance.IDNumber WHERE showstatus=1 AND CONCAT(borrower.IDNumber, borrower.lastname, borrower.firstname, borrower.mi) LIKE '%$borrower%' AND  logindatetime='$logindatetime' AND  logoutdatetime='$logoutdatetime' ORDER BY attendanceID DESC";
			}
		$borrowerLogsQuery = mysqli_query($dbconnect, $borrowerLogsSQL);
		$borrowerLogs = mysqli_fetch_assoc($borrowerLogsQuery);
		$rows = mysqli_num_rows($borrowerLogsQuery);
	?>
	<div class="logs">
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
	</div>
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

		$("#borrowerlogssearchform").submit(function(e){
			var borrower = $("#borrower").val();
			var logindate = $("#logindate").val();
			var logoutdate = $("#logoutdate").val();
			var logintime = $("#logintime").val();
			var logouttime = $("#logouttime").val();
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
			} else if(logindate==logoutdate) {
				if(logintime > logouttime) {
					$("#invalidlogintime").modal("show");
					e.preventDefault();
				}
			}
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
</div>