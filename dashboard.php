<?php
require "dbconnect.php";
?>
<div id="info">
	<a href="?page=books">
		<div class="well dashboardwell" id="well1">
			<div class="quantity">
				<?php
					$bookSQL = "SELECT COUNT(*) AS noofbooks FROM book WHERE status!='Archived'";
					$bookQuery = mysqli_query($dbconnect, $bookSQL);
					$noofbooks = mysqli_fetch_assoc($bookQuery);
					echo "<p>".$noofbooks['noofbooks']."</p>";
				?>
			</div>
			<img src="images/booksicon.png" class="wellicon">
			<p>Books</p>
		</div>
	</a>
	<a href="?page=borrowers">
		<div class="well dashboardwell" id="well2">
			<div class="quantity">
				<?php
					$borrowerSQL = "SELECT COUNT(*) AS noofborrowers FROM borrower";
					$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
					$noofborrowers = mysqli_fetch_assoc($borrowerQuery);
					echo "<p>".$noofborrowers['noofborrowers']."</p>";
				?>
			</div>
			<img src="images/borrowersicon.png" id="borrowersicon">
			<p>Borrowers</p>
		</div>
	</a>
	<a href="?page=vrs">
		<div class="well dashboardwell" id="well3">
			<div class="quantity">
				<?php
					$reservationSQL = "SELECT COUNT(*) AS noofreservation FROM reservation WHERE showstatus=1";
					$reservationQuery = mysqli_query($dbconnect, $reservationSQL);
					$noofreservation = mysqli_fetch_assoc($reservationQuery);
					echo "<p>".$noofreservation['noofreservation']."</p>";
				?>
			</div>
			<img src="images/reservedbooksicon.png" class="wellicon">
			<p>Reserved Books</p>
		</div>
	</a>
	<a href="?page=vbr">
		<div class="well dashboardwell" id="well4">
			<div class="quantity">
				<?php
					$borrowedbookSQL = "SELECT * FROM booklog WHERE datereturned IS NULL";
					$borrowedbookQuery = mysqli_query($dbconnect, $borrowedbookSQL);
					$noborrowedbooks = mysqli_num_rows($borrowedbookQuery);
					echo "<p>$noborrowedbooks</p>";
				?>
			</div>
			<img src="images/borrowedbooksicon.png" class="wellicon">
			<p>Borrowed Books</p>
		</div>
	</a>
	<div class="well dashboardwell" id="well5">
		<div class="quantity">
			<?php
				$availablebookSQL = "SELECT COUNT(*) AS noofavailablebooks FROM book WHERE status='On Shelf'";
				$availablebookQuery = mysqli_query($dbconnect, $availablebookSQL);
				$noofavailablebooks = mysqli_fetch_assoc($availablebookQuery);
				echo "<p>".$noofavailablebooks['noofavailablebooks']."</p>";
			?>
		</div>
		<img src="images/availablebooksicon.png" class="wellicon">
		<p> Available Books</p>
	</div>
	<a href="?page=classifications">
		<div class="well dashboardwell" id="well6">
			<div class="quantity">
				<?php
					$classificationSQL = "SELECT COUNT(*) AS noofclassification FROM classification WHERE status=1";
					$classificationQuery = mysqli_query($dbconnect, $classificationSQL);
					$noofclassification = mysqli_fetch_assoc($classificationQuery);
					echo "<p>".$noofclassification['noofclassification']."</p>";
				?>
			</div>
			<img src="images/classificationsicon.png" class="wellicon">
			<p>Classifications</p>
		</div>
	</a>
	<a href="?page=authors">
		<div class="well dashboardwell" id="well6">
			<div class="quantity">
				<?php
					$authorSQL = "SELECT COUNT(*) AS noofauthors FROM author WHERE status=1";
					$authorQuery = mysqli_query($dbconnect, $authorSQL);
					$noofauthors = mysqli_fetch_assoc($authorQuery);
					echo "<p>".$noofauthors['noofauthors']."</p>";
				?>
			</div>
			<img src="images/authorsicon.png" class="wellicon">
			<p>Authors</p>
		</div>
	</a>
	<a href="?page=publishers">
		<div class="well dashboardwell" id="well6">
			<div class="quantity">
				<?php
					$publisherSQL = "SELECT COUNT(*) AS noofpublishers FROM publisher WHERE status=1";
					$publisherQuery = mysqli_query($dbconnect, $publisherSQL);
					$noofpublishers = mysqli_fetch_assoc($publisherQuery);
					echo "<p>".$noofpublishers['noofpublishers']."</p>";
				?>
			</div>
			<img src="images/publishersicon.png" class="wellicon">
			<p>Publishers</p>
		</div>
	</a>
</div>
