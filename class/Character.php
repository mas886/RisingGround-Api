<?php

include_once("config.php");
include_once("Token.php");

class Character {

    //ADD character
    function addCharacter($characterName, $token) {
        //Add character to db given token
        if (strlen($characterName) > 1 && strlen($token) == 30) {
            if($this->exist($characterName)){
                return "Name exist";
            }
            $tkn = new Token;
            $userId = $tkn->getUserIdByToken($token);
            if ($userId == "Expired" || $userId == "Bad token") {
                return $userId;
            } else {
                //Return 1 if is succesfull, 0 if character is not added
                return $this->insertCharacterIntoDb($characterName, $userId);
            }
        } else {
            return 0;
        }
    }

    private function insertCharacterIntoDb($characterName, $userId) {
        //Insert
        $connection = connect();
        $sql = "INSERT INTO `user_character` (`name`, `userId`) VALUES (:name, :userId)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName, ':userId' => $userId));
        //Select to prove character inexistence
        
        if ($this->searchCharacter($characterName)) {
            $result = $sth->fetch();
        }
        if (sizeof($result) > 0) { //character has been added
            return 1;
        }
        return 0;
    }
 
    //LIST character
    function characterList($token) {
        //Returns a list with all the characters's ID of the user given token
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        return $this->selectCharacterList($userId);
    }

    private function selectCharacterList($userId) {
        $connection = connect();
        $sql = "SELECT `name`, `experience` FROM `user_character` WHERE `userId` = :id";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $userId));
        $characters = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $characters;
    }

    //GET EXPERIENCE
    function getExp($characterName, $token) {
        //Returns the character's experience given token
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        if ($this->characterBelongs($userId, $characterName)) {
            return 0;
        }
        return $this->selectExp($characterName);
    }

    private function selectExp($characterName) {
        //Get experiance of the character
        $connection = connect();
        $sql = "SELECT `experience` FROM `user_character` WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName));
        $characterExp = $sth->fetch();
        return $characterExp['experience'];
    }
    
       //VALIDATE general functions
    private function characterBelongs($characterName, $userId) {
        //Check if a character belongs to a user
        $connection = connect();
        $sql = "SELECT `id` FROM `user_character` WHERE `userId` = :userId AND `name` = :characterName";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $character = [];
        if ($sth->execute(array(':userId' => $userId, ':characterName' => $characterName))) {
            $character = $sth->fetch();
        }
        if (sizeof($character) > 1) {
            return true;
        } else {
            return false;
        }
    }
    private function exist($characterName){
        //Check name existence on user_character
        $connection = connect();
        $sql = "SELECT `name` FROM `user_character` WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName));
        $result = [];
        $result = $sth->fetch();
        if(sizeof($result)){
            return true;
        }
        return false;
    }
    


}
