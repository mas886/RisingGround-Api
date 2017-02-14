<?php
/**
 * Class to connect with user_character table
 * @autor tinez09
 */
include_once("./class/config.php");

class CharacterDAO {

    function insertCharacter($characterName, $userId) {
        //Add character
        $connection = connect();
        $sql = "INSERT INTO `user_character` (`name`, `userId`) VALUES (:name, :userId)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName, ':userId' => $userId));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }
    
    function selectCharacterList($userId) {
        //Return a list of user's characters
        $connection = connect();
        $sql = "SELECT `name`, `experience` FROM `user_character` WHERE `userId` = :id";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $userId));
        $characters = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $characters;
    }

    function selectExp($characterName) {
        //Get experiance of the character
        $connection = connect();
        $sql = "SELECT `experience` FROM `user_character` WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName));
        $characterExp = $sth->fetch(PDO::FETCH_ASSOC);
        return $characterExp['experience'];
    }

    function updateExp($battleExp, $characterName, $userId) {
        //Increase actual experience with battle experience
        $connection = connect();
        $sql = "UPDATE `user_character` SET `experience` = ((SELECT experience FROM `user_character` WHERE `name` = :name) + :battleExp) WHERE `name` = :name AND `userId` = :userId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':battleExp' => $battleExp, ':name' => $characterName, `:userId` => $userId));
        //Check update
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function updateBuild($buildId, $characterName) {
        //Select build ID to battle
        $connection = connect();
        $sql = "UPDATE `user_character` SET `selectedBuildId` = :selectedBuildId WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':selectedBuildId' => $buildId, ':name' => $characterName));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }
    
        
    function selectCharacterSlots($userId){
        //Select character slots from user
        $connection = connect();
        $sql = "SELECT characterSlots FROM `user` WHERE id = :id";
        $sth = $connection->prepare($sql);
        $sth->execute(array(':id' => $userId));
        $avaliableSlots = $sth->fetch(PDO::FETCH_ASSOC);
        return $avaliableSlots['characterSlots'];        
    }
    
  

    function exist($characterName) {
        //Check name existence on user_character
        $connection = connect();
        $sql = "SELECT name FROM `user_character` WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if (sizeof($result) > 1) {
            return 1;
        } else {
            return 0;
        }
    }

}
