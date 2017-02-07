<?php

/**
 * Class to control the mail system between game ~ user
 *
 * @author mas886/redrednose/arnau
 */
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
            return $this->getMessagesById($tokenOwner);
        }
    }

    private function getMessagesById($userId) {
        $connection = connect();
        $sql = 'SELECT `nameSender`, `id`, `sendDate`, `content` FROM `user_game_inbox` WHERE `userId`=:userId';
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':userId' => $userId));
        $messages = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $messages;
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
            return $this->deleteMessageFromDb($tokenOwner, $messageId);
        }
    }

    private function deleteMessageFromDb($tokenOwner, $messageId) {
        $connection = connect();
        $sql = "DELETE FROM `user_game_inbox` WHERE `id`=:messageId AND `userId`=:userId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':messageId' => $messageId, ':userId' => $tokenOwner));
        //If any row was affected will return success code (1)
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return "Message does not exist";
        }
    }

}
