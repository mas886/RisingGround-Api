<?php

/**
 * Description of BuildDAO
 *
 * @author PATATA
 */
include_once("./class/config.php");
include_once("CharacterDAO.php");

class BuildDAO {

    public function addBuild($characterName, $buildName) {
        $connection = connect();
        $sql = "INSERT INTO `character_build` (`characterId`, `name`) VALUES ((SELECT `id` FROM `user_character` WHERE `name` = :characterName), :buildName )";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName, ':buildName' => $buildName));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getBuildSlots($characterName) {
        $connection = connect();
        $sql = "SELECT `buildSlots` FROM `user_character` WHERE `name` = :characterName";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName));
        $buildSlots = $sth->fetch(PDO::FETCH_ASSOC);
        return $buildSlots['buildSlots'];
    }
   
       public function checkCharacterBuildOwnership($characterMontserId, $buildId) {
        //Will return true if the character_monster and the build belongs at the same character, if not false
        $connection = connect();
        $sql = "SELECT `id` FROM `character_build` WHERE `id` = :buildId AND `characterId` = (SELECT `characterId` FROM `character_monster` WHERE `id` = :characterMonsterId)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterMonsterId' => $characterMontserId, ':buildId' => $buildId));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if ($result != false) {
            return true;
        } else {
            return false;
        }
    }

    public function getBuilds($characterName) {
        $connection = connect();
        $sql = "SELECT `id`, `name` FROM `character_build` WHERE `characterId` = (SELECT `id` FROM `user_character` WHERE `name` = :characterName)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName));
        $builds = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $builds;
    }

   public function getBuildMonsterIds($buildId){
        $connection = connect();
        $sql="SELECT `id` FROM `character_monster` WHERE `buildId` = :buildId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':buildId' => $buildId));
        $buildMonsters = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(!$buildMonsters){
            return array();
        }else{
            return $buildMonsters;
        }
    }
    
    public function checkBuildBelongsToUser($buildId, $userId){
        //returns true if the build belongs to the user
        $connection = connect();
        $sql="SELECT `userId` FROM `user_character` WHERE `id` = (SELECT `characterId` FROM  `character_build` WHERE `id` = :buildId) AND `userId` = :userId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':buildId' => $buildId,':userId' => $userId));
        $returnedValue = $sth->fetch(PDO::FETCH_ASSOC);
        if($returnedValue!=false){
            return true;
        }else{
            return false;
        }
    }
    
    public function deleteBuild($buildId){
        $connection = connect();
        $sql="DELETE FROM `character_build` WHERE `id` = :buildId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':buildId' => $buildId));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function changeName($buildId, $buildName){
        $connection = connect();
        $sql="UPDATE `character_build` SET `name` = :buildName WHERE `id` = :buildId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':buildId' => $buildId, ':buildName' => $buildName));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }

}
