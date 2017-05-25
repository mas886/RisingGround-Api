<?php

/**
 * Queries to interact with user_game_inbox table
 *
 * @author mas886/redrednose/arnau
 */

include_once("./class/config.php");

class GameMessageDAO {
    
    public function getMessagesById($userId) {
        $connection = connect();
        $sql = 'SELECT `nameSender`, `id`, `sendDate`, `content` FROM `user_game_inbox` WHERE `userId`=:userId';
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':userId' => $userId));
        $messages = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $messages;
    }
    
    public function deleteMessageFromDb($tokenOwner, $messageId) {
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
    
    public function sendMessage($sender, $message, $receiver) {
        $connection = connect();
        $sql = "INSERT INTO `user_game_inbox`(`userId`, `nameSender`, `content`) VALUES (:userId,:nameSender,:message)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':userId' => $receiver, ':nameSender' => $sender, ':message' => $message));
        return $sth;
    }
    
}
