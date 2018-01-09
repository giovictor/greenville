<title>Barcode Generator</title>
<div class="admincontainer">
	<h3 style="margin:20px 0px 20px 0px;">Barcode Generator</h3>
	<form id="booksearchform" class="form-inline">
		<div class="form-group">
			<label for="barcode">Search for book: </label>
			<select name="searchtype" id="booksearchtype" class="form-control">
				<option value="booktitle">Title</option>
				<option value="accession_no">Accession Number</option>
				<option value="barcode">Barcode</option>
				<option value="callnumber">Call Number</option>
			</select>
			<input type="text" name="booksearch" id="booksearch" class="form-control" placeholder="Search for title, accession number, barcode or callnumber">
		</div>
		<div class="form-group">
			<button id="searchbookbutton" class="btn btn-success btn-sm button">Search for Book <span class="glyphicon glyphicon-search"></span></button>
		</div>
	</form>
	<div id="booksearchresults"></div>
	<div class="barcodegenerator">
		<h4>Input an accession number or a range of accession numbers.</h4>
		<form id="barcodeform" class="form-inline">
			<label>Accession Number:</label>
			<input type="text" name="barcode1" id="barcode1" class="form-control"> - 
			<input type="text" name="barcode2" id="barcode2" class="form-control" disabled>
			<button class="btn btn-success btn-sm button" id="generatebarcodebutton">
				Generate Barcode
				<span class="glyphicon glyphicon-barcode"></span>
			</button>
		</form>
	</div>
	<div id="bc"></div>
</div>
<script>
$(document).ready(function(){
	$("#barcodeform").submit(function(e){
		e.preventDefault();
		var barcode1 = $("#barcode1").val();
		var barcode2 = $("#barcode2").val();
		if(barcode1=="" && barcode2=="") {
			$("#emptyacc").modal("show");
			$("#barcodeform")[0].reset();
		} else if(barcode1=="" && barcode2!="") {
			$("#emptybarcode1").modal("show");
			$("#barcodeform")[0].reset();
		} else {
			$.ajax({
				url:"generatebarcode.php",
				method:"POST",
				data:{barcode1:barcode1, barcode2:barcode2},
				success:function(data) {
					$("#bc").html(data);
				}
			});
		}
	});

	$("#barcode1").keypress(function(){
		$("#barcode2").attr("disabled", false);
	});

	$("#booksearchform").submit(function(e){
		e.preventDefault();
		var booksearchtype = $("#booksearchtype").val();
		var booksearch = $("#booksearch").val();
		if(booksearch=="") {
			$("#emptysearch").modal("show");
		} else {
			$.ajax({
				url:"borrowbooksearchresults.php",
				method:"POST",
				data:{booksearchtype:booksearchtype, booksearch:booksearch},
				beforeSend:function() {
					$("#searchbookbutton").html("Searching...")
				},
				success:function(data) {
					if(data=="Invalid") {
						$("#invalidsearch").modal("show");
						$("#searchbookbutton").html("Search for Book <span class='glyphicon glyphicon-search'></span>");
					} else {
						$("#booksearchresults").html(data);
						$("#searchbookbutton").html("Search for Book <span class='glyphicon glyphicon-search'></span>");
					}
				}
			});
		}
	});
	
	$("#emptysearch").on("hide.bs.modal", function(){
		$("#booksearch").focus();
	});

	$("#invalidsearch").on("hide.bs.modal", function(){
		$("#booksearch").focus();
	});
});
</script>