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
    
    

}
