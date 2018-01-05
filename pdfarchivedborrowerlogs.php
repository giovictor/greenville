<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$archivedborrowerLogsSQL = $_POST['query'];
	$archivedborrowerLogsQuery = mysqli_query($dbconnect, $archivedborrowerLogsSQL);
	$archivedborrowerLogs = mysqli_fetch_assoc($archivedborrowerLogsQuery);
	$rows = mysqli_num_rows($archivedborrowerLogsQuery);
?>
	<title>Archived Borrower Logs List</title>
	<link rel='stylesheet' href='bootstrap/css/bootstrap.min.css'>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Ubuntu" rel="stylesheet">    
	<link rel='stylesheet' href='greenville.css'>
	<div id="header">
		<div id="logoandschool">
			<img id="gvclogopdf" src="pics/gvclogo.jpg">
			<h2>Greenville College Library</h2>
		</div>
			<p>112 Belfast Street Corner San Salvador, Greenpark Village, Manggahan, Pasig City</p>
			<p>682-37-12 | 681-35-54</p>
			<h3>Archived Borrower Logs List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class='table table-hover table-bordered'>
			<tr>
				<th>ID Number</th>
				<th>Borrower</th>
				<th>Login Datetime</th>
				<th>Logout Datetime</th>
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
					<td><?php echo $archivedborrowerLogs['logindatetime'];?></td>
					<td><?php echo $archivedborrowerLogs['logoutdatetime']; ?></td>
				</tr>
		<?php	
				} while($archivedborrowerLogs = mysqli_fetch_assoc($archivedborrowerLogsQuery));
			}
		?>
	</table>
	</div>
<?php
	$data = ob_get_clean();
	$pdf->loadHtml($data);
	$pdf->setPaper("A4","portrait");
	$pdf->render();
	$pdf->stream("gvcborrowerdata",array("Attachment"=>0));
}	
?>