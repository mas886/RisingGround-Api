<?php

/**
 * Description of ItemDAO
 *
 * @author PATATA
 */
include_once "./class/config.php";

class ItemDAO {
   
    public function getItem($itemId){
        $connetion = connect();
        $sql = "SELECT `name`, `description`, `properties`, `category` FROM `items` WHERE `id` = :itemId";
        $sth = $connetion->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':itemId' => $itemId));
        $item = $sth->fetch(PDO::FETCH_ASSOC);
        return $item;
    }
}
