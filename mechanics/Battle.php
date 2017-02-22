<?php

/**
 * Battle mechanics class
 *
 * @author mas886/redrednose/arnau
 */

class Battle {
    
    private $AccuracySpeedRange=[[-200,100],[1,99]];
    private $AttackDefenceRange=[[-300,300],[0.1,1.9]];
    
    private function firstAttackSecond($monster1,$monster2){
        //Turn based function where the $monster1 will try to attack $monster2
        $monster1Strenght=$monster1->getStrength();
        $monster2Defence=$monster2->getDefence();
        $monster1Accuracy=$monster1->getAccuracy();
        $monster2Speed=$monster2->getSpeed();
        //Chance will have a value from 1 to 99 to use as a hit probability %
        $chance=$this->calculateRange($monster1Accuracy-$monster2Speed, $this->AccuracySpeedRange);
        
        if((rand(0*100, 100*100)/100)<=$chance){
            $damage=$monster1Strenght*$this->calculateRange($monster1Strenght-$monster2Defence, $this->AttackDefenceRange);
            $monster2->damage($this->damageRandomizer($damage));
        }
        
    }
    
    private function damageRandomizer($damage){
        //This will apply a random multiplier from 0.8 50 1.2 to the original static calculated damage
        $damageRandomizer=rand (0.80*100, 1.20*100) / 100;
        return $damageRandomizer*$damage;
    }
    
    private function calculateRange($difference,$range){
        //Formula: low2+(x-low1)*(high2-low2)/(high1-low1)
        $result=$range[1][0]+($difference-$range[0][0])*($range[1][1]-$range[1][0])/($range[0][1]-$range[0][0]);
        return $result;
    }
     
}
