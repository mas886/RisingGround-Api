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
    

}
