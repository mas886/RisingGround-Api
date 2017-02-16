<?php

/**
 * Class to control monster of every character
 *
 * @author PATATA
 */
include_once "Token.php";
include_once "./db/CharacterMonsterDAO.php";

class CharacterMonster {

    public function addMonster($monsterName, $characterName, $token) {
        //Add monsters to the character's collection
        if (strlen($characterName) > 20 && strlen($characterName) < 5 && strlen($monsterName) > 20 && strlen($monsterName) < 5 && strlen($token) != 30) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        
        $dao = new CharacterMonsterDAO;
        return $dao->insertCharacterMonster($monsterName, $characterName);
         
    }

    public function deleteMonster() {
        
    }

    public function monsterList() {
        
    }

    public function addExp() {
        
    }

    public function getMonster() {
        
    }

}