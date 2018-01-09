<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['query'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$reservationsSQL = $_POST['query'];
	$reservationsQuery = mysqli_query($dbconnect, $reservationsSQL);
	$reservations = mysqli_fetch_assoc($reservationsQuery);
?>
	<title>Reserved Book List</title>
	<link rel='stylesheet' href='bootstrap/css/bootstrap.min.css'>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Ubuntu" rel="stylesheet">    
	<link rel='stylesheet' href='greenville.css'>
	<div id="header">
		<div id="logoandschool">
			<img id="gvclogopdf" src="pics/gvclogo.png">
			<h2>Greenville College Library</h2>
		</div>
			<p>112 Belfast Street Corner San Salvador, Greenpark Village, Manggahan, Pasig City</p>
			<p>682-37-12 | 681-35-54</p>
			<h3>Reserved Book List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table>
			<tr>
				<th>ID Number</th>
				<th>Borrower</th>
				<th>Accession Number</th>
				<th>Title</th>
				<th>Reservation Date</th>
				<th>Expiration Date</th>
			</tr>
			<?php
				do {
			?>
				<tr>
					<td><?php echo $reservations['IDNumber'];?></td>
					<td><?php echo $reservations['lastname'].", ".$reservations['firstname']." ".$reservations['mi']; ?></td>
					<td><?php echo $reservations['accession_no'];?></td>
					<td><?php echo $reservations['booktitle'];?></td>
					<td><?php echo $reservations['reservationdate'];?></td>
					<td><?php echo $reservations['expdate'];?></td>
				</tr>
			<?php
				} while($reservations = mysqli_fetch_assoc($reservationsQuery));
			?>
		</table>
	</div>
<?php
	$data = ob_get_clean();
	$pdf->loadHtml($data);
	$pdf->setPaper("A4","landscape");
	$pdf->render();
	$pdf->stream("gvcreservationsdata");
}	
?>