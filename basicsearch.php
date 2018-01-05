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
					<option value="All">Any Field</option>
					<option value="Title">Title</option>
					<option value="Author">Author</option>
					<option value="Publisher">Publisher</option>
					<option value="Year">Year</option>
					<option value="Call Number">Call Number</option>
					<option value="ISBN">ISBN</option>
					<option value="Accession Number">Accession Number</option>
					<option value="Classification">Classification</option>
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