<?php

/**
 * Shop of monsters and items
 *
 * @author PATATA
 */
include_once("Token.php");
include_once("User.php");
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

    public function buyArticleGold($articleId, $characterName, $token) {
        if (strlen($token) != 30 || strlen($characterName) < 3 || strlen($characterName) > 15 || !is_numeric($articleId)) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        return $this->buyArticle($userId, $characterName, $articleId, "gold");
    }

    public function buyArticleGems($articleId, $characterName, $token) {
        if (strlen($token) != 30 || strlen($characterName) < 3 || strlen($characterName) > 15 || !is_numeric($articleId)) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        return $this->buyArticle($userId, $characterName, $articleId, "gems");
    }
    
    private function buyArticle($userId, $characterName, $articleId, $currency){
        $dao = new ShopDAO;
        $usr = new User;
        if($currency=="gems"){
            $article = $dao->getItemGems($articleId);
            $money = $usr->getGems($userId);
        }else if($currency=="gold"){
            $article = $dao->getItemGold($articleId);
            $money = $usr->getGold($userId);
        }
        $amount = intval($article['amount']);
        $value = floatval($article['value']);
        //No ($money < ($amount * $value)) cause we are buying the full bundle, not spare items!
        if ($money < $value) {
            return "Not enough ".$currency;
        }
        if (!$dao->subtractCurrency(1, $value, $userId, $currency)) {
            return $currency." transition failed";
        } else {
            return $dao->addCharacterItem(intval($article['itemId']), $amount, $characterName);
        }
    }

}
