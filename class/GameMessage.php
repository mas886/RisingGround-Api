<?php

/**
 * Class to control the mail system between game ~ user
 *
 * @author mas886/redrednose/arnau
 */

include_once("./db/GameMessageDAO.php");

class GameMessage {

    function getMessages($token) {
        if (strlen($token) != 30) {
            return 0;
        } else {
            $tkn = new Token;
            $tokenOwner = $tkn->getUserIdByToken($token);
            if ($tokenOwner == "Expired" || $tokenOwner == "Bad token") {
                return $tokenOwner;
            }
            //If the token is correct we will continue
            $dao=new GameMessageDAO;
            return $dao->getMessagesById($tokenOwner);
        }
    }

    function deleteMessage($token, $messageId) {
        if (strlen($token) != 30 || strlen($messageId) == 0) {
            return 0;
        } else {
            $tkn = new Token;
            $tokenOwner = $tkn->getUserIdByToken($token);
            if ($tokenOwner == "Expired" || $tokenOwner == "Bad token") {
                return $tokenOwner;
            }
            //If the token is correct we will continue
            $dao=new GameMessageDAO;
            return $dao->deleteMessageFromDb($tokenOwner, $messageId);
        }
    }

}
