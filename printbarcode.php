<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;
if(isset($_GET['barcode1']) && isset($_GET['barcode2'])) {
	$pdf = new Dompdf();
	ob_start();
	include "barcode/src/BarcodeGenerator.php";
	include "barcode/src/BarcodeGeneratorHTML.php";
	require "dbconnect.php";
?>
<link rel="stylesheet" href="greenville.css">
<div class="printbarcode">
<?php
	$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
	$barcode1 = $_GET['barcode1'];
	$barcode2 = $_GET['barcode2'];
	if(!empty($barcode1) && empty($barcode2)) {
		$createbarcodeSQL = "SELECT * FROM book WHERE accession_no='$barcode1'";
		$createbarcodeQuery = mysqli_query($dbconnect, $createbarcodeSQL);
		$createbarcode = mysqli_fetch_assoc($createbarcodeQuery);
		echo "<div id='printbarcode'><center>";
		echo "<div style='margin-left:10%;'>".$generator->getBarcode($createbarcode['barcode'],$generator::TYPE_CODE_128, 1, 50)."</div>";
		echo $createbarcode['barcode']."<br>";
		echo $createbarcode['callnumber']." ".$createbarcode['accession_no']." - ".$createbarcode['booktitle'];
		echo "</center></div>";
	} else if(!empty($barcode1) && !empty($barcode2)) {
		$createbarcodeSQL = "SELECT * FROM book WHERE accession_no BETWEEN $barcode1 AND $barcode2";
		$createbarcodeQuery = mysqli_query($dbconnect, $createbarcodeSQL);
		$createbarcode = mysqli_fetch_assoc($createbarcodeQuery);
		$i=1;
		do {
			echo "<div id='printbarcode'><center>";
			echo "<div style='margin-left:10%;'>".$generator->getBarcode($createbarcode['barcode'],$generator::TYPE_CODE_128, 1, 50)."</div>";
			echo $createbarcode['barcode']."<br>";
			echo $createbarcode['callnumber']." ".$createbarcode['accession_no']." - ".$createbarcode['booktitle'];
			echo "</center></div>";
		} while($createbarcode = mysqli_fetch_assoc($createbarcodeQuery));
	}
?>
</div>
<?php
	$data = ob_get_clean();
	$pdf->loadHtml($data);
	$pdf->setPaper("A4","landscape");
	$pdf->render();
	$pdf->stream("gvcbarcode",array("Attachment"=>0));
}	
?>