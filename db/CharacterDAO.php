<?php

/**
 * Class to connect with user_character table
 * @autor tinez09 & mas886/Arnau
 */
include_once("./class/config.php");

class CharacterDAO {

    public function selectCharacterList($userId) {
        //Return a list of user's characters
        $connection = connect();
        $sql = "SELECT `name`, `experience` FROM `user_character` WHERE `userId` = :id";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $userId));
        $characters = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $characters;
    }

    public function selectCharacter($characterName) {
        //Get information of the character
        $connection = connect();
        $sql = "SELECT `userId`, `experience`, `buildSlots`, `buildId`, `amulet` FROM `user_character` WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName));
        $character = $sth->fetch(PDO::FETCH_ASSOC);
        return $character;
    }

    public function updateExp($battleExp, $characterName) {
        //Increase actual experience with battle experience
        $connection = connect();
        $sql = "UPDATE `user_character` SET `experience` = `experience` + :battleExp WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':battleExp' => $battleExp, ':name' => $characterName));
        //Check update
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function updateBuild($buildId, $characterName) {
        //Select build ID to battle
        $connection = connect();
        $sql = "UPDATE `user_character` SET `buildId`=:buildId where `name`=:name AND `user_character`.`id`=(SELECT `characterId` FROM `character_build` WHERE `id`=:buildId)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':buildId' => $buildId, ':name' => $characterName));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function deleteCharacter($characterName){
        $connection = connect();
        $sql="DELETE FROM `user_character` WHERE `user_character`.`name` = :characterName";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function insertCharacter($characterName, $userId) {
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

    public function checkOwner($characterName, $userId) {
        $connection = connect();
        $sql = "SELECT `userId` FROM `user_character` WHERE `name` = :name AND `userId` = :userId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName, ':userId' => $userId));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if ($result != false) {
            return true;
        } else {
            return false;
        }
    }

    public function selectCharacterSlots($userId) {
        //Select character slots from user
        $connection = connect();
        $sql = "SELECT characterSlots FROM `user` WHERE id = :id";
        $sth = $connection->prepare($sql);
        $sth->execute(array(':id' => $userId));
        $avaliableSlots = $sth->fetch(PDO::FETCH_ASSOC);
        return $avaliableSlots['characterSlots'];
    }
    
    public function getCharacterExp($characterName){
        //Returns character EXP
        $connection = connect();
        $sql = "SELECT `experience`FROM `user_character` WHERE `name`= :characterName";
        $sth = $connection->prepare($sql);
        $sth->execute(array(':characterName' => $characterName));
        $avaliableSlots = $sth->fetch(PDO::FETCH_ASSOC);
        return $avaliableSlots['experience'];
    }
    
    public function addGold($characterName, $gold) {
        //Increase player gold
        $connection = connect();
        $sql = "UPDATE `user` SET `gold` = `gold`+ :gold WHERE `id`= (SELECT `userId`FROM `user_character` WHERE `name` = :name)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':gold' => $gold, ':name' => $characterName));
        //Check update
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function getSelectedBuildId($characterName){
        $connection = connect();
        $sql="SELECT `buildId` FROM `user_character` WHERE `name` = :characterName";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName));
        $build=$sth->fetch(PDO::FETCH_ASSOC);
        return $build['buildId'];
    }
    
    public function addCharacterDefaultWaitTime($characterName){
        //Adds a default wait time (current) into the `battle_status`table to prevent it from being empty.
        $connection = connect();
        $sql="INSERT INTO `battle_status`(`characterId`) VALUES ((SELECT `id`FROM `user_character` WHERE `name` = :characterName))";
        $sth = $connection->prepare($sql);
        $res=$sth->execute(array(':characterName' => $characterName));
        return $res;
    }

    public function updateCharacterWaitTime($characterName,$waitTime){
        $connection = connect();
        $sql="UPDATE `battle_status` SET `restUntil` = (CURRENT_TIMESTAMP + INTERVAL :minutes MINUTE) WHERE `battle_status`.`characterId` = (SELECT `id`FROM `user_character` WHERE `name` = :characterName)";
        $sth = $connection->prepare($sql);
        $sth->execute(array(':characterName' => $characterName,':minutes' => $waitTime));
        //Check update
        if ($sth->rowCount() != 0) {
            $sql="SELECT `restUntil` FROM `battle_status` WHERE `characterId`= (SELECT `id`FROM `user_character` WHERE `name` = :characterName)";
            $sth = $connection->prepare($sql);
            $sth->execute(array(':characterName' => $characterName));
            $res=$sth->fetch(PDO::FETCH_ASSOC);
            return $res['restUntil'];
        } else {
            return 0;
        }
    }
    
    public function isCharacterResting($characterName){
        $connection = connect();
        $sql="SELECT IF((SELECT `restUntil` FROM `battle_status` WHERE `characterId` = (SELECT `id`FROM `user_character` WHERE `name` = :characterName)) > CURRENT_TIMESTAMP,'yes','no') AS `resting`";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName));
        $res=$sth->fetch(PDO::FETCH_ASSOC);
        if($res['resting']=="yes"){
            return true;
        }else{
            return false;
        }
    }
    
}
