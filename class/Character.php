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

}
