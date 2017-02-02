<?php

include_once("config.php");
include_once("Token.php");

class Character {

    function addCharacter($characterName, $token) {
        //add character to db given token
        if (strlen($characterName) > 1 && strlen($token) == 30) {
            $tkn = new Token;
            $userId = $tkn->getUserIdByToken($token);
            if ($userId == "Expired" || $userId == "Bad token") {
                return $userId;
            } else {
                return $this->insertCharacterIntoDb($characterName, $userId);            }
        } else {
            return 0;
        }
    }

    private function insertCharacterIntoDb($characterName, $userId) {
        $connection = connect();
        $sql = "INSERT INTO `user_character` (`name`, `userId`) VALUES (:name, :userId)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName, ':userId' => $userId));
        return 1;
    }
    
    function deleteCharacter($characterId, $token) {
        //add character to db given token
        if (strlen($characterId) > 0 && strlen($token) == 30) {
            $tkn = new Token;
            $userId = $tkn->getUserIdByToken($token);
            if ($userId == "Expired" || $userId == "Bad token") {
                return $userId;
            } else {
                return $this->deleteCharacterFromDb($characterId);            }
        } else {
            return 0;
        }
    }

    private function deleteCharacterFromDb($characterId) {
        $connection = connect();
        $sql = "DELETE FROM `user_character` WHERE `id` = :id";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $characterId));
        return 1;
    }
    
    function characterList($userId) {
        // returns a list with all the characters's ID of de user
        if ($userId > 0) {
            $connection = connect();
            $sql = "SELECT `id` FROM `user_character` WHERE `userId` = :id";
            $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id' => $userId));
            $charactersId = $sth->fetchAll();
            return $charactersId;
        } else{
            return 0;
        }
    }
    function getExp($characterId) {
        //get experiance of the character
        $connection = connect();
        $sql = "SELECT `experience` FROM `user_character` WHERE `id` = :id";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $characterId));
        $characterExp = $sth->fetch();
        return $characterExp;
        
    }


}
