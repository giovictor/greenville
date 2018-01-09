<?php
include "barcode/src/BarcodeGenerator.php";
include "barcode/src/BarcodeGeneratorHTML.php";
require "dbconnect.php";
if(isset($_POST['barcode1']) && isset($_POST['barcode2'])) {
?>
<div class="barcode">
<?php
	$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
	$barcode1 = $_POST['barcode1'];
	$barcode2 = $_POST['barcode2'];
	if(!empty($barcode1) && empty($barcode2)) {
		$createbarcodeSQL = "SELECT * FROM book WHERE accession_no='$barcode1'";
		$createbarcodeQuery = mysqli_query($dbconnect, $createbarcodeSQL);
		$createbarcode = mysqli_fetch_assoc($createbarcodeQuery);
		echo "<div id='barcode'>";
		echo "<div style='margin-left:47px;'>".$generator->getBarcode($createbarcode['barcode'],$generator::TYPE_CODE_128, 1, 50)."</div>";
		echo $createbarcode['barcode']."<br>";
		echo $createbarcode['callnumber']." ".$createbarcode['accession_no']." - ".$createbarcode['booktitle'];
		echo "</div>";
	} else if(!empty($barcode1) && !empty($barcode2)) {
		$createbarcodeSQL = "SELECT * FROM book WHERE accession_no BETWEEN $barcode1 AND $barcode2";
		$createbarcodeQuery = mysqli_query($dbconnect, $createbarcodeSQL);
		$createbarcode = mysqli_fetch_assoc($createbarcodeQuery);
		$i=1;
		do {
			echo "<div id='barcode'>";
			echo "<div style='margin-left:47px;'>".$generator->getBarcode($createbarcode['barcode'],$generator::TYPE_CODE_128, 1, 50)."</div>";
			echo $createbarcode['barcode']."<br>";
			echo $createbarcode['callnumber']." ".$createbarcode['accession_no']." - ".$createbarcode['booktitle'];
			echo "</div>";
		} while($createbarcode = mysqli_fetch_assoc($createbarcodeQuery));
	}
?>
</div>
<a target="_blank"  href="printbarcode.php?barcode1=<?php echo $barcode1;?>&barcode2=<?php echo $barcode2;?>">
	<button class="btn btn-success btn-sm button">
		Print Barcode <span class="glyphicon glyphicon-print"></span>
	</button>
</a>
<?php
}
?>