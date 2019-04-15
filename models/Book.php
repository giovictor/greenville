<?php

class Book 
{

    public $dbconnect;

    public function __construct($dbconnect)
    {
        $this->dbconnect = $dbconnect;
    }

    public function searchBookWithTotalResultsCount($keyword, $type)
    {
        if ($type=="accession_no") {
            $query = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, classification.classification, publishingyear, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no=:keyword AND book.status!='Archived' GROUP BY bookID";
            $stmt = $this->dbconnect->prepare($query);
            $stmt->execute(['keyword'=>$keyword]);
		} else {
            $query = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, classification.classification, publishingyear, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $type LIKE :keyword AND book.status!='Archived' GROUP BY bookID";
            $stmt = $this->dbconnect->prepare($query);
            $stmt->execute(['keyword'=>'%'.$keyword.'%']);
        }
        return $stmt->rowCount();
    }

    public function searchBookWithLimitedResults($keyword, $type, $firstresult, $itemsperpages)
    {
        if ($type=="accession_no") {
            $query = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, classification.classification, publishingyear, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.accession_no=:keyword AND book.status!='Archived' GROUP BY bookID LIMIT $firstresult, $itemsperpages";
            $stmt = $this->dbconnect->prepare($query);
            $stmt->execute(['keyword'=>$keyword]);
		} else {
            $query = "SELECT bookID,book.accession_no, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors , publisher.publisher, classification.classification, publishingyear, book.status FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE $type LIKE :keyword AND book.status!='Archived' GROUP BY bookID LIMIT $firstresult, $itemsperpages";
            $stmt = $this->dbconnect->prepare($query);
            $stmt->execute(['keyword'=>'%'.$keyword.'%']);
        }
        return $stmt->fetchAll();
    }

    public function listAllBooks()
    {
        $query = "SELECT bookID, book.accession_no, callnumber, booktitle, GROUP_CONCAT(DISTINCT author SEPARATOR', ') AS authors, publisher.publisher, publishingyear, classification.classification, COUNT(DISTINCT book.accession_no) AS copies, book.status, price FROM book LEFT JOIN bookauthor ON book.accession_no=bookauthor.accession_no LEFT JOIN author ON author.authorID=bookauthor.authorID LEFT JOIN publisher ON publisher.publisherID=book.publisherID JOIN classification ON classification.classificationID=book.classificationID WHERE book.status!='Archived' GROUP BY accession_no ORDER BY accession_no DESC";
        $stmt = $this->dbconnect->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteBook($accession_no)
    {
        $query = "UPDATE book SET status='Archived', bookcondition='Archived' WHERE accession_no=:accession_no";
        $stmt = $this->dbconnect->prepare($query);
        $stmt->execute(['accession_no'=>$accession_no]);
    }
}

?>