<?php

/**
 * Class to control mechanics 
 *
 * @author mas886/redrednose/arnau
 */
include_once("./db/DungeonDAO.php");

class DungeonSys {

    public function getLevelStages($levelId, $characterName) {
        $dao = new DungeonDAO;
        $stages = $dao->listCharacterDungeonLevelStages($levelId, $characterName);
        return $stages;
    }

}
