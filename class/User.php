<?php

include_once("./db/UserDAO.php");
include_once("Token.php");

class User {

    function login($username, $password) {
        //Check if we have something
        if (strlen($username) > 1 && strlen($password) > 1) {
            $dao=new UserDAO;
            $user= $dao->getPassword($username);
            if (password_verify($password, $user['password'])) {
                $token = new Token;
                return $token->createToken($username);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function logout($token) {
        //Checks if the lenght is correct (tokens are 30 lengh long)
        if (strlen($token) == 30) {
            $tkn = new Token;
            return $tkn->deleteToken($token);
        } else {
            return 0;
        }
    }

    function signUp($username, $password, $email) {
        //We check if wehave all the variables correctly
        if (!$this->correctCredentials($username, $password, $email)) {
            return "Some field is incorrect.";
        }
        //Check if the username is already in the db
        $dao=new UserDAO;
        if ($this->exist($username)) {
            return "The user already exists.";
        } else {
            return $dao->addUser($username, $password, $email);
        }
    }

    private function correctCredentials($username, $password, $email) {
        //The size of the credentials will depend on the db
        if (strlen($username) >= 5 && strlen($username) <= 20 && strlen($password) >= 8 && strlen($password) <= 40 && strlen($email) >= 5 && strlen($email) <= 60) {
            return True;
        } else {
            return False;
        }
    }

    function exist($username) {
        $dao=new UserDAO;
        return $dao->exist($username);
    }

}
