<title>Borrowers</title>
<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
?>
<div class="admincontainer">
	<a href="?page=addborrower" class="btn btn-success btn-sm button">Add Borrower <span class="glyphicon glyphicon-plus"></span></a>
	<div class="panel panel-success" style="margin-top:20px;height:140px;">
		<div class="panel-heading">
			<h4>Search Borrowers <span class="glyphicon glyphicon-search"></span></h4>
		</div>
		<div class="panel-body">
			<form method="GET" class="form-inline" id="borrowersearchform">
					Search by:
					<div class="form-group">
						<select name="aedsearchtype" class="form-control">
							<option value="idnumber">ID Number</option>
							<option value="name">Name</option>
						</select>
					</div>
					<div class="form-group">
						<input class="form-control" id="borrowersearchbox" type="text" name="mngborrowersearch" size="20">
					</div>
					<input id="button" class="btn btn-success btn-sm" type="submit" name="mngborrowerbutton" value="Search">
			</form>
		</div>
	</div>
	<?php 
		require "dbconnect.php";
		$borrowersperpages = 10;
		$totalborrowerSQL = "SELECT * FROM borrower ORDER BY dateregistered DESC";
		$totalborrowerQuery = mysqli_query($dbconnect, $totalborrowerSQL);
		$rows = mysqli_num_rows($totalborrowerQuery);

		$numberofpages = ceil($rows/$borrowersperpages);

		if(!isset($_GET['borrowerpage'])) {
			$page = 1;
		} else {
			$page = $_GET['borrowerpage'];
		}

		if($page < 1) {
			$page = 1;
		} else if($page > $numberofpages) {
			$page = $numberofpages;
		}

		$firstresult = ($page - 1) * $borrowersperpages;

		$borrowerSQL = "SELECT * FROM borrower ORDER BY dateregistered DESC LIMIT $firstresult, $borrowersperpages";
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
						<th> </th>
					</tr>
		<?php		
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
									if($borrower['accountbalance'] > 0.00) {
							?>
										<button class="btn btn-danger btn-sm deactivatebutton" data-id="<?php echo $borrower['IDNumber']; ?>" data-toggle="modal" data-target="#deactivateborrower" title="Deactivate borrower." disabled>
											<span class="glyphicon glyphicon-ban-circle"></span>
										</button>
							<?php
									} else {
							?>
										<button class="btn btn-danger btn-sm deactivatebutton" data-id="<?php echo $borrower['IDNumber']; ?>" data-toggle="modal" data-target="#deactivateborrower" title="Deactivate borrower.">
											<span class="glyphicon glyphicon-ban-circle"></span>
										</button>
							<?php
									}
								} else if($borrower['status']=="Inactive") {
									if($borrower['accountbalance'] > 0) {
							?>
										<button class="btn btn-success btn-sm activatebutton" data-id="<?php echo $borrower['IDNumber']; ?>" data-toggle="modal" data-target="#activateborrower" title="Activate borrower." disabled>
											<span class="glyphicon glyphicon-ok-circle"></span>
										</button>
							<?php
									} else {
							?>
										<button class="btn btn-success btn-sm activatebutton" data-id="<?php echo $borrower['IDNumber']; ?>" data-toggle="modal" data-target="#activateborrower" title="Activate borrower.">
											<span class="glyphicon glyphicon-ok-circle"></span>
										</button>
							<?php
									}
								} 
							?>
						</td>
					</tr>
		<?php
				} while($borrower = mysqli_fetch_assoc($borrowerQuery));
		?>
		</table>
	</div>
	<?php
		$pagination = '';
		if($numberofpages > 1) {
			if($page > 1) {
				$previous = $page - 1;
				$pagination .= '<a href="?page=borrowers&borrowerpage='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href=?page=borrowers&borrowerpage='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
			}

			$pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="?page=borrowers&borrowerpage='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="?page=borrowers&borrowerpage='.$next.'">Next</a>&nbsp;';	
			}
		}
	?>
	<div class="pagination"><?php echo $pagination;?></div>
	<form id="pagination_data">
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
			$.ajax({
				url:"deactivateborrower.php",
				method:"POST",
				data:{idnumber:idnumber, borrowersperpages:borrowersperpages, firstresult:firstresult},
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
			$.ajax({
				url:"activateborrower.php",
				method:"POST",
				data:{idnumber:idnumber, borrowersperpages:borrowersperpages, firstresult:firstresult},
				success:function(data) {
					$("#activateborrower").modal("hide");
					$(".borrowerdisplay").html(data);
				}
			});
		});

		$(".paymentbutton").click(function(){
			var idnumber = $(this).attr("id");
			var borrowersperpages = $("#borrowersperpages").val();
			var firstresult = $("#firstresult").val();
			$.ajax({
				url:"paymentmodal.php",
				method:"POST",
				data:{idnumber:idnumber, borrowersperpages:borrowersperpages, firstresult:firstresult},
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
</div>


