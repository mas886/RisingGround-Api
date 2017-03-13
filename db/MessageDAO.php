<?php

/**
 * Queries to interact with user_inbox table
 *
 * @author redrednose
 */

include_once("./class/config.php");

class MessageDAO {

    public function insertMessageIntoDb($userId, $receiver, $text) {
        $connection = connect();
        $sql = "INSERT INTO `user_inbox`(`userSendId`, `userReceiveId`, `content`) VALUES (:userId,(SELECT `id` FROM `user` WHERE name=:receiver),:text)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':userId' => $userId, ':receiver' => $receiver, ':text' => $text));
        return 1;
    }
    
    public function getMessagesById($userId) {
        $connection = connect();
        $sql = 'SELECT (SELECT `name`FROM `user`WHERE `id`= `userSendId`) as`from`, `id`, `sendDate`, `content` FROM `user_inbox` WHERE `userReceiveId`=:userId';
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':userId' => $userId));
        $messages = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $messages;
    }
    
    public function deleteMessageFromDb($tokenOwner, $messageId){
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
