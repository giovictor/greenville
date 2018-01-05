<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$archivedreservationsSQL = $_POST['query'];
	$archivedreservationsQuery = mysqli_query($dbconnect, $archivedreservationsSQL);
	$archivedreservations = mysqli_fetch_assoc($archivedreservationsQuery);
	$rows = mysqli_num_rows($archivedreservationsQuery);
?>
	<title>Archived Reserved Book List</title>
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
			<h3>Archived Reserved Book List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class="table table-hover table-bordered">
		<tr>
			<th>ID Number</th>
			<th>Borrower</th>
			<th>Accession Number</th>
			<th>Title</th>
			<th>Reservation Date</th>
			<th>Expiration Date</th>
		</tr>
		<?php
			if($rows==0) {
				echo "<tr><td style='text-align:center;' colspan='6'><h4>There were no archived reservations.</h4></td></tr>";
			} else if($rows>=1) {
				do {
		?>
			<tr>
				<td><?php echo $archivedreservations['IDNumber'];?></td>
				<td><?php echo $archivedreservations['lastname'].", ".$archivedreservations['firstname']." ".$archivedreservations['mi']; ?></td>
				<td><?php echo $archivedreservations['accession_no'];?></td>
				<td><?php echo $archivedreservations['booktitle'];?></td>
				<td><?php echo $archivedreservations['reservationdate'];?></td>
				<td><?php echo $archivedreservations['expdate'];?></td>
			</tr>
		<?php
			} while($archivedreservations = mysqli_fetch_assoc($archivedreservationsQuery));
		}
		?>
	</table>
	</div>
<?php
	$data = ob_get_clean();
	$pdf->loadHtml($data);
	$pdf->setPaper("A4","portrait");
	$pdf->render();
	$pdf->stream("gvcreservationsdata",array("Attachment"=>0));
}	
?>