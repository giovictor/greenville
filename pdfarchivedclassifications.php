<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$archivedClassificationsSQL = $_POST['query'];
	$archivedClassificationsQuery = mysqli_query($dbconnect,$archivedClassificationsSQL);
	$archivedClassifications = mysqli_fetch_assoc($archivedClassificationsQuery);
	$rows = mysqli_num_rows($archivedClassificationsQuery);
?>
	<title>Archived Classifications List</title>
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
			<h3>Archived Classification List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class="table table-hover table-striped" id="ctable">
			<table class="table table-hover table-bordered">
		<tr>
			<th width="30%">Classification ID</th>
			<th width="60%">Classification</th>
		</tr>
		<?php
			if($rows==0) {
				echo "<tr><td colspan='3'><center><h4>There were no archived classifications.</h4></center></td></tr>";
			} else if($rows>=1) {
				do {
		?>
				<tr>
					<td><?php echo $archivedClassifications['classificationID'];?></td>
					<td><?php echo $archivedClassifications['classification'];?></td>
				</tr>
		<?php
				} while($archivedClassifications = mysqli_fetch_assoc($archivedClassificationsQuery));
			}
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