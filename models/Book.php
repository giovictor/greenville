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
}

?>