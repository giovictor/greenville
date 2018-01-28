<title>Settings</title>
<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
?>
<div class="admincontainer">
<div class="settings">
<h3>SETTINGS</h3>
<?php
require "dbconnect.php";	
	$settingsSQL = "SELECT * FROM settings";
	$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
	$settings = mysqli_fetch_assoc($settingsQuery);
?>
<div class="settingsform">
	<form id="settingsform">
		<table id="settingsformtable">
				<tr>
					<!--<td><label>Due Days:</label></td>
					<td>
						<input type="text" name="duedays" id="duedays" class="form-control" value="<?php echo $settings['duedays'];?>">
					</td>-->
					<td><label>Penalty:</label></td>
					<td>
						<input type="text" name="penalty" id="penalty" class="form-control" value="<?php echo $settings['penalty'];?>">
					</td>
					<td><label>Reserve Limit:</label></td>
					<td>
						<input type="text" name="reservelimit" id="reservelimit" class="form-control" value="<?php echo $settings['reservelimit'];?>">
					</td>
				</tr>
				<tr>
					<td><label>Borrow Limit:</label></td>
					<td>
						<input type="text" name="borrowlimit" id="borrowlimit" class="form-control" value="<?php echo $settings['borrowlimit'];?>">
					</td>
				</tr>
			<tr>
				<td>
					<button class="btn btn-success btn-md button" id="savesettings">Save Settings <span class="glyphicon glyphicon-save"></span></button>
				</td>
			</tr>
		</table>
	</form>
</div>
	<h3>NON WORKING DAYS</h3>
	<form id="holidayform" class="form-inline">
		<label>Holiday:</label>
		<input type="text" name="holiday" id="holiday" class="form-control">
		<label>Start Date:</label>
		<input type="date" name="startdate" id="startdate" class="form-control">
		<label>End Date:</label>
		<input type="date" name="enddate" id="enddate" class="form-control">
		<button class="btn btn-success btn-md button" id="submitholiday">Submit <span class="glyphicon glyphicon-save"></span></button>
	</form>
	<div class="holidaytable">
		<?php
			$holidaySQL = "SELECT * FROM holiday";
			$holidayQuery = mysqli_query($dbconnect, $holidaySQL);
			$holiday = mysqli_fetch_assoc($holidayQuery);
			$rows = mysqli_num_rows($holidayQuery);

			if($rows>=1) {
		?>
		<table class="table table-hover" id="holidaytable">
			<tr>
				<th width='25%'>Holiday</th>
				<th width='25%'>Start Date</th>
				<th width='25%'>End Date</th>
				<th width='15%'> </th>
			</tr>
			<?php
				do {
			?>
				<tr>
					<td><?php echo $holiday['holiday'];?></td>
					<td><?php echo $holiday['startdate'];?></td>
					<td><?php echo $holiday['enddate'];?></td>
					<td>
						<a href="?page=editupdateholiday&holidayID=<?php echo $holiday['holidayID'];?>" class="btn btn-success btn-sm">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
						<button class="btn btn-danger btn-sm deleteholiday" data-id="<?php echo $holiday['holidayID'];?>" data-toggle="modal" data-target="#deleteholiday">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
					</td>
				</tr>
			<?php
				} while($holiday = mysqli_fetch_assoc($holidayQuery));
			?>
		</table>
		<?php
			}
		?>
	</div>
</div>
</div>
<script>
$(document).ready(function(){
	$("#holidayform").submit(function(e){
		e.preventDefault();
		var holiday = $("#holiday").val();
		var startdate = $("#startdate").val();
		var enddate = $("#enddate").val();
		if(holiday=="" ||  startdate=="" || enddate=="") {
			$("#emptyinput").modal("show");
		} else if(startdate > enddate) {
			$("#invalidstartdate").modal("show");
		} else {
			$.ajax({
				url:"addholiday.php",
				method:"POST",
				data:{holiday:holiday, startdate:startdate, enddate:enddate},
				beforeSend:function() {
					$("#submitholiday").html("Add Holiday...");
				},
				success:function(data) {
					$("#submitholiday").html("Submit <span class='glyphicon glyphicon-save'></span>");
					$(".holidaytable").html(data);
					$("#addmsg6").modal("show");
				}
			});
		}
	});

	$("#invalidstartdate").on("hide.bs.modal", function(){
		$("#startdate").val("").end();
		$("#holiday").val("").focus();
		$("#enddate").val("").end();
	});

	$(document).on("click",".deleteholiday",function(){
		var holidayID = $(this).data("id");
		$(".confirmdeleteholiday").data("id",holidayID);
	});

	$(".confirmdeleteholiday").click(function(){
		var holidayID = $(this).data("id");
		$.ajax({
			url:"deleteholiday.php",
			method:"POST",
			data:{holidayID:holidayID},
			success:function(data) {
				$("#deleteholiday").modal("hide");
				$(".holidaytable").html(data);
			}
		});
	});

	$("#settingsform").submit(function(e){
		e.preventDefault();
		var duedays = $("#duedays").val();
		var penalty = $("#penalty").val();
		var borrowlimit = $("#borrowlimit").val();
		var reservelimit = $("#reservelimit").val();

		if(duedays=="" || penalty=="" || borrowlimit=="" || reservelimit=="") {
			$("#emptyinput").modal("show");
		} else {
			$.ajax({
				url:"updatesettings.php",
				method:"POST",
				data:{duedays:duedays, penalty:penalty, borrowlimit:borrowlimit, reservelimit:reservelimit},
				beforeSend:function(data) {
					$("#savesettings").html("Saving Settings...");
				},
				success:function(data) {
					$("#savesettings").html("Save Settings <span class='glyphicon glyphicon-save'></span>");
					$(".settingscontent").html(data);
					$("#settingsupdated").modal("show");
				}
			});
		}
	});
});
</script>