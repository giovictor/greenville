<?php
require "dbconnect.php";
	if(isset($_GET['classificationID'])) {
		$classificationID = $_GET['classificationID'];
			$sql = "SELECT * FROM classification WHERE classificationID='$classificationID'";
			$query = mysqli_query($dbconnect, $sql);
			$classification = mysqli_fetch_assoc($query); 
?>
<div class="collectionsearch">
<h2><?php echo strtoupper($classification['classification']);?></h2>
<h4>Search for Greenville College's <?php echo $classification['classification']; ?> collections</h4>
		<form method="GET" class="form-inline" id="collectionssearchform">
			<div class="form-group">
				Limit to: <select name="selectsearchtype" class="form-control selectsearchtype">
					<option value="booktitle">Title</option>
					<option value="author">Author</option>
					<option value="publisher">Publisher</option>
					<option value="publishingyer">Year</option>
					<option value="accession_no">Accession Number</option>
				</select>
			</div>
			<div class="form-group">
				<input class="form-control collectionssearchbox" type="text" name="collectionssearch">
				<button id="button" class="btn btn-success btn-sm collectionssearchbtn" type="submit">Search</button>
			</div>
			<input type="hidden" name="classificationID" value="<?php echo $classificationID;?>">
		</form>
	</div>
	<script>
		$(document).ready(function(){
			$("#collectionssearchform").submit(function(e){
				var searchbox = $(".collectionssearchbox").val();
					if(searchbox=="") {
						$("#emptysearch").modal("show");
						e.preventDefault();
					}
			});
		});
	</script>
<?php
	}
?>