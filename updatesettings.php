<?php
require "dbconnect.php";
if(isset($_POST['duedays']) && isset($_POST['penalty']) && isset($_POST['borrowlimit']) && isset($_POST['reservelimit'])) {
	$duedays = $_POST['duedays'];
	$penalty = $_POST['penalty'];
	$borrowlimit = $_POST['borrowlimit'];
	$reservelimit = $_POST['reservelimit'];

		$updatesettingsSQL = "UPDATE settings SET duedays='$duedays', penalty='$penalty', borrowlimit='$borrowlimit', reservelimit='$reservelimit'";
		$updatesettings = mysqli_query($dbconnect, $updatesettingsSQL);

		$settingsSQL = "SELECT * FROM settings";
		$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
		$settings = mysqli_fetch_assoc($settingsQuery);
?>
<form id="settingsform">
	<table id="settingsformtable">
			<tr>
				<td><label>Due Days:</label></td>
				<td>
					<input type="text" name="duedays" id="duedays" class="form-control" value="<?php echo $settings['duedays'];?>">
				</td>
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
<script>
$(document).ready(function(){
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
<?php
}
?>