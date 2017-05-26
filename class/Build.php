<?php

/**
 * Description of CharacterBuild
 *
 * @author PATATA
 */
include_once("./db/BuildDAO.php");
include_once("./class/Character.php");
include_once("Token.php");

class Build {

    public function addBuild($characterName, $buildName, $token) {
        if (strlen($token) != 30 || strlen($characterName) < 3 || strlen($characterName) > 20 || strlen($buildName) < 3 || strlen($buildName) > 13) {
            return 0;
        }
        //Value securities
        $securities = $this->addBuildCheckValues($characterName, $token);
        if ($securities != "Access") {
            return $securities;
        }
        //Check if there's enought Build Slots
        if ($this->checkFreeBuildSlots($characterName)) {
            $dao = new BuildDAO;
            return $dao->addBuild($characterName, $buildName);
        } else {
            return "Build slots full";
        }
    }

    public function deleteBuild($buildId, $token) {
        if (!is_numeric($buildId) || strlen($token) != 30) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        if (!$this->checkBuildBelongsToUser($buildId, $userId)) {
            return "Owner Error";
        } else {
            if (!$this->setMonstersOutBuild($buildId)) {
                return "Quit monsters error";
            } else {
                $dao = new BuildDAO;
                return $dao->deleteBuild($buildId);
            }
        }
    }

    public function changeName($buildId, $buildName, $token) {
        if (strlen($buildName) < 3 || strlen($buildName) > 15 || !is_numeric($buildId) || strlen($token) != 30) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        if (!$this->checkBuildBelongsToUser($buildId, $userId)) {
            return "Owner Error";
        }
        $dao = new BuildDAO;
        return $dao->changeName($buildId, $buildName);
    }

    public function getCharacterBuilds($characterName, $token) {
        //indexed function
        if (strlen($characterName) < 3 || strlen($characterName) > 15 || strlen($token) != 30) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $char = new Character;
        if (!$char->checkOwner($characterName, $userId)) {
            return "Character does not belong to the user.";
        }
        $dao = new BuildDAO;
        return $dao->getBuilds($characterName);
    }

    //Non indexed functions

    private function checkFreeBuildSlots($characterName) {
        $dao = new BuildDAO;
        $buildSlots = $dao->getBuildSlots($characterName);
        $slotsUsed = sizeof($dao->getBuilds($characterName));
        if ($buildSlots > $slotsUsed) {
            return true;
        } else {
            return false;
        }
    }

    private function checkBuildBelongsToUser($buildId, $userId) {
        //returns true if the build belongs to the user
        $dao = new BuildDAO;
        return $dao->checkBuildBelongsToUser($buildId, $userId);
    }

    private function addBuildCheckValues($characterName, $token) {
        //Check token
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        //Check if character owns at userId
        $character = new Character;
        $characterOwner = $character->checkOwner($characterName, $userId);
        if (!$characterOwner) {
            return "Character Owner Error";
        } else {
            return "Access";
        }
    }

    public function getCharacterSelectedBuildMonsters($characterName) {
        $char = new Character;
        $buildId = $char->getSelectedBuildId($characterName);
        $dao = new BuildDAO;
        $sqlIdsArray = $dao->getBuildMonsterIds($buildId);
        $idsArray = [];
        foreach ($sqlIdsArray as $monstID) {
            $idsArray[] = (int) $monstID['id'];
        }
        return $idsArray;
    }

    public function getBuilds($characterName) {
        //not indexed
        //Return a bidimensional array of buildsId of character: $builds as $build -> $build['id'] = buildId
        $dao = new BuildDAO;
        return $dao->getBuilds($characterName);
    }

    public function checkCharacterBuildOwnership($characterMonsterId, $buildId) {
        //Will return true if the character_monster and the build belongs at the same character, if not false
        $dao = new BuildDAO;
        return $dao->checkCharacterBuildOwnership($characterMonsterId, $buildId);
    }

    public function getMonsters($buildId) {
        //Return idArray with monsters of build
        $dao = new BuildDAO;
        return $dao->getBuildMonsterIds($buildId);
    }

    public function setMonstersOutBuild($buildId) {
        $dao = new BuildDAO;
        return $dao->setMonstersOutBuild($buildId);
    }

}
