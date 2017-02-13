<?php

/*
 * Copyright (C) 2017 PATATA
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

include_once("./class/config.php");

class CharacterDAO {

    function insertCharacterIntoDb($characterName, $userId) {
        //Insert
        $connection = connect();
        $sql = "INSERT INTO `user_character` (`name`, `userId`) VALUES (:name, :userId)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName, ':userId' => $userId));
        //Select to prove character inexistence

        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function selectCharacterList($userId) {
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
        $characterExp = $sth->fetch();
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

    function exist($characterName) {
        //Check name existence on user_character
        $connection = connect();
        $sql = "SELECT name FROM `user_character` WHERE `name` = :name";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':name' => $characterName));
        $result = $sth->fetch();
        if (sizeof($result) > 1) {
            return 1;
        } else {
            return 0;
        }
    }

}
