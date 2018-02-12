<?php
	set_time_limit(0);
	ob_start();
	$content = ob_get_clean();
	//$content = utf8_encode($content);
	require "dbconnect.php";

if(isset($_POST['query'])) {
	$content .= "<table>
					<tr>
						<th>Title</th>
						<th>Author</th>
						<th>Publisher</th>
						<th>Year</th>
						<th>Copies</th>
					</tr>";
	$query = $_POST['query'];
	$query_run = mysqli_query($dbconnect, $query);
	$data = mysqli_fetch_assoc($query_run);

	do {
		$content .= "<tr>
						<td>".$data['booktitle']."</td>
						<td>".$data['authors']."</td>
						<td>".$data['publisher']."</td>
						<td>".$data['publishingyear']."</td>
						<td>".$data['copies']."</td>
					</tr>";
	} while($data = mysqli_fetch_assoc($query_run));
	$content .= "</table>";
	include "mpdf/mpdf.php";
	$pdf = new mPDF();
	//$pdf->allow_charset_conversion = true;
	//$pdf->charset_in = "UTF_8";
	$pdf->WriteHTML($content);
	$pdf->Output('gvcbookbytitle','I');
}
?>