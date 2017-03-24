<?php

/**
 * Dungeon stage object
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
    
    public function __construct($stageArray) {
        $this->id=$stageArray['id'];
        $this->levelId=$stageArray['dungeonLevelId'];
        $this->type=$stageArray['type'];
        $this->position=$stageArray['position'];
        $this->pictureUrl= $this->parsePicture($stageArray['content']);
        $this->displayText= $this->parseDisplayText($stageArray['content']);
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
    
}
