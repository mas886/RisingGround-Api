<?php

/**
 * Battle mechanics class
 *
 * @author mas886/redrednose/arnau
 */

class Battle {
    
    private $AccuracySpeedRange=[[-200,100],[1,99]];
    private $AttackDefenceRange=[[-300,300],[0.1,1.9]];
        
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
