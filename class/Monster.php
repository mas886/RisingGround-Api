<?php

/**
 * Class to interact with "monster" db table.
 *
 * @author mas886/redrednose/arnau
 */

include_once("./db/MonsterDAO.php");

class Monster {
    
    //Will return a monster based on it's ID
    public function getMonster($monsterId){
        $dao=new MonsterDAO;
        return $dao->getMonster($monsterId);
    }
    
}
