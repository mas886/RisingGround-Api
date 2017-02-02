<?php

/**
 * Class to control the mail system between user ~ user and game ~ user
 * @author mas886/redrednose/arnau
 */

include_once("Token.php");
include_once("User.php");

class Message {
    
    function sendMessage($token,$receiver,$text){
        if(strlen($token)!=30&& strlen($receiver)<=1&&strlen($text)<10&& strlen($text)>800){
            return 0;
        }else{
            $tkn=new Token;
            $tokenOwner=$tkn->getUserIdByToken($token);
            if($tokenOwner=="Expired"||$tokenOwner==0){
                return $tokenOwner;
            }
            //If the token is correct we will continue
            $usr=new User();
            //We check if the reciever exist
            if($usr->exist($receiver)){
                return $this->insertMessageIntoDb($tokenOwner,$receiver,$text);
            }else{
                return "User doesn't exist";
            }
        }
    }
    
    private function insertMessageIntoDb($userId,$receiver,$text){
        $connection = connect();
        $sql = "INSERT INTO `user_inbox`(`userSendId`, `userReceiveId`, `content`) VALUES (:userId,(SELECT `id` FROM `user` WHERE name=:receiver),:text)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':userId' => $userId, ':receiver' => $receiver, ':text' => $text));
        return 1;
    }
    
    
    
}
