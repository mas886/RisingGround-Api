<?php

/**
 * Class to connect to character_monsters table
 *
 * @author PATATA and mas886
 */
include_once("./class/config.php");

class CharacterMonsterDAO {

    public function addMonster($characterName, $monsterId) {
        $connection = connect();
        $sql = "BEGIN;
                    INSERT INTO `character_monster` (`characterId`, `monsterId`) 
                        VALUES ((SELECT `id` FROM `user_character` WHERE `name` = :characterName),:monsterId);
                    INSERT INTO `character_monster_stats` (`characterMonsterId`) 
                        VALUES (LAST_INSERT_ID());
                COMMIT;";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $success=$sth->execute(array(':characterName' => $characterName, ':monsterId' => $monsterId));
        if ($success) {
            return 1;
        } else {
            return 0;
        }
    }

    public function deleteCharacterMonster($characterMonsterId) {
        $connection = connect();
        $sql = "DELETE FROM `character_monster` WHERE `id` = :characterMonsterId;";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterMonsterId' => $characterMonsterId));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function characterMonsterList($characterName) {
        //Return a array if there's some monster and empty array if there's no one
        $connection = connect();
        $sql = "SELECT monster.name, monster.description, monster.sprite, character_monster.monsterId, character_monster.id, character_monster.experience, character_monster.buildId FROM `character_monster` JOIN monster WHERE characterId = (SELECT `id` FROM `user_character` WHERE name = :name) AND monster.id = character_monster.monsterId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName));
        $monsters = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $monsters;
    }

    function getCharacterMonster($characterMonsterId) {
        $connection = connect();
        $sql = "SELECT `id`, `experience`, (SELECT `name` FROM `monster` WHERE `monster`.`id`=`character_monster`.`monsterId`) as `name`,
            ((SELECT `accuracy` FROM `monster_stats` WHERE `monster_stats`.`monsterId`=`character_monster`.`monsterId`)+`character_monster_stats`.`accuracy`)as `accuracy`, 
            ((SELECT `speed` FROM `monster_stats` WHERE `monster_stats`.`monsterId`=`character_monster`.`monsterId`)+`character_monster_stats`.`speed`) as `speed`, 
            ((SELECT `strength` FROM `monster_stats` WHERE `monster_stats`.`monsterId`=`character_monster`.`monsterId`)+`character_monster_stats`.`strength`) as `strength`,
            ((SELECT `vitality` FROM `monster_stats` WHERE `monster_stats`.`monsterId`=`character_monster`.`monsterId`)+`character_monster_stats`.`vitality`) as `vitality`, 
            ((SELECT `defence` FROM `monster_stats` WHERE `monster_stats`.`monsterId`=`character_monster`.`monsterId`)+`character_monster_stats`.`defence`) as `defence` 
            FROM `character_monster` JOIN `character_monster_stats` WHERE `id`= :characterMonsterId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterMonsterId' => $characterMonsterId));
        $monster = $sth->fetch(PDO::FETCH_ASSOC);
        return $monster;
    }

    public function addExp($experience, $characterMonsterId) {
        $connection = connect();
        $sql = "UPDATE `character_monster` SET `experience` = `experience` + :experience WHERE `id` = :characterMonsterId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':experience' => $experience, ':characterMonsterId' => $characterMonsterId));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getExp($characterMonsterId) {
        $connection = connect();
        $sql = "SELECT `experience` FROM `character_monster` WHERE `id` = :characterMonsterId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterMonsterId' => $characterMonsterId));
        $experience = $sth->fetch(PDO::FETCH_ASSOC);
        return $experience;
        
    }


    public function checkMonsterOwner($characterMonsterId, $userId) {
        $connection = connect();
        $sql = "SELECT `id` FROM `character_monster` WHERE `id` = :characterMonsterId  AND characterId = (SELECT `id` FROM `user_character` WHERE `userId` = :userId AND `id`  = characterId)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterMonsterId' => $characterMonsterId, ':userId' => $userId));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if ($result != false) {
            return true;
        } else {
            return false;
        }
    }
    
    public function changeBuild($characterMonsterId, $buildId){
        $connection = connect();
        $sql = "UPDATE `character_monster` SET `buildId` = :buildId WHERE `id` = :characterMonsterId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterMonsterId' => $characterMonsterId, ':buildId' => $buildId));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function getBuildId($characterMonsterId){
        //Returns the buildId where character_monster belongs, null if don't belongs at anyone
        $connection = connect();
        $sql = "SELECT `buildId` FROM `character_monster` WHERE `id` = :characterMonsterId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterMonsterId' => $characterMonsterId));
        $buildId = $sth->fetch(PDO::FETCH_ASSOC);
        return $buildId['buildId'];
    }
    
    public function monsterSlots($buildId){
        //Will return how many monsters there are in build
        $connection = connect();
        $sql = "SELECT `id` FROM `character_monster` WHERE `buildId` = :buildId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array( ':buildId' => $buildId));
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return sizeof($result);
    }
    

 

}
