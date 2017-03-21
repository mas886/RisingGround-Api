<?php

/**
 * Description of BuildDAO
 *
 * @author PATATA
 */
include_once("./class/config.php");

class BuildDAO {

    public function addBuild($characterName, $characterMonsterId) {
            $connection = connect();
            $sql = "INSERT INTO `character_build` (`characterId`, `monster1`) VALUES ((SELECT `id` FROM `user_character` WHERE `name` = :characterName), :characterMonsterId)";
            $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':characterName' => $characterName, ':characterMonsterId' => $characterMonsterId));
            if ($sth->rowCount() != 0) {
                return 1;
            } else {
                return 0;
            }
      }

    public function checkBuildSlots($charaterName) {
        $connection = connect();
        $sql = "SELECT `buildSlots` FROM `user_character` WHERE `name` = :characterName";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $charaterName));
        $buildSlots = $sth->fetch(PDO::FETCH_ASSOC);
        $slotsUsed = sizeof($this->getMonsters($charaterName));
        if ($buildSlots['buildSlots'] > $slotsUsed) {
            return true;
        } else {
            return false;
        }
    }

    public function addMonster($characterMonsterId, $characterName, $buildId, $counter) {
        $connection = connect();
        $sql = $this->orderMonsterSQL($counter);
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterMonsterId' => $characterMonsterId, ':characterName' => $characterName, 'buildId' => $buildId));
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
        $sql = $sql . " = :characterMonsterId WHERE characterId = (SELECT `id` FROM `user_character` WHERE `name` = :characterName) AND `id` = :buildId";
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

}
