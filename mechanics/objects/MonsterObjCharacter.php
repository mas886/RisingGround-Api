<?php

/**
 * Instance this to create a monsterObj type object with character's monster stats
 * Needs team variable to define the team in the battle flow and character monster id
 * from character_monster table
 *
 * @author mas886/redrednose/arnau
 */


include_once("./class/CharacterMonster.php");
include_once("./mechanics/objects/MonsterObj.php");

class MonsterObjCharacter extends monsterObj{
    
    function __construct($characterMonsterId,$team) {
        $monster=new CharacterMonster;
        $statsArray=$monster->getCharacterMonsterInternal($characterMonsterId);
        parent::__construct($statsArray,$team);
    }
}
