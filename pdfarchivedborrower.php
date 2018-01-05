<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$archivedborrowerSQL = $_POST['query'];
	$archivedborrowerQuery = mysqli_query($dbconnect,$archivedborrowerSQL);
	$archivedborrower = mysqli_fetch_assoc($archivedborrowerQuery);
	$rows = mysqli_num_rows($archivedborrowerQuery);
?>
	<title>Archived Borrower List</title>
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
			<h3>Archived Borrower List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class='table table-hover table-striped table-bordered' id='borrowertable'>
				<tr>
					<th>ID Number</th>
					<th>Name</th>
					<th>Contact Number</th>
					<th>Course</th>
					<th>Date Registered</th>
					<th width="8%">Account Type</th>
					<th width="5%">Account Balance</th>
				</tr>
		<?php
			if($rows==0) {
				echo "<tr><td colspan='9'><center><h4>There were no archived borrowers.</h4></center></td></tr>";
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
				</tr>
		<?php
				} while($archivedborrower = mysqli_fetch_assoc($archivedborrowerQuery));
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