<?php
        
    function connect(){
        
        $hostdb="SERVER";
        $userdb="USERNAME"; 
        $passdb="PASSWORD";
        $database="DATANAME";       
         
        $connection = new PDO("mysql:host=$hostdb;dbname=$database;charset=utf8mb4", "$userdb", "$passdb", array(PDO::ATTR_PERSISTENT=>true))or die ("Couldn't connect to db.");
        
        return $connection;
    }

    function closeConnection(){
        $connection=null; 
        return $connection;
    }
?>
