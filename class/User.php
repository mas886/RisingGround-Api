<?php

include_once("config.php");
include_once("Token.php");

class User {

    function login($username, $password) {
        //Check if we have something
        if (strlen($username) > 1 && strlen($password) > 1) {
            $connection = connect();
            $sql = "SELECT password FROM `user` WHERE name=:name";
            $user = [];
            $stmt = $connection->prepare($sql);
            if ($stmt->execute(array(':name' => $username))) {
                $user = $stmt->fetch();
            }
            $connection = closeConnection();
            if (password_verify($password, $user['password'])) {
                $token = new Token;
                return $token->createToken($username);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    
    function logout($token){
        //Checks if the lenght is correct (tokens are 30 lengh long)
        if (strlen($token)==30){
            $tkn = new Token;
            return $tkn->deleteToken($token);
        }else{
            return 0;
        }
    }

    function signUp($username, $password, $email) {
        //We check if wehave all the variables correctly
        if (!$this->correctCredentials($username, $password, $email)) {
            return "Some field is incorrect.";
        }
        //Check if the username is already in the db
        if ($this->exist($username)) {
            return "The user already exists.";
        } else {
            return $this->addUser($username, $password, $email);
        }
    }

    private function addUser($username, $password, $email) {
        $connection = connect();
        //Hashing passwords will prevent future security problems
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `user`(`name`, `password`, `email`) VALUES (:name, :password, :email)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $username, ':password' => $password, ':email' => $email));
        $connection = closeConnection();
        return 1;
    }

    private function correctCredentials($username, $password, $email) {
        //The size of the credentials will depend on the db
        if (strlen($username) >= 5 && strlen($username) <= 20 && strlen($password) >= 8 && strlen($password) <= 40 && strlen($email) >= 5 && strlen($email) <= 60) {
            return True;
        } else {
            return False;
        }
    }

    function exist($username) {
        $connection = connect();
        $sql = "SELECT name FROM `user` WHERE name=:name";
        $user = [];
        $stmt = $connection->prepare($sql);
        if ($stmt->execute(array(':name' => $username))) {
            $user = $stmt->fetch();
        }
        $connection = closeConnection();
        if (sizeof($user) > 1) {
            return True;
        } else {
            return false;
        }
    }

}
