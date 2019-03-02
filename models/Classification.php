<?php 

class Classification  
{
    public $dbconnect;

    public function __construct($dbconnect)
    {
        $this->dbconnect = $dbconnect;
    }

    public function index() 
    {
        $query = 'SELECT * FROM classification WHERE status=1';
        $stmt = $this->dbconnect->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>