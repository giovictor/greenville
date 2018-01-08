<?php
	require "dbconnect.php";
	$classificationSQL = "SELECT * FROM classification WHERE status=1";
	$classificationQuery = mysqli_query($dbconnect, $classificationSQL);
	$classification = mysqli_fetch_assoc($classificationQuery);
?>

<nav class="navbar navbar-default navbar-static-top" id="gvcnavbar">
	<div class="navbar-header">
		<a href="index.php" class="navbar-brand">
			<span><img src="pics/gvclogo.png" id="gvclogo"></span>
			Greenville College Library
		</a>
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#gvcnavbarcollapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<div class="collapse navbar-collapse" id="gvcnavbarcollapse">
		<ul class="nav navbar-nav navbar-right" id="navbarright">
			<?php
				if(empty($_SESSION) || isset($_SESSION['borrower'])){
			?>
					<li><a href="index.php">HOME</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							Classifications
							<div class="caret"></div>
						</a>
						<ul class="dropdown-menu">
							<?php
								do {
							?>
									<li><a href="?page=collections&classificationID=<?php echo $classification['classificationID'];?>"><?php echo $classification['classification'];?></a></li>
							<?php
								} while($classification = mysqli_fetch_assoc($classificationQuery));
							?>
						</ul>
					</li>
					<li><a href="?page=newcollections">New Acquisitions</a></li>
					<li><a href="?page=top10books">Top 10 Books</a></li>
			<?php
				}
			
				if(empty($_SESSION)) {
			?>
					<li><a href="#" data-toggle="modal" data-target="#login">Login</a></li>
			<?php
				} else if(isset($_SESSION['borrower'])) {
			?>
					<li class="dropdown">
						<a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo $_SESSION['borrower'];?> <div class="caret"></div></a>
						<ul class="dropdown-menu">
							<li><a href="?page=reservations">Reservations</a></li>
							<li><a href="?page=borrowerbooklogs">Book Logs</a></li>
							<li><a href="#">Edit Profile</a></li>
							<li><a href="#">Change Password</a></li>
							<li><a href="sessionunset.php">Log Out</a></li>
						</ul>
					</li>
			<?php
				} else if(isset($_SESSION['librarian'])) {
			?>
					<li class="dropdown">
						<a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo $_SESSION['librarian'];?> <div class="caret"></div></a>
						<ul class="dropdown-menu">
							<li><a href="#">Edit Profile</a></li>
							<li><a href="#">Change Password</a></li>
							<li><a href="sessionunset.php">Log Out</a></li>
						</ul>
					</li>
			<?php
				}
			?>
		</ul>
	</div>
</nav>


