<?php
        
    function connect(){
        
        $hostdb="localhost";
        $userdb="root"; 
        $passdb="";
        $database="credit";       
         
        $connection = new PDO("mysql:host=$hostdb;dbname=$database;charset=utf8mb4", "$userdb", "$passdb", array(PDO::ATTR_PERSISTENT=>true))or die ("Couldn't connect to db.");
        
        return $connection;
    }

    function closeConnection(){
        $connection=null; 
        return $connection;
    }
?>
