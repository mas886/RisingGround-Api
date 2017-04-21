<?php

/**
 * Shop of monsters and items
 *
 * @author PATATA
 */
include_once("Token.php");
include_once("./db/ShopDAO.php");

class Shop {
    
    public function getItems($token){
        if (strlen($token) != 30 ) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $dao = new ShopDAO;
        $shop = array("gems" => $dao->getShopGems(),"gold"=> $dao->getShopGold());
        return $shop;
    }
    
   
    
}
