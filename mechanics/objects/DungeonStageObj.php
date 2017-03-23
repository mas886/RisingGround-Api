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
    
    
}
