<?php

/**
 * Logic class for the reward system
 *
 * @author mas886/redrednose/arnau
 */

include_once("./mechanics/Parser.php");
include_once("./class/Character.php");

class RewardSys {
    
    public function applyReward($characterName,$stage){
        $rewardContent= $stage->getReward();
        $gold = $this->parseGold($rewardContent);
        $experience= $this->parseExperience($rewardContent);
        return $this->addCharacterRewards($characterName,$gold,$experience);
    }
    
    private function addCharacterRewards($characterName,$gold,$experience){
        $check=1;
        $char=new Character;
        if ($experience != NULL){
            $check=$char->addExp($experience, $characterName);
        }
        if($gold != NULL){
            $check=$char->addGold($characterName, $gold);
        }
        if ($check==1){
            return 1;
        }else{
            return "Reward Apply Error";
        }
    }
        
    private  function parseGold($rewardString){
        $parser=new Parser;
        return $parser->parseContent($rewardString,"gold")[0];
    }
    
    private  function parseExperience($rewardString){
        $parser=new Parser;
        return $parser->parseContent($rewardString,"exp")[0];
    }
    
}
