<?php
class Database {
    protected $dbconnect;
    
    public function connect() 
    {
        try {
            $this->dbconnect = new PDO('mysql:host=localhost;dbname=greenville', 'root', '');
            // $this->dbconnect->setAttribute(ATTR::ERRMODE, PDO::ERRMODE_EXCEPTION);
            // $this->dbconnect->setAttribute(ATTR::DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $this->dbconnect;
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    } 
}

?>