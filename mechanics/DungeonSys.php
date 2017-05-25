<?php

/**
 * Class to control mechanics 
 *
 * @author mas886/redrednose/arnau
 */
include_once("./db/DungeonDAO.php");
include_once("./class/Reward.php");
include_once("./class/Build.php");
include_once("./class/GameMessage.php");
include_once("./class/Character.php");
include_once("./mechanics/objects/DungeonStageObj.php");
include_once("./mechanics/objects/MonsterObjBase.php");
include_once("./mechanics/objects/MonsterObjCharacter.php");
include_once("./mechanics/Battle.php");

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

    private function processStageCombat($characterName,$stage){
        $stageWaitTime= $stage->getWaitTime($stage);
        $waitUntil=0;
        if($stageWaitTime!=0){
            $waitUntil= $this->updateCharacterWaitTime($characterName,$stageWaitTime);
        }
        $battleWon=$this->makeBattle($characterName,$stage->getMonstersArray());
        if($battleWon){
            if($waitUntil!=0){
                $this->stashStageReward($characterName,$stage->getId(),$waitUntil);
            }else{
                //If there's no wait time we apply the reward directly.
                $this->applyReward($characterName,$stage);
            }
        }else{
            $userId=Character::getUserId($characterName);
            $mes=new GameMessage;
            $mes->sendMessage("Battle System", "$characterName lost the battle.", $userId);
        }
        return 1;
    }
    
    private function applyReward($characterName,$stage){
        $rew = new Reward;
        return $rew->applyReward($characterName, $stage);
    }
    
    private function stashStageReward($characterName,$stageId,$visibleAfter){
        //This will add an stage reward into reward stash
        $rew=new Reward;
        return $rew->stashStageReward($characterName, $stageId, $visibleAfter);
    }
    
    private function updateCharacterWaitTime($characterName,$waitTime){
        $char=new Character;
        return $char->updateCharacterWaitTime($characterName, $waitTime);
    }
    
    private function makeBattle($characterName, $stageMonsterArray){
        //A team identifier is assigned at each team, used later on the foreach loops
        $playerTeam=1;
        $enemyTeam=2;
        $buil=new Build;
        $charMonsters=$buil->getCharacterSelectedBuildMonsters($characterName);
        $monsterArray=[];
        foreach($charMonsters as $charMonster){
            $monsterArray[]=new MonsterObjCharacter($charMonster,$playerTeam);
        }
        foreach($stageMonsterArray as $stageMonster){
            $monsterArray[]=new monsterObjBase($stageMonster,$enemyTeam);
        }
        $bat=new Battle;
        //The winner team identifier is returned
        $battleWinner=$bat->fight(array($monsterArray));
        //If the winner team is the player team a "True" will be returned.
        if($battleWinner==$playerTeam){
            return True;
        }else{
            return False;
        }
    }
    
}
