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
        //1 means everything went correctly, 0 that something (most likely sql query) failed(We should avoid this one) or
        //"Stage already completed", tht will be returned whenever stage is already completed or when we are on the last stage level.
        //Or Reward Apply Error (sql query to apply some reward failed)
        $proceedVal= $this->proceedToNextStage($characterName, $stage->getLevelId(), $stage->getId());
        if($proceedVal!="Stage was already completed." || $stage->getType()=="combat"){
            $rewardContent= $stage->getReward();
            return $this->applyRewardString($characterName,$rewardContent);
        }
        return $proceedVal;
    }
    
    public function applyRewardString($characterName,$rewardContent){
        //Applies a reward string into a character
        $gold = $this->parseGold($rewardContent);
        $experience= $this->parseExperience($rewardContent);
        $monsters= $this->parseMonsters($rewardContent);
        $rewResult= $this->addCharacterRewards($characterName,$monsters,$gold,$experience);
        return $rewResult;
    }
    
    private function proceedToNextStage($characterName,$dungeonLevelId, $stageId){
        //Can return 0 (means proceeding level failed somehow (Means previous checks weren't done correctly)
        //1 means we correctly proceeded the level
        // And "Stage was already completed." means literally what it says, this will also be returned if we are on the latest dungeon level
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
    
    private function addCharacterRewards($characterName,$monsters,$gold,$experience){
        $check=1;
        $char=new Character;
        if ($experience != NULL){
            $check=$char->addExp($experience, $characterName);
        }
        if($gold != NULL){
            $check=$char->addGold($characterName, $gold);
        }
        if($monsters != NULL){
            foreach($monsters as $monsterId){
                $char->addMonster($characterName, $monsterId);
            }
        }
        if ($check==1){
            return 1;
        }else{
            return "Reward Apply Error";
        }
    }
    
    private  function parseMonsters($rewardString){
        $parser=new Parser;
        return $parser->parseContent($rewardString,"monsters");
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
