<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$bookSQL = $_POST['query'];
	$bookQuery = mysqli_query($dbconnect, $bookSQL);
	$book = mysqli_fetch_assoc($bookQuery);
?>
	<title>Book List</title>
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
			<h3>Book List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class='table table-hover table-striped' id='booktable'>
				<tr>
					<th width='5%'>Call No.</th>
					<th width='22%'>Title</th>
					<th width='34%'>Authors</th>
					<th width='34%'>Publication Details</th>
					<th width='5%'>Copies</th>
				</tr>
	<?php
	do {
	?>			
				<tr>
					<td><?php echo $book['callnumber'];?></td>
					<td><?php echo $book['booktitle'];?></td>
					<td><?php echo $book['authors'];?></td>
					<td><?php echo $book['publisher']." c".$book['publishingyear'];?></td>
					<td><?php echo $book['copies'];?></td>
				</tr>
	<?php
	} while($book = mysqli_fetch_assoc($bookQuery));
	?>
		</table>
	</div>
<?php
	$data = ob_get_clean();
	$pdf->loadHtml($data);
	$pdf->setPaper("A4","portrait");
	$pdf->render();
	$pdf->stream("gvcbookdata",array("Attachment"=>0));
}	
?>