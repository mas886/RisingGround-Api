<?php

include_once("./db/TokenDAO.php");

class Token {

   public function createToken($userName) {
        //This prevents generating duplicate tokens
        do {
            $token = bin2hex(random_bytes(15));
        } while ($this->exist($token));
        //Clean old tokens from the user
        $dao=new TokenDAO;
        $dao->cleanTokens($userName);
        //Insert new token
        $dao->insertTokenIntoDB($userName, $token);
        return $token;
    }

    public function deleteToken($token) {
        //First we check if the token exist
        if ($this->exist($token)) {
            $dao=new TokenDAO;
            return $dao->deleteToken($token);
        } else {
            return 0;
        }
    }

    private function exist($token) {
        $dao=new TokenDAO;
        return $dao->exist($token);
    }
    
    public function getUserIdByToken($token){
        $dao=new TokenDAO;
        return $dao->getUserIdByToken($token);
    }

}
