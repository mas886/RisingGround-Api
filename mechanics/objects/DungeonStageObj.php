<?php

/**
 * Dungeon stage object class, intended to convert data at "dungeon_level_stages" into usable data.
 *
 * @author mas886/redrednose/arnau
 */
class DungeonStageObj {
    
    public $id;
    public $levelId;
    public $type;
    public $position;
    public $pictureUrl;
    public $displayText;
    public $monstersArray;
    
    public function __construct($stageArray) {
        $this->id=$stageArray['id'];
        $this->levelId=$stageArray['dungeonLevelId'];
        $this->type=$stageArray['type'];
        $this->position=$stageArray['position'];
        $this->pictureUrl= $this->parsePicture($stageArray['content']);
        $this->displayText= $this->parseDisplayText($stageArray['content']);
    }
    
    private function parseDisplayText($contentString){
        return $this->parseContent($contentString,"text");
    }
    
    private  function parsePicture($contentString){
        return $this->parseContent($contentString,"picture");
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
    
    public function getStage(){
        return array('StageId'=> $this->id,'LevelId'=>$this->levelId,'Type'=>$this->type, 'Position'=>$this->position, 'Picture'=>$this->pictureUrl, 'Text'=> $this->displayText);
    }
    
}
