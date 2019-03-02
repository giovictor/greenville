<?php

class Book 
{

    public $dbconnect;
    public $type;
    public $keyword;

    public function __construct($dbconnect)
    {
        $this->dbconnect = $dbconnect;
    }

    public function searchBookWithTotalResults($keyword, $type)
    {
        // if($type=="any") {
		// 	$query = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, classification.classification, publishingyear, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE booktitle LIKE '%:keyword%' OR author.author LIKE '%:keyword%' OR publisher.publisher LIKE '%:keyword%' OR publishingyear LIKE '%:keyword%' OR classification LIKE '%:keyword%' AND book.status!='Archived' GROUP BY bookID";
		// } else if ($type=="accession_no") {
		// 	$query = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, classification.classification, publishingyear, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no=':keyword' AND book.status!='Archived' GROUP BY bookID";
		// } else {
        //     $query = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, classification.classification, publishingyear, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE :type LIKE '%:keyword%' AND book.status!='Archived' GROUP BY bookID";
        // }

        if($type=="any") {
			$query = "SELECT bookID, book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE booktitle LIKE '%$keyword%' AND book.status!='Archived' GROUP BY bookID";
		} else if ($type=="accession_no") {
			$query = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no=':keyword' AND book.status!='Archived' GROUP BY bookID";
		} else {
			$query = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, callnumber, classification.classification, publishingyear, ISBN, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $type LIKE '%:keyword%' AND book.status!='Archived' GROUP BY bookID";
		}

        $stmt = $this->dbconnect->prepare($query);
        // $stmt->bindParam(':keyword', $keyword);
        // // $stmt->bindParam(':type', $type);
        $stmt->execute();
        return $stmt->fetchAll();
        


    }

    public function searchBookWithLimitedResults()
    {

    }
}

?>