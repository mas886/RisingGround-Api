<?php

include_once("config.php");
include_once("Token.php");

class Character {

    //ADD character
    function addCharacter($characterName, $token) {
        //Add character to db given token
        if (strlen($characterName) > 20 && strlen($characterName) < 1 && strlen($token) != 30) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        if ($this->exist($characterName)) {
            return "Name exist";
        }
        //Return 1 if is succesfull, 0 if character is not added
        return $this->insertCharacterIntoDb($characterName, $userId);
    }

    private function insertCharacterIntoDb($characterName, $userId) {
        //Insert
        $connection = connect();
        $sql = "INSERT INTO `user_character` (`name`, `userId`) VALUES (:name, :userId)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName, ':userId' => $userId));
        //Select to prove character inexistence

        if ($sth->rowCount() != 0) {
            return 1;
        }

        return 0;
    }

    //LIST character
    function characterList($token) {
        //Returns a list with all the characters's ID of the user given token
        if (strlen($token) != 30) {
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
        if (strlen($characterName) > 20 && strlen($characterName) < 1 && $token != 30) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        if (!$this->exist($characterName)) {
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
        if ($token != 30 && !ctype_digit($battleExp) && strlen($characterName) > 12 && strlen($characterName) < 1) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        return $this->updateExp($battleExp, $characterName, $userId);
    }

    private function updateExp($battleExp, $characterName, $userId) {
        //Increase actual experience with battle experience
        $connection = connect();
        $sql = "UPDATE `user_character` SET `experience` = ((SELECT experience FROM `user_character` WHERE `name` = :name) + :battleExp) WHERE `name` = :name AND `userId` = :userId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':battleExp' => $battleExp, ':name' => $characterName, `:userId` => $userId));
        //Check update
        if ($sth->rowCount() != 0) {
            return 1;
        }
        return 0;
    }

    //SELECT BUILD
    function selectBuild($buildId, $characterName, $token) {
        //select the build for battle of the character
        if (strlen($characterName) > 20 && strlen($characterName) < 1 && strlen($token) != 30 && !ctype_digit($buildId)) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        return $this->updateBuild($buildId, $characterName);
    }

    private function updateBuild($buildId, $characterName) {
        $connection = connect();
        $sql = "UPDATE `user_character` SET `selectedBuildId` = :selectedBuildId WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':selectedBuildId' => $buildId, ':name' => $characterName));
        if ($sth->rowCount() != 0) {
            return 1;
        }
        return 0;
    }

    //VALIDATE general function

    function exist($characterName) {
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

}
