<?php
include_once '../../config/DatabaseConnection.php';
include_once '../../models/Book.php';
if(isset($_POST['accession_no'])) {
	$accession_no = $_POST['accession_no'];

    $database = new Database();
    $dbconnect = $database->connect();

    $book = new Book($dbconnect);
    $book->deleteBook($accession_no);
}
?>