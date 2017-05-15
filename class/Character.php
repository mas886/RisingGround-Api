<?php

/**
 * Class of Character
 * @autor tinez09 & mas886/Arnau
 */
include_once("./db/CharacterDAO.php");
include_once("Token.php");
include_once("Build.php");
include_once("CharacterMonster.php");
include_once("./mechanics/Level.php");

class Character {

    public function addCharacter($characterName, $token) {
        //Add character to db given token
        if (strlen($characterName) > 20 || strlen($characterName) < 3 || strlen($token) != 30) {
            return 0;
        } else {
            $tkn = new Token;
            $userId = $tkn->getUserIdByToken($token);
            if ($userId == "Expired" || $userId == "Bad token") {
                return $userId;
            }
            //Now we'll check if user has enough character slots for new one
            $dao = new CharacterDAO;
            $slotsUsed = sizeof($dao->selectCharacterList($userId));
            $slots = $dao->selectCharacterSlots($userId);
            if ($slots <= $slotsUsed) {
                return "Character Slots Full";
            }
            if (sizeof($this->getCharacter($characterName, $token)) > 1) {
                return "Name already exist";
            } else {
                //Return 1 if is succesfull, 0 if character is not added
                $res = $dao->insertCharacter($characterName, $userId);
                if ($res == 1) {
                    //We add a default "wait time" on `battle_status` table to prevent it from being empty.
                    $this->addCharacterDefaultWaitTime($characterName);
                }
                return $res;
            }
        }
    }
    
    public function deleteCharacter($characterName, $token){
        //Returns 1 when successfull, this function will delete character and ALL db entries depending of the character table, use with precaution
        if (strlen($characterName) > 20 || strlen($characterName) < 3 || strlen($token) != 30) {
            return 0;
        } else {
            $tkn = new Token;
            $userId = $tkn->getUserIdByToken($token);
            if ($userId == "Expired" || $userId == "Bad token") {
                return $userId;
            }
            if(!$this->checkOwner($characterName, $userId)){
                return "Character Owner Error";
            }
            //Further security i.e: sending confirmation mail should be implemented
            $dao = new CharacterDAO;
            return $dao->deleteCharacter($characterName);
        }
    }

    public function characterList($token) {
        //Returns a list with all the characters's ID of the user given token
        if (strlen($token) != 30) {
            return 0;
        } else {
            $tkn = new Token;
            $userId = $tkn->getUserIdByToken($token);
            if ($userId == "Expired" || $userId == "Bad token") {
                return $userId;
            } else {
                $dao = new CharacterDAO;
                return $dao->selectCharacterList($userId);
            }
        }
    }

    public function getCharacter($characterName, $token) {
        //Returns the character's information () given token
        if (strlen($characterName) > 20 || strlen($characterName) < 3 || strlen($token) != 30) {
            return 0;
        } else {
            $tkn = new Token;
            $userId = $tkn->getUserIdByToken($token);
            if ($userId == "Expired" || $userId == "Bad token") {
                return $userId;
            } 
            if($this->checkOwner($characterName, $userId)){
                $dao = new CharacterDAO;
                return $dao->selectCharacter($characterName);
            }else{
                return "Character ownership error";
            }
        }
    }

    public function addExp($battleExp, $characterName) {
        //After a battle, experience of character is increased with battleExp
        $dao = new CharacterDAO;
        return $dao->updateExp($battleExp, $characterName);
    }

    public function addGold($characterName, $gold) {
        //After a battle, gold of character is increased with battleGold
        $dao = new CharacterDAO;
        return $dao->addGold($characterName, $gold);
    }
    
    public function addMonster($characterName, $monsterId) {
        $charmonst=new CharacterMonster;
        return $charmonst->addMonster($characterName, $monsterId);
    }

    public function updateBuild($buildId, $characterName, $token) {
        //select the build for battle of the character
        if (strlen($characterName) > 20 || strlen($characterName) < 3 || strlen($token) != 30 || !is_numeric($buildId)) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        if (!$this->checkOwner($characterName, $userId)) {
            return "Character Owner Error";
        } 
        $dao = new CharacterDAO;
        if($dao->updateBuild($buildId, $characterName)!=1){
            return "Build Owner Error";
        }else{
            return 1;
        }
    }

    public function checkOwner($characterName, $userId) {
        //THIS FUNCTION IS NOT INDEXED ON index.php
        //Return true if the character belongs to the user, false if not
        $dao = new CharacterDAO;
        return $dao->checkOwner($characterName, $userId);
    }

    public function getCharacterExp($characterName) {
        //Returns -1 if the character doesn't exist
        if (strlen($characterName) > 1) {
            $dao = new CharacterDAO;
            $exp = $dao->getCharacterExp($characterName);
            if ($exp != NULL) {
                return $exp;
            } else {
                return -1;
            }
        } else {
            return -1;
        }
    }

    public function getCharacterLevel($characterName) {
        //Not indexed (by now)
        $lvl = new Level;
        $charExp = $this->getCharacterExp($characterName);
        return $lvl->calculatePlayerLevel($charExp);
    }

    public function getSelectedBuildId($characterName) {
        //Not indexed (by now)
        $dao = new CharacterDAO;
        return $dao->getSelectedBuildId($characterName);
    }

    private function addCharacterDefaultWaitTime($characterName) {
        $dao = new CharacterDAO;
        return $dao->addCharacterDefaultWaitTime($characterName);
    }

    public function updateCharacterWaitTime($characterName, $waitTime) {
        $dao = new CharacterDAO;
        return $dao->updateCharacterWaitTime($characterName, $waitTime);
    }

    public function isCharacterResting($characterName) {
        $dao = new CharacterDAO;
        return $dao->isCharacterResting($characterName);
    }
    
    private function restingUntil($characterName) {
        //returns until when the character is resting
        $dao = new CharacterDAO;
        return $dao->restingUntil($characterName);
    }

}
