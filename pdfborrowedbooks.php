<?php
set_time_limit(0);
require "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

if(isset($_POST['createpdf'])) {
	$pdf = new Dompdf();
	ob_start();
	require "dbconnect.php";
	$borrowedbooksSQL = $_POST['query'];
	$borrowedbooksQuery = mysqli_query($dbconnect, $borrowedbooksSQL);
	$borrowedbooks = mysqli_fetch_assoc($borrowedbooksQuery);
	$currentdate = date("Y-m-d");

	$holidaySQL = "SELECT * FROM holiday";
	$holidayQuery = mysqli_query($dbconnect, $holidaySQL);
	$holiday = mysqli_fetch_assoc($holidayQuery);
	$holidayarray = array();
	do {
		$startdate = $holiday['startdate'];
		$enddate = $holiday['enddate'];
		$startdateobj = new DateTime($startdate);
		$enddateobj = new DateTime($enddate);
		$enddateobj->modify("+1 day");
		$holidaydates = new DatePeriod($startdateobj, new DateInterval("P1D"), $enddateobj);
		foreach($holidaydates AS $dates) {
			$holidayarray[] = $dates->format("Y-m-d");
		}
	} while($holiday = mysqli_fetch_assoc($holidayQuery));
?>
	<title>Borrowed Book List</title>
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
			<h3>Borrowed Book List</h3>
			<p><?php echo date("F d, Y"." | "."l");?></p>
	</div>
	<div class="datapdf">
		<table class="table table-hover table-striped">
			<tr>
				<th>ID Number</th>
				<th>Borrower</th>
				<th width="10%">Acc. No.</th>
				<th>Title</th>
				<th>Date Borrowed</th>
				<th>Due Date</th>
				<th>Days Overdue</th>
				<th>Pending Penalty</th>
			</tr>
			<?php
				do {
			?>
				<tr>
					<td><?php echo $borrowedbooks['IDNumber'];?></td>
					<td><?php echo $borrower = $borrowedbooks['lastname'].", ".$borrowedbooks['firstname']." ".$borrowedbooks['mi'];;?></td>
					<td><?php echo $borrowedbooks['accession_no'];?></td>
					<td><?php echo $borrowedbooks['booktitle'];?></td>
					<td><?php echo $borrowedbooks['dateborrowed'];?></td>
					<td><?php echo $duedate = $borrowedbooks['duedate'];?></td>
				<td>	
					<?php
						if(strtotime($currentdate) > strtotime($duedate)) {
							$duedatetime = new Datetime($duedate);
							$currentdatetime = new Datetime($currentdate);
							$datediff = $currentdatetime->diff($duedatetime);
							$daysoverdue = $datediff->days;

							$betweendays = new DatePeriod($duedatetime, new DateInterval("P1D"), $currentdatetime);
							foreach($betweendays AS $days) {
								$day = $days->format("D");
								if($day=="Sat" || $day=="Sun") {
									$daysoverdue--;
								} else if(in_array($days->format("Y-m-d"), $holidayarray)) {
									$daysoverdue--;
								}
							}
							echo "$daysoverdue days(s)";
						} else {
							echo "0 day(s)";
						}
					?>
				</td>
				<td>	
					<?php
						if(strtotime($currentdate) > strtotime($duedate)) {
							$duedatetime = new Datetime($duedate);
							$currentdatetime = new Datetime($currentdate);
							$datediff = $currentdatetime->diff($duedatetime);
							$daysoverdue = $datediff->days;

							$betweendays = new DatePeriod($duedatetime, new DateInterval("P1D"), $currentdatetime);
							foreach($betweendays AS $days) {
								$day = $days->format("D");
								if($day=="Sat" || $day=="Sun") {
									$daysoverdue--;
								} else if(in_array($days->format("Y-m-d"), $holidayarray)) {
									$daysoverdue--;
								}
							}
							echo $daysoverdue * $settings['penalty'].".00";
						} else {
							echo "0.00";
						}
					?>
				</td>
				</tr>
			<?php
				} while($borrowedbooks = mysqli_fetch_assoc($borrowedbooksQuery));
			?>
		</table>
	</div>
<?php
	$data = ob_get_clean();
	$pdf->loadHtml($data);
	$pdf->setPaper("A4","portrait");
	$pdf->render();
	$pdf->stream("gvcborrowedbooksdata",array("Attachment"=>0));
}	
?>