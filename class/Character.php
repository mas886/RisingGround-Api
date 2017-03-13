<?php

/**
 * Class of Character
 * @autor tinez09
 */
include_once("./db/CharacterDAO.php");
include_once("Token.php");

class Character {

    public function addCharacter($characterName, $token) {
        //Add character to db given token
        if (strlen($characterName) > 20 || strlen($characterName) < 5 || strlen($token) != 30) {
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
                return "Name exist";
            } else {
                //Return 1 if is succesfull, 0 if character is not added
                return $dao->insertCharacter($characterName, $userId);
            }
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
        if (strlen($characterName) > 20 || strlen($characterName) < 5 || strlen($token) != 30) {
            return 0;
        } else {
            $tkn = new Token;
            $userId = $tkn->getUserIdByToken($token);
            if ($userId == "Expired" || $userId == "Bad token") {
                return $userId;
            } else {
                $dao = new CharacterDAO;

                return $dao->selectCharacter($characterName);
            }
        }
    }

    public function addExp($battleExp, $characterName, $token) {
        //After a battle, experience of character is increased with battleExp
        if ($token != 30 || !ctype_digit($battleExp) || strlen($characterName) > 12 || strlen($characterName) < 1) {
            return 0;
        } else {
            $tkn = new Token;
            $userId = $tkn->getUserIdByToken($token);
            if ($userId == "Expired" || $userId == "Bad token") {
                return $userId;
            } else {
                $dao = new CharacterDAO;
                return $dao->updateExp($battleExp, $characterName, $userId);
            }
        }
    }

    public function selectBuild($buildId, $characterName, $token) {
        //select the build for battle of the character
        if (strlen($characterName) > 20 || strlen($characterName) < 1 || strlen($token) != 30 || !ctype_digit($buildId)) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $dao = new CharacterDAO;
        return $dao->updateBuild($buildId, $characterName);
    }
    
    public function checkOwner($characterName, $userId) {
        //THIS FUNCTION IS NOT INDEXED ON index.php
        //Return true if the character belongs to the user, false if not
        $dao = new CharacterDAO;
        return $dao->checkOwner($characterName, $userId);
    }

}
