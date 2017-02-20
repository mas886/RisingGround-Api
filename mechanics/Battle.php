<?php

/**
 * Battle mechanics class
 *
 * @author mas886/redrednose/arnau
 */

class Battle {
    
    private $AccuracySpeedRange=[[-200,100],[1,99]];
    
    private function calculateChance($difference,$range){
        //Formula: low2+(x-low1)*(high2-low2)/(high1-low1)
        $chance=$range[1][0]+($difference-$range[0][0])*($range[1][1]-$range[1][0])/($range[0][1]-$range[0][0]);
        return $chance;
    }
     
}
