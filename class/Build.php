<?php

/**
 * Description of CharacterBuild
 *
 * @author PATATA
 */
include_once("./db/BuildDAO.php");
include_once("Token.php");

class Build {

    public function addBuild($characterName, $characterMonsterId, $token) {
        if (strlen($token) != 30 || strlen($characterName) < 5 || strlen($characterName) > 20) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $dao = new BuildDAO;
        if ($dao->checkBuildSlots($characterName)) {
            return $dao->addBuild($characterName, $characterMonsterId);
        } else {
            return "Build Slots Full";
        }
    }

    public function addMonster($characterName, $characterMonsterId, $buildId, $token) {
        if (strlen($token) != 30 || !is_numeric($characterMonsterId) || strlen($characterName) < 5 || strlen($characterName) > 20 || !is_numeric($buildId)) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $dao = new BuildDAO;
        //Check how many monster there are in the build 
        $counter = $this->checkMonstersBuild($characterName, $characterMonsterId, $buildId);
        if ($counter == 3) {
            return "Full Build";
        }
        if ($counter != -1) {
            return $dao->addMonster($characterMonsterId, $characterName, $buildId, $counter);
        } else {
            return "Monster is already in.";
        }
    }

    public function checkMonstersBuild($characterName, $characterMonsterId, $buildId) {
        $dao = new BuildDAO;
        $counter = 0;
        $monsters = $dao->getMonsters($characterName, $buildId);
        foreach ($monsters as $monster) {
            if ($monster != NULL) {
                if ($monster == $characterMonsterId) {
                    return -1;
                }
                $counter = $counter + 1;
            }
        }
        return $counter;
    }

}
