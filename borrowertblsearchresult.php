<div class="admincontainer">
	<a href="?page=addborrower" class="btn btn-success btn-sm button">Add Borrower <span class="glyphicon glyphicon-plus"></span></a>
	<div class="panel panel-success" style="margin-top:20px;height:140px;">
		<div class="panel-heading">
			<a href="?page=borrowers" class="btn btn-success btn-sm button viewlinks">View All Borrowers</a>
			<h4>Search Borrowers <span class="glyphicon glyphicon-search"></span></h4>
		</div>
		<div class="panel-body">
			<form method="GET" class="form-inline" id="borrowersearchform">		
				Search by:
					<div class="form-group">
						<select name="aedsearchtype" class="form-control">
							<option value="idnumber"
								<?php
									if($_GET['aedsearchtype']=='idnumber') {
										echo "selected='selected'";
									}
								?>
							>ID Number</option>
							<option value="name"
								<?php
									if($_GET['aedsearchtype']=='name') {
										echo "selected='selected'";
									}
								?>
							>Name</option>
						</select>
					</div>
					<div class="form-group">
						<input class="form-control" type="text" name="mngborrowersearch" size="20">
					</div>
					<input id="button" class="btn btn-success btn-sm" type="submit" name="mngborrowerbutton" value="Search">
			</form>
		</div>
	</div>
	<?php
	require "dbconnect.php";
		if(isset($_GET['mngborrowerbutton'])) {
			$keyword = $_GET['mngborrowersearch'];
			$searchtype = $_GET['aedsearchtype'];
			if($_GET['aedsearchtype']=="idnumber") {
				$totalborrowerSQL = "SELECT * FROM borrower WHERE IDNumber LIKE '%$keyword%' ORDER BY dateregistered DESC";
			} else if($_GET['aedsearchtype']=="name") {
				$totalborrowerSQL = "SELECT * FROM borrower WHERE CONCAT(lastname, firstname, mi) LIKE '%$keyword%' ORDER BY dateregistered DESC";
			}

			$borrowersperpages = 10;
			$totalborrowerQuery = mysqli_query($dbconnect, $totalborrowerSQL);
			$rows = mysqli_num_rows($totalborrowerQuery);

			$numberofpages = ceil($rows/$borrowersperpages);

			if(!isset($_GET['borrowerpage'])) {
				$page = 1;
			} else {
				$page = $_GET['borrowerpage'];
			}

			$firstresult = ($page - 1) * $borrowersperpages;

			if($_GET['aedsearchtype']=="idnumber") {
				$borrowerSQL = "SELECT * FROM borrower WHERE IDNumber LIKE '%$keyword%' ORDER BY dateregistered DESC LIMIT $firstresult, $borrowersperpages";
			} else if($_GET['aedsearchtype']=="name") {
				$borrowerSQL = "SELECT * FROM borrower WHERE CONCAT(lastname, firstname, mi) LIKE '%$keyword%' ORDER BY dateregistered DESC LIMIT $firstresult, $borrowersperpages";
			}

			$borrowerQuery = mysqli_query($dbconnect, $borrowerSQL);
			$borrower = mysqli_fetch_assoc($borrowerQuery);
	?>
	<div class="reportpdf">
		<form id="printpdf" target="_blank" action="pdfborrower.php" method="POST">
			<input type="hidden" name="query" value="<?php echo $totalborrowerSQL;?>">
			<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
		</form>
	</div>
	<div class='borrowerdisplay'>
	 <table class='table table-hover table-striped table-bordered' id='borrowertable'>
					<tr>
						<th>ID Number</th>
						<th>Name</th>
						<th>Contact Number</th>
						<th>Course</th>
						<th>Date Registered</th>
						<th>Account Type</th>
						<th>Account Balance</th>
						<th>Status</th>
						<th></th>
					</tr>
		<?php	
			if($rows==0) {
				echo "<tr><td colspan='9'><center><h4>No results found. Try searching again.</h4></center></td></tr>";
			} else if($rows>=1) {
				do {
		?>
					<tr>
						<td>
							<button class="btn btn-link btn-sm viewlinks viewborrowerinfo" style="color:#1CA843;" id="<?php echo $borrower['IDNumber'];?>">
								<b><?php echo $borrower['IDNumber']; ?></b>
							</button>
						</td>
						<td><?php echo $borrower['lastname'].", ".$borrower['firstname']." ".$borrower['mi']; ?></td>
						<td><?php echo $borrower['contactnumber']; ?></td>
						<td><?php echo $borrower['course']; ?></td>
						<td><?php echo $borrower['dateregistered']; ?></td>
						<td><?php echo $borrower['accounttype']; ?></td>
						<td><?php echo $borrower['accountbalance']; ?></td>
						<td><?php echo $borrower['status']; ?></td>
						<td>
							<?php
								if($borrower['accountbalance']==0.00) {
							?>
								<button class="btn btn-warning btn-sm paymentbutton" id="<?php echo $borrower['IDNumber'];?>" data-toggle="modal" data-target="#paymentmodal" disabled>
									<span class="glyphicon glyphicon-credit-card"></span>
								</button>
							<?php
								} else {
							?>
								<button class="btn btn-warning btn-sm paymentbutton" id="<?php echo $borrower['IDNumber'];?>" data-toggle="modal" data-target="#paymentmodal">
									<span class="glyphicon glyphicon-credit-card"></span>
								</button>
							<?php
								}
							?>
							<a class="btn btn-success btn-sm button" href="?page=editupdateborrower&idNum=<?php echo $borrower['IDNumber']; ?>">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>
							<?php
								if($borrower['status']=="Active") {
							?>
								<button class="btn btn-danger btn-sm deactivatebutton" data-id="<?php echo $borrower['IDNumber']; ?>" data-toggle="modal" data-target="#deactivateborrower" title="Deactivate borrower.">
									<span class="glyphicon glyphicon-ban-circle"></span>
								</button>
							<?php
								} else if($borrower['status']=="Inactive") {
							?>
								<button class="btn btn-success btn-sm activatebutton" data-id="<?php echo $borrower['IDNumber']; ?>" data-toggle="modal" data-target="#activateborrower" title="Activate borrower.">
									<span class="glyphicon glyphicon-ok-circle"></span>
								</button>
							<?php
								}
							?>
						</td>
					</tr>
	<?php
			} while($borrower = mysqli_fetch_assoc($borrowerQuery));
		}
	?>
	</table>
	</div>
	<?php
		if($numberofpages > 1) {
	?>
			<p style='margin-top:20px;'>Showing <?php echo $rows;?> results</p>
			<p>Page: <?php echo $page;?> of <?php echo $numberofpages;?></p>
			<ul class="pagination">
				<?php
					for($i=1; $i<=$numberofpages; $i++) {
				?>
						<li><a href="index.php?aedsearchtype=<?php echo $searchtype;?>&mngborrowersearch=<?php echo $keyword;?>&mngborrowerbutton=Search&borrowerpage=<?php echo $i;?>"><?php echo $i;?></a></li>
				<?php
					}
				?>
			</ul>
	<?php
		}
	?>
	<form id="pagination_data">
		<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword;?>">
		<input type="hidden" name="searchtype" id="searchtype" value="<?php echo $searchtype;?>">
		<input type="hidden" name="borrowersperpages" id="borrowersperpages" value="<?php echo $borrowersperpages;?>">
		<input type="hidden" name="firstresult" id="firstresult" value="<?php echo $firstresult;?>">
	</form>
	<script>
	$(document).ready(function(){
		$("#borrowersearchform").submit(function(e){
			var searchbox = $("#borrowersearchbox").val();
			if(searchbox=="") {
				$("#emptysearch").modal("show");
				e.preventDefault();
			}
		});

		$(document).on("click",".deactivatebutton", function(){
			var idnumber = $(this).data("id");
			$(".confirmdeactivateborrower").data("id",idnumber);
		});

		$(".confirmdeactivateborrower").click(function(){
			var idnumber = $(this).data("id");
			var borrowersperpages = $("#borrowersperpages").val();
			var firstresult = $("#firstresult").val();
			var keyword = $("#keyword").val();
			var searchtype = $("#searchtype").val();
			$.ajax({
				url:"deactivateborrower.php",
				method:"POST",
				data:{idnumber:idnumber,  borrowersperpages:borrowersperpages, firstresult:firstresult, keyword:keyword, searchtype:searchtype},
				success:function(data) {
					$("#deactivateborrower").modal("hide");
					$(".borrowerdisplay").html(data);
				}
			});
		});


		$(document).on("click",".activatebutton", function(){
			var idnumber = $(this).data("id");
			$(".confirmactivateborrower").data("id",idnumber);
		});

		$(".confirmactivateborrower").click(function(){
			var idnumber = $(this).data("id");
			var borrowersperpages = $("#borrowersperpages").val();
			var firstresult = $("#firstresult").val();
			var keyword = $("#keyword").val();
			var searchtype = $("#searchtype").val();
			$.ajax({
				url:"activateborrower.php",
				method:"POST",
				data:{idnumber:idnumber,  borrowersperpages:borrowersperpages, firstresult:firstresult, keyword:keyword, searchtype:searchtype},
				success:function(data) {
					$("#activateborrower").modal("hide");
					$(".borrowerdisplay").html(data);
				}
			});
		});

		$(".paymentbutton").click(function(){
			var idnumber = $(this).attr("id");
			var keyword = $("#keyword").val();
			var searchtype = $("#searchtype").val();
			var borrowersperpages = $("#borrowersperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"paymentmodal.php",
				method:"POST",
				data:{idnumber:idnumber, borrowersperpages:borrowersperpages, firstresult:firstresult, keyword:keyword, searchtype:searchtype},
				success:function(data) {
					$("#takepaymentdata").html(data);
					$("#paymentmodal").modal("show");
				}
			});
		});

		$(".viewborrowerinfo").click(function(){
			var idnumber = $(this).attr("id");
			$.ajax({
				url:"borrowermodalinfo.php",
				method:"POST",
				data:{idnumber:idnumber},
				success:function(data) {
					$("#borrowerinfodata").html(data);
					$("#borrowerinfomodal").modal("show");
				}
			});
		});
	});
	</script>
	<?php				
	}
	?>
</div>