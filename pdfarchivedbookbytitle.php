<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$archivedbookSQL = $_POST['query'];
	$archivedbookQuery = mysqli_query($dbconnect, $archivedbookSQL);
	$archivedbook = mysqli_fetch_assoc($archivedbookQuery);
	$rows = mysqli_num_rows($archivedbookQuery);
?>
	<title>Archived Book List</title>
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
			<h3>Archived Book List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class='table table-hover table-bordered table-striped' id='booktable'>
		<tr>
			<th width="25%">Title</th>
			<th width="28%">Authors</th>
			<th width="21%">Publication Details</th>
			<th width="5%">Copies</th>
			<th width="5%">Remarks</th>
		</tr>
	<?php
		if($rows==0) {
			echo "<tr><td colspan='9'><center><h4>There were no archived books.</h4></center></td></tr>";
		} else if($rows>=1) {
			do {
	?>
			<tr>
				<td><?php echo $archivedbook['callnumber'];?></td>
				<td><?php echo $archivedbook['booktitle'];?></td>
				<td><?php echo $archivedbook['authors'];?></td>
				<td><?php echo $archivedbook['publisher']." c".$archivedbook['publishingyear'];?></td>
				<td><?php echo $archivedbook['copies'];?></td>
				<td><?php echo $archivedbook['bookcondition'];?></td>
			</tr>
	<?php
			} while($archivedbook = mysqli_fetch_assoc($archivedbookQuery));
		}
	?>
</table>
	</div>
<?php
	$data = ob_get_clean();
	$pdf->loadHtml($data);
	$pdf->setPaper("A4","portrait");
	$pdf->render();
	$pdf->stream("gvcarchivedbookdata",array("Attachment"=>0));
}	
?>