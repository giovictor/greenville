<div class="basicsearch">
<h3>Greenville College Library</h3>
<h4>Search for library's materials and collections</h4>
		<form method="GET" class="form-inline" id="basicsearchform">
			<div class="form-group">
				<input id="basicsearchbox" type="text" name="basicsearch" class="form-control" size="50">
				<input id="button" class="btn btn-success btn-sm" type="submit" name="basicsearchbutton" value="Search">
			</div>
			<br>
			<div class="form-group">
				Limit to: <select name="selectsearchtype" class="selectsearchtype form-control">
					<option value="any">Any Field</option>
					<option value="booktitle">Title</option>
					<option value="author">Author</option>
					<option value="publisher">Publisher</option>
					<option value="publishingyear">Year</option>
					<option value="accession_no">Accession Number</option>
				</select>
			</div>
		</form>
</div>
<script>
$(document).ready(function(){
	$("#basicsearchform").submit(function(e) {
		var searchbox = $("#basicsearchbox").val();
		if(searchbox=="") {
			$("#emptysearch").modal("show");
			e.preventDefault();
		}
	});
});
</script>