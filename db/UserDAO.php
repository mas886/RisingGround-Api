<?php

/**
 * Queries to interact with user table
 *
 * @author redrednose
 */
include_once("./class/config.php");

class UserDAO {

    public function getPassword($username) {
        $connection = connect();
        $sql = "SELECT password FROM `user` WHERE name=:name";
        $user = [];
        $stmt = $connection->prepare($sql);
        if ($stmt->execute(array(':name' => $username))) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $user;
    }

    public function addUser($username, $password, $email) {
        $connection = connect();
        //Hashing passwords will prevent future security problems
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `user`(`name`, `password`, `email`) VALUES (:name, :password, :email)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $username, ':password' => $password, ':email' => $email));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function exist($username) {
        $connection = connect();
        $sql = "SELECT name FROM `user` WHERE name=:name";
        $user = [];
        $stmt = $connection->prepare($sql);
        if ($stmt->execute(array(':name' => $username))) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        if(!$user){
            return false;
        }else{
            return true;
        }
    }

    public function getGold($userId) {
        $connection = connect();
        $sql = "SELECT `gold` FROM `user` WHERE `id`=:userId";
        $stmt = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute(array(':userId' => $userId));
        $gold = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($gold['gold']);
    }
    
     public function getGems($userId) {
        $connection = connect();
        $sql = "SELECT `gems` FROM `user` WHERE `id`=:userId";
        $stmt = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute(array(':userId' => $userId));
        $gems = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($gems['gems']);
    }

}
