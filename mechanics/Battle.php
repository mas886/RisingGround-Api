<?php

/**
 * Battle mechanics class
 *
 * @author mas886/redrednose/arnau
 */

class Battle {

    private $AccuracySpeedRange = [[-200, 100], [1, 99]];
    private $AttackDefenceRange = [[-300, 300], [0.1, 1.9]];
    //Amount of teams that will participate in the battle.
    private $Teams;
    
    function fight($monsters) {
        //Initialization of the battle parameters
        $speedOrderedArray= $this->orderMonsters($monsters);
        $teamSortedArray=$this->teamSorting($speedOrderedArray);
        $this->Teams=$this->getTeams($teamSortedArray);
        return 1;
    }
    
    private function getWeakTeamMonster($monsterTeam){
        //Will return the weakest team monster
        //Note: By now this method is pretty simplified, the weakest "monster" is considered to be the one with lowest vitality
        if (count($monsterTeam)<=0){
            //Error.
            return -1;
        }
        $weakMonster=reset($monsterTeam);
        foreach($monsterTeam as $monster){
            if($monster->getVitality()<$weakMonster->getVitality()){
                $weakMonster=$monster;
            }
        }
        return $weakMonster;
    }
    
    private function getNextTeam($currentTeamName,$teamSortedArray){
        //Will return te name of the next team in the array
        //Used to define attack priority
        $teamPos=$this->getTeamPosition($currentTeamName);
        $nextTeam=$teamPos+1;
        if($nextTeam>= count($this->Teams)){
            $nextTeam=0;
        }
        //If the next team is already dead it will return this one's next
        //In case all the teams except the current one are dead will return the current
        while(count($teamSortedArray[$this->Teams[$nextTeam]])==0){
            if($teamSortedArray[$this->Teams[$nextTeam]]==$currentTeamName){
                //Error protection, we should never get here
                //In case that all the teams are dead
                return $currentTeamName;
            }
            $nextTeam++;
            if($nextTeam>= count($this->Teams)){
                $nextTeam=0;
            }
        }
        //If the current team is returned it means all the teams are dead
        //Returning the same team should be avoided!
        return $this->Teams[$nextTeam];
    }
    
    private function getTeamPosition($team){
        //Returns the team position in the array $this->Team
        foreach($this->Teams as $teamPosition=>$teamName){
            if($team==$teamName){
                return $teamPosition;
            }
        }
        //We should not get here if everything was correctly initialized
        return -1;
    }
    
    private function getTeams($teamSortedArray){
        //will return an array with each team
        $teamsArray=[];
        foreach($teamSortedArray as $key=>$team){
            $teamsArray[]=$key;
        }
        return $teamsArray;
    }
    
    private function removeDeadMonsters($teamSortedArray){
        //Returns an array cleaned of dead monsters
        foreach($teamSortedArray as $teamIndex=>$team){
            foreach($team as $monsterIndex=>$monster){
                if($monster->getVitality()<=0){
                    unset($teamSortedArray[$teamIndex][$monsterIndex]);
                }
            }
        }
        return $teamSortedArray;
    }    
    
    private function teamsAlive($teamSortedArray){
        //Returns the amount of teams alive
        $cont=0;
        foreach($teamSortedArray as $team){
           if(count($team)>0){
               $cont++;
           }
        }
        return $cont;
    }
    
    private function teamSorting($monsterArray){
        //Will return an array made of monster arrays, each monster array key will be acording to team name/number
        $teamSortedArray;
        foreach($monsterArray as $monster){
            $teamSortedArray[$monster->getTeam()][]=$monster;
        }
        return $teamSortedArray;
    }

    private function orderMonsters($monsterArray) {
        //This function will convert the two team arrays into one ordered by monster speed
        $singleArray=[];
        foreach($monsterArray as $team){
            $singleArray= array_merge($singleArray, $team);
        }
        usort($singleArray, function ($a, $b) {
            if ($a->getSpeed() == $b->getSpeed()) {
                return 0;
            }
            return($a->getSpeed() > $b->getSpeed()) ? -1 : 1;
        });
        return $singleArray;
    }

    private function firstAttackSecond($monster1, $monster2) {
        //Turn based function where the $monster1 will try to attack $monster2
        $monster1Strenght = $monster1->getStrength();
        $monster2Defence = $monster2->getDefence();
        $monster1Accuracy = $monster1->getAccuracy();
        $monster2Speed = $monster2->getSpeed();
        //Chance will have a value from 1 to 99 to use as a hit probability %
        $chance = $this->calculateRange($monster1Accuracy - $monster2Speed, $this->AccuracySpeedRange);

        if ((rand(0 * 100, 100 * 100) / 100) <= $chance) {
            $damage = $monster1Strenght * $this->calculateRange($monster1Strenght - $monster2Defence, $this->AttackDefenceRange);
            $monster2->damage($this->damageRandomizer($damage));
        }
    }

    private function damageRandomizer($damage) {
        //This will apply a random multiplier from 0.8 50 1.2 to the original static calculated damage
        $damageRandomizer = rand(0.80 * 100, 1.20 * 100) / 100;
        return $damageRandomizer * $damage;
    }

    private function calculateRange($difference, $range) {
        //Formula: low2+(x-low1)*(high2-low2)/(high1-low1)
        $result = $range[1][0] + ($difference - $range[0][0]) * ($range[1][1] - $range[1][0]) / ($range[0][1] - $range[0][0]);
        return $result;
    }

}
