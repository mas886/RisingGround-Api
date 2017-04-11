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
        if (strlen($token) != 30 || strlen($characterName) < 5 || strlen($characterName) > 20 || strlen($buildName) < 3 || strlen($buildName) > 13) {
            return 0;
        }
        //Value securities
        $securities = $this->addBuildCheckValues($characterName, $token);
        if ($securities != "Access") {
            return $securities;
        }
        //Check if there's enought Build Slots
        $dao = new BuildDAO;
        if ($dao->checkBuildSlots($characterName)) {
            return $dao->addBuild($characterName, $buildName);
        } else {
            return "Build Slots Full";
        }
    }

    //PRIVATE functions

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

    public function getCharacterSelectedBuildMonsters($characterName){
        $char=new Character;
        $buildId=$char->getSelectedBuildId($characterName);
        $dao = new BuildDAO;
        $sqlIdsArray= $dao->getBuildMonsterIds($buildId);
        $idsArray=[];
        foreach($sqlIdsArray as $monstID){
            $idsArray[]=(int)$monstID['id'];
        }
        return $idsArray;
    }
    
}
