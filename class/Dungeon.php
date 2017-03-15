<?php

/**
 * Class to interact with the dungeon system
 *
 * @author mas886/redrednose/arnau
 */
include_once("./class/Character.php");
include_once("./db/DungeonDAO.php");

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

    private function levelAccessCheck($characterName, $dungeonId) {
        $dao = new DungeonDAO;
        if($dao->checkCharacterDungeonAccessLvl($dungeonId, $characterName)==FALSE){
            //If the query results is FALSE means the user have no access to the dungeon
            return false;
        }else{
            return true;
        }
    }

}
