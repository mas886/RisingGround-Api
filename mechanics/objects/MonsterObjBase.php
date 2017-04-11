<?php

/**
 * Instance this to create a monsterObj type object with base stats
 * Needs team variable to define the team in the battle flow and monster id
 * from monster table
 *
 * @author mas886/redrednose/arnau
 */

include_once("./class/Monster.php");
include_once("./mechanics/objects/MonsterObj.php");

class monsterObjBase extends monsterObj{
    
    function __construct($monsterId,$team) {
        $monster=new Monster;
        $statsArray=$monster->getMonsterInternal($monsterId);
        parent::__construct($statsArray,$team);
    }

}
