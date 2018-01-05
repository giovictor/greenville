<div class="admincontainer">
<?php
require "dbconnect.php";
if(isset($_GET['bookID'])) {
	$bookID = $_GET['bookID'];
	$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR',') AS authors, publisher, publishingyear, classification, callnumber, ISBN, pages, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status!='Archived' AND bookID='$bookID' ORDER BY book.accession_no DESC";
	$bookQuery = mysqli_query($dbconnect, $bookSQL);
	$book = mysqli_fetch_assoc($bookQuery);
} else if(isset($_GET['acc'])) {
	$acc = $_GET['acc'];
	$bookSQL = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR',') AS authors, publisher, publishingyear, classification, callnumber, ISBN, pages, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status!='Archived' AND book.accession_no='$acc' ORDER BY book.accession_no DESC";
	$bookQuery = mysqli_query($dbconnect, $bookSQL);
	$book = mysqli_fetch_assoc($bookQuery);
}
?>
	<title>Update - <?php echo $book['booktitle'];?></title>
	<div id="edit">
		<div class="panel panel-success">
			<div class="panel-heading">
				<a class="btn btn-success btn-sm button viewlinks" href="?page=books">View All Books</a>
				<a class="btn btn-success btn-sm button viewlinks viewbookinfo" href="#" id="<?php echo $book['accession_no'];?>">View Book Info</a>
				<a href="?page=addbook" class="btn btn-success btn-sm button viewlinks">Add Book <span class="glyphicon glyphicon-plus"></span></a>
				<h4>UPDATE BOOK</h4>
			</div>
			<div class="panel-body">
			<?php
				if(isset($_GET['bookID'])) {
			?>
				<form action="updatebookbytitle.php" method="POST" id="editbook">
			<?php
				} else if(isset($_GET['acc'])){
			?>
				<form action="updatebookbycopy.php" method="POST" id="editbook">
			<?php
				}
			?>
					<table id="edittable">
						<?php
							if(isset($_GET['acc'])) {
						?>
								<tr>
									<td>Accession No:</td>
									<td><input type="text" style="width:400px;" name="accession_no" class="form-control" id="accession_no" value="<?php echo $book['accession_no'];?>" disabled></td>
								</tr>
						<?php
							}
						?>
						<tr>
							<td>Title:</td>
							<td><input type="text" style="width:400px;" name="title" class="form-control" id="title" value="<?php echo $book['booktitle'];?>"></td>
						</tr>
						<tr>
							<td>Author:</td>
							<td><input type="text" style="width:400px;" name="author" class="form-control" id="author" value="<?php echo $book['authors'];?>"></td>
						</tr>
						<tr>
							<td>Publisher: </td>
							<td><input type="text" style="width:400px;" name="publisher" class="form-control" id="publisher" value="<?php echo $book['publisher'];?>">
							</td>
						</tr>
						<tr>
							<td>Year:</td> 
							<td><input type="text" style="width:400px;" name="year" class="form-control" id="year" value="<?php echo $book['publishingyear'];?>"></td>
						</tr>
						<tr>
							<td>Classification:</td>
							<td>
								<select name="classification" class="form-control" style="width:400px;"> 
							<?php
								require "dbconnect.php";
									$sql = "SELECT * FROM classification WHERE status=1";
									$query = mysqli_query($dbconnect, $sql);
									$classification = mysqli_fetch_assoc($query);
									do {?>
									<option value="<?php echo $classification['classificationID'];?>" 
										<?php
											if($book['classification']==$classification['classification']) {
												echo "selected='selected'";
											}
										?>
									><?php echo $classification['classification'];?></option>
								<?php
									} while($classification = mysqli_fetch_assoc($query));
							
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Call No:</td> 
							<td><input type="text" style="width:400px;" name="callnumber" class="form-control" id="callnumber" value="<?php echo $book['callnumber'];?>"></td>
						</tr>
						<tr>
							<td>ISBN:</td>
							<td><input type="text" style="width:400px;" name="ISBN" class="form-control" id="ISBN" value="<?php echo $book['ISBN'];?>"></td>
						</tr>	
						<tr>
							<td>Pages:</td>
							<td><input type="text" style="width:400px;" name="pages" class="form-control" id="pages" value="<?php echo $book['pages'];?>"></td>
						</tr>
					
						<tr>
							<td>Price: </td>
							<td><input type="text" style="width:400px;" name="price" class="form-control" id="price" value="<?php echo $book['price'];?>">
							</td>
						</tr>
					
						<tr>
							<td><input class="btn btn-success btn-sm button" type="submit" value="Update Book" name="editbutton"></td>
						</tr>
					</table>
					<?php
						if(isset($_GET['bookID'])){
					?>
						<input type="hidden" name="bookID" class="bookID" value="<?php echo $bookID;?>">
					<?php
						} else if(isset($_GET['acc'])) {
					?>
						<input type="hidden" name="acc" class="acc" value="<?php echo $acc;?>">
					<?php
						}
					?>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
	var click = 1;
		$("#addauthortxt").click(function(){
			click++;
			$("#edittable").append('<tr id="atxtrow'+click+'"><td></td><td><input type="text" style="width:200px;" name="author[]" class="form-control authortxtbox"><button id="'+click+'" type="button" class="btn btn-danger btn-sm removeatxt"><span class="glyphicon glyphicon-remove"></span></button></td></tr>')
		});

		$(document).on("click",".removeatxt", function(){
			var id = $(this).attr("id");
			$("#atxtrow"+id+'').remove();
		});	

	$(".viewbookinfo").click(function(){
		var accession_no = $(this).attr("id");
		$.ajax({
			url:"bookmodalinfo.php",
			method:"POST",
			data:{accession_no:accession_no},
			success:function(data) {
				$("#content").html(data);
				$("#bookInfo").modal("show");
			}
		});
	});
});
</script>
