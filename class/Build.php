<?php

/**
 * Description of CharacterBuild
 *
 * @author PATATA
 */
class Build {

    public function addBuild($characterName, $token) {
        if (strlen($token) != 30 || strlen($characterName) < 5 || strlen($characterName) > 20) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $dao = new BuildDAO;
        return $dao->addBuild($characterName);
    }
    

}
