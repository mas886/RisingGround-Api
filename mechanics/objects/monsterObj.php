<?php

/**
 * Monster object class to help the class battle.
 *
 * @author mas886/redrednose/arnau
 */

include_once("./class/Monster.php");

class monsterObj {
    
    protected $accuracy;
    protected $speed;
    protected $strength;
    protected $vitality;
    protected $defence;
    
    function __construct($monsterId) {
        $monster=new Monster;
        $statsArray=$monster->getMonster($monsterId);
        $this->accuracy = $statsArray['accuracy'];
        $this->speed =  $statsArray['speed'];
        $this->strength = $statsArray['strength'];
        $this->vitality = $statsArray['vitality'];
        $this->defence = $statsArray['defence'];
    }
    
    function getAccuracy() {
        return $this->accuracy;
    }

    function getSpeed() {
        return $this->speed;
    }

    function getStrength() {
        return $this->strength;
    }

    function getVitality() {
        return $this->vitality;
    }

    function getDefence() {
        return $this->defence;
    }

    function setAccuracy($accuracy) {
        $this->accuracy = $accuracy;
    }

    function setSpeed($speed) {
        $this->speed = $speed;
    }

    function setStrength($strength) {
        $this->strength = $strength;
    }

    function setVitality($vitality) {
        $this->vitality = $vitality;
    }

    function setDefence($defence) {
        $this->defence = $defence;
    }
    
}
