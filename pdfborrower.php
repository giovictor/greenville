<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$borrowerSQL = $_POST['query'];
	$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
	$borrower = mysqli_fetch_assoc($borrowerQuery);
?>
	<title>Borrower List</title>
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
			<h3>Borrower List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class='table table-hover table-striped' id='booktable'>
				<tr>
					<th width='15%'>ID Number</th>
					<th width='15%'>Name</th>
					<th width='5%'>Contact Number</th>
					<th width='15%'>Course</th>
					<th width='10%'>Date Registered</th>
					<th width='5%'>Account Type</th>
					<th width='5%'>Account Balance</th>
					<th width='5%'>Status</th>
				</tr>
	<?php
	do {
	?>			
				<tr>
					<td><?php echo $borrower['IDNumber']; ?></td>
					<td><?php echo $borrower['lastname'].", ".$borrower['firstname']." ".$borrower['mi']; ?></td>
					<td><?php echo $borrower['contactnumber']; ?></td>
					<td><?php echo $borrower['course']; ?></td>
					<td><?php echo $borrower['dateregistered']; ?></td>
					<td><?php echo $borrower['accounttype']; ?></td>
					<td><?php echo $borrower['accountbalance']; ?></td>
					<td><?php echo $borrower['status']; ?></td>
				</tr>
	<?php
	} while($borrower = mysqli_fetch_assoc($borrowerQuery));
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