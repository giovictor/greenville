<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$archivedAuthorsSQL = $_POST['query'];
	$archivedAuthorsQuery = mysqli_query($dbconnect,$archivedAuthorsSQL);
	$archivedAuthors = mysqli_fetch_assoc($archivedAuthorsQuery);
	$rows = mysqli_num_rows($archivedAuthorsQuery);
?>
	<title>Archived Author List</title>
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
			<h3>Archived Author List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class="table table-hover table-bordered">
		<tr>
			<th width="30%">Author ID</th>
			<th width="60%">Author</th>
		</tr>
		<?php
			if($rows==0) {
				echo "<tr><td colspan='3'><center><h4>There were no archived authors.</h4></center></td></tr>";
			} else if($rows>=1) {
				do {
		?>
				<tr>
					<td><?php echo $archivedAuthors['authorID'];?></td>
					<td><?php echo $archivedAuthors['author'];?></td>
				</tr>
		<?php
				} while($archivedAuthors = mysqli_fetch_assoc($archivedAuthorsQuery));
			}
		?>
	</table>
	</div>
<?php
	$data = ob_get_clean();
	$pdf->loadHtml($data);
	$pdf->setPaper("A4","portrait");
	$pdf->render();
	$pdf->stream("gvcauthorsdata",array("Attachment"=>0));
}	
?>