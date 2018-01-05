<?php
require "dbconnect.php";
if(isset($_POST['idnumber'])) {
	$idnumber = $_POST['idnumber'];
	$borrowerSQL = "SELECT * FROM borrower WHERE IDNumber='$idnumber'";
	$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
	$borrower = mysqli_fetch_assoc($borrowerQuery);
?>
	<h3><?php echo $borrower['lastname'].", ".$borrower['firstname']." ".$borrower['mi'];?></h3>
	<p>ID Number: <?php echo $borrower['IDNumber'];?></p>
	<p>Contact Number: <?php echo $borrower['contactnumber'];?></p>
	<p>Course: <?php echo $borrower['course'];?></p>
	<p>Date Registered: <?php echo $borrower['dateregistered'];?></p>
	<p>Account Type: <?php echo $borrower['accounttype'];?></p>
	<p>Account Balance: <?php echo $borrower['accountbalance'];?></p>
	<p>Status: <?php echo $borrower['status'];?></p>
<?php
}
?>