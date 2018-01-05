<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$classificationSQL = $_POST['query'];
	$classificationQuery = mysqli_query($dbconnect, $classificationSQL);
	$classification = mysqli_fetch_assoc($classificationQuery);
?>
	<title>Classifications List</title>
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
			<h3>Classification List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class="table table-hover table-striped" id="ctable">
		<tr>
			<th width="50%">Classification ID</th>
			<th width="50%">Classification</th>
		</tr>
	<?php
	do {
	?>
		<tr>
			<td><?php echo $classificationID = $classification['classificationID'];?></td>
			<td><?php echo $classification['classification'];?></td>
		</tr>
	<?php	
	} while($classification = mysqli_fetch_assoc($classificationQuery));
	?>
	</table>
	</div>
<?php
	$data = ob_get_clean();
	$pdf->loadHtml($data);
	$pdf->setPaper("A4","portrait");
	$pdf->render();
	$pdf->stream("gvcclassficationsdata",array("Attachment"=>0));
}	
?>