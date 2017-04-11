<?php

/**
 * Class to control monster of every character
 *
 * @author PATATA and mas886
 */
include_once("Token.php");
include_once("./db/CharacterMonsterDAO.php");
include_once("./db/BuildDAO.php");

class CharacterMonster {

    private $maxBuildMonsterSlots = 3;

    public function addMonster($characterName, $monsterName) {
        //Add monsters to the character's collection
        //MUSN'T be indexed.
        $dao = new CharacterMonsterDAO;
        return $dao->insertCharacterMonster($characterName, $monsterName);
    }

    public function deleteMonster($characterMonsterId, $token) {
        if (!is_numeric($characterMonsterId) || sizeof($token) != 30) {
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

    public function getCharacterMonster($characterMonsterId, $token) {
        if (!is_numeric($characterMonsterId) || $token != 30) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $dao = new CharacterMonsterDAO;
        return $dao->getCharacterMonster($characterMonsterId);
    }

    
    public function getCharacterMonsterInternal($characterMonsterId) {
        //Non inedxed version of getCharacterMonster for internal operations
        $dao = new CharacterMonsterDAO;
        return $dao->getCharacterMonster($characterMonsterId);
    }
    
    public function addExp($experience, $characterMonsterId) {
        $dao = new CharacterMonsterDAO;
        return $dao->addExp($experience, $characterMonsterId);
    }

    public function getLevel($characterMonsterId, $token) {
        if (sizeof($characterMonsterId) != 18 || !is_numeric($characterMonsterId) || strlen($token) != 30) {
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

    public function changeBuild($characterMonsterId, $buildId, $token) {
        if (!is_numeric($characterMonsterId) || !is_numeric($buildId) || strlen($token) != 30) {
            return 0;
        }
        $securities = $this->valuesSecurities($characterMonsterId, $buildId, $token);
        if ($securities != "Acces") {
            return $securities;
        }

        $dao = new CharacterMonsterDAO;
        return $dao->changeBuild($characterMonsterId, $buildId);
    }

    public function valuesSecurities($characterMonsterId, $buildId, $token) {
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);

        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        if ($this->getBuildId($characterMonsterId) == $buildId) {
            return "Already in build";
        }
        if(!$this->checkBuildOwner($characterMonsterId, $buildId)){
            return "Doesn't belongs same character";
        }
        if(!$this->checkMonsterOwner($characterMonsterId, $userId)){
            return "Error monster owner";
        }
        if ($this->monstersSlots($buildId) >= $this->maxBuildMonsterSlots) {
            return "Build full";
        } else {
            return "Acces";
        }
    }

    public function getBuildId($characterMonsterId) {
        //If monster belongs to some build returns buildId if not returns -1
        $dao = new CharacterMonsterDAO;
        $buildId = $dao->getBuildId($characterMonsterId);
        if ($buildId != null) {
            return $buildId;
        } else {
            return -1;
        }
    }

    public function monstersSlots($buildId) {
        //Will return the number of monster there are in build
        $dao = new CharacterMonsterDAO;
        return $dao->monsterSlots($buildId);
    }

    private function checkMonsterOwner($characterMonsterId, $userId) {
        //Will check if this monster belongs to user
        $dao = new CharacterMonsterDAO;
        return $dao->checkMonsterOwner($characterMonsterId, $userId);
    }

    private function checkBuildOwner($characterMonsterId, $buildId) {
        //Will return true if the character_monster and the build belongs at the same character, if not false
        $dao = new BuildDAO;
        return $dao->buildOwner($characterMonsterId, $buildId);
    }

}
