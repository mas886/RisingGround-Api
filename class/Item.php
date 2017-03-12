<?php
/**
 * Description of Item
 *
 * @author PATATA
 */
class Item {
    
    public function getItem($itemId){
        if(!ctype_digit($itemId)){
            return 0;
        }
        $dao = new ItemDAO;
        return $dao->getItem($itemId);
    }
}
