<?php
class Database 
{
    protected $dbconnect;
    
    public function connect() 
    {
        try {
            $this->dbconnect = new PDO('mysql:host=localhost;dbname=greenville', 'root', '');
            // $this->dbconnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // $this->dbconnect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $this->dbconnect;
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    } 
}

?>