<?php

/**
 *  Class to use and access reward system and `character_reward` table.
 *
 * @author mas886/redrednose/arnau
 */
class RewardDAO {
    
    public function stashStageReward($characterName,$stageId,$visibleAfter){
        //This will add an stage reward into reward stash
        $connection = connect();
        $sql="INSERT INTO `character_reward`(`characterId`, `stageCompletedId`, `visibleAfter`) VALUES ((SELECT `id`FROM `user_character` WHERE `name` = :characterName),:stageId,:visibleAfter)";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $res=$sth->execute(array(':characterName' => $characterName,':stageId' => $stageId,':visibleAfter' => $visibleAfter));
        return $res;
    }
    
}
