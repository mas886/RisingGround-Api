<?php

/**
 * Shop of monsters and items
 *
 * @author PATATA and mas886/Arnau/redrednose
 */
include_once("Token.php");
include_once("User.php");
include_once("Character.php");
include_once("./db/ShopDAO.php");

class Shop {

    public function getArticles($token) {
        if (strlen($token) != 30) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $dao = new ShopDAO;
        $shop = array("gems" => $dao->getShopGems(), "gold" => $dao->getShopGold());
        return $shop;
    }

    public function buyArticleGold($articleId, $characterName, $amount, $token) {
        if (strlen($token) != 30 || strlen($characterName) < 3 || strlen($characterName) > 15 || !is_numeric($articleId) || !is_numeric($amount)) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $character = new Character;
        $characterOwner = $character->checkOwner($characterName, $userId);
        if (!$characterOwner) {
            return "Character Owner Error";
        }else{
            return $this->buyArticle($userId, $characterName, $articleId, $amount, "gold");
        }
    }

    public function buyArticleGems($articleId, $characterName, $amount, $token) {
        if (strlen($token) != 30 || strlen($characterName) < 3 || strlen($characterName) > 15 || !is_numeric($articleId)) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        $character = new Character;
        $characterOwner = $character->checkOwner($characterName, $userId);
        if (!$characterOwner) {
            return "Character Owner Error";
        }else{
            return $this->buyArticle($userId, $characterName, $articleId, $amount, "gems");
        }
    }
    
    private function buyArticle($userId, $characterName, $articleId, $amount, $currency){
        $dao = new ShopDAO;
        $usr = new User;
        if($currency=="gems"){
            $article = $dao->getItemGems($articleId);
            $money = $usr->getGems($userId);
        }else if($currency=="gold"){
            $article = $dao->getItemGold($articleId);
            $money = $usr->getGold($userId);
        }
        $bundleAmount = intval($article['amount']);
        $value = floatval($article['value']);
        //No ($money < ($bundleAmount * $value)) cause we are buying the full bundle, not the spare items!
        if ($money < ($value * $amount)) {
            return "Not enough ".$currency;
        }
        if (!$dao->subtractCurrency($amount, $value, $userId, $currency)) {
            return $currency." transition failed";
        } else {
            return $dao->addCharacterItem($article['itemId'], $amount * $bundleAmount, $characterName);
        }
    }

}
