<?php

include_once("config.php");

class Token {

    function createToken($user) {
        //This prevents generating duplicate tokens
        do {
            $token = bin2hex(random_bytes(30));
        } while ($this->exist($token));
        //Clean old tokens from the user
        $this->cleanTokens($user);
        //Insert new token
        $this->insertTokenIntoDB($user, $token);
        return $token;
    }

    function deleteToken($token) {
        //First we check if the token exist
        if ($this->exist($token)) {
            $connection = connect();
            $sql = "DELETE FROM `user_login_tokens` WHERE `user_login_tokens`.`token` = :token";
            $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':token' => $token));
            return 1;
        } else {
            return 0;
        }
    }
    
    private function cleanTokens($user) {
        //This will clean expired user tokens by Username
        $connection = connect();
        $sql = "DELETE FROM `user_login_tokens` WHERE `expireDate`< CURRENT_TIME AND `userId` = (SELECT `id` FROM `user` WHERE name=:user)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':user' => $user));
    }
    
    private function cleanTokensById($userId) {
        //This will clean expired user tokens by userId
        $connection = connect();
        $sql = "DELETE FROM `user_login_tokens` WHERE `expireDate`< CURRENT_TIME AND `userId` = :userId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':userId' => $userId));
    }

    private function insertTokenIntoDB($user, $token) {
        //Inserts the token into the db with an expire date of +30 days
        $connection = connect();
        $sql = "INSERT INTO `user_login_tokens`(`userId`, `token`, `expireDate`) VALUES ((SELECT `id` FROM `user` WHERE name=:user),:token,DATE_ADD(CURRENT_TIME,INTERVAL 30 DAY))";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':user' => $user, ':token' => $token));
    }

    private function exist($token) {
        $connection = connect();
        $sql = "SELECT token FROM `user_login_tokens` WHERE token=:token";
        $tokendb = [];
        $stmt = $connection->prepare($sql);
        if ($stmt->execute([':token' => $token])) {
            $tokendb = $stmt->fetch();
        }
        if (sizeof($tokendb) > 1) {
            return True;
        } else {
            return False;
        }
    }
    
    function getUserIdByToken($token){
        //Will return userID when the token is correct, if not will return 0 (When the token doesn'texist) or "Expired" if the token expired (old token)
        $connection = connect();
        $sql = "SELECT `userId`,`expireDate` FROM `user_login_tokens` WHERE `token`=:token";
        $tokendb = [];
        $stmt = $connection->prepare($sql);
        if ($stmt->execute([':token' => $token])) {
            $tokendb = $stmt->fetch();
        }
        if (sizeof($tokendb) > 1) {
            if (date('Y/m/d h:i:s', strtotime($tokendb['expireDate']))>date('Y/m/d h:i:s', time())){
                return $tokendb['userId'];
            }else{
                $this->cleanTokensById($tokendb['userId']);
                return "Expired";
            }
        } else {
            return "Bad token";
        }
    }

}
