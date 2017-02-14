<?php

/**
 * Class to interact with "monster" db table.
 *
 * @author mas886/redrednose/arnau
 */

class Monster {
    
    //Will return a monster based on it's ID
    function getMonster($monsterId){
        $dao=new MonsterDAO;
        return $dao->getMonster($monsterId);
    }
    
}
