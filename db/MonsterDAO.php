<?php

/**
 * Description of MonsterDAO
 *
 * @author mas886/redrednose/arnau
 */

include_once("../class/config.php");

class MonsterDAO {
    
    function getMonster($monsterId){
        $connection = connect();
        $sql = "SELECT `id`, `name`, `description`, `sprite`, `accuracy`,`speed`,`strength`,`vitality` FROM `monsters`JOIN `monster_stats` WHERE `monsters`.`id`= `monster_stats`.`monsterId` AND `monsters`.`id` = :monsterId";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':monsterId' => $monsterId));
        $monster = $sth->fetch(PDO::FETCH_ASSOC);
        return $monster;
    }
    
}
