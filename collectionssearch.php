<?php
require "dbconnect.php";
	if(isset($_GET['classification'])) {
		$classification = $_GET['classification'];
			$sql = "SELECT * FROM classification WHERE classificationID='$classification'";
			$query = mysqli_query($dbconnect, $sql);
			$classification = mysqli_fetch_assoc($query); 
?>
<div class="collectionsearch">
<h2><?php echo strtoupper($classification['classification']);?></h2>
<h4>Search for Greenville College's <?php echo $classification['classification']; ?> collections</h4>
		<form action="?page=collectionssearchresults&classification=<?php echo $classification['classificationID'];?>" method="POST" class="form-inline" id="collectionssearchform">
			<div class="form-group">
				Limit to: <select name="selectsearchtype" class="form-control selectsearchtype">
					<option value="Title">Title</option>
					<option value="Author">Author</option>
					<option value="Publisher">Publisher</option>
					<option value="Year">Year</option>
					<option value="Call Number">Call Number</option>
					<option value="Accession Number">Accession Number</option>
				</select>
			</div>
			<div class="form-group">
				<input class="form-control collectionssearchbox" type="text" name="collectionssearch">
				<input id="button" class="btn btn-success btn-sm" type="submit" name="searchbutton" value="Search">
			</div>
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