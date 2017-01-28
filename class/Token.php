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
        //This will clean expired user tokens
        //
        $connection = connect();
        $sql = "DELETE FROM `user_login_tokens` WHERE `expireDate`< CURRENT_TIME AND `user_id` = (SELECT `id` FROM `user` WHERE name=:user)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':user' => $user));
    }

    private function insertTokenIntoDB($user, $token) {
        //Inserts the token into the db with an expire date of +30 days
        $connection = connect();
        $sql = "INSERT INTO `user_login_tokens`(`user_id`, `token`, `expireDate`) VALUES ((SELECT `id` FROM `user` WHERE name=:user),:token,DATE_ADD(CURRENT_TIME,INTERVAL 30 DAY))";
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

}
