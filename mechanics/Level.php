<?php

/**
 * Class with level calculus mechanics
 *
 * @author mas886/redrednose/arnau
 */
class Level {

    private $characterConstants = [300, 2, 7, 4];

    public function calculatePlayerLevel($exp) {
        return $this->calculateLevelFromExp($exp, $this->characterConstants);
    }
    
    public function calculateExpToLevelPlayer($lvl){
        return $this->calculateExpFromLvl($lvl, $this->characterConstants);
    }

    private function calculateLevelFromExp($exp, $constants) {
        //Outputs lvl based on the exp
        if ($exp >= 0) {
            $lvl = 0;
            $a = 0;
            do {
                $lvl += 1;
                $a += floor($lvl + $constants[0] * pow($constants[1], ($lvl / $constants[2])));
            } while (floor($a / $constants[3]) <= $exp);

            return $lvl;
        } else {
            return -1;
        }
    }

    private function calculateExpFromLvl($lvl, $constants) {
        //Output exp needed to lvl
        $a = 0;
        for ($x = 1; $x < $lvl; $x++) {
            $a += floor($x + $constants[0] * pow($constants[1], ($x / $constants[2])));
        }
        return floor($a / $constants[3]);
    }

}
