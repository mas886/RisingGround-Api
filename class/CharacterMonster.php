<?php

/**
 * Class to control monster of every character
 *
 * @author PATATA and mas886
 */
include_once("Token.php");
include_once("./db/CharacterMonsterDAO.php");
include_once("./class/Build.php");
include_once("./mechanics/Level.php");

class CharacterMonster {

    private $maxBuildMonsterSlots = 3;

    public function addMonster($characterName, $monsterId) {
        //Add monsters to the character's collection
        //MUSN'T be indexed.
        $dao = new CharacterMonsterDAO;
        return $dao->addMonster($characterName, $monsterId);
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

        $level = new Level;
        return $level->calculateExpToLvlMonster($experience[experience]);
    }

    public function changeBuild($characterMonsterId, $buildId, $token) {
        //If $buildId == -1 it will put buildId = null in SQL
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
        if ($buildId != -1) {
            if ($this->getBuildId($characterMonsterId) == $buildId) {
                return "Already in build";
            }
            if (!$this->checkCharacterBuildOwnership($characterMonsterId, $buildId)) {
                return "Build doesn't belongs to same character";
            }
            if (!$this->checkMonsterOwner($characterMonsterId, $userId)) {
                return "Error monster owner";
            }
            if ($this->monstersSlots($buildId) >= $this->maxBuildMonsterSlots) {
                return "Build full";
            }
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

    private function checkCharacterBuildOwnership($characterMonsterId, $buildId) {
        //Will return true if the character_monster and the build belongs at the same character, if not false
        $build = new Build;
        return $build->checkCharacterBuildOwnership($characterMonsterId, $buildId);
    }

}
