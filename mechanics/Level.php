<?php

/**
 * Class with level calculus mechanics
 *
 * @author mas886/redrednose/arnau
 */
class Level {

    private $characterMonsterConstants = [500, 1.5];
    private $characterConstants = [300, 2, 7, 4];

    public function calculatePlayerLevel($exp) {
        return $this->calculateLevelFromExp($exp, $this->characterConstants);
    }
    
    public function calculateExpToLevelPlayer($lvl){
        return $this->calculateExpFromLvl($lvl, $this->characterConstants);
    }
    
    public function calculateExpToLvlMonster($lvl){
        return $this->experienceToLevelMonster($lvl, $this->characterMonsterConstants);
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
    
     private function experienceToLevelMonster($experience, $constants) {
        /*
         * returns level array 
         * ['level'], 
         * ['experience'] (How much have monster to next level) and
         * ['nextLevel'] (Experience necessary to level up)
         */
        $level = array('level' => 0, 'experience' => 0, 'nextLevel' => 0);
        //Firt level is with 500 experience points
        $levelUp = $constants[0];
        while ($experience > $levelUp) {
            $level[level] = $level[level] + 1;
            $experience = $experience - $levelUp;
            //Experience level will be increased with 50% any level
            $levelUp = $levelUp * $constants[1];
        }
        //Experience to next level
        $level[experience] = $experience;
        //Next level 
        $level[nextLevel] = $levelUp;

        return $level;
    }

}
