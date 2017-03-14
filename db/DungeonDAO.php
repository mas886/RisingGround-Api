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
        //We get user's lvl
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
    
}
