<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$archivedPublishersSQL = $_POST['query'];
	$archivedPublishersQuery = mysqli_query($dbconnect,$archivedPublishersSQL);
	$archivedPublishers = mysqli_fetch_assoc($archivedPublishersQuery);
	$rows = mysqli_num_rows($archivedPublishersQuery);
?>
	<title>Archived Publishers List</title>
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
			<h3>Archived Publisher List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class="table table-hover table-bordered">
		<tr>
			<th width="30%">Publisher ID</th>
			<th width="60%">Publisher</th>
		</tr>
		<?php
			if($rows==0) {
				echo "<tr><td colspan='3'><center><h4>There were no archived publishers.</h4></center></td></tr>";
			} else if($rows>=1) {
				do {
		?>
				<tr>
					<td><?php echo $archivedPublishers['publisherID'];?></td>
					<td><?php echo $archivedPublishers['publisher'];?></td>
				</tr>
		<?php
				} while($archivedPublishers = mysqli_fetch_assoc($archivedPublishersQuery));
			}
		?>
	</table>
	</div>
<?php
	$data = ob_get_clean();
	$pdf->loadHtml($data);
	$pdf->setPaper("A4","portrait");
	$pdf->render();
	$pdf->stream("gvcpublishersdata",array("Attachment"=>0));
}	
?>