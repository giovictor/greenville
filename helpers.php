<?php
    function validateInput($input) {
        require "dbconnect.php";
        htmlentities($input);
        //mysqli_real_escape_string($dbconnect, $input);
        return $input;
    }


?>