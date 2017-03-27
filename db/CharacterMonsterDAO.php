<?php

/**
 * Class to connect to character_monsters table
 *
 * @author PATATA and mas886
 */
include_once("./class/config.php");

class CharacterMonsterDAO {

    public function insertCharacterMonster($monsterName, $characterName) {
        $connection = connect();
        $sql = "INSERT INTO `character_monster` (`characterId`, `monsterId`) VALUES ((SELECT `id` FROM `user_character` WHERE `name` = :characterName),(SELECT `id` FROM monster WHERE `name` = :monsterName))";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName, ':monsterName' => $monsterName));
        if ($sth->rowCount() != 0) {
            $characterMonsterId = mysqli_insert_id();
            return $this->setBaseStats($characterMonsterId, $connection);
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
        $sql = "SELECT `monsterId`,`id`,`experience`,`statsModifier` FROM `character_monster` WHERE characterId = (SELECT `id` FROM `user_character` WHERE name = :name)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName));
        $monsters = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $monsters;
    }

    function getCharacterMonster($characterMonsterId) {
        $connection = connect();
        $sql = "SELECT `id`, `experience`, 
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


    public function checkMonsterOwner($characterMonsterId, $characterName) {
        $connection = connect();
        $sql = "SELECT `id` FROM `character_monster` WHERE `id` = :characterMonsterId  AND characterId = (SELECT `id` FROM `user_character` WHERE `name` = :characterName)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterMonsterId' => $characterMonsterId, ':characterName' => $characterName));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if ($result != false) {
            return true;
        } else {
            return false;
        }
    }

    private function setBaseStats($characterMonsterId) {
        $connection = connect();
        $sql = "INSERT INTO `character_monster_stats` (characterMonsterId) VALUES (:characterMonsterId)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array('characterMonsterId' => $characterMonsterId));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return "Stats error";
        }
    }

}
