<?php
/**
 * Description of Item
 *
 * @author PATATA
 */
include_once ("./db/ItemDAO.php");
include_once ("Token.php");

class Item {
    
    public function getItem($itemId, $token){
        if(!is_numeric($itemId) || strlen($token) != 30){
            return 0;
        }
        $tkn = new Token();
        $userId = $tkn->getUserIdByToken($token);
        if($userId == "Expired" || $userId == "Bad token"){
            return $userId;
        }
        $dao = new ItemDAO;
        return $dao->getItem($itemId);
    }
}
