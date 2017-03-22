<?php

/**
 * Class to interact with the dungeon system
 *
 * @author mas886/redrednose/arnau
 */
include_once("./class/Character.php");
include_once("./db/DungeonDAO.php");
include_once("./mechanics/DungeonSys.php");

class Dungeon {

    public function getCharacterAvailableDungeons($characterName, $token) {
        if (strlen($characterName) > 20 || strlen($characterName) < 5 || strlen($token) != 30) {
            return 0;
        } else {
            $tkn = new Token;
            //Check of user token.
            $tokenOwner = $tkn->getUserIdByToken($token);
            if ($tokenOwner == "Expired" || $tokenOwner == "Bad token") {
                return $tokenOwner;
            }
            $char = new Character;
            //Check character belonging
            if (!$char->checkOwner($characterName, $tokenOwner)) {
                return "Wrong Character";
            } else {
                //Once we passed everything we proceed to get the available dungeons
                $dao = new DungeonDAO;
                return $dao->getCharacterDungeons($characterName);
            }
        }
    }

    public function listDungeonLevels($characterName, $token, $dungeonId) {
        //Returns the list of dungeon lvls plus a field indicating it's availability towards the player
        if (strlen($characterName) > 20 || strlen($characterName) < 5 || strlen($token) != 30 || !is_numeric($dungeonId)) {
            return 0;
        } else {
            $tkn = new Token;
            //Check of user token.
            $tokenOwner = $tkn->getUserIdByToken($token);
            if ($tokenOwner == "Expired" || $tokenOwner == "Bad token") {
                return $tokenOwner;
            }
            $char = new Character;
            //Check character belonging
            if (!$char->checkOwner($characterName, $tokenOwner)) {
                return "Wrong Character";
            }
            //We check dungeon access, if there are no lvls on a dungeon access will be dennied
            if (!$this->checkCharacterDungeonAccess($characterName, $dungeonId)) {
                return "Access Dennied";
            }
            //Once we passed everything we proceed to get the available dungeons
            $dao=new DungeonDAO;
            return $dao->listDungeonLevels($dungeonId, $characterName);
        }
    }

    private function checkCharacterDungeonAccess($characterName, $dungeonId) {
        //On this function we follow a sequence of checks, once all are passed "true"is returned 
        if (!$this->levelAccessCheck($characterName, $dungeonId)) {
            return false;
        }
        return true;
    }

    private function levelAccessCheck($characterName, $dungeonId) {
        $dao = new DungeonDAO;
        return $dao->checkCharacterDungeonAccessLvl($dungeonId, $characterName);
    }
    
    private function checkCharacterDungeonAccessByLevelId($characterName, $levelId) {
        //Gets the dungeon Id of the level and checks the character access to that dungeon
        $dao=new DungeonDAO;
        $dungeonId=$dao->getDungeonId($levelId);
        return $this->checkCharacterDungeonAccess($characterName, $dungeonId);
    }
    
    public function getCharacterDungeonLevelStages($characterName, $token, $levelId){
        //Returns the list of dungeon stages that can be accessed by a player
        if (strlen($characterName) > 20 || strlen($characterName) < 5 || strlen($token) != 30 || !is_numeric($levelId)) {
            return 0;
        } else {
            $tkn = new Token;
            //Check of user token.
            $tokenOwner = $tkn->getUserIdByToken($token);
            if ($tokenOwner == "Expired" || $tokenOwner == "Bad token") {
                return $tokenOwner;
            }
            $char = new Character;
            //Check character belonging
            if (!$char->checkOwner($characterName, $tokenOwner)) {
                return "Wrong Character";
            }
            //We check dungeon access, if there are no lvls on a dungeon access will be dennied
            $dao=new DungeonDAO;
            if (!$this->checkCharacterDungeonAccessByLevelId($characterName, $levelId) || !$dao->checkCharacterDungeonLevelAccess($characterName, $levelId)) {
                return "Access Dennied";
            }
            //Once we passed everything we proceed to get the available dungeons
            $dun= new DungeonSys;
            return $dun->getLevelStages($levelId, $characterName);
        }
    }

}
