<?php

/**
 * Class to interact with "monster" db table.
 *
 * @author mas886/redrednose/arnau
 */

include_once("./db/MonsterDAO.php");
include_once("Token.php");

class Monster {
    
    //Will return a monster based on it's ID
    public function getMonster($monsterId, $token){
        if(!is_numeric($monsterId) || strlen($token) != 30){
            return 0;
        }
        $tkn = new Token();
        $userId = $tkn->getUserIdByToken($token);
        if($userId == "Expired" || $userId == "Bad token"){
            return $userId;
        }
        $dao=new MonsterDAO;
        return $dao->getMonster($monsterId);
    }
    
    //Will return a monster based on it's ID
    public function getMonsterInternal($monsterId){
        //Non inedxed version of getMonster for internal operations
        $dao=new MonsterDAO;
        return $dao->getMonster($monsterId);
    }
    
}
