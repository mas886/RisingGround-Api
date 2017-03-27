<?php

/**
 * Dungeon stage object class, intended to convert data at "dungeon_level_stages" into usable data.
 *
 * @author mas886/redrednose/arnau
 */
class DungeonStageObj {
    
    private $id;
    private $levelId;
    private $type;
    private $position;
    private $pictureUrl;
    private $displayText;
    private $monstersArray;
    private $reward;
    
    public function __construct($stageArray) {
        $this->id=$stageArray['id'];
        $this->levelId=$stageArray['dungeonLevelId'];
        $this->type=$stageArray['type'];
        $this->position=$stageArray['position'];
        $this->pictureUrl= $this->parsePicture($stageArray['content']);
        $this->displayText= $this->parseDisplayText($stageArray['content']);
        $this->monsters= $this->parseMonsters($stageArray['content']);
        $this->reward=$stageArray['reward'];
    }
    
    private function parseMonsters($contentString){
        return $this->parseContent($contentString, "monsters");
    }
    
    private function parseDisplayText($contentString){
        return $this->parseContent($contentString,"text")[0];
    }
    
    private  function parsePicture($contentString){
        return $this->parseContent($contentString,"picture")[0];
    }


    private function parseContent($stageContent,$keyWord){
        $contentArray= explode("|", $stageContent);
        foreach ($contentArray as $content){
            $secExplode= explode(":", $content);
            if($secExplode[0]==$keyWord){
                return explode(";",$secExplode[1]);
            }
        }
        return NULL;
    }
    
    function getId() {
        return $this->id;
    }

    function getLevelId() {
        return $this->levelId;
    }

    function getType() {
        return $this->type;
    }

    function getPosition() {
        return $this->position;
    }

    function getPictureUrl() {
        return $this->pictureUrl;
    }

    function getDisplayText() {
        return $this->displayText;
    }

    function getMonstersArray() {
        return $this->monstersArray;
    }

    function getReward() {
        return $this->reward;
    }
    
    public function getStage(){
        //Used by client to display stage visible information
        return array('StageId'=> $this->id,'LevelId'=>$this->levelId,'Type'=>$this->type, 'Position'=>$this->position, 'Picture'=>$this->pictureUrl, 'Text'=> $this->displayText);
    }
    
}
