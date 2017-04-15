<?php

/**
 * Class to use and access reward system and `character_reward` table.
 *
 * @author mas886/redrednose/arnau
 */
include_once("./mechanics/RewardSys.php");
include_once("./db/RewardDAO.php");
include_once ("Character.php");

class Reward {
    
    public function listAvailableRewards($characterName,$token){
        //Returns all visible character rewards.
        if (strlen($characterName) > 20 || strlen($characterName) < 1 || strlen($token) != 30) {
            return 0;
        }
        $tkn = new Token;
        $userId = $tkn->getUserIdByToken($token);
        if ($userId == "Expired" || $userId == "Bad token") {
            return $userId;
        }
        if(!Character::checkOwner($characterName, $userId)){
            return ("Wrong character.");
        }else{
            $dao=new RewardDAO;
            return $dao->listAvailableRewards($characterName);
        }
        
    public function applyRewardString($characterName, $reward){
        $rew=new RewardSys;
        return $this->applyRewardString($characterName, $reward);
    }
    
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
}