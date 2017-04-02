<?php

/**
 * Class to control mechanics 
 *
 * @author mas886/redrednose/arnau
 */
include_once("./db/DungeonDAO.php");
include_once("./mechanics/objects/DungeonStageObj.php");
include_once("./class/Reward.php");

class DungeonSys {

    public function getLevelStages($levelId, $characterName) {
        $dao = new DungeonDAO;
        $stages = $dao->listCharacterDungeonLevelStages($levelId, $characterName);
        //We convert what we get from the DB to usefull data for the client
        foreach ($stages as $stage){
            $dunObj=new DungeonStageObj($stage);
            $stagesRet[]=$dunObj->getStage();
        }
        return $stagesRet;
    }
    
    public function proceedStage($characterName,$stageId){
        $dao = new DungeonDAO;
        $stageContent = $dao->getStage($stageId);
        $stage=new DungeonStageObj($stageContent);
        return $this->processStage($characterName, $stage);
    }
    
    private function processStage($characterName,$stage){
        //We decide here what to do with each stage type
        
        $type = $stage->getType();
        if($type=="text"){
            return $this->processStageText($characterName,$stage);
        }else if($type=="combat"){
            return $this->processStageCombat($characterName,$stage);
        }
    }
    
    private function processStageText($characterName,$stage){
        //On this function we will give the reward inmediately
        $rew=new Reward;
        return $rew->applyReward($characterName, $stage);
    }

}
