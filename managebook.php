<div class="admincontainer">
	<?php
	require "dbconnect.php";
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}

	?>
	<title>Add Book</title>
	<div class="panel panel-success" id="add">
		<div class="panel-heading">
			<a class="btn btn-success btn-sm button viewlinks" href="?page=books">View All Books</a>
			<h4><span class="glyphicon glyphicon-plus"></span> ADD BOOK</h4>
		</div>
		<div class="panel-body">
			<form action="addbook.php" method="POST" id="addbook"> 
				<table>
					<tr>
						<td>Title:</td>
						<td><input type="text" style="width:400px;" name="title" class="form-control" id="title"></td>
					</tr>
					<tr>
						<td>Author:</td>
						<td><input type="text" style="width:400px;" name="author" id="author" class="form-control"></td>
					</tr>
					<tr>
						<td>Publisher:</td>
						<td><input type="text" style="width:400px;" name="publisher" class="form-control" id="publisher"></td>
					</tr>
					<tr>
						<td>Year:</td>
						<td><input type="text" style="width:400px;" name="year" class="form-control" id="year"></td>
					</tr>
					<tr>
						<td>Classification:</td>
						<td><select name="classification" class="form-control" style="width:400px;" id="classification"> 
						<?php
							require "dbconnect.php";
								$sql = "SELECT * FROM classification WHERE status=1";
								$query = mysqli_query($dbconnect, $sql);
								$classification = mysqli_fetch_assoc($query);
								do {?>
								<option value="<?php echo $classification['classificationID'];?>"><?php echo $classification['classification'];?></option>
							<?php
								} while($classification = mysqli_fetch_assoc($query));
						
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Call No:</td>
						<td><input type="text" style="width:400px;" name="callnumber" class="form-control" id="callnumber"></td>
					</tr>
					<tr>
						<td>ISBN:</td>
						<td><input type="text" style="width:400px;" name="ISBN" class="form-control" id="ISBN"></td>
					</tr>
					<tr>
						<td>Pages:</td>
						<td><input type="text" style="width:400px;" name="pages" class="form-control" id="pages"></td>
					</tr>
					<tr>
						<td>Price:</td>
						<td><input type="text" style="width:400px;" name="price" class="form-control" id="price"></td>
					</tr>
					<tr>
						<td>Copies:</td>
						<td><input type="text" style="width:400px;" name="copies" class="form-control" id="copies"></td>
					</tr>
					
					<tr>
						<td><input id="addbutton" class="btn btn-success btn-md" type="submit" value="Add Book" name="addbutton"></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$("#addbook").submit(function(e){
			var title = $("#title").val();
			var copies = $("#copies").val();
			if(title=="" || copies=="") {
				$("#bookemptyinput").modal("show");
				e.preventDefault();
			} 
		});
	});
</script>




		
