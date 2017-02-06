<?php

include_once("config.php");
include_once("Token.php");

class Character {

    //ADD character
    function addCharacter($characterName, $token) {
        //Add character to db given token
        if (strlen($characterName) > 1 && strlen($token) == 30) {
            if ($this->checkName($characterName)) {
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

        if ($this->checkName($characterName)) {
            return 1;
        }

        return 0;
    }

    //LIST character
    function characterList($token) {
        //Returns a list with all the characters's ID of the user given token
        if($token != 30){
            return 0;
        }
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
        if($token != 30){
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        if (!$this->checkName($characterName)) {
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

    //ADD EXPERIENCE

    function addExp($battleExp, $characterName, $token) {
        if($token != 30){
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        if (!$this->characterBelongs($characterName, $userId)) {
            return "User error";
        }

        return $this->updateExp($battleExp, $characterName);
    }

    private function updateExp($battleExp, $characterName) {
        //Select for actual character's experience
        $characterExp = $this->selectExp($characterName);
        //Increase actual experience with battle experience
        $totalExp = $characterExp + $battleExp;
        $connection = connect();
        $sql = "UPDATE `user_character` SET `experience` = :exp WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':exp' => $totalExp, ':name' => $characterName));
        //Check update
        if ($this->checkExp($totalExp, $characterName)) {
            return 1;
        }
        return 0;
    }

    //VALIDATE general functions
    private function characterBelongs($characterName, $userId) {
        //Check if a character belongs to a user
        $connection = connect();
        $sql = "SELECT `id` FROM `user_character` WHERE `userId` = :userId AND `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':userId' => $userId, ':name' => $characterName));
        $result = $sth->fetch();
        if (sizeof($result) > 1) {
            return true;
        }
        return false;
    }

    private function checkName($characterName) {
        //Check name existence on user_character
        $connection = connect();
        $sql = "SELECT name FROM `user_character` WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName));
        $result = $sth->fetch();
        if (sizeof($result) > 1) {
            return true;
        }
        return false;
    }

    private function checkExp($experience, $characterName) {
        //Check name existence on user_character
        $connection = connect();
        $sql = "SELECT experience FROM `user_character` WHERE `experience` = :exp AND `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':exp' => $experience, ':name' => $characterName));
        $result = $sth->fetch();
        if (sizeof($result) > 1) {
            return true;
        }
        return false;
    }

}
