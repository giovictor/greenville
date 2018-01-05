<div class="settings">
<?php
require "dbconnect.php";	
	if(isset($_GET['holidayID'])) {
		$holidayID = $_GET['holidayID'];
		$holidayeditSQL = "SELECT * FROM holiday WHERE holidayID='$holidayID'";
		$holidayeditQuery = mysqli_query($dbconnect, $holidayeditSQL);
		$holidayedit = mysqli_fetch_assoc($holidayeditQuery);
		$rows = mysqli_num_rows($holidayeditQuery);
	}
?>
	<a href="?page=librarysettings" style="margin-top:20px;" class="btn btn-success btn-md button">
		Back To Settings
		<span class="glyphicon glyphicon-menu-hamburger"> </span>
	</a>
	<h3>NON WORKING DAYS</h3>
	<form id="holidayform" class="form-inline">
		<label>Holiday:</label>
		<input type="text" name="holiday" id="holiday" class="form-control" value="<?php echo $holidayedit['holiday'];?>">
		<label>Start Date:</label>
		<input type="date" name="startdate" id="startdate" class="form-control" value="<?php echo $holidayedit['startdate'];?>">
		<label>End Date:</label>
		<input type="date" name="enddate" id="enddate" class="form-control" value="<?php echo $holidayedit['enddate'];?>">
		<input type="hidden" name="holidayID" id="holidayID" value="<?php echo $holidayID;?>">
		<button class="btn btn-success btn-md button" id="submitholiday">Update Holiday <span class="glyphicon glyphicon-save"></span></button>
	</form>
	<div class="holidaytable">
		<?php
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
					<td><?php echo $holidayedit['holiday'];?></td>
					<td><?php echo $holidayedit['startdate'];?></td>
					<td><?php echo $holidayedit['enddate'];?></td>
					<td>
						<a href="?page=editupdateholiday&holidayID=<?php echo $holidayedit['holidayID'];?>" class="btn btn-success btn-sm">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					<button class="btn btn-danger btn-sm deleteholiday" data-id="<?php echo $holidayedit['holidayID'];?>" data-toggle="modal" data-target="#deleteholiday">
						<span class="glyphicon glyphicon-trash"></span>
					</button>
				</tr>
			<?php
				} while($holidayedit = mysqli_fetch_assoc($holidayeditQuery));
			?>
		</table>
		<?php
			}
		?>
	</div>
</div>
<script>
$(document).ready(function(){
	$("#holidayform").submit(function(e){
		e.preventDefault();
		var holidayID = $("#holidayID").val();
		var holiday = $("#holiday").val();
		var startdate = $("#startdate").val();
		var enddate = $("#enddate").val();

		if(holiday=="" ||  startdate=="" || enddate=="") {
			$("#emptyinput").modal("show");
		} else if(startdate > enddate) {
			$("#invalidstartdate").modal("show");
		} else {
			$.ajax({
				url:"editholiday.php",
				method:"POST",
				data:{holidayID:holidayID, holiday:holiday, startdate:startdate, enddate:enddate},
				beforeSend:function() {
					$("#submitholiday").html("Updating Holiday...");
				},
				success:function(data) {
					$("#submitholiday").html("Update Holiday <span class='glyphicon glyphicon-save'></span>");
					$(".holidaytable").html(data);
					var holiday = $("#holiday").val("");
					var startdate = $("#startdate").val("");
					var enddate = $("#enddate").val("");
					$("#editmsg4").modal("show");
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
});
</script>