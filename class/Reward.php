<?php

/**
 * Class to use and access reward system.
 *
 * @author mas886/redrednose/arnau
 */
include_once("./mechanics/RewardSys.php");
include_once("./db/RewardDAO.php");

class Reward {
    
    public function applyReward($characterName,$stage){
        $rew=new RewardSys;
        return $rew->applyReward($characterName, $stage);
    }
    
    public function stashStageReward($characterName,$stageId,$visibleAfter){
        //This will add an stage reward into reward stash
        $dao=new RewardDAO;
        return $dao->stashStageReward($characterName, $stageId, $visibleAfter);
    }
    
}
