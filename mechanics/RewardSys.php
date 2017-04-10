<?php

/**
 * Logic class for the reward system
 *
 * @author mas886/redrednose/arnau
 */

include_once("./mechanics/Parser.php");
include_once("./class/Character.php");
include_once("./class/Dungeon.php");

class RewardSys {
    
    public function applyReward($characterName,$stage){
        $rewardContent= $stage->getReward();
        $gold = $this->parseGold($rewardContent);
        $experience= $this->parseExperience($rewardContent);
        return $this->proceedToNextStage($characterName, $stage->getLevelId(), $stage->getId());
    }
    
    private function proceedToNextStage($characterName,$dungeonLevelId, $stageId){
        //Can return 0 (means proceeding level failed somehow (Means previous checks weren't done correctly)
        //1 means we correctly proceeded the level
        // And "Stage was already completed." means literally what it says
        $dun=new Dungeon;
        $stageIsSet = $dun->checkCharacterStageStatusEntry($characterName, $dungeonLevelId);
        $check=1;
        if($stageIsSet){
            $check=$this->updateDungeonStageEntry($characterName,$dungeonLevelId, $stageId);
        }else{
            $check=$this->addDungeonStageEntry($characterName,$dungeonLevelId, $stageId);
        }
        return $check;
    }
    
    private function updateDungeonStageEntry($characterName,$dungeonLevelId, $stageId){
        $dun=new Dungeon;
        return $dun->updateDungeonStageEntry($characterName, $dungeonLevelId, $stageId);
    }
    
    private function addDungeonStageEntry($characterName,$dungeonLevelId, $stageId){
        $dun=new Dungeon;
        return $dun->addDungeonStageEntry($characterName,$dungeonLevelId, $stageId);
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
