<?php

/**
 * Class to control the mail system between user ~ user
 * @author mas886/redrednose/arnau
 */

include_once("Token.php");
include_once("User.php");
include_once("./db/MessageDAO.php");

class Message {

    public function sendMessage($token, $receiver, $text) {
        if (strlen($token) != 30 || strlen($receiver) <= 1 || strlen($text) < 10 || strlen($text) > 800) {
            return 0;
        } else {
            $tkn = new Token;
            $tokenOwner = $tkn->getUserIdByToken($token);
            if ($tokenOwner == "Expired" || $tokenOwner == "Bad token") {
                return $tokenOwner;
            }
            //If the token is correct we will continue
            $usr = new User();
            //We check if the reciever exist
            if ($usr->exist($receiver)) {
                $dao=new MessageDAO;
                return $dao->insertMessageIntoDb($tokenOwner, $receiver, $text);
            } else {
                return "User doesn't exist";
            }
        }
    }

    public function getMessages($token) {
        if (strlen($token) != 30) {
            return 0;
        } else {
            $tkn = new Token;
            $tokenOwner = $tkn->getUserIdByToken($token);
            if ($tokenOwner == "Expired" || $tokenOwner == "Bad token") {
                return $tokenOwner;
            }
            //If the token is correct we will continue
            $dao=new MessageDAO;
            return $dao->getMessagesById($tokenOwner);
        }
    }

    public function deleteMessage($token, $messageId){
        if (strlen($token) != 30 || strlen($messageId)==0) {
            return 0;
        } else {
            $tkn = new Token;
            $tokenOwner = $tkn->getUserIdByToken($token);
            if ($tokenOwner == "Expired" || $tokenOwner == "Bad token") {
                return $tokenOwner;
            }
            //If the token is correct we will continue
            $dao=new MessageDAO;
            return $dao->deleteMessageFromDb($tokenOwner, $messageId);
        }
    }

}
