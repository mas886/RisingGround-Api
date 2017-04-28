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
        $dao = new BuildDAO;
        if ($dao->checkBuildSlots($characterName)) {
            return $dao->addBuild($characterName, $buildName);
        } else {
            return "Build Slots Full";
        }
    }
    
    public function deleteBuild($buildId, $token){
        if(!is_numeric($buildId) || strlen($token) != 30){
            return 0;
        }
         $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $dao = new BuildDAO;
        if(!$dao->checkBuildOwnsUser($buildId, $userId)){
            return "Owner Error";
        }else{
            return $dao->deleteBuild($buildId);
        }
        
    }
    
    public function changeName($buildId, $buildName, $token){
         if(strlen($buildName) < 3 || strlen($buildName) > 15 || !is_numeric($buildId) || strlen($token) != 30){
            return 0;
        }
         $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $dao = new BuildDAO;
        return $dao->changeName($buildId, $buildName);
        
        
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
    public function getCharacterBuilds($characterName, $token){
        //indexed function
        if(strlen($characterName) < 3 || strlen($characterName) > 15 || strlen($token) != 30){
            return 0;
        }
         $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        //not indexed
        $dao = new BuildDAO;
        return $dao->getBuilds($characterName);
    }
    
    
    public function getBuilds($characterName){
        //not indexed
        //Return a bidimensional array of buildsId of character: $builds as $build -> $build['id'] = buildId
        $dao = new BuildDAO;
        return $dao->getBuilds($characterName);
    }
    
    public function checkCharacterBuildOwnership($characterMonsterId, $buildId){
        //Will return true if the character_monster and the build belongs at the same character, if not false
        $dao = new BuildDAO;
        return $dao->checkCharacterBuildOwnership($characterMonsterId, $buildId);
    }
    
    public function getMonsters($buildId){
        //Return idArray with monsters of build
         $dao = new BuildDAO;
         return $dao->getBuildMonsterIds($buildId);
    }
    
    
}
