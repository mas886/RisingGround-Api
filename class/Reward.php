<?php

/**
 * Class to use and access reward system.
 *
 * @author mas886/redrednose/arnau
 */
include_once("./mechanics/RewardSys.php");

class Reward {
    
    public function applyReward($characterName,$stage){
        $rew=new RewardSys;
        return $rew->applyReward($characterName, $stage);
    }
    
}
