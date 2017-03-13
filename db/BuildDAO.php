<?php

/**
 * Description of BuildDAO
 *
 * @author PATATA
 */
class BuildDAO {

    public function addBuild($characterName) {
        //Check if character have enought build slots
        $buildSlots = $this->checkBuildSlots($charaterName);
        if ($buildSlots) {
            $connection = connect;
            $sql = "INSERT INTO character_build `characterId` VALUES (SELECT `id` FROM `user_character` WHERE `name` = :characterName)";
            $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':characterName' => $characterName));
            if ($sth->rowCount() != 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return $buildSlots;
        }
    }

    private function checkBuildSlots($charaterName) {
        $connection = connect;
        $sql = "SELECT `buildSlots` FROM `user_character` WHERE `name` = :characterName";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $charaterName));
        if ($sth->rowCount() != 0) {
            return true;
        } else {
            return "Build Slots FULL";
        }
    }

}
