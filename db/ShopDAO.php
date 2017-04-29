<?php

/**
 * Class to connec with shop_gold and shop_gems table
 *
 * @author PATATA
 */
include_once("./class/config.php");

class ShopDAO {

    public function getShopGems() {
        $connection = connect();
        $sql = "SELECT (SELECT `name` FROM `item` WHERE `id` = `itemId`) AS 'name', `discount`, `value`, `sprite` FROM `shop_gems` WHERE 1";
        $sth = $connection->query($sql);
        $shop = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($shop != false) {
            return $shop;
        } else {
            return array();
        }
    }

    public function getShopGold() {
        $connection = connect();
        $sql = "SELECT (SELECT `name` FROM `item` WHERE `id` = `itemId`) AS 'name', `discount`, `value`, `sprite` FROM `shop_gold` WHERE 1";
        $sth = $connection->query($sql);
        $shop = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($shop != false) {
            return $shop;
        } else {
            return array();
        }
    }

    public function getItemGold($articleId) {
        $connection = connect();
        $sql = "SELECT `value`,`amount`,`itemId` FROM `shop_gold` WHERE `id` = :articleId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':articleId' => $articleId));
        $article = $sth->fetch(PDO::FETCH_ASSOC);
        return $article;
    }

    public function getItemGems($articleId) {
        $connection = connect();
        $sql = "SELECT `value`,`amount`,`itemId` FROM `shop_gems` WHERE `id` = :articleId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':articleId' => $articleId));
        $article = $sth->fetch(PDO::FETCH_ASSOC);
        return $article;
    }

    public function subtractCurrency($amount, $value, $userId, $coin) {
        //Make money transition at user
        $connection = connect();
        $sql = "";
        switch ($coin) {
            case "gold":
                $sql = "UPDATE `user` SET `gold` = `gold` - (:value * :amount) WHERE `id` = :userId";
                break;
            case "gems":
                $sql = "UPDATE `user` SET `gems` = `gems` - (:value * :amount) WHERE `id` = :userId";
                break;
        }
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':value' => $value, ':amount' => $amount, ':userId' => $userId));
        if ($sth->rowCount() != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addCharacterItem($itemId, $amount, $characterName) {
        //insert the new item at character inventory
        $connection = connect();
        if ($this->itemAlreadyIn($itemId, $characterName)) {
            $sql = "UPDATE `character_item` SET `amount` = `amount` + :amount WHERE `characterId` = "
                    . "(SELECT `id` FROM `user_character` WHERE `name` = :characterName) AND `itemId` = :itemId";
        } else {
            $sql = "INSERT INTO `character_item` (`characterId`,`itemId`,`amount`) VALUES "
                    . "((SELECT `id` FROM `user_character` WHERE `name` = :characterName),:itemId,:amount)";
        }
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':amount' => $amount, ':itemId' => $itemId, ':characterName' => $characterName));
        if ($sth->rowCount() != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    private function itemAlreadyIn($itemId, $characterName) {
        $connection = connect();
        $sql = "SELECT `characterId` FROM `character_item` WHERE `characterId` = (SELECT `id` FROM `user_character` WHERE `name` = :characterName) AND `itemId` = :itemId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':itemId' => $itemId, ':characterName' => $characterName));
        $result = $sth->fetch();
        if (sizeof($result) > 1) {
            return True;
        } else {
            return false;
        }
    }

}
