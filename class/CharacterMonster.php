<?php

/**
 * Class to control monster of every character
 *
 * @author PATATA and mas886
 */



include_once "Token.php";

include_once "./db/CharacterMonsterDAO.php";


class CharacterMonster {

    public function addMonster($monsterName, $characterName, $token) {
        //Add monsters to the character's collection
        if (strlen($characterName) > 20 || strlen($characterName) < 5 || strlen($monsterName) > 20 || strlen($monsterName) < 5 || strlen($token) != 30) {
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

    public function deleteMonster($characterMonsterId, $token) {
        if (!ctype_digit($characterMonsterId) || sizeof($token) != 30) {
            return 0;
        }

        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);

        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }

        $dao = new CharacterMonsterDAO;
        return $dao->deleteCharacterMonster($characterMonsterId);
    }

    public function monsterList($characterName, $token) {
        if (strlen($characterName) > 20 || strlen($characterName) < 5 || strlen($token) != 30) {
            return 0;
        }
        
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);

        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        
        $dao = new CharacterMonsterDAO;
        return $dao->characterMonsterList($characterName);
        
    }
    
    public function getCharacterMonster($characterMonsterId){
        $dao=new CharacterMonsterDAO;
        return $dao->getCharacterMonster($characterMonsterId);
    }
    
    public function addExp($experience, $characterMonsterId, $token){
        if(!ctype_digit($experience) || sizeof($characterMonsterId) != 18 || !ctype_digit($characterMonsterId) || strlen($token) != 30){
            return 0;
        }
        
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
    }
    public function getLevel($characterMonsterId, $token) {
        if (sizeof($characterMonsterId) != 18 || !ctype_digit($characterMonsterId) || strlen($token) != 30) {
            return 0;
        }

        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);

        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }

        $dao = new CharacterMonsterDAO;
        $experience = $dao->getExp($characterMonsterId);

        return $this->experienceToLevel($experience[experience]);
    }

    private function experienceToLevel($experience) {
        /*
         * returns level array 
         * ['level'], 
         * ['experience'] (How much have monster to next level) and
         * ['nextLevel'] (Experience necessary to level up)
         */
        $level = array('level' => 0, 'experience' => 0, 'nextLevel' => 0);
        //Firt level is with 500 experience points
        $levelUp = 500;
        while ($experience > $levelUp) {
            $level[level] = $level[level] + 1;
            $experience = $experience - $levelUp;
            //Experience level will be increased with 50% any level
            $levelUp = $levelUp * 1.5;
        }
        //Experience to next level
        $level[experience] = $experience;
        //Next level 
        $level[nextLevel] = $levelUp;

        return $level;
    }

}
