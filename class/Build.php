<?php

/**
 * Description of CharacterBuild
 *
 * @author PATATA
 */
include_once("./db/BuildDAO.php");
include_once("./db/CharacterMonsterDAO.php");
include_once("./db/CharacterDAO.php");
include_once("Token.php");

class Build {

    public function addBuild($characterName, $token) {
        if (strlen($token) != 30 || strlen($characterName) < 5 || strlen($characterName) > 20) {
            return 0;
        }
        $securities = $this->addBuildCheckValues($characterName, $token);
        if ($securities != "Acces") {
            return $securities;
        }
        //Check if there's enought Build Slots
        $dao = new BuildDAO;
        if ($dao->checkBuildSlots($characterName)) {
            return $dao->addBuild($characterName);
        } else {
            return "Build Slots Full";
        }
    }

    private function addBuildCheckValues($characterName, $token) {
        //Check token
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        //Check if character owns at userId
        $character = new CharacterDAO;
        $characterOwner = $character->checkOwner($characterName, $userId);
        if (!$characterOwner) {
            return "Character Owner Error";
        } else {
            return "Acces";
        }
    }

    public function addMonster($characterName, $characterMonsterId, $buildId, $token) {
        if (strlen($token) != 30 || !is_numeric($characterMonsterId) || strlen($characterName) < 5 || strlen($characterName) > 20 || !is_numeric($buildId)) {
            return 0;
        }
        $securities = $this->addMonsterCheckValues($characterName, $characterMonsterId, $buildId, $token);
        if ($securities != "Acces") {
            return $securities;
        }
        //Check how many monster there are in the build 
        $counter = $this->checkMonsterToAdd($characterName, $characterMonsterId, $buildId);
        if ($counter == 3) {
            return "Full Build";
        }
        if ($counter == -1) {
            return "Monster is already in.";
        }
        $dao = new BuildDAO;
        return $dao->addMonster($characterMonsterId, $characterName, $buildId, $counter);
    }

    private function addMonsterCheckValues($characterName, $characterMonsterId, $buildId, $token) {
        $securities = $this->addBuildCheckValues($characterName, $token);
        if ($securities != "Acces") {
            return $securities;
        }
         //Check if monster owns at charcterId
        $monster = new CharacterMonsterDAO;
        $owner = $monster->checkMonsterOwner($characterMonsterId, $characterName);
        if (!$owner) {
            return "Monster Owner Error";
        } 
        //Check build's owner
        if (!$this->buildOwner($characterName, $buildId)) {
            return "Build Owner Error";
        }
        if($this->alreadyInOtherBuild($characterName, $characterMonsterId)){
            return "Already in other build";
        }else {
            return "Acces";
        }
    }

    private function checkMonsterToAdd($characterName, $characterMonsterId, $buildId) {
        //Returns a number of monster there are in build to know if it's full and check if the monster to add is already in
        $dao = new BuildDAO;
        $counter = 0;
        $monsters = $dao->getMonsters($characterName, $buildId);
        foreach ($monsters as $monster) {
            if ($monster != NULL) {
                //If monster is already in
                if ($monster == $characterMonsterId) {
                    return -1;
                }
                $counter = $counter + 1;
            }
        }
        return $counter;
    }
    
    private function alreadyInOtherBuild($characterName, $characterMonsterId){
        $dao = new BuildDAO;
        return $dao->alreadyInOtherBuild($characterName, $characterMonsterId);
    }

    private function buildOwner($characterName, $buildId) {
        $dao = new BuildDAO;
        return $dao->buildOwner($characterName, $buildId);
    }

}
