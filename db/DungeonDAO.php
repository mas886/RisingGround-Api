<?php

/**
 * Class to interact with dungeon table/s
 *
 * @author mas886/redrednose/arnau
 */

include_once("./class/config.php");
include_once("./class/Character.php");

class DungeonDAO {
    
    public function getCharacterDungeons($characterName){
        //We get character's lvl
        $lvl= $this->getCharacterLevel($characterName);
        $connection = connect();
        $sql = "SELECT `id`, `name`, `description`, `minLevel` FROM `dungeon` WHERE`minLevel`<= :minLvl";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':minLvl' => $lvl));
        $dungeons = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $dungeons;
    }
    
    private function getCharacterLevel($characterName){
        $char=new Character;
        return $char->getCharacterLevel($characterName);
    }
    
    public function checkCharacterDungeonAccessLvl($dungeonId,$characterName){
        //This function returns an array with the first dungeon_level's level values if the access is granted.
        //False is returned when the lvl requirement is not met.
        //We get character's lvl
        $level=$this->getCharacterLevel($characterName);
        $connection = connect();
        $sql = "SELECT `dungeon_level`.`id`, `dungeon_level`.`dungeonId`, `dungeon`.`name`, `dungeon_level`.`name`, `dungeon_level`.`description`  FROM `dungeon_level` JOIN `dungeon` WHERE `dungeon_level`.`dungeonId` = `dungeon`.`id` AND `dungeon`.`id` = :dungeonId AND `dungeon`.`minLevel`<= :playerLvl LIMIt 1";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':playerLvl' => $level,':dungeonId' => $dungeonId));
        $dungeonData = $sth->fetch(PDO::FETCH_ASSOC);
        //If there's NO access to the dungeon FALSE is returned
        //Note: If a dungeon exist but doesn't contain any lvl even if the minLvl requirement is met FALSE will be returned
        return $dungeonData;
    }
    
    public function listDungeonLvls($dungeonId,$characterName){
        //Will return all dungeon lvls from a dungeon + a field called "available" with values "yes" or "no" depending on the availability of the lvl
        //The availability of the lvl depends of the current lvl (at dungeon_character_status)'s position  (i.e if the position of the current stored 
        //lvl is x all the lvls at position x+1 will be marked as available, also in case any level status is stored any lvl with position "0" will be
        //marked always available.
        $connection = connect();
        $sql = "SELECT `id`, `dungeonId`,`position`, `name`, `description`, 
            IF(((SELECT `position` FROM `dungeon_character_status` JOIN `dungeon_level` ON `dungeon_level`.`id`= `dungeon_character_status`.`levelId` WHERE `dungeon_character_status`.`characterId`= (SELECT `id` from `user_character` WHERE `name`=:characterName)  AND `dungeon_level`.`dungeonId`=:dungeonId  )>=`position`-1) OR (`position`=0),'yes','no'
            ) as `available` 
            FROM `dungeon_level` 
            WHERE `dungeonId` = :dungeonId  
            ORDER BY `dungeon_level`.`position`  ASC";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName,':dungeonId' => $dungeonId));
        $dungeonLevelData = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $dungeonLevelData;
    }
    
}
