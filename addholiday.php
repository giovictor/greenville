<?php
session_start();
require "dbconnect.php";
if(isset($_POST['holiday']) && isset($_POST['startdate']) && isset($_POST['enddate'])) {
	$username = $_SESSION['librarian'];
	$getuseridSQL = "SELECT * FROM user WHERE username='$username'";
	$getuseridQuery = mysqli_query($dbconnect, $getuseridSQL);
	$getuserid = mysqli_fetch_assoc($getuseridQuery);
	$userID = $getuserid['userID'];

	$holiday = $_POST['holiday'];
	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];

	$addholidaySQL = "INSERT INTO holiday(holiday, startdate, enddate, userID) VALUES('$holiday', '$startdate','$enddate','$userID')";
	$addholiday = mysqli_query($dbconnect, $addholidaySQL);

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
<script>
$(document).ready(function(){
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
<?php
	}
}
?>