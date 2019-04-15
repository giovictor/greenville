<title>Books</title>
<?php
	if(!isset($_SESSION['librarian'])) {
		header("Location:index.php");
	}
?>
<div class="admincontainer">	
	<a href="?page=addbook" class="btn btn-success btn-sm button">Add Book <span class="glyphicon glyphicon-plus"></span></a>
	<?php 
        include_once 'config/DatabaseConnection.php';
        include_once 'models/Book.php';
        
        $database = new Database();
        $dbconnect = $database->connect();
        
        $book = new Book($dbconnect);
        $books = $book->listAllBooks();
    ?>

	<div class="table-responsive" id='bookdisplay'>
		<!-- <div class="reportbtn">
			<form id="printpdf" target="_blank" action="pdfbookbycopy.php" method="POST" class="form-inline">
				<button class="btn btn-default btn-sm">Print PDF <i class="fa fa-file-pdf-o"></i></button>
				<input type="hidden" name="query" value="<?php echo $totalbookSQL;?>">
			</form>
		</div> -->
		<table class='table table-hover table-bordered table-striped' id='booktable'>
            <thead>
                <tr>
                    <th></th>
                    <th>Accession No.</th>
                    <th>Title</th>
                    <th>Authors</th>
                    <th>Publication Details</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                <?php
                        foreach($books as $book) {
                ?>
                        <tr data-id="<?php echo $book['accession_no'];?>" class="book-row">
                            <td><input type="checkbox" name="booksToDelete[]"></td>
                            <td><?php echo $book['accession_no'];?></td>
                            <td>
                                <button class="btn btn-link btn-sm viewbookinfo" style="color:#1CA843;" id="<?php echo $book['accession_no'];?>">
                                    <b><?php echo $book['booktitle'];?></b>
                                </button>
                            </td>
                            <td><?php echo $book['authors'];?></td>
                            <td><?php echo $book['publisher']." c".$book['publishingyear'];?></td>
                            <td>
                                <a href="?page=updatebook&bookID=<?php echo $book['accession_no'];?>" class="btn btn-success btn-sm" title="Edit book.">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                                <button data-id="<?php echo $book['accession_no'];?>" class="btn btn-danger btn-sm deletebook" data-toggle="modal" data-target="#confirm-modal" title="Delete book.">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </td>
                        </tr>
                <?php
                    } 
                ?>
            </tbody>
		</table>
	</div>
    <?php include_once 'views/partials/confirm-modal.php'; ?>
	<script>
		$(document).ready(function(){
            $("#booktable").DataTable({
                "lengthChange":false
            });

			$("#booktable tbody").on("click", ".deletebook", function(){
				var accession_no = $(this).data("id");
                $("#confirm-modal .modal-body p").text("Are you sure you want to delete this book?");
				$(".confirmbtn").data("id", accession_no);
			});
			
			$(".confirmbtn").click(function(){
				var accession_no = $(this).data("id");
				$.ajax({
					url:"controllers/books/deletebook.php",
					method:"POST",
					data:{accession_no:accession_no},
					beforeSend:function() {
						$(".confirmbtn").html("Deleting Book...");
					},
					success:function(data) {
						$("#confirm-modal").modal("hide");
						$(".confirmbtn").html("Confirm");
                        $(".book-row[data-id='"+accession_no+"']").remove().draw(false);
					}
				});
			});
			

			$(".viewbookinfo").click(function(){
				var accession_no = $(this).attr("id");
				$.ajax({
					url:"bookmodalinfo.php",
					method:"POST",
					data:{accession_no, accession_no},
					success:function(data) {
						$("#content").html(data);
						$("#bookInfo").modal("show");
					}
				});
			});
		});
	</script>
</div>