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
        if($dungeonData==false){
            return false;
        }else{
            return true;
        }
    }
    
    public function listDungeonLevels($dungeonId,$characterName){
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
    
    public function checkCharacterDungeonLevelAccess($characterName,$levelId){
        //Will return true if a character have access to a concrete dungeon level
        $connection = connect();
        $sql= "SELECT
            IF(((SELECT `position` FROM `dungeon_character_status` JOIN `dungeon_level` ON `dungeon_level`.`id`= `dungeon_character_status`.`levelId` WHERE `dungeon_character_status`.`characterId`= (SELECT `id` from `user_character` WHERE `name`= :characterName)  AND `dungeon_level`.`dungeonId`= (SELECT `dungeonId` FROM `dungeon_level` WHERE `id`= :levelId ) )>=`position`-1) OR (`position`=0),'yes','no'
            ) as `available` 
            FROM `dungeon_level` 
            WHERE `id`= :levelId
            ORDER BY `dungeon_level`.`position`  ASC";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName,':levelId' => $levelId));
        $dungeonLevelData = $sth->fetch(PDO::FETCH_ASSOC);
        if($dungeonLevelData['available']=="yes"){
            return true;
        }else{
            return false;
        }
        
    }
    
    public function listCharacterDungeonLevelStages($levelId,$characterName){
        //This will return the list of stages directly from the DB `dungeon_level_stages`alongside with it's stage type
        //First we get from which position we must get the stage list
        $position=$this->getLastCharacterLevelStage($levelId, $characterName);
        $connection = connect();
        //We get the levels till the last character's position
        $sql = "SELECT `dungeon_level_stages`.`id`, `dungeon_level_stages`.`dungeonLevelId`, `dungeon_level_stages_type`.`name` as `type`, `dungeon_level_stages`.`position`, `dungeon_level_stages`.`content` FROM `dungeon_level_stages` JOIN `dungeon_level_stages_type` ON `dungeon_level_stages_type`.id = `dungeon_level_stages`.`typeId` WHERE `dungeonLevelId`= :levelId AND `dungeon_level_stages`.`position`<= :position";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':levelId' => $levelId, ':position' => $position));
        $levels= $sth->fetchAll(PDO::FETCH_ASSOC);
        return $levels;
    }
    
    private function getLastCharacterLevelStage($levelId,$characterName){
        //Returns the position of the last lvl stage the character is at `dungeon_level_character_status` if there's no state 
        //for a character on that lvl in the db 0 will be returned
        $connection = connect();
        $sql = "SELECT `position` FROM `dungeon_level_character_status`  JOIN `dungeon_level_stages` ON  `dungeon_level_stages`.`id` = `dungeon_level_character_status`.`stageId` WHERE `dungeon_level_stages`.`dungeonLevelId`= :levelId AND `dungeon_level_character_status`.`characterId`= (SELECT `id`FROM `user_character` WHERE `name`= :characterName)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName,':levelId' => $levelId));
        $position = $sth->fetch(PDO::FETCH_ASSOC);
        if($position!=false){
            return (int)$position['position'];
        }else{
            return 0;
        }
    }
    
    public function checkCharacterStageAccess($stageId,$characterName){
        $stageInfo= $this->getStage($stageId);
        if($stageInfo!=NULL){
            $charPosition = $this->getLastCharacterLevelStage($stageInfo['dungeonLevelId'], $characterName);
            $stagePosition = (int)$stageInfo['position'];
            if($stagePosition>$charPosition){
                return false;
            }else{
                return true;
            }
            
        }else{
            return false;
        }
    }
    
    public function getStageLevelId($stageId){
        //returns level id which the stage belongs
        $connection = connect();
        $sql = "SELECT `dungeonLevelId` FROM `dungeon_level_stages` WHERE `id`= :stageId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':stageId' => $stageId));
        $level = $sth->fetch(PDO::FETCH_ASSOC);
        return $level['dungeonLevelId'];
    }
    
    public function getStage($stageId){
        $connection = connect();
        $sql="SELECT `dungeon_level_stages`.`id`, `dungeon_level_stages`.`dungeonLevelId`, `dungeon_level_stages_type`.`name` as `type`, `dungeon_level_stages`.`position`, `dungeon_level_stages`.`content`, `dungeon_level_stages`.`reward` FROM `dungeon_level_stages` JOIN `dungeon_level_stages_type` ON `dungeon_level_stages_type`.id = `dungeon_level_stages`.`typeId`WHERE `dungeon_level_stages`.`id`= :stageId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':stageId' => $stageId));
        $stage = $sth->fetch(PDO::FETCH_ASSOC);
        return $stage;
    }
    
    public function getDungeonId($levelId){
        //Returns de dungeon Id based on the level Id
        $connection = connect();
        $sql = "SELECT `dungeonId` FROM `dungeon_level` WHERE `id`= :levelId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':levelId' => $levelId));
        $dungeonId = $sth->fetch(PDO::FETCH_ASSOC);
        return (int)$dungeonId['dungeonId'];
        
    }
    
}
