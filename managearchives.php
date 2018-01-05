<title>Archives</title>
<div class="archives">
	<h4>Welcome to Greenville College Library Archives</h4>
	<h5>Some lists of records or data that is hidden or archived and cannot be shown to any user but can be restored or be permanently deleted.</h5> 
	<a href="?page=archvsbooks">
		<div class="well archivewell">
			<div class="archivequantity">
				<?php
					$archivedbookSQL = "SELECT COUNT(*) AS noofarchivedbooks FROM book WHERE status='Archived'";
					$archivedbookQuery = mysqli_query($dbconnect, $archivedbookSQL);
					$archivedbook = mysqli_fetch_assoc($archivedbookQuery);
					echo "<p>".$archivedbook['noofarchivedbooks']."</p>";
				?>
			</div>
			<img src="pics/booksicon.png" class="wellicon">
			<p>Books</p>
		</div>
	</a>
	<a href="?page=archvsborrowers">
		<div class="well archivewell">
			<div class="archivequantity">
				<?php
					$archivedborrowerSQL = "SELECT COUNT(*) AS noofarchivedborrowers FROM borrower WHERE status='Inactive'";
					$archivedborrowerQuery = mysqli_query($dbconnect, $archivedborrowerSQL);
					$archivedborrower = mysqli_fetch_assoc($archivedborrowerQuery);
					echo "<p>".$archivedborrower['noofarchivedborrowers']."</p>";
				?>
			</div>
			<img src="pics/borrowersicon.png" id="borrowersicon">
			<p>Borrowers</p>
		</div>
	</a>
	<a href="?page=archvsrs">
		<div class="well archivewell">
			<div class="archivequantity">
				<?php
					$archivedreservationSQL = "SELECT COUNT(*) AS noofarchivedreservations FROM reservation WHERE showstatus=0";
					$archivedreservationQuery = mysqli_query($dbconnect, $archivedreservationSQL);
					$archivedreservation = mysqli_fetch_assoc($archivedreservationQuery);
					echo "<p>".$archivedreservation['noofarchivedreservations']."</p>";
				?>
			</div>
			<img src="pics/reservedbooksicon.png" class="wellicon">
			<p>Reservations</p>
		</div>
	</a>
	<a href="?page=archvsbklogs">
		<div class="well archivewell">
			<div class="archivequantity">
				<?php
					$archivedbooklogSQL = "SELECT COUNT(*) AS noofarchivedbooklogs FROM booklog WHERE showstatus=0";
					$archivedbooklogQuery = mysqli_query($dbconnect, $archivedbooklogSQL);
					$archivedbooklog = mysqli_fetch_assoc($archivedbooklogQuery);
					echo "<p>".$archivedbooklog['noofarchivedbooklogs']."</p>";
				?>
			</div>
			<img src="pics/borrowedbooksicon.png" class="wellicon">
			<p>Book Logs</p>
		</div>
	</a>
	<a href="?page=archvsbrlogs">
		<div class="well archivewell">
			<div class="archivequantity">
				<?php
					$archivedborrowerlogSQL = "SELECT COUNT(*) AS noofarchivedborrowerlogs FROM attendance WHERE showstatus=0";
					$archivedborrowerlogQuery = mysqli_query($dbconnect, $archivedborrowerlogSQL);
					$archivedborrowerlog = mysqli_fetch_assoc($archivedborrowerlogQuery);
					echo "<p>".$archivedborrowerlog['noofarchivedborrowerlogs']."</p>";
				?>
			</div>
			<img src="pics/borrowerlogsicon.png" class="wellicon">
			<p>Borrower Logs</p>
		</div>
	</a>
	<a href="?page=archvsc">
		<div class="well archivewell">
			<div class="archivequantity">
				<?php
					$archivedclassificationSQL = "SELECT COUNT(*) AS noofarchivedclassifications FROM classification WHERE status=0";
					$archivedclassificationQuery = mysqli_query($dbconnect, $archivedclassificationSQL);
					$archivedclassification = mysqli_fetch_assoc($archivedclassificationQuery);
					echo "<p>".$archivedclassification['noofarchivedclassifications']."</p>";
				?>
			</div>
			<img src="pics/classificationsicon.png" class="wellicon">
			<p>Classifications</p>
		</div>
	</a>
	<a href="?page=archvsa">
		<div class="well archivewell">
			<div class="archivequantity">
				<?php
					$archivedauthorSQL = "SELECT COUNT(*) AS noofarchivedauthors FROM author WHERE status=0";
					$archivedauthorQuery = mysqli_query($dbconnect, $archivedauthorSQL);
					$archivedauthor = mysqli_fetch_assoc($archivedauthorQuery);
					echo "<p>".$archivedauthor['noofarchivedauthors']."</p>";
				?>
			</div>
			<img src="pics/authorsicon.png" class="wellicon">
			<p>Authors</p>
		</div>
	</a>
	<a href="?page=archvsp">
		<div class="well archivewell">
			<div class="archivequantity">
				<?php
					$archivedpublisherSQL = "SELECT COUNT(*) AS noofarchivedpublishers FROM publisher WHERE status=0";
					$archivedpublisherQuery = mysqli_query($dbconnect, $archivedpublisherSQL);
					$archivedpublisher = mysqli_fetch_assoc($archivedpublisherQuery);
					echo "<p>".$archivedpublisher['noofarchivedpublishers']."</p>";
				?>
			</div>
			<img src="pics/publishersicon.png" class="wellicon">
			<p>Publishers</p>
		</div>
	</a>
</div>