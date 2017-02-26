<?php

/**
 * Class to connect to character_monsters table
 *
 * @author PATATA
 */
include_once "./class/config.php";

class CharacterMonsterDAO {

    public function insertCharacterMonster($monsterName, $characterName) {
        $connection = connect();
        $sql = "INSERT INTO `character_monster` (`characterId`, `monsterId`) VALUES ((SELECT `id` FROM `user_character` WHERE `name` = :characterName),(SELECT `id` FROM monster WHERE `name` = :monsterName))";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName, ':monsterName' => $monsterName));
        if ($sth->rowCount() != 0) {
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
        $sql = "SELECT `monsterId`,`id`,`experience`,`statsModifier` FROM `character_monster` WHERE characterId = (SELECT `id` FROM `user_character` WHERE name = :name)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName));
        $monsters = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $monsters;
    }

}
