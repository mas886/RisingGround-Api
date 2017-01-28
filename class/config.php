<?php
        
    function connect(){
        
        $hostdb="localhost";
        $userdb="USERNAME"; 
        $passdb="PASSWORD";
        $database="DATABASE";        
         
        $connection = new PDO("mysql:host=$hostdb;dbname=$database;charset=utf8mb4", "$userdb", "$passdb")or die ("Couldn't connect to db.");
        
        return $connection;
    }

    function closeConnection(){
        $connection=null; 
        return $connection;
    }