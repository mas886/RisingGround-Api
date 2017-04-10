<?php

/**
 * Description of BuildDAO
 *
 * @author PATATA
 */
include_once("./class/config.php");

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

    public function checkBuildSlots($characterName) {
        $connection = connect();
        $sql = "SELECT `buildSlots` FROM `user_character` WHERE `name` = :characterName";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName));
        $buildSlots = $sth->fetch(PDO::FETCH_ASSOC);
        $slotsUsed = sizeof($this->getBuilds($characterName));
        if ($buildSlots['buildSlots'] > $slotsUsed) {
            return true;
        } else {
            return false;
        }
    }

    public function addMonster($characterMonsterId, $buildId, $counter) {
        $connection = connect();
        $sql = $this->orderMonsterSQL($counter);
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterMonsterId' => $characterMonsterId, 'buildId' => $buildId));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    private function orderMonsterSQL($counter) {
        $sql = "UPDATE `character_build` SET ";
        switch ($counter) {
            case 0:
                $sql = $sql . "`monster1`";
                break;
            case 1:
                $sql = $sql . "`monster2`";
                break;
            case 2:
                $sql = $sql . "`monster3`";
                break;
        }
        $sql = $sql . " = :characterMonsterId WHERE `id` = :buildId";
        return $sql;
    }

    public function getMonsters($characterName, $buildId) {
        $connection = connect();
        $sql = "SELECT `monster1`, `monster2`, `monster3` FROM `character_build` WHERE `characterId` = (SELECT `id` FROM `user_character` WHERE `name` = :characterName) AND `id` = :buildId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName, ':buildId' => $buildId));
        $monsters = $sth->fetch(PDO::FETCH_ASSOC);
        return $monsters;
    }

    public function buildOwner($characterMontserId, $buildId) {
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
        $sql = "SELECT `id` FROM `character_build` WHERE `characterId` = (SELECT `id` FROM `user_character` WHERE `name` = :characterName)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName));
        $builds = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $builds;
    }

    public function alreadyInOtherBuild($characterName, $characterMonsterId) {
        $connection = connect();
        $sql = "SELECT `id` FROM `character_build` WHERE `characterId` = (SELECT `id` FROM `user_character` WHERE `name` = :characterName) "
                . "AND (`monster1` = :characterMonsterId OR `monster2` = :characterMonsterId OR `monster3` = :characterMonsterId)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName, ':characterMonsterId' => $characterMonsterId));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if ($result != false) {
            return true;
        } else {
            return false;
        }
    }

   

}
