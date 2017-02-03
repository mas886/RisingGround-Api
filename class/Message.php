<?php

/**
 * Class to control the mail system between user ~ user and game ~ user
 * @author mas886/redrednose/arnau
 */

include_once("Token.php");
include_once("User.php");

class Message {

    function sendMessage($token, $receiver, $text) {
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
                return $this->insertMessageIntoDb($tokenOwner, $receiver, $text);
            } else {
                return "User doesn't exist";
            }
        }
    }

    private function insertMessageIntoDb($userId, $receiver, $text) {
        $connection = connect();
        $sql = "INSERT INTO `user_inbox`(`userSendId`, `userReceiveId`, `content`) VALUES (:userId,(SELECT `id` FROM `user` WHERE name=:receiver),:text)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':userId' => $userId, ':receiver' => $receiver, ':text' => $text));
        return 1;
    }

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
            return $this->getMessagesById($tokenOwner);
        }
    }

    private function getMessagesById($userId) {
        $connection = connect();
        $sql = 'SELECT (SELECT `name`FROM `user`WHERE `id`= `userSendId`) as`from`, `id`, `sendDate`, `content` FROM `user_inbox` WHERE `userReceiveId`=:userId';
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':userId' => $userId));
        $messages = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $messages;
    }
    
    function deleteMessage($token, $messageId){
        if (strlen($token) != 30 || strlen($messageId)==0) {
            return 0;
        } else {
            $tkn = new Token;
            $tokenOwner = $tkn->getUserIdByToken($token);
            if ($tokenOwner == "Expired" || $tokenOwner == "Bad token") {
                return $tokenOwner;
            }
            //If the token is correct we will continue
            return $this->deleteMessageFromDb($tokenOwner, $messageId);
        }
    }
    
    private function deleteMessageFromDb($tokenOwner, $messageId){
        $connection = connect();
        $sql = "DELETE FROM `user_inbox` WHERE `id`= :messageId AND `userReceiveId`=:userId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':messageId' => $messageId, ':userId' => $tokenOwner));
        //If any row was affected will return success code (1)
        if($sth->rowCount()!=0){
            return 1;
        }else{
            return "Message does not exist";
        }
    }

}
