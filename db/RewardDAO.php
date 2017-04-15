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
    
    public function listAvailableRewards($characterName){
        //Returns the available rewards from a character.
        $connection = connect();
        $sql="SELECT `id`, `stageCompletedId`, `reward` FROM `character_reward` WHERE `characterId`= (SELECT `id`FROM `user_character` WHERE `name` = :characterName) AND `visibleAfter`<= CURRENT_TIMESTAMP";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName));
        $rewards=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $rewards;
    }
    
    public function getCharacterRewardWhenAvailable($characterName,$rewardId){
        //Returns a reward if it exists, belongs to the character and it's available.
        $connection = connect();
        $sql="SELECT `stageCompletedId`, `reward`, `visibleAfter` FROM `character_reward` WHERE  `id` = :rewardId AND `characterId` = (SELECT `id`FROM `user_character` WHERE `name` = :characterName) AND`visibleAfter` <= CURRENT_TIMESTAMP";
        $sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':characterName' => $characterName,':rewardId' => $rewardId));
        $reward=$sth->fetch(PDO::FETCH_ASSOC);
        return $reward;
    }
    
}
